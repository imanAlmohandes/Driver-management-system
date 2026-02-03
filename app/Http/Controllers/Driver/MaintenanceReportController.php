<?php
namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceCompany;
use App\Models\MaintenanceLog;
use App\Models\User;
use App\Notifications\AdminDashboardNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// Assuming you will report to this model

class MaintenanceReportController extends Controller
{
    /**
     * Show the form for creating a new maintenance report.
     */
    public function create()
    {
        $maintenanceLog = new MaintenanceLog();

        // Get only the vehicles currently assigned to this driver
        $vehicles = Auth::user()->driver->vehicles()->wherePivotNull('end_date')->get();

        // It's better to let the admin assign the company later,
        // but we can pass them all if the driver needs to choose.
        $companies = MaintenanceCompany::all();

        return view('driver.maintenance_reports.create', compact('maintenanceLog', 'vehicles', 'companies'));
    }

    /**
     * Store a newly created maintenance report in storage.
     */
    // public function store(Request $request)
    // {
    //     // For a driver, we only need a few fields. Cost and service_date will be filled by admin.
    //     $data = $request->validate([
    //         'vehicle_id'   => 'required|exists:vehicles,id',
    //         'service_type' => 'required|string|max:255', // Example: 'Flat Tire', 'Engine Noise'
    //         'notes'        => 'required|string',
    //     ]);

    //                                    // Automatically set some default values
    //     $data['cost']         = 0;     // Cost is initially 0, to be updated by admin
    //     $data['service_date'] = now(); // The date the report was made

    //     // We can assign a default "Pending Review" company or leave it null
    //     $data['company_id'] = MaintenanceCompany::firstOrCreate(['name' => 'Pending Review'])->id;

    //     $log = MaintenanceLog::create($data);

    //     // To-Do: Send a notification to the admin about this new report.

    //     return redirect()->route('driver.dashboard')->with('msg', 'Issue Reported Successfully! The admin has been notified.')->with('type', 'success');
    // }
    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicle_id'       => 'required|exists:vehicles,id',
            'service_type'     => 'required|string|max:255',
            'notes'            => 'required|string',
            'company_id'       => 'nullable|exists:maintenance_companies,id',             // Can be from the list
            'new_company_name' => 'nullable|string|max:255|required_if:company_id,other', // Required only if "Other" is selected
        ]);

        // Determine the company ID
        $companyId = $request->company_id;
        if ($companyId === 'other' && $request->filled('new_company_name')) {
            // Find or create the new company and get its ID
            $newCompany = MaintenanceCompany::firstOrCreate(['name' => $request->new_company_name]);
            $companyId  = $newCompany->id;
        }

        // Prepare data for the maintenance log
        $logData = [
            'vehicle_id'   => $data['vehicle_id'],
            'company_id'   => $companyId,
            'service_type' => $data['service_type'],
            'cost'         => 0, // Admin will update this
            'service_date' => now(),
            'notes'        => $data['notes'],
        ];

        $log = MaintenanceLog::create($logData);
        //  تغيير حالة المركبة إلى "تحت الصيانة"
        if ($log->vehicle) {
            $log->vehicle->update(['status' => 'InMaintenance']);
        }

        // ارسال الاشعار للادمن
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new AdminDashboardNotification([
                'text'  => 'Driver ' . Auth::user()->name . ' reported an issue for vehicle ' . $log->vehicle->plate_number,
                'icon'  => 'fa-tools',
                'color' => 'danger',
                'route' => route('admin.maintenance_logs.show', $log->id),
            ]));
        }

        return redirect()->route('driver.dashboard')->with('msg', __('driver.issue_reported'))->with('type', 'success');
    }

}
