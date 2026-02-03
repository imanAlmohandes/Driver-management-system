<?php
namespace Database\Seeders;

use App\Models\Driver;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class DriversSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $fakerEn = Faker::create('en_US');
        $fakerAr = Faker::create('ar_SA');

        $driverUsers = User::where('role', 'driver')->take(100)->get();

        foreach ($driverUsers as $user) {
            Driver::create([
                'user_id'             => $user->id,
                'license_number'      => $fakerEn->unique()->bothify('LIC-#######'),
                'license_type'        => [
                    'en' => $fakerEn->randomElement(['Private', 'Commercial', 'Heavy']),
                    'ar' => $fakerAr->randomElement(['خصوصي', 'عمومي', 'ثقيل']),
                ],
                'license_expiry_date' => $fakerEn->dateTimeBetween('2026-01-01', '2030-12-31')->format('Y-m-d'),
                'driver_image'        => null,
            ]);
        }
    }

}
