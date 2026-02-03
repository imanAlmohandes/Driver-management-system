<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaintenanceLog>
 */
class MaintenanceLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fakerAr = \Faker\Factory::create('ar_SA');
        $fakerEn = $this->faker;

        return [
            // 'company_id'   => MaintenanceCompany::factory(),
            // 'vehicle_id'   => Vehicle::factory(),
            'service_type' => [
                'en' => fake()->randomElement(['Oil Change', 'Tire Rotation', 'Brake Repair']),
                'ar' => fake()->randomElement(['تغيير زيت', 'تبديل إطارات', 'تصليح فرامل']),
            ],
            'cost'         => fake()->randomFloat(2, 100, 1500),
            'service_date' => fake()->dateTimeBetween('2021-01-01', '2030-12-31')->format('Y-m-d'),
            'notes'        => fake()->boolean(60) ? [
                'en' => $fakerEn->sentence(10),
                'ar' => $fakerAr->sentence(10),
            ] : null,

        ];
    }
}
