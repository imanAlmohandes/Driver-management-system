<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class VehiclesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
    {
        $fakerEn = Faker::create('en_US');
        $fakerAr = Faker::create('ar_SA');

        for ($i = 0; $i < 100; $i++) {
            Vehicle::create([
                'type' => [
                    'en' => $fakerEn->randomElement(['Truck', 'Van', 'Bus', 'Car']),
                    'ar' => $fakerAr->randomElement(['شاحنة', 'فان', 'باص', 'سيارة']),
                ],
                'model' => [
                    'en' => 'Model ' . $fakerEn->bothify('??-###'),
                    'ar' => 'موديل ' . $fakerAr->bothify('??-###'),
                ],
                'plate_number' => $fakerEn->unique()->bothify('??-####'),
                'status' => $fakerEn->randomElement(['Available', 'UnAvailable', 'InMaintenance']),
            ]);
        }
    }

}
