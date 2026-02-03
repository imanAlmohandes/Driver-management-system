<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Trip;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Station;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class TripsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
    {
        $fakerEn = Faker::create('en_US');
        $fakerAr = Faker::create('ar_SA');

        $drivers = Driver::inRandomOrder()->take(100)->get();
        $vehicles = Vehicle::inRandomOrder()->take(100)->get();
        $stations = Station::inRandomOrder()->take(100)->get();

        $statuses = ['Pending', 'Ongoing', 'Completed', 'Cancelled'];

        for ($i = 0; $i < 100; $i++) {
            $driver = $drivers->random();
            $vehicle = $vehicles->random();

            $from = $stations->random();
            do { $to = $stations->random(); } while ($to->id === $from->id);

            // start/end بين 2021-2030 و end بعد start بساعات
            $start = Carbon::instance($fakerEn->dateTimeBetween('2021-01-01', '2030-12-31'));
            $end   = (clone $start)->addMinutes($fakerEn->numberBetween(30, 6 * 60));

            $status = $fakerEn->randomElement($statuses);

            Trip::create([
                'driver_id' => $driver->id,
                'vehicle_id' => $vehicle->id,
                'from_station_id' => $from->id,
                'to_station_id' => $to->id,
                'start_time' => $start,
                'end_time' => $end,
                'distance_km' => $fakerEn->randomFloat(2, 1, 500),
                'status' => $status,
                'notes' => $fakerEn->boolean(60) ? [
                    'en' => $fakerEn->sentence(10),
                    'ar' => $fakerAr->sentence(10),
                ] : null,
            ]);
        }
    }

}
