<?php

// ... (All your use statements)
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\Driver\DriverDashboardController;
use App\Http\Controllers\Driver\FuelLogController as DriverFuelLogController;
use App\Http\Controllers\Driver\MaintenanceReportController;
use App\Http\Controllers\FuelLogController;
use App\Http\Controllers\MaintenanceCompanyController;
use App\Http\Controllers\MaintenanceLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// ...
Route::prefix(LaravelLocalization::setLocale())->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    });
    Route::get('/auth/{provider}', [SocialAuthController::class, 'redirect'])->name('social.redirect');
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

    Route::middleware(['auth', 'verified'])->group(function () {

        // --- Profile routes (Breeze default) ---
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        // ...

        //======================================================================
        //  DRIVER ROUTES (This is the new group for drivers)
        //======================================================================
        Route::middleware('role:driver')->prefix('driver')->name('driver.')->group(function () {

            Route::get('/dashboard', [DriverDashboardController::class, 'dashboard'])->name('dashboard');
            Route::get('/my-work', [DriverDashboardController::class, 'myWork'])->name('my_work');
            // --- New Routes for Driver Actions ---
            Route::get('/fuel-logs/create', [DriverFuelLogController::class, 'create'])->name('fuel_logs.create');
            Route::post('/fuel-logs', [DriverFuelLogController::class, 'store'])->name('fuel_logs.store');

            Route::get('/maintenance-reports/create', [MaintenanceReportController::class, 'create'])->name('maintenance.report');
            Route::post('/maintenance-reports', [MaintenanceReportController::class, 'store'])->name('maintenance.store');
        });

        //======================================================================
        //  ADMIN ROUTES
        //======================================================================
        Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('index');
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

                // CRUD
                Route::resource('/', TripController::class)->parameters([
                    '' => 'id',
                ]);
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

            Route::prefix('reports')->name('reports.')->group(function () {

                // trash & bulk actions
                Route::get('trash', [ReportController::class, 'trash'])->name('trash');
                Route::get('restore_all', [ReportController::class, 'restore_all'])->name('restore_all');
                Route::get('delete_all', [ReportController::class, 'delete_all'])->name('delete_all');

                // single actions
                Route::get('{id}/restore', [ReportController::class, 'restore'])->name('restore');
                Route::get('{id}/forcedelete', [ReportController::class, 'forcedelete'])->name('forcedelete');

                // CRUD
                Route::resource('/', ReportController::class)->parameters(['' => 'id']);
            });
        });

    });

    require __DIR__ . '/auth.php';
});
