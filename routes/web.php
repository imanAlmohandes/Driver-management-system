<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\DriverInvitationController;
use App\Http\Controllers\Driver\DriverDashboardController;
use App\Http\Controllers\Driver\DriverTripController;
use App\Http\Controllers\Driver\FuelLogController as DriverFuelLogController;
use App\Http\Controllers\Driver\MaintenanceReportController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FuelLogController;
use App\Http\Controllers\MaintenanceCompanyController;
use App\Http\Controllers\MaintenanceLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// Route::get('/test-mail', function () {
//     Mail::raw('Hello from Laravel', function ($msg) {
//         $msg->to('imanawni120@gmail.com')
//             ->subject('Test Mail');
//     });
//     return 'sent';
// });

Route::prefix(LaravelLocalization::setLocale())->group(function () {
    // Guest: driver completes registration from email
    Route::middleware('guest')->prefix('driver')->name('driver.')->group(function () {
        Route::prefix('invite')->name('invite.')->group(function () {
            Route::get('{token}', [DriverInvitationController::class, 'show'])->name('show');
            Route::post('{token}', [DriverInvitationController::class, 'store'])->name('store');
            Route::post('{token}/resend', [DriverInvitationController::class, 'resend'])->name('resend');
        });
    });

    Route::get('/', function () {
        return view('auth.login');
    });
    Route::middleware(['auth', 'verified'])->group(function () {

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::middleware('role:driver')->prefix('driver')->name('driver.')->group(function () {
            // Driver Routes
            Route::get('/dashboard', [DriverDashboardController::class, 'dashboard'])->name('dashboard');
            Route::get('/my-work', [DriverDashboardController::class, 'myWork'])->name('my_work');
            // لانشاء سجل وقود او صيانة
            Route::get('/fuel-logs/create', [DriverFuelLogController::class, 'create'])->name('fuel_logs.create');
            Route::post('/fuel-logs', [DriverFuelLogController::class, 'store'])->name('fuel_logs.store');
            Route::get('/maintenance-reports/create', [MaintenanceReportController::class, 'create'])->name('maintenance.create');
            Route::post('/maintenance-reports', [MaintenanceReportController::class, 'store'])->name('maintenance.store');

            // Routes for Trip Actions لتغيير حالة الرحلة
            Route::post('/trips/{trip}/start', [DriverTripController::class, 'start'])->name('trips.start');
            Route::post('/trips/{trip}/complete', [DriverTripController::class, 'complete'])->name('trips.complete');
            Route::post('/trips/{trip}/cancel', [DriverTripController::class, 'cancel'])->name('trips.cancel');

        });
        Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('index');

            // Admin sends invitation email
            Route::get('/drivers/invite', [DriverInvitationController::class, 'create'])->name('drivers.invite');
            Route::post('/drivers/invite', [DriverInvitationController::class, 'send'])->name('drivers.invite.send');

            // للحالات عشان يعرضهن بالاجاكس
            Route::get('vehicles-by-status/{status}', [AdminController::class, 'vehiclesByStatus']);
            Route::get('trips-by-status/{status}', [AdminController::class, 'tripsByStatus']);

            Route::prefix('drivers')->name('drivers.')->group(function () {
                Route::post('{user}/toggle-status', [DriverController::class, 'toggleStatus'])->name('toggle_status');

                Route::get('trash', [DriverController::class, 'trash'])->name('trash');
                Route::get('restore_all', [DriverController::class, 'restore_all'])->name('restore_all');
                Route::get('delete_all', [DriverController::class, 'delete_all'])->name('delete_all');

                Route::get('{id}/restore', [DriverController::class, 'restore'])->name('restore');
                Route::get('{id}/forcedelete', [DriverController::class, 'forcedelete'])->name('forcedelete');

                Route::resource('/', DriverController::class)->parameters([
                    '' => 'id',
                ]);
            });

            Route::prefix('stations')->name('stations.')->group(function () {

                // trash & bulk actions
                Route::get('trash', [StationController::class, 'trash'])->name('trash');
                Route::get('restore_all', [StationController::class, 'restore_all'])->name('restore_all');
                Route::get('delete_all', [StationController::class, 'delete_all'])->name('delete_all');

                // single actions
                Route::get('{id}/restore', [StationController::class, 'restore'])->name('restore');
                Route::get('{id}/forcedelete', [StationController::class, 'forcedelete'])->name('forcedelete');

                // CRUD
                Route::resource('/', StationController::class)->parameters([
                    '' => 'id',
                ]);
            });

            Route::prefix('trips')->name('trips.')->group(function () {
                // عشان يغير الادمن حالة الرحلة من فيوز الاندكس
                Route::post('{trip}/change-status', [TripController::class, 'changeStatus'])->name('change_status');
                // trash & bulk actions
                Route::get('trash', [TripController::class, 'trash'])->name('trash');
                Route::get('restore_all', [TripController::class, 'restore_all'])->name('restore_all');
                Route::get('delete_all', [TripController::class, 'delete_all'])->name('delete_all');

                // single actions
                Route::get('{id}/restore', [TripController::class, 'restore'])->name('restore');
                Route::get('{id}/forcedelete', [TripController::class, 'forcedelete'])->name('forcedelete');

                // عشان لو بده يكنسل الرحلة
                Route::post('{trip}/cancel', [TripController::class, 'cancel'])->name('cancel');

                // CRUD
                Route::resource('/', TripController::class)->parameters(['' => 'id']);
            });

            Route::prefix('fuel-logs')->name('fuel_logs.')->group(function () {

                Route::get('trips/{driver}', [FuelLogController::class, 'getTripsByDriver'])->name('trips_by_driver');

                Route::get('trash', [FuelLogController::class, 'trash'])->name('trash');
                Route::get('restore_all', [FuelLogController::class, 'restore_all'])->name('restore_all');
                Route::get('delete_all', [FuelLogController::class, 'delete_all'])->name('delete_all');

                Route::get('{id}/restore', [FuelLogController::class, 'restore'])->name('restore');
                Route::get('{id}/forcedelete', [FuelLogController::class, 'forcedelete'])->name('forcedelete');

                Route::resource('/', FuelLogController::class)->parameters(['' => 'id']);
            });

            Route::prefix('vehicles')->name('vehicles.')->group(function () {
                Route::post('{vehicle}/change-status', [VehicleController::class, 'changeStatus'])->name('change_status');

                Route::get('trash', [VehicleController::class, 'trash'])->name('trash');
                Route::get('restore_all', [VehicleController::class, 'restore_all'])->name('restore_all');
                Route::get('delete_all', [VehicleController::class, 'delete_all'])->name('delete_all');

                Route::get('{id}/restore', [VehicleController::class, 'restore'])->name('restore');
                Route::get('{id}/forcedelete', [VehicleController::class, 'forcedelete'])->name('forcedelete');

                Route::resource('/', VehicleController::class)->parameters(['' => 'id']);
            });

            Route::prefix('maintenance-logs')->name('maintenance_logs.')->group(function () {
                Route::get('last_service/{vehicleId}', [MaintenanceLogController::class, 'getLastServiceDate']);

                Route::get('trash', [MaintenanceLogController::class, 'trash'])->name('trash');
                Route::get('restore_all', [MaintenanceLogController::class, 'restore_all'])->name('restore_all');
                Route::get('delete_all', [MaintenanceLogController::class, 'delete_all'])->name('delete_all');

                Route::get('{id}/restore', [MaintenanceLogController::class, 'restore'])->name('restore');
                Route::get('{id}/forcedelete', [MaintenanceLogController::class, 'forcedelete'])->name('forcedelete');

                Route::resource('/', MaintenanceLogController::class)->parameters(['' => 'id']);
            });

            Route::prefix('maintenance-companies')->name('maintenance_companies.')->group(function () {

                Route::get('trash', [MaintenanceCompanyController::class, 'trash'])->name('trash');
                Route::get('{id}/restore', [MaintenanceCompanyController::class, 'restore'])->name('restore');
                Route::get('{id}/forcedelete', [MaintenanceCompanyController::class, 'forcedelete'])->name('forcedelete');
                Route::get('restore_all', [MaintenanceCompanyController::class, 'restore_all'])->name('restore_all');
                Route::get('delete_all', [MaintenanceCompanyController::class, 'delete_all'])->name('delete_all');

                Route::resource('/', MaintenanceCompanyController::class)->parameters(['' => 'id']);
            });

            Route::prefix('export')->name('export.')->group(function () {

                // Trips
                Route::get('trips', [ExportController::class, 'exportTrips'])->name('trips');
                // Drivers
                Route::get('drivers', [ExportController::class, 'exportDrivers'])->name('drivers');
                // Vehicles
                Route::get('vehicles', [ExportController::class, 'exportVehicles'])->name('vehicles');
                // Fuel Logs
                Route::get('fuel-logs', [ExportController::class, 'exportFuelLogs'])->name('fuel_logs');
                // Stations
                Route::get('stations', [ExportController::class, 'exportStations'])->name('stations');
                // Maintenance Logs
                Route::get('maintenance-logs', [ExportController::class, 'exportMaintenanceLogs'])->name('maintenance_logs');
                // Maintenance Companies
                Route::get('maintenance-companies', [ExportController::class, 'exportMaintenanceCompanies'])->name('maintenance_companies');
            });

            Route::prefix('notifications')->name('notifications.')->group(function () {

                // Route to display all notifications in a dedicated page
                Route::get('/', function () {
                    $notifications = Auth::user()->notifications()->paginate(20);
                    return view('admin.notifications.index', compact('notifications'));
                })->name('index');

                // Route for the navbar dropdown (AJAX)
                Route::get('/json', function () {
                    $user = Auth::user();
                    return response()->json([
                        'notifications' => $user->notifications()->latest()->take(5)->get(),
                        // 'unread'        => $user->unreadNotifications()->count(),
                        'unread'        => $user->notifications()->whereNull('read_at')->count(),

                    ]);
                })->name('json');

                // Route to mark a notification as read and redirect
                Route::get('/{id}/read', function ($id) {
                    $notification = Auth::user()->notifications()->find($id);
                    if ($notification) {
                        $notification->markAsRead();
                        return redirect($notification->data['route'] ?? route('admin.dashboard'));
                    }
                    return redirect()->back();
                })->name('read');

                // Route to mark all as read
                Route::get('/read-all', function () {
                    // Auth::user()->unreadNotifications->markAsRead();
                    // نفس الل فوق بس باختصار
                    Auth::user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);
                    return back()->with('msg', 'All notifications marked as read.')->with('type', 'success');
                })->name('read_all');

                // Route to delete a single notification
                Route::delete('/{id}', function ($id) {
                    Auth::user()->notifications()->find($id)->delete();
                    return back()->with('msg', 'Notification deleted.')->with('type', 'info');
                })->name('destroy');

                // Route to delete all notifications
                Route::get('/delete-all', function () {
                    Auth::user()->notifications()->delete();
                    return back()->with('msg', 'All notifications have been deleted.')->with('type', 'danger');
                })->name('destroy_all');
            });
        });
    });

    require __DIR__ . '/auth.php';
    // لغيت صفحات الريجيستر
    Route::get('/register', fn() => abort(404));
    Route::post('/register', fn() => abort(404));

    // سوشيال ميديا
    Route::get('/auth/{provider}', [SocialAuthController::class, 'redirect'])->name('social.redirect');
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

});
