<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Station;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class StationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
    {
        $fakerEn = Faker::create('en_US');
        $fakerAr = Faker::create('ar_SA');

        for ($i = 0; $i < 100; $i++) {
            Station::create([
                'name' => [
                    'en' => 'Station ' . $fakerEn->unique()->word(),
                    'ar' => 'محطة ' . $fakerAr->unique()->word(),
                ],
                'city' => [
                    'en' => $fakerEn->city(),
                    'ar' => $fakerAr->city(),
                ],
            ]);
        }
    }

}
