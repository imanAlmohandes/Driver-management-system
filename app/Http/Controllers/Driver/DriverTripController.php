<?php
namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\User;
use App\Notifications\AdminDashboardNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverTripController extends Controller
{
    /**
     * Start a pending trip.
     */
    public function start(Trip $trip)
    {
        // Security Check: Ensure the trip belongs to the logged-in driver
        if ($trip->driver_id !== Auth::user()->driver->id) {
            abort(403, 'This is not your trip.');
        }

        // Logic Check: Ensure the trip is actually pending
        if ($trip->status !== 'Pending') {
            return back()->with('error', 'This trip cannot be started.');
        }

        $trip->update([
            'status'     => 'Ongoing',
            'start_time' => now(), // Optional: Update start_time to the actual start moment
        ]);

        $trip->vehicle->update(['status' => 'UnAvailable']);

        return back()->with('success', __('driver.trip_started'));
    }

    /**
     * Complete an ongoing trip.
     */
    public function complete(Trip $trip)
    {
        // Security Check: Ensure the trip belongs to the logged-in driver
        if ($trip->driver_id !== Auth::user()->driver->id) {
            abort(403, 'This is not your trip.');
        }

        // Logic Check: Ensure the trip is actually ongoing
        if ($trip->status !== 'Ongoing') {
            return back()->with('error', __('driver.trip_not_ongoing'));
        }

        $trip->update([
            'status'   => 'Completed',
            'end_time' => now(), // Set the end_time to the actual completion moment
        ]);

        $trip->vehicle->update(['status' => 'Available']);

        return back()->with('success', __('driver.trip_completed'));
    }

    public function cancel(Request $request, Trip $trip)
    {
        //  Security & Logic Checks
        if ($trip->driver_id !== Auth::user()->driver->id) {
            abort(403, 'This is not your trip.');
        }
        if ($trip->status !== 'Pending') {
        return back()->with('error', __('driver.trip_not_cancellable'));
        }
        // Check if the trip start time has passed (e.g., by 15 minutes)
        if ($trip->start_time && now()->greaterThan(Carbon::parse($trip->start_time)->addMinutes(15))) {
            return back()->with('error', __('driver.trip_cancellation_time_passed'));
        }

        //  Validate that the reason is provided
        $request->validate(['notes' => 'required|string|min:10']);

        //  Update the trip
        $trip->update([
            'status' => 'Cancelled',
            'notes'  => $request->notes,
            // Save the reason in the notes field
        ]);
    // السيارة تضل/ترجع Available
    if ($trip->vehicle) {
        $trip->vehicle->update(['status' => 'Available']);
    }

        //  Notify the admin
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new AdminDashboardNotification([
            'text'  => 'Driver ' . Auth::user()->name . ' cancelled a trip. Reason: ' . $request->notes,
            'icon'  => 'fa-ban',
            'color' => 'danger',
            'route' => route('admin.trips.show', $trip->id),
            ]));
        }
        return back()->with('success', __('driver.trip_cancelled_by_driver'));
    }

}
