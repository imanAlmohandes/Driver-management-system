<?php
namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Station;
use App\Models\Trip;
use App\Models\Vehicle;
use App\Notifications\NewTripAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Trip::with(['driver.user', 'vehicle', 'fromStation', 'toStation']);
        // Filters
        $query->when($request->driver_id, fn($q) => $q->where('driver_id', $request->driver_id));
        $query->when($request->vehicle_id, fn($q) => $q->where('vehicle_id', $request->vehicle_id));
        $query->when($request->status, fn($q) => $q->where('status', $request->status));
        $query->when($request->from_date, fn($q) => $q->whereDate('start_time', '>=', $request->from_date));
        $query->when($request->to_date, fn($q) => $q->whereDate('start_time', '<=', $request->to_date));

        $trips = $query->orderByDesc('id')->paginate(10);
        // Data for filter dropdowns
        // Get only drivers who have actually made a trip, regardless of their current status.
        $drivers = Driver::whereHas('trips')->with('user')->get();

        // Get only vehicles that have actually been on a trip, regardless of their current status.
        $vehicles = Vehicle::whereHas('trips')->get();
        if ($request->ajax()) {
            return view('admin.trips.partials.table', compact('trips'))->render();
        }
        return view('admin.trips.index', compact('trips', 'drivers', 'vehicles'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $trip     = new Trip();
        $drivers  = Driver::whereHas('user', fn($q) => $q->where('is_active', true))->with('user')->get();
        $vehicles = Vehicle::where('status', 'Available')->get();
        $stations = Station::all();
        $statuses = collect(Trip::STATUSES)->mapWithKeys(function ($label, $key) {
            return [$key => __('driver.status_' . strtolower($key))];
        })->toArray();
        return view('admin.trips.create', compact('trip', 'statuses', 'drivers', 'vehicles', 'stations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'driver_id'           => 'required|exists:drivers,id',
            'vehicle_id'          => 'required|exists:vehicles,id',
            'from_station_id'     => 'required|exists:stations,id',
            'to_station_id'       => 'required|exists:stations,id',
            'start_time'          => 'required|date',
            'end_time'            => 'required|date|after:start_time',
            'notes'               => 'nullable|string',
            'status'              => ['required', Rule::in(array_keys(Trip::STATUSES))],
            // 'status'              => 'required',
            'distance_km'         => 'required|numeric|min:0.01|max:10000',
            'end_time.after'      => 'End time must be after start time.',
            'distance_km.min'     => 'Distance must be greater than zero.',
            'distance_km.numeric' => 'Distance must be a number.',
        ]);
        $start = $request->start_time;
        $end   = $request->end_time;

        //  فحص السيارة استثني الحالة المكتملة والملغاة والسيارة والسائق الل برحلة اخرى
        $vehicleConflict = Trip::where('vehicle_id', $request->vehicle_id)
            ->whereIn('status', ['Pending', 'Ongoing'])
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })
            ->exists();

        if ($vehicleConflict) {
            throw ValidationException::withMessages([
                'vehicle_id' => __('driver.vehicle_conflict'),
            ]);
        }

        // فحص السائق
        $driverConflict = Trip::where('driver_id', $request->driver_id)
            ->whereIn('status', ['Pending', 'Ongoing'])
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })
            ->exists();

        if ($driverConflict) {
            throw ValidationException::withMessages([
                'driver_id' => 'This driver is already assigned to another trip during this time.',
            ]);
        }

        $trip = Trip::create([
            'driver_id'       => $request->driver_id,
            'vehicle_id'      => $request->vehicle_id,
            'from_station_id' => $request->from_station_id,
            'to_station_id'   => $request->to_station_id,
            'start_time'      => $request->start_time,
            'end_time'        => $request->end_time,
            'status'          => $request->status,
            'distance_km'     => $request->distance_km,
            'notes'           => $request->notes,
        ]);
        $trip->vehicle?->update(['status' => 'UnAvailable']);

        // Trip::create($request->all());

        // --- إرسال إشعار للسائق ---
        //  Get the Driver model associated with the trip
        $driver = $trip->driver;

        //  Get the User model for that driver
        $driverUser = $driver->user;

        // ---  NOTIFY THE DRIVER ---
        if ($driver && $driverUser) {
            $trip->driver->user->notify(new NewTripAssignedNotification($trip));
        }

        // $driverUser = $trip->driver->user;
        // if ($driverUser) {
        //     $driverUser->notify(new \App\Notifications\NewTripAssignedNotification($trip)); // Use a dedicated notification class
        // }

        // //  Send the notification
        // if ($driverUser) {
        //     $driverUser->notify(new AdminDashboardNotification([
        //         'text'  => 'You have a new trip assigned: ' . ($trip->fromStation->name ?? 'N/A') . ' -> ' . ($trip->toStation->name ?? 'N/A'),
        //         'icon'  => 'fa-route',
        //         'color' => 'success',
        //         'route' => route('driver.my_work'), // Takes them to their work page
        //     ]));
        // }

        return redirect()->route('admin.trips.index')->with('msg', __('driver.trip_created'))->with('type', 'success');
    }

    public function cancel(Request $request, Trip $trip)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $trip->update([
            'status' => 'Cancelled',
            'notes'  => $request->notes ?: $trip->notes,
        ]);

        if ($trip->vehicle) {
            $trip->vehicle->update(['status' => 'Available']);
        }

        return back()->with('msg', __('driver.trip_cancelled'))->with('type', 'warning');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $trip = Trip::findOrFail($id);
        if (! $trip) {
            return redirect()->route('admin.trips.index');
        }
        return view('admin.trips.show', compact('trip'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $trip = Trip::findOrFail($id);
        // We don't need to send drivers/vehicles as they will be readonly
        $stations = Station::all();
        $statuses = collect(Trip::STATUSES)->mapWithKeys(function ($label, $key) {
            return [$key => __('driver.status_' . strtolower($key))];
        })->toArray();
        return view('admin.trips.edit', compact('trip', 'statuses', 'stations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $trip = Trip::findOrFail($id);
        $request->validate([
            'from_station_id'     => 'required|exists:stations,id',
            'to_station_id'       => 'required|exists:stations,id',
            'start_time'          => 'required|date',
            'end_time'            => 'required|date|after:start_time',
            // 'status'              => 'required',
            'status'              => ['required', Rule::in(array_keys(Trip::STATUSES))],
            'distance_km'         => 'required|numeric|min:0.01|max:10000',
            'notes'               => 'nullable|string',
            'end_time.after'      => 'End time must be after start time.',
            'distance_km.min'     => 'Distance must be greater than zero.',
            'distance_km.numeric' => 'Distance must be a number.',

        ]);
        $start = $request->start_time;
        $end   = $request->end_time;

        //  السيارة ما تطلع رحلة وهي اصلا ب رحلة
        $vehicleConflict = Trip::where('vehicle_id', $trip->vehicle_id)
            ->where('id', '!=', $trip->id)
            ->whereIn('status', ['Pending', 'Ongoing'])
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })
            ->exists();

        if ($vehicleConflict) {
            throw ValidationException::withMessages([
                'vehicle_id' => __('driver.vehicle_conflict'),
            ]);
        }

        //  السائق ما يطلع رحلة وهو اصلا ب رحلة
        $driverConflict = Trip::where('driver_id', $trip->driver_id)
            ->where('id', '!=', $trip->id)
            ->whereIn('status', ['Pending', 'Ongoing'])
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })
            ->exists();

        if ($driverConflict) {
            throw ValidationException::withMessages([
                'driver_id' => 'This driver is already assigned to another trip during this time.',
            ]);
        }

        // $trip->update($request->all());
        $trip->update([
            'from_station_id' => $request->from_station_id,
            'to_station_id'   => $request->to_station_id,
            'start_time'      => $request->start_time,
            'end_time'        => $request->end_time,
            'status'          => $request->status,
            'distance_km'     => $request->distance_km,
            'notes'           => $request->notes,
        ]);
        return redirect()->route('admin.trips.index')->with('msg', __('driver.trip_updated'))->with('type', 'info');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Trip::find($id)->delete();
        return redirect()->route('admin.trips.index')->with('msg', __('driver.trip_deleted'))->with('type', 'warning');
    }

    public function trash()
    {
        $trips = Trip::onlyTrashed()->orderByDesc('id')->get();
        return view('admin.trips.trash', compact('trips'));
    }

    public function restore($id)
    {
        Trip::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.trips.trash');
    }

    public function forcedelete($id)
    {
        Trip::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.trips.trash');
    }

    public function restore_all()
    {
        Trip::onlyTrashed()->restore();
        return redirect()->route('admin.trips.trash');
    }

    public function delete_all()
    {
        Trip::onlyTrashed()->forceDelete();
        return redirect()->route('admin.trips.trash');
    }

    /**
     * Change the status of a specific trip.
     */
    public function changeStatus(Request $request, Trip $trip)
    {
        //  Validate that the new status is one of the allowed statuses
        $request->validate([
            'status' => ['required', Rule::in(array_keys(Trip::STATUSES))],
        ]);

        //  Update the trip's status
        $trip->update([
            'status' => $request->status,
        ]);

        //  Redirect back with a success message
        return back()->with('msg', "Trip status updated to '{$request->status}' successfully.")->with('type', 'success');
    }

}
