<?php
namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\FuelLog;
use App\Models\Trip;
use App\Models\User;
use App\Notifications\AdminDashboardNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FuelLogController extends Controller
{
    /**
     * Show the form for creating a new fuel log.
     */
    public function create()
    {
        $fuelLog = new FuelLog();

        //  جلب الرحلات التي لم تبدأ بعد أو الجارية حالياً فقط
        $trips = Auth::user()->driver->trips()
            ->whereIn('status', ['Pending', 'Ongoing']) // The most important condition
            ->with(['fromStation', 'toStation', 'vehicle'])
            ->orderBy('start_time', 'asc') // Show upcoming trips first
            ->get();

        // Get only the trips assigned to the currently logged-in driver
        // $trips = Auth::user()->driver->trips()->with(['fromStation', 'toStation', 'vehicle'])->get();

        return view('driver.fuel_logs.create', compact('fuelLog', 'trips'));
    }

    /**
     * Store a newly created fuel log in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'trip_id'            => 'required|exists:trips,id',
            'receipt_number'     => 'required|string|unique:fuel_logs,receipt_number',
            'station_name'       => 'nullable|string|max:255',
            'fuel_amount'        => 'required|numeric|min:0',
            'fuel_cost'          => 'required|numeric|min:0',
            'log_date'           => 'required|date|before_or_equal:today',
            'receipt_image_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // عشان يجيب الرحلات المستمرة او المنتظرة عشان يعبيلها وقود مش يعبي ل رحلات منتهي تاريخها
        //  إذا حاول السائق إرسال trip_id لرحلة مكتملة، سيتم رفض الطلب وإظهار رسالة خطأ واضحة
        $trip = Trip::find($data['trip_id']);
        // Check if the trip belongs to the driver AND its status is correct
        if ($trip->driver_id != Auth::user()->driver->id || ! in_array($trip->status, ['Pending', 'Ongoing'])) {
            return back()->withErrors(['trip_id' => __('driver.invalid_trip_for_fuel
            ')])->withInput();
        }

        // Automatically assign the driver_id from the authenticated user
        $data['driver_id'] = Auth::user()->driver->id;
        // Handle image upload
        if ($request->hasFile('receipt_image_path')) {
            $data['receipt_image_path'] = $request->file('receipt_image_path')->store('fuel_receipts', 'public');
        }

        $log = FuelLog::create($data);

        // ارسال اشعار للادمن
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new AdminDashboardNotification([
                'text'  => 'Driver ' . Auth::user()->name . ' logged a new fuel entry.',
                'icon'  => 'fa-gas-pump',
                'color' => 'success',
                'route' => route('admin.fuel_logs.show', $log->id),
            ]));
        }

        return redirect()->route('driver.dashboard')->with('msg', __('driver.fuel_log_created'))->with('type', 'success');
    }
}
