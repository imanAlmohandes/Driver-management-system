<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\MaintenanceCompany;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MaintenanceCompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
    {
        $fakerEn = Faker::create('en_US');
        $fakerAr = Faker::create('ar_SA');

        for ($i = 0; $i < 100; $i++) {
            MaintenanceCompany::create([
                'name' => [
                    'en' => $fakerEn->company() . ' Auto Repair',
                    'ar' => 'شركة ' . $fakerAr->word() . ' لصيانة المركبات',
                ],
                'phone' => $fakerEn->phoneNumber(),
                'address' => [
                    'en' => $fakerEn->address(),
                    'ar' => $fakerAr->city() . ' - ' . $fakerAr->streetName(),
                ],
            ]);
        }
    }

}
