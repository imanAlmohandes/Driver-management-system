<?php
namespace Database\Factories;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    protected $model = Driver::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fakerEn = $this->faker;
        $fakerAr = \Faker\Factory::create('ar_SA');

        return [
            'user_id'             => User::factory()->state(['role' => 'driver']),
            'license_number'      => $this->faker->unique()->numerify('LIC-#####'),
            'license_type'        => [
                'en' => $fakerEn->randomElement(['A', 'B', 'C']),
                'ar' => $fakerAr->randomElement(['فئة A', 'فئة B', 'فئة C']),
            ],
            'license_expiry_date' => $this->faker->dateTimeBetween('2026-01-01', '2030-12-31')->format('Y-m-d'),
            'driver_image'        => null,
            'deleted_at'          => null,
        ];
    }
}
