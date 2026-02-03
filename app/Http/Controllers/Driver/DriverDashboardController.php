<?php
namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceLog;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DriverDashboardController extends Controller
{
    public function dashboard()
    {
        $driver = Auth::user()->driver;
        if (! $driver) {return view('driver.dashboard_empty');}

        $tripsThisMonth    = $driver->trips()->whereMonth('start_time', now()->month)->count();
        $distanceThisMonth = $driver->trips()->whereMonth('start_time', now()->month)->sum('distance_km');
        $licenseExpiryDays = $driver->license_expiry_date ? (int) Carbon::now()->diffInDays(Carbon::parse($driver->license_expiry_date), false) : null;

        $tripsByWeek = $driver->trips()
            ->where('start_time', '>=', now()->subWeeks(4)->startOfWeek())
            ->select(DB::raw('WEEK(start_time, 1) as week_number'), DB::raw('count(*) as trip_count'))
            ->groupBy('week_number')->get()->pluck('trip_count', 'week_number');

        $chartLabels = [];
        $chartData   = [];
        for ($i = 3; $i >= 0; $i--) {
            $week          = now()->subWeeks($i);
            $chartLabels[] = "Week " . $week->weekOfYear;
            $chartData[]   = $tripsByWeek[$week->weekOfYear] ?? 0;
        }

        $upcoming_trips = $driver->trips()->where('status', 'Pending')->where('start_time', '>', now())
            ->with(['fromStation', 'toStation', 'vehicle'])->orderBy('start_time', 'asc')->take(5)->get();

        // سجلات الوقود الاخيرة
        $recent_fuel_logs = $driver->fuelLogs()
            ->with('trip.vehicle')
            ->latest('log_date')
            ->paginate(5, ['*'], 'fuel_logs_page')
            ->through(fn($log) => $log);
        // Force this specific pagination instance to use Tailwind CSS
        $recent_fuel_logs->onEachSide(1)->useTailwind();

        //  Get the IDs of all vehicles ever assigned to this driver
        $vehicleIds = $driver->vehicles()->pluck('vehicles.id');

        //  Fetch maintenance logs for those specific vehicle IDs
        $recent_maintenance_logs = MaintenanceLog::whereIn('vehicle_id', $vehicleIds)
            ->with('vehicle', 'company')
            ->latest('service_date')
            ->paginate(5, ['*'], 'maintenance_logs_page');

        // Force this specific pagination to use Tailwind
        if ($recent_maintenance_logs->count() > 0) {
            $recent_maintenance_logs->onEachSide(1)->useTailwind();
        }
        return view('driver.dashboard', compact('tripsThisMonth', 'distanceThisMonth', 'licenseExpiryDays', 'chartLabels', 'chartData', 'upcoming_trips', 'recent_fuel_logs', 'recent_maintenance_logs'));
    }

    /**
     * Show the "My Work" page with next trip and vehicle info.
     */
    public function myWork()
    {
        $driver = Auth::user()->driver;
        if (! $driver) {return view('driver.dashboard_empty');}

        $active_trip      = $driver->trips()->where('status', 'Ongoing')->orderBy('start_time', 'asc')->with(['fromStation', 'toStation', 'vehicle'])->first();
        $next_trip        = ! $active_trip ? $driver->trips()->where('status', 'Pending')->orderBy('start_time', 'asc')->with(['fromStation', 'toStation', 'vehicle'])->first() : null;
        $assigned_vehicle = $driver->vehicles()->wherePivotNull('end_date')->first();
        $today_trips      = $driver->trips()->whereDate('start_time', today())->with(['fromStation', 'toStation'])->orderBy('start_time')->get();

        return view('driver.my-work', compact('active_trip', 'next_trip', 'assigned_vehicle', 'today_trips'));
    }

}
