<?php
namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\FuelLog;
use App\Models\MaintenanceCompany;
use App\Models\MaintenanceLog;
use App\Models\Station;
use App\Models\Trip;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function exportTrips(Request $request): StreamedResponse
    {
        $fileName = 'trips_export_' . now()->format('Y_m_d_H_i') . '.csv';

        return response()->streamDownload(function () use ($request) {

            $handle = fopen('php://output', 'w');
                                             // عشان العربي والانجليزي يفتح صح بكلا الحالتين
            fwrite($handle, "\xEF\xBB\xBF"); // BOM

            // عناوين الأعمدة
            fputcsv($handle, [
                __('driver.id'),
                __('driver.driver'),
                __('driver.vehicle'),
                __('driver.from'),
                __('driver.to'),
                __('driver.start_time'),
                __('driver.end_time'),
                __('driver.distance_km'),
                __('driver.status'),
                __('driver.created_at'),
                __('driver.updated_at'),
            ], ';');

            //  نفس فلترة صفحة الاندكس
            $query = Trip::with(['driver.user', 'vehicle', 'fromStation', 'toStation']);

            $query->when($request->driver_id, fn($q) => $q->where('driver_id', $request->driver_id));
            $query->when($request->vehicle_id, fn($q) => $q->where('vehicle_id', $request->vehicle_id));
            $query->when($request->status, fn($q) => $q->where('status', $request->status));
            $query->when($request->from_date, fn($q) => $q->whereDate('start_time', '>=', $request->from_date));
            $query->when($request->to_date, fn($q) => $q->whereDate('start_time', '<=', $request->to_date));

            $query->chunk(200, function ($trips) use ($handle) {
                foreach ($trips as $trip) {
                    fputcsv($handle, [
                        $trip->id,
                        $trip->driver?->user?->name,
                        $trip->vehicle?->plate_number,
                        $trip->fromStation?->name,
                        $trip->toStation?->name,
                        $trip->start_time,
                        $trip->end_time,
                        $trip->distance_km,
                        $trip->status,
                        $trip->created_at,
                        $trip->updated_at,
                    ], ';');
                }
            });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }
    public function exportDrivers(Request $request)
    {
        $query = Driver::query()->with('user');

        //  نفس فلترة صفحة الإندكس
        $query->when($request->search, function ($q) use ($request) {
            $q->where(function ($subQ) use ($request) {
                $subQ->where('license_number', 'like', "%{$request->search}%")
                    ->orWhereHas('user', function ($userQ) use ($request) {
                        $userQ->where('name', 'like', "%{$request->search}%");
                    });
            });
        });

        $query->when($request->status, function ($q) use ($request) {
            $q->whereHas('user', function ($userQ) use ($request) {
                $userQ->where('is_active', $request->status === 'active');
            });
        });

        $drivers = $query->orderBy('id')->get();

        $fileName = 'drivers_export_' . now()->format('Y_m_d_H_i') . '.csv';

        return response()->streamDownload(function () use ($drivers) {
            $handle = fopen('php://output', 'w');

            // BOM عشان Excel العربي
            fwrite($handle, "\xEF\xBB\xBF");

            // Header
            fputcsv($handle, [
                '#',
                __('driver.name'),
                __('driver.status'),
                __('driver.license_number'),
                __('driver.license_type'),
                __('driver.license_expiry'),
                __('driver.created_at'),
                __('driver.updated_at'),
            ], ';');

            foreach ($drivers as $driver) {
                fputcsv($handle, [
                    $driver->id,
                    $driver->user?->name,
                    $driver->user?->is_active ? 'Active' : 'Inactive',
                    $driver->license_number,
                    $driver->license_type,
                    $driver->license_expiry_date,
                    $driver->created_at,
                    $driver->updated_at,
                ], ';');
            }

            fclose($handle);
        }, $fileName);
    }

    public function exportVehicles(Request $request)
    {
        $query = Vehicle::query();

        // نفس فلترة صفحة الإندكس
        $query->when($request->status, fn($q) =>
            $q->where('status', $request->status)
        );

        $query->when($request->search, function ($q) use ($request) {
            $q->where(function ($subQ) use ($request) {
                $subQ->where('plate_number', 'like', "%{$request->search}%")
                    ->orWhere('type', 'like', "%{$request->search}%")
                    ->orWhere('model', 'like', "%{$request->search}%");
            });
        });

        $vehicles = $query->orderBy('id')->get();

        $fileName = 'vehicles_export_' . now()->format('Y_m_d_H_i') . '.csv';

        return response()->streamDownload(function () use ($vehicles) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                '#',
                __('driver.plate_number'),
                __('driver.type'),
                __('driver.model'),
                __('driver.status'),
                __('driver.created_at'),
                __('driver.updated_at'),
            ], ';');

            foreach ($vehicles as $vehicle) {
                fputcsv($handle, [
                    $vehicle->id,
                    $vehicle->plate_number,
                    $vehicle->type,
                    $vehicle->model,
                    $vehicle->status,
                    $vehicle->created_at,
                    $vehicle->updated_at,
                ], ';');
            }

            fclose($handle);
        }, $fileName);
    }

    public function exportFuelLogs(Request $request)
    {
        $query = FuelLog::with(['driver.user', 'trip.vehicle']);

        // نفس الفلترة
        $query->when($request->driver_id, fn($q) =>
            $q->where('driver_id', $request->driver_id)
        );

        $query->when($request->from_date, fn($q) =>
            $q->whereDate('log_date', '>=', $request->from_date)
        );

        $query->when($request->to_date, fn($q) =>
            $q->whereDate('log_date', '<=', $request->to_date)
        );

        $fuelLogs = $query->orderBy('id')->get();

        $fileName = 'fuel_logs_export_' . now()->format('Y_m_d_H_i') . '.csv';

        return response()->streamDownload(function () use ($fuelLogs) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF"); // BOM

            fputcsv($handle, [
                '#',
                __('driver.driver'),
                __('driver.vehicle'),
                __('driver.station'),
                __('driver.fuel_amount'),
                __('driver.fuel_cost'),
                __('driver.log_date'),
                __('driver.created_at'),
                __('driver.updated_at'),
            ], ';');

            foreach ($fuelLogs as $log) {
                fputcsv($handle, [
                    $log->id,
                    $log->driver?->user?->name,
                    $log->trip?->vehicle?->plate_number,
                    $log->station_name,
                    $log->fuel_amount,
                    $log->fuel_cost,
                    $log->log_date,
                    $log->created_at,
                    $log->updated_at,
                ], ';');
            }

            fclose($handle);
        }, $fileName);
    }
    public function exportStations(Request $request)
    {
        $query = Station::query();

        // نفس الفلترة
        $query->when($request->search, function ($q) use ($request) {
            $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('city', 'like', "%{$request->search}%");
        });

        $stations = $query->orderBy('id')->get();

        $fileName = 'stations_export_' . now()->format('Y_m_d_H_i') . '.csv';

        return response()->streamDownload(function () use ($stations) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                '#',
                __('driver.name'),
                __('driver.city'),
                __('driver.created_at'),
                __('driver.updated_at'),
            ], ';');

            foreach ($stations as $station) {
                fputcsv($handle, [
                    $station->id,
                    $station->name,
                    $station->city,
                    $station->created_at,
                    $station->updated_at,

                ], ';');
            }

            fclose($handle);
        }, $fileName);
    }
    public function exportMaintenanceLogs(Request $request)
    {
        $query = MaintenanceLog::with(['vehicle', 'company']);

        // نفس الفلاتر
        $query->when($request->vehicle_id, fn($q) =>
            $q->where('vehicle_id', $request->vehicle_id)
        );

        $query->when($request->company_id, fn($q) =>
            $q->where('company_id', $request->company_id)
        );

        $query->when($request->from_date, fn($q) =>
            $q->whereDate('service_date', '>=', $request->from_date)
        );

        $query->when($request->to_date, fn($q) =>
            $q->whereDate('service_date', '<=', $request->to_date)
        );

        $query->when($request->service_type, fn($q, $type) =>
            $q->where('service_type', 'like', "%{$type}%")
        );

        $query->when($request->min_cost, fn($q, $min) =>
            $q->where('cost', '>=', $min)
        );

        $query->when($request->max_cost, fn($q, $max) =>
            $q->where('cost', '<=', $max)
        );

        $logs = $query->orderBy('id')->get();

        $fileName = 'maintenance_logs_export_' . now()->format('Y_m_d_H_i') . '.csv';

        return response()->streamDownload(function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF"); // BOM

            fputcsv($handle, [
                '#',
                __('driver.vehicle'),
                __('driver.company'),
                __('driver.service_type'),
                __('driver.cost'),
                __('driver.service_date'),
                __('driver.notes'),
                __('driver.created_at'),
                __('driver.updated_at'),
            ], ';');

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->id,
                    $log->vehicle?->plate_number,
                    $log->company?->name,
                    $log->service_type,
                    $log->cost,
                    $log->service_date,
                    $log->notes,
                    $log->created_at,
                    $log->updated_at,

                ], ';');
            }

            fclose($handle);
        }, $fileName);
    }
    public function exportMaintenanceCompanies(Request $request)
    {
        $query = MaintenanceCompany::withCount('maintenanceLogs');

        // نفس فلترة
        $query->when($request->search, function ($q) use ($request) {
            $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('phone', 'like', "%{$request->search}%");
        });

        // نفس ترتيب اللوجز
        if ($request->logs_filter === 'most') {
            $query->orderBy('maintenance_logs_count');
        } elseif ($request->logs_filter === 'lest') {
            $query->orderBy('maintenance_logs_count');
        } else {
            $query->orderBy('id');
        }

        $companies = $query->get();

        $fileName = 'maintenance_companies_export_' . now()->format('Y_m_d_H_i') . '.csv';

        return response()->streamDownload(function () use ($companies) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                '#',
                __('driver.name'),
                __('driver.maintenanceLogs'),
                __('driver.phone'),
                __('driver.address'),
                __('driver.created_at'),
                __('driver.updated_at'),
            ], ';');

            foreach ($companies as $company) {
                fputcsv($handle, [
                    $company->id,
                    $company->name,
                    $company->maintenance_logs_count,
                    $company->phone,
                    $company->address,
                    $company->created_at,
                    $company->updated_at,
                ], ';');
            }

            fclose($handle);
        }, $fileName);
    }

}
