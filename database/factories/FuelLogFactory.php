<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FuelLog>
 */
class FuelLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'driver_id'          => Driver::factory(),
            // 'trip_id'            => Trip::factory(),
            // 'receipt_image_path' => fake()->imageUrl(640, 480, 'receipt'),
            'receipt_number' => fake()->unique()->ean8(),
            'station_name'   => fake()->company(),
            'fuel_amount'    => fake()->randomFloat(2, 20, 80),
            'fuel_cost'      => fake()->randomFloat(2, 50, 250),
            'log_date'       => fake()->dateTimeBetween('2021-01-01', '2030-12-31')->format('Y-m-d'),

        ];
    }
}
