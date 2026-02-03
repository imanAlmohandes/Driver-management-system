<?php
namespace App\Observers;

use App\Models\Vehicle;
use App\Models\MaintenanceLog;
use App\Services\VehicleStatusService;

class MaintenanceLogObserver
{
    /**
     * Handle the MaintenanceLog "created" event.
     */
    public function created(MaintenanceLog $log): void
    {
        VehicleStatusService::refresh($log->vehicle);
    }

    /**
     * Handle the MaintenanceLog "updated" event.
     */
    public function updated(MaintenanceLog $log): void
    {
        VehicleStatusService::refresh($log->vehicle);
    }

    /**
     * Handle the MaintenanceLog "deleted" event.
     */
    public function deleted(MaintenanceLog $log): void
    {
        if ($log->vehicle_id) {
            $vehicle = Vehicle::find($log->vehicle_id);

            if ($vehicle) {
                VehicleStatusService::refresh($vehicle);
            }

        }
    }

    /**
     * Handle the MaintenanceLog "restored" event.
     */
    public function restored(MaintenanceLog $log): void
    {
        //
    }

    /**
     * Handle the MaintenanceLog "force deleted" event.
     */
    public function forceDeleted(MaintenanceLog $log): void
    {
        //
    }
}
