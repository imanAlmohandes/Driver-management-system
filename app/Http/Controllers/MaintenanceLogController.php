<?php
namespace App\Http\Controllers;

use App\Models\MaintenanceCompany;
use App\Models\MaintenanceLog;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class MaintenanceLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MaintenanceLog::with(['vehicle', 'company']);

        $query->when($request->vehicle_id, fn($q) => $q->where('vehicle_id', $request->vehicle_id));
        $query->when($request->company_id, fn($q) => $q->where('company_id', $request->company_id));
        $query->when($request->from_date, fn($q) => $q->whereDate('service_date', '>=', $request->from_date));
        $query->when($request->to_date, fn($q) => $q->whereDate('service_date', '<=', $request->to_date));

        $query->when($request->service_type, fn($q, $type) =>
            $q->where('service_type', 'like', "%{$type}%")
        );
        $query->when($request->min_cost, fn($q, $min) =>
            $q->where('cost', '>=', $min)
        );
        $query->when($request->max_cost, fn($q, $max) =>
            $q->where('cost', '<=', $max)
        );
        $maintenanceLogs = $query->orderByDesc('id')->paginate(10);
        $vehicles        = Vehicle::whereHas('maintenanceLogs')->get();
        $companies       = MaintenanceCompany::whereHas('maintenanceLogs')->get();

        return view('admin.maintenance_logs.index', compact('maintenanceLogs', 'vehicles', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $maintenanceLog = new MaintenanceLog();

        //  إضافة جديدة: التعامل مع المركبة المحددة مسبقاً --- هان لو بدي اغير حالة المركبة
        if ($request->has('vehicle_id')) {
            $maintenanceLog->vehicle_id = $request->vehicle_id;
        }
        // جلب كل المركبات
        $vehicles = Vehicle::all();

        // جلب كل شركات الصيانة
        $companies = MaintenanceCompany::all();

        return view('admin.maintenance_logs.create', compact('maintenanceLog', 'vehicles', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_id'   => 'required|exists:maintenance_companies,id',
            'vehicle_id'   => 'required|exists:vehicles,id',
            'service_type' => 'required|string|max:255',
            'cost'         => 'required|numeric|min:0',
            'service_date' => 'required|date|before_or_equal:today',
            'notes'        => 'nullable|string',
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        // آخر صيانة لهاي المركبة
        $lastMaintenance = MaintenanceLog::where('vehicle_id', $vehicle->id)
            ->orderByDesc('service_date')
            ->first();

        // قيد بعد تاريخ إنشاء المركبة
        if ($request->service_date < $vehicle->created_at->toDateString()) {
            return back()->withErrors([
                'service_date' => "The maintenance date cannot be earlier than the vehicle's manufacturing date.",
            ])->withInput();
        }

        //  قيد بعد آخر صيانة
        if ($lastMaintenance && $request->service_date <= $lastMaintenance->service_date) {
            return back()->withErrors([
                'service_date' => "The maintenance date should be after the vehicle's last service.",
            ])->withInput();
        }

        MaintenanceLog::create([
            'company_id'   => $request->company_id,
            'vehicle_id'   => $request->vehicle_id,
            'service_type' => $request->service_type,
            'cost'         => $request->cost,
            'service_date' => $request->service_date,
            'notes'        => $request->notes,
        ]);

        // --- إضافة جديدة: تحديث حالة المركبة --- هان لو بدي اغير حالة المركبة بجدول المركبات
        $vehicle = Vehicle::find($request->vehicle_id);
        if ($vehicle) {
            $vehicle->update(['status' => 'InMaintenance']);
        }

        return redirect()
            ->route('admin.maintenance_logs.index')
            ->with('msg', __('driver.maintenance_log_created'))->with('type', 'success');
    }
    public function getLastServiceDate($vehicleId)
    {
        // هاي الميثود للعرض ف صفحة الداشبورد الرئيسية
        $lastLog = MaintenanceLog::where('vehicle_id', $vehicleId)
            ->orderByDesc('service_date')
            ->first();

        return response()->json([
            'last_service_date' => $lastLog ? $lastLog->service_date->format('Y-m-d') : null,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $maintenanceLog = MaintenanceLog::find($id);
        if (! $maintenanceLog) {
            return redirect()->route('admin.maintenance_logs.index');
        }
        return view('admin.maintenance_logs.show', compact('maintenanceLog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $maintenanceLog = MaintenanceLog::findOrFail($id);

        // No need to pass vehicles/companies as they will be readonly
        // $vehicles       = Vehicle::all();
        // $companies      = MaintenanceCompany::all();

        return view('admin.maintenance_logs.edit', compact('maintenanceLog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $maintenanceLog = MaintenanceLog::findOrFail($id);
        $vehicle        = $maintenanceLog->vehicle; // المركبة الأصلية

        $request->validate([
            // 'company_id'   => 'required|exists:maintenance_companies,id',
            // 'vehicle_id'   => 'required|exists:vehicles,id',
            'service_type' => 'required',
            'cost'         => 'required|numeric|min:0',
            'service_date' => 'required|date|before_or_equal:today',
            'notes'        => 'nullable|string',
        ]);

        $oldDate = $maintenanceLog->service_date->toDateString();
        $newDate = $request->service_date;

// فقط إذا تغيّر التاريخ
        if ($newDate !== $oldDate) {

            // قيد بعد تاريخ إنشاء المركبة
            if ($newDate < $vehicle->created_at->toDateString()) {
                return back()->withErrors([
                    'service_date' => "The maintenance date cannot be earlier than the vehicle creation date.",
                ]);
            }

            // آخر صيانة (استثناء الحالي)
            $lastMaintenance = MaintenanceLog::where('vehicle_id', $maintenanceLog->vehicle_id)
                ->where('id', '!=', $maintenanceLog->id)
                ->orderByDesc('service_date')
                ->first();

            if ($lastMaintenance && $newDate <= $lastMaintenance->service_date->toDateString()) {
                return back()->withErrors([
                    'service_date' => "The maintenance date must be after the last maintenance date.",
                ]);
            }
        }

        // // ممنوع تغيير المركبة
        // if ($request->vehicle_id && $request->vehicle_id != $maintenanceLog->vehicle_id) {
        //     return back()->withErrors([
        //         'vehicle_id' => 'The vehicle associated with the maintenance record cannot be changed.',
        //     ]);
        // }
        // if ($request->service_date < $vehicle->created_at->toDateString()) {
        //     return back()->withErrors([
        //         'service_date' => "The maintenance date cannot be earlier than the vehicle's manufacturing date",
        //     ]);
        // }

        // $originalServiceDate = $maintenanceLog->service_date->format('Y-m-d');
        // $newServiceDate      = $request->service_date;

        // // فحص فقط إذا التاريخ تغير
        // if ($newServiceDate !== $originalServiceDate) {

        //     $lastMaintenance = MaintenanceLog::where('vehicle_id', $maintenanceLog->vehicle_id)
        //         ->where('id', '!=', $id)
        //         ->orderByDesc('service_date')
        //         ->first();

        //     if ($lastMaintenance && $newServiceDate <= $lastMaintenance->service_date->format('Y-m-d')) {
        //         return back()->withErrors([
        //             'service_date' => "The maintenance date should be after the vehicle's last service.",
        //         ])->withInput();
        //     }
        // }

        // //  آخر صيانة (استثناء الحالي)
        // $lastMaintenance = MaintenanceLog::where('vehicle_id', $maintenanceLog->vehicle_id)
        //     ->where('id', '!=', $id)
        //     ->orderByDesc('service_date')
        //     ->first();

        // if ($lastMaintenance && $request->service_date <= $lastMaintenance->service_date) {
        //     return back()->withErrors([
        //         'service_date' => "The maintenance date should be after the vehicle's last service.",
        //     ]);
        // }

        $maintenanceLog->update([
            // 'company_id'   => $request->company_id,
            // 'vehicle_id'   => $request->vehicle_id,
            'service_type' => $request->service_type,
            'cost'         => $request->cost,
            'service_date' => $request->service_date,
            'notes'        => $request->notes,
        ]);

        return redirect()
            ->route('admin.maintenance_logs.index')
            ->with('msg', __('driver.maintenance_log_updated'))->with('type', 'info');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $log = MaintenanceLog::find($id);

        if (! $log) {
            return redirect()
                ->route('admin.maintenance_logs.index')
                ->with('msg', __('driver.maintenance_log_not_found'))
                ->with('type', 'danger');
        }

        $log->delete();

        return redirect()
            ->route('admin.maintenance_logs.index')
            ->with('msg', __('driver.maintenance_log_deleted'))
            ->with('type', 'warning');
    }

    public function trash()
    {
        $maintenanceLogs = MaintenanceLog::onlyTrashed()->orderByDesc('id')->get();
        return view('admin.maintenance_logs.trash', compact('maintenanceLogs'));
    }

    public function restore($id)
    {
        MaintenanceLog::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.maintenance_logs.trash');
    }

    public function forcedelete($id)
    {
        MaintenanceLog::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.maintenance_logs.trash');
    }

    public function restore_all()
    {
        MaintenanceLog::onlyTrashed()->restore();
        return redirect()->route('admin.maintenance_logs.trash');
    }

    public function delete_all()
    {
        MaintenanceLog::onlyTrashed()->forceDelete();
        return redirect()->route('admin.maintenance_logs.trash');
    }
}
