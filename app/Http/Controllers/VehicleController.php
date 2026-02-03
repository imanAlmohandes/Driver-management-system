<?php
namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $vehicles = Vehicle::query()

            ->when($request->status, fn($q) =>
                $q->where('status', $request->status)
            )

            ->when($request->search, fn($q) =>
                $q->where(function ($q) use ($request) {
                    $q->where('plate_number', 'like', "%{$request->search}%")
                        ->orWhere('type', 'like', "%{$request->search}%")
                        ->orWhere('model', 'like', "%{$request->search}%");
                })
            )

            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.vehicles.partials.table', compact('vehicles'))->render();
        }

        return view('admin.vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicle = new Vehicle();

        $statuses = collect(Vehicle::STATUSES)->mapWithKeys(function ($label, $key) {
    return [$key => __('driver.status_' . strtolower($key))];
})->toArray();

        return view('admin.vehicles.create', compact('vehicle', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type'         => 'required',
            'model'        => 'required',
            'plate_number' => 'required|unique:vehicles,plate_number',
            'status'       => 'required',
        ]);

        $vehicle = Vehicle::create([
            'type'         => $request->type,
            'model'        => $request->model,
            'plate_number' => $request->plate_number,
            'status'       => $request->status,
        ]);
        return redirect()->route('admin.vehicles.index')->with('msg', __('driver.vehicle_created'))->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $vehicle = Vehicle::find($id);
        if (! $vehicle) {
            return redirect()->route('admin.vehicles.index');
        }
        return view('admin.vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $statuses = collect(Vehicle::STATUSES)->mapWithKeys(function ($label, $key) {
    return [$key => __('driver.status_' . strtolower($key))];
})->toArray();

        return view('admin.vehicles.edit', compact('vehicle', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'type'         => 'required',
            'model'        => 'required',
            'plate_number' => 'required',
            'status'       => 'required',
        ]);

        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update([
            'type'         => $request->type,
            'model'        => $request->model,
            'plate_number' => $request->plate_number,
            'status'       => $request->status,
        ]);

        return redirect()->route('admin.vehicles.index')->with('msg', __('driver.vehicle_updated'))->with('type', 'info');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Vehicle::find($id)->delete();
        return redirect()->route('admin.vehicles.index')->with('msg', __('driver.vehicle_deleted'))->with('type', 'warning');
    }

    public function trash()
    {
        $vehicles = Vehicle::onlyTrashed()->orderByDesc('id')->get();
        return view('admin.vehicles.trash', compact('vehicles'));
    }

    public function restore($id)
    {
        Vehicle::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.vehicles.trash');
    }

    public function forcedelete($id)
    {
        Vehicle::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.vehicles.trash');
    }

    public function restore_all()
    {
        Vehicle::onlyTrashed()->restore();
        return redirect()->route('admin.vehicles.trash');
    }

    public function delete_all()
    {
        Vehicle::onlyTrashed()->forceDelete();
        return redirect()->route('admin.vehicles.trash');
    }

    public function changeStatus(Request $request, Vehicle $vehicle)
    {
        //  Validate that the new status is one of the allowed statuses
        $request->validate([
            'status' => ['required', Rule::in(array_keys(Vehicle::STATUSES))],
        ]);

        // If the vehicle is now available, find the last open maintenance log and close it.
        if ($request->status == 'Available') {
            $openLog = $vehicle->maintenanceLogs()->whereNull('end_date')->latest('service_date')->first();
            if ($openLog) {
                $openLog->update(['end_date' => now()]);
            }
        }

        //  Update the vehicle's status
        $vehicle->update([
            'status' => $request->status,
        ]);

        //  Redirect back with a success message
        return back()->with('msg',  __('driver.vehicle_status_updated', ['status' => $request->status]))->with('type', 'success');
    }

}
