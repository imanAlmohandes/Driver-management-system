<?php
namespace App\Http\Controllers;

use App\Models\MaintenanceCompany;
use Illuminate\Http\Request;

class MaintenanceCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $companies = MaintenanceCompany::withCount('maintenanceLogs')
    //         ->orderByDesc('id')->paginate(10);
    //     return view('admin.maintenance_companies.index', compact('companies'));
    // }
    public function index(Request $request)
    {
        // Start with the base query and add a count of related maintenance logs
        $query = MaintenanceCompany::withCount('maintenanceLogs');

        // Filter by search term (name or phone)
        $query->when($request->search, function ($q, $search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        });

          // Filter by log count (most or lest)
        $filterByLogs = $request->get('logs_filter');
        if ($filterByLogs == 'most') {
            $query->orderByDesc('maintenance_logs_count');
        } elseif ($filterByLogs == 'lest') {
            $query->orderBy('maintenance_logs_count', 'asc');
        } else {
            // Default order if no log filter is applied
            $query->orderByDesc('id');
        }

        $companies = $query->paginate(10);

        return view('admin.maintenance_companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $company = new MaintenanceCompany();
        return view('admin.maintenance_companies.create', compact('company'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        MaintenanceCompany::create($request->all());

        return redirect()->route('admin.maintenance_companies.index')
            ->with('msg', __('driver.maintenance_company_created'))->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $company = MaintenanceCompany::find($id);
        if (! $company) {
            return redirect()->route('admin.maintenance_companies.index');
        }
        return view('admin.maintenance_companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $company = MaintenanceCompany::findOrFail($id);
        return view('admin.maintenance_companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $company = MaintenanceCompany::findOrFail($id);

        // عندها سجلات صيانة؟
        if ($company->maintenanceLogs()->exists()) {
            // الاسم تغيّر؟
            if ($request->name !== $company->name) {
                return back()->withErrors([
                    'name' => 'The company name cannot be changed because it is linked to maintenance records.',
                ]);
            }
        }

        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        // نحدث فقط المسموح
        $company->update([
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.maintenance_companies.index')
            ->with('msg',__('driver.maintenance_company_updated') )->with('type', 'info');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $company = MaintenanceCompany::findOrFail($id);

        if ($company->maintenanceLogs()->count() > 0) {
            return redirect()->route('admin.maintenance_companies.index')
                ->with('msg',__('driver.company_delete_has_logs'))->with('type', 'danger');
        }

        $company->delete();

        return redirect()->route('admin.maintenance_companies.index')
            ->with('msg', __('driver.maintenance_company_deleted'))->with('type', 'warning');
    }

    public function trash()
    {
        $companies = MaintenanceCompany::onlyTrashed()->orderByDesc('id')->get();
        return view('admin.maintenance_companies.trash', compact('companies'));
    }

    public function restore($id)
    {
        MaintenanceCompany::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.maintenance_companies.trash');
    }

    public function forcedelete($id)
    {
        MaintenanceCompany::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.maintenance_companies.trash');
    }

    public function restore_all()
    {
        MaintenanceCompany::onlyTrashed()->restore();
        return redirect()->route('admin.maintenance_companies.trash');
    }

    public function delete_all()
    {
        MaintenanceCompany::onlyTrashed()->forceDelete();
        return redirect()->route('admin.maintenance_companies.trash');
    }
}
