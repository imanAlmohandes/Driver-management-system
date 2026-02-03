<?php
namespace Database\Factories;

use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Trip::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('2021-01-01', '2030-12-31');
        $end   = (clone $start);
        $end->modify('+' . $this->faker->numberBetween(1, 8) . ' hours');
        
        $fakerAr = \Faker\Factory::create('ar_SA');
        $fakerEn = $this->faker;

        return [
            // 'vehicle_id'      => Vehicle::factory(),
            // 'driver_id'       => Driver::factory(),
            // 'from_station_id' => Station::factory(),
            // 'to_station_id'   => Station::factory(),
            'start_time'  => $start,
            'end_time'    => $end,
            'status'      => $this->faker->randomElement(array_keys(Trip::STATUSES)),
            'distance_km' => $this->faker->randomFloat(2, 1, 500),
            'notes'       => [
                'en' => $fakerEn->optional()->sentence(10),
                'ar' => $fakerAr->optional()->sentence(10),
            ],
            'deleted_at'  => null,
        ];
    }
}
