<?php
namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

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
            'type'         => [
                'en' => fake()->randomElement(['Truck', 'Van', 'Bus', 'Car']),
                'ar' => fake()->randomElement(['شاحنة', 'فان', 'باص', 'سيارة']),
            ],
            'model'        => [
                'en' => 'Model ' . $fakerEn->bothify('??-###'),
                'ar' => 'موديل ' . $fakerAr->bothify('??-###'),
            ],
            'plate_number' => strtoupper($this->faker->bothify('??-####-??')),
            'status'       => $this->faker->randomElement(array_keys(Vehicle::STATUSES)),
        ];
    }
}
