<?php
namespace App\Providers;

use App\Models\MaintenanceLog;
use App\Models\Trip;
use App\Observers\MaintenanceLogObserver;
use App\Observers\TripObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();
        // Paginator::useTailwind();

        Trip::observe(TripObserver::class);
        MaintenanceLog::observe(MaintenanceLogObserver::class);

    }
}
