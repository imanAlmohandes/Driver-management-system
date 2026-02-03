<?php
namespace App\Services;

use App\Models\Vehicle;

class VehicleStatusService
{
    public static function refresh(?Vehicle $vehicle): void
    {
        if (! $vehicle) {
            return;
        }

        // صيانة فعّالة
        $inMaintenance = $vehicle->maintenanceLogs()
            ->whereNull('end_date')
            ->exists();

        if ($inMaintenance) {
            $vehicle->update(['status' => 'InMaintenance']);
            return;
        }

        // رحلات شغالة او معلقة
        $hasActiveTrip = $vehicle->trips()
            ->whereIn('status', ['Ongoing'])
            ->exists();

        if ($hasActiveTrip) {
            $vehicle->update(['status' => 'UnAvailable']);
            return;
        }

        $vehicle->update(['status' => 'Available']);
    }

}
