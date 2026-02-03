<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fakerEn = Faker::create('en_US');
        $fakerAr = Faker::create('ar_SA');

        // Admin ثابت
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'      => ['en' => 'Admin', 'ar' => 'أدمن'],
                'password'  => Hash::make('password'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );

        // 100 User Drivers (role=driver)
        for ($i = 0; $i < 100; $i++) {
            User::create([
                'name'      => [
                    'en' => $fakerEn->name(),
                    'ar' => $fakerAr->name(),
                ],
                'email'     => $fakerEn->unique()->safeEmail(),
                'password'  => Hash::make('password'),
                'role'      => 'driver',
                'is_active' => $fakerEn->boolean(90),
            ]);
        }
    }
}
