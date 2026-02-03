<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\MaintenanceLog;
use App\Models\MaintenanceCompany;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MaintenanceLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
    {
        $fakerEn = Faker::create('en_US');
        $fakerAr = Faker::create('ar_SA');

        $companies = MaintenanceCompany::inRandomOrder()->take(100)->get();
        $vehicles  = Vehicle::inRandomOrder()->take(100)->get();

        for ($i = 0; $i < 100; $i++) {
            MaintenanceLog::create([
                'company_id' => $companies->random()->id,
                'vehicle_id' => $vehicles->random()->id,

                'service_type' => [
                    'en' => $fakerEn->randomElement(['Oil Change', 'Tire Rotation', 'Brake Repair', 'Engine Check']),
                    'ar' => $fakerAr->randomElement(['تغيير زيت', 'تبديل إطارات', 'تصليح فرامل', 'فحص محرك']),
                ],
                'cost' => $fakerEn->randomFloat(2, 100, 1500),
                'service_date' => $fakerEn->dateTimeBetween('2021-01-01', '2030-12-31')->format('Y-m-d'),
                'notes' => $fakerEn->boolean(60) ? [
                    'en' => $fakerEn->sentence(10),
                    'ar' => $fakerAr->sentence(10),
                ] : null,
            ]);
        }
    }

}
