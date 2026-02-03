<?php
namespace App\Observers;

use App\Models\Trip;
use App\Services\VehicleStatusService;

class TripObserver
{
    /**
     * Handle the Trip "created" event.
     */
    public function created(Trip $trip): void
    {
        VehicleStatusService::refresh($trip->vehicle);
    }

    /**
     * Handle the Trip "updated" event.
     */
    public function updated(Trip $trip): void
    {
        VehicleStatusService::refresh($trip->vehicle);
    }

    /**
     * Handle the Trip "deleted" event.
     */
    public function deleted(Trip $trip): void
    {
        VehicleStatusService::refresh($trip->vehicle);
    }

    /**
     * Handle the Trip "restored" event.
     */
    public function restored(Trip $trip): void
    {
        //
    }

    /**
     * Handle the Trip "force deleted" event.
     */
    public function forceDeleted(Trip $trip): void
    {
        //
    }
}
