<?php
namespace Database\Factories;

use App\Models\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Station>
 */
class StationFactory extends Factory
{
    protected $model = Station::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fakerAr = \Faker\Factory::create('ar_SA');
        $fakerEn = $this->faker;

        $cityEn = $fakerEn->city();
        $cityAr = $fakerAr->city();

        return [
            'name'       => [
                'en' => "Station " . $fakerEn->word(),
                'ar' => "محطة " . $fakerAr->word(),
            ],
            'city'       => [
                'en' => $cityEn,
                'ar' => $cityAr,
            ],
            'deleted_at' => null,
        ];
    }
}
