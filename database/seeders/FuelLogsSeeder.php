<?php
namespace Database\Seeders;

use App\Models\FuelLog;
use App\Models\Trip;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class FuelLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fakerEn = Faker::create('en_US');
        $fakerAr = Faker::create('ar_SA');

        $trips = Trip::inRandomOrder()->take(100)->get();

        for ($i = 0; $i < 100; $i++) {
            $trip = $trips->random();

            FuelLog::create([
                'driver_id'      => $trip->driver_id,
                'trip_id'        => $trip->id,

                'receipt_number' => $fakerEn->unique()->ean8(),
                'station_name'   => $fakerEn->company(),
                'fuel_amount'    => $fakerEn->randomFloat(2, 10, 80),
                'fuel_cost'      => $fakerEn->randomFloat(2, 50, 350),

                // خلي log_date قريب من وقت الرحلة
                'log_date'       => $fakerEn->dateTimeBetween($trip->start_time, $trip->end_time)->format('Y-m-d'),
            ]);
        }
    }

}
