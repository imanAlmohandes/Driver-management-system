<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Driver;
use App\Models\FuelLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Notifications\AdminDashboardNotification;

class FuelLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $fuelLogs = FuelLog::orderByDesc('id')->paginate(10);
    //     return view('admin.fuel_logs.index', compact('fuelLogs'));
    // }
    public function index(Request $request)
    {
        $query = FuelLog::with(['driver.user', 'trip']);

        $query->when($request->driver_id, fn($q) => $q->where('driver_id', $request->driver_id));
        $query->when($request->from_date, fn($q) => $q->whereDate('log_date', '>=', $request->from_date));
        $query->when($request->to_date, fn($q) => $q->whereDate('log_date', '<=', $request->to_date));

        $fuelLogs = $query->orderByDesc('id')->paginate(10);
        $drivers  = Driver::with('user')->get();

        if ($request->ajax()) {
            return view('admin.fuel_logs.partials.table', compact('fuelLogs'))->render();
        }

        return view('admin.fuel_logs.index', compact('fuelLogs', 'drivers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fuelLog = new FuelLog();
        // السائقين الل الهم رحلات
        $drivers = Driver::whereHas('trips')->with('user')->get();
        // $trips   = Trip::with('vehicle')->get(); // جلب كل الرحلات مع المركبة

        return view('admin.fuel_logs.create', compact('fuelLog', 'drivers'));
    }

    // اجاكس عشان نرجع الرحلات ع حسب اسم السائق
    // public function getTripsByDriver($driverId)
    // {
    //     $trips = Trip::where('driver_id', $driverId)
    //         ->with(['fromStation', 'toStation', 'vehicle'])
    //         ->get();

    //     return response()->json($trips->map(function ($trip) {
    //         return [

    //             'id'           => $trip->id,
    //             'from_station' => $trip->fromStation ? ['name' => $trip->fromStation->name] : null,
    //             'to_station'   => $trip->toStation ? ['name' => $trip->toStation->name] : null,
    //         ];
    //     }));
    // }
    public function getTripsByDriver(Driver $driver)
    {
        // Eager load all necessary relationships
        $trips = $driver->trips()->with(['fromStation', 'toStation', 'vehicle'])->get();

        // Directly return the collection. Laravel will handle the JSON conversion.
        // This ensures all loaded relationships (like 'vehicle') are included.
        return response()->json($trips);
    }

    // اجاكس عشان يجيب المركبات الل ركبها السائق لرحلاته
    // public function getVehiclesByDriver(Driver $driver)
    // {
    // Return only vehicles currently assigned to this driver
    //     return response()->json($driver->vehicles()->wherePivotNull('end_date')->get());
    // }
    // public function getTripsByDriverAndVehicle(Driver $driver, Vehicle $vehicle)
    // {
    //     return response()->json(
    //         $driver->trips()
    //             ->where('vehicle_id', $vehicle->id)
    //             ->with(['fromStation', 'toStation'])
    //             ->get()
    //     );
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'driver_id'          => 'required|exists:drivers,id',
            'trip_id'            => 'required|exists:trips,id',
            'receipt_number'     => 'required|string|unique:fuel_logs,receipt_number',
            'station_name'       => 'nullable|string|max:255',
            'fuel_amount'        => 'required|numeric|min:0',
            'fuel_cost'          => 'required|numeric|min:0',
            'log_date'           => 'required|date',
            'receipt_image_path' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        //upload file
        $img_name = null;
        if ($request->hasFile('receipt_image_path')) {

            $img_name = rand() . time() . $request->file('receipt_image_path')->getClientOriginalName();
            $request->file('receipt_image_path')->move(public_path('uploads/fuelLogs'), $img_name);
        }

        $log = FuelLog::create([
            'driver_id'          => $request->driver_id,
            'trip_id'            => $request->trip_id,
            'receipt_number'     => $request->receipt_number,
            'station_name'       => $request->station_name,
            'fuel_amount'        => $request->fuel_amount,
            'fuel_cost'          => $request->fuel_cost,
            'log_date'           => $request->log_date,
            'receipt_image_path' => $img_name,
        ]);

        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new AdminDashboardNotification([
                'text'  => 'New fuel log by ' . Auth::user()->name,
                'icon'  => 'fa-gas-pump',
                'color' => 'success',
                'route' => route('admin.fuel_logs.show', $log->id),
            ]));
        }
        return redirect()->route('admin.fuel_logs.index')->with('msg', __('driver.fuel_logs_created'))->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $fuelLog = FuelLog::findOrFail($id);
        if (! $fuelLog) {
            return redirect()->route('admin.fuel_logs.index');
        }
        return view('admin.fuel_logs.show', compact('fuelLog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $fuelLog = FuelLog::findOrFail($id);
        // لانه بديش اغير قيمة السائق والمركبة والرحلة
        // $drivers = Driver::with('user')->get();
        // // جلب الرحلات الخاصة بالسائق الحالي فقط
        // $trips = Trip::where('driver_id', $fuelLog->driver_id)
        //     ->with(['fromStation', 'toStation'])
        //     ->get();

        return view('admin.fuel_logs.edit', compact('fuelLog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'receipt_number'     => 'required|string|unique:fuel_logs,receipt_number,' . $id,
            'station_name'       => 'nullable|string|max:255',
            'fuel_amount'        => 'required|numeric|min:0',
            'fuel_cost'          => 'required|numeric|min:0',
            'log_date'           => 'required|date',
            'receipt_image_path' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        $fuelLog  = FuelLog::findOrFail($id);
        $img_name = $fuelLog->receipt_image_path;
        if ($request->hasFile('receipt_image_path')) {
            //upload file
            $img_name = rand() . time() . $request->file('receipt_image_path')->getClientOriginalName();
            $request->file('receipt_image_path')->move(public_path('uploads/fuelLogs'), $img_name);
        }

        $fuelLog->update([
            'driver_id'          => $request->driver_id,
            'trip_id'            => $request->trip_id,
            'receipt_number'     => $request->receipt_number,
            'station_name'       => $request->station_name,
            'fuel_amount'        => $request->fuel_amount,
            'fuel_cost'          => $request->fuel_cost,
            'log_date'           => $request->log_date,
            'receipt_image_path' => $img_name,
        ]);
        return redirect()->route('admin.fuel_logs.index')->with('msg', __('driver.fuel_logs_updated'))->with('type', 'info');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $fuelLog = FuelLog::findOrFail($id);
        File::delete(public_path('uploads/fuelLogs/' . $fuelLog->receipt_image_path));

        // if ($fuelLog->receipt_image_path && Storage::disk('uploads/fuelLogs')->exists($fuelLog->receipt_image_path)) {
        //     Storage::disk('uploads/fuelLogs')->delete($fuelLog->receipt_image_path);
        // }
        $fuelLog->delete();
        return redirect()->route('admin.fuel_logs.index')->with('msg', __('driver.fuel_logs_deleted'))->with('type', 'warning');
    }

    public function trash()
    {
        $fuelLogs = FuelLog::onlyTrashed()->orderByDesc('id')->get();
        return view('admin.fuel_logs.trash', compact('fuelLogs'));
    }

    public function restore($id)
    {
        FuelLog::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.fuel_logs.trash');
    }

    public function forcedelete($id)
    {
        FuelLog::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.fuel_logs.trash');
    }

    public function restore_all()
    {
        FuelLog::onlyTrashed()->restore();
        return redirect()->route('admin.fuel_logs.trash');
    }

    public function delete_all()
    {
        FuelLog::onlyTrashed()->forceDelete();
        return redirect()->route('admin.fuel_logs.trash');
    }
}
