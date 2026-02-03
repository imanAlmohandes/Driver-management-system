<?php
namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\FuelLog;
use App\Models\MaintenanceCompany;
use App\Models\MaintenanceLog;
use App\Models\Station;
use App\Models\Trip;
use App\Models\Vehicle;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // ===== Counters =====
        $driversCount     = Driver::count();
        $vehiclesCount    = Vehicle::count();
        $tripsCount       = Trip::count();
        $stationCount     = Station::count();
        $maintenanceCount = MaintenanceLog::count();
        $fuelLogsCount    = FuelLog::count();
        $companiesCount   = MaintenanceCompany::count();

        // ===== Latest Data =====
        $latestDrivers = Driver::with('user')
            ->latest()
            ->take(5)
            ->get();

        $latestMaintenances = MaintenanceLog::with(['vehicle', 'company'])
            ->latest()
            ->take(5)
            ->get();

        // ===== Alerts =====
        $expiringLicenses = Driver::whereDate(
            'license_expiry_date',
            '<=',
            Carbon::now()->addDays(30)
        )->count();

        $vehiclesInMaintenance = Vehicle::where('status', 'InMaintenance')->count();

        // ===== Vehicles Status =====
        $vehiclesStatus = Vehicle::select('status')
            ->selectRaw('COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        // ===== Trips Status =====
        $tripsStatus = Trip::select('status')
            ->selectRaw('COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        // ===== Latest Activities =====
        $latestActivities = collect()
        // Drivers
            ->merge(
                Driver::with('user')->latest()->take(3)->get()->map(fn($d) => [
                    'text'  => 'New driver added: ' . ($d->user->name ?? 'Unknown'),
                    'icon'  => 'fa-id-card',
                    'color' => 'primary',
                    'route' => route('admin.drivers.show', $d->id),
                    'time'  => $d->created_at,
                ])
            )
            // Vehicles
            ->merge(
                Vehicle::latest()->take(3)->get()->map(fn($v) => [
                    'text'  => 'New vehicle added: ' . $v->plate_number,
                    'icon'  => 'fa-car',
                    'color' => 'success',
                    'route' => route('admin.vehicles.show', $v->id),
                    'time'  => $v->created_at,
                ])
            )
            // Trips
            ->merge(
                Trip::latest()->take(3)->get()->map(fn($t) => [
                    'text'  => 'New trip created',
                    'icon'  => 'fa-route',
                    'color' => 'info',
                    'route' => route('admin.trips.show', $t->id),
                    'time'  => $t->created_at,
                ])
            )
            // Maintenances
            ->merge(
                MaintenanceLog::with('vehicle')->latest()->take(3)->get()->map(fn($m) => [
                    'text'  => 'Maintenance for vehicle ' . ($m->vehicle->plate_number ?? '-'),
                    'icon'  => 'fa-tools',
                    'color' => 'warning',
                    'route' => route('admin.maintenance_logs.show', $m->id),
                    'time'  => $m->created_at,
                ])
            )
            // Sort + Limit
            ->sortByDesc('time')
            ->take(6)
            ->values();

        return view('admin.index', compact(
            'driversCount',
            'vehiclesCount',
            'tripsCount',
            'stationCount',
            'maintenanceCount',
            'fuelLogsCount',
            'companiesCount',
            'latestDrivers',
            'latestMaintenances',
            'expiringLicenses',
            'vehiclesInMaintenance',
            'vehiclesStatus',
            'tripsStatus',
            'latestActivities',
        ));
    }
    public function vehiclesByStatus($status)
    {
        $locale = app()->getLocale();

        $vehicles = Vehicle::where('status', $status)->get(['id', 'type', 'model', 'plate_number']);
        $data     = $vehicles->map(function ($v) use ($locale) {
            $type  = method_exists($v, 'getTranslation') ? $v->getTranslation('type', $locale) : $v->type;
            $model = method_exists($v, 'getTranslation') ? $v->getTranslation('model', $locale) : $v->model;
            return [
                'id'           => $v->id,
                'type'         => $type ?? '',
                'model'        => $model ?? '',
                'plate_number' => $v->plate_number ?? '',
            ];
        });

        return response()->json($data);
    }

    public function tripsByStatus($status)
    {
        $locale = app()->getLocale(); // ar أو en
        Carbon::setLocale($locale);

        $trips = Trip::with(['vehicle', 'driver', 'fromStation', 'toStation'])->where('status', $status)->get();
        $data  = $trips->map(fn($t) => [
            'id'         => $t->id,
            'vehicle'    => $t->vehicle->plate_number ?? '',
            'driver'     => $t->driver->user->name ?? '',
            // ارجعيلها
            // 'driver' => optional($t->driver->user)->name ?? '', // مش مقتنعة فيها لانه انا عاملة لما احذف اليوزر ينحذف السائق بس ممكن لو حذفت السائق من جدول السائقين معقول يضل ب جدول اليوزرز؟
            'from'       => $t->fromStation->name ?? '',
            'to'         => $t->toStation->name ?? '',
            'start_time' => $t->start_time,
            'end_time'   => $t->end_time,
        ]);
        return response()->json($data);
    }

}
