<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Driver;
use App\Models\User;
use App\Notifications\AdminDashboardNotification;
use Carbon\Carbon;

class CheckLicenseExpiries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // دتحقق من رخصة القيادة وتاريخ انتهائها
    protected $signature = 'app:check-license-expiries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for driver licenses expiring soon and notify admins';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expiring licenses...');

        $admins = User::where('role', 'admin')->get();
        if ($admins->isEmpty()) {
            $this->warn('No admin users found. Skipping notification.');
            return;
        }

        // Find drivers whose licenses expire in exactly 30, 15, or 7 days
        $expiringDrivers = Driver::whereIn(
            'license_expiry_date',
            [
                now()->addDays(30)->toDateString(),
                now()->addDays(15)->toDateString(),
                now()->addDays(7)->toDateString(),
            ]
        )->with('user')->get();

        if ($expiringDrivers->isEmpty()) {
            $this->info('No licenses expiring on target dates.');
            return;
        }

        foreach ($expiringDrivers as $driver) {
            $daysLeft = Carbon::now()->diffInDays($driver->license_expiry_date, false);

            $notificationData = [
                'text' => "License for driver {$driver->user->name} will expire in {$daysLeft} days.",
                'icon'  => 'fa-id-card',
                'color' => 'warning',
                'route' => route('admin.drivers.show', $driver->id),
            ];

            // Notify all admins
            foreach ($admins as $admin) {
                $admin->notify(new AdminDashboardNotification($notificationData));
            }
            $this->info("Notified admin about driver: {$driver->user->name}");
        }

        $this->info('Finished checking licenses.');
    }

}
