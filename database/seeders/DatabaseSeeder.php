<?php
namespace Database\Seeders;

use App\Models\Driver;
use App\Models\FuelLog;
use App\Models\MaintenanceCompany;
use App\Models\MaintenanceLog;
use App\Models\Report;
use App\Models\Station;
use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Clearing all data...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Driver::truncate();
        Station::truncate();
        Vehicle::truncate();
        MaintenanceCompany::truncate();
        MaintenanceLog::truncate();
        Trip::truncate();
        FuelLog::truncate();
        Report::truncate();
        DB::table('notifications')->truncate();
        DB::table('driver_vehicle')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->call([
            UsersSeeder::class,
            DriversSeeder::class,
            VehiclesSeeder::class,
            StationsSeeder::class,
            TripsSeeder::class,
            MaintenanceCompaniesSeeder::class,
            MaintenanceLogsSeeder::class,
            FuelLogsSeeder::class,
        ]);

    }
}
