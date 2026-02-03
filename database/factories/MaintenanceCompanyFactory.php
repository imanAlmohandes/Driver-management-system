<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaintenanceCompany>
 */
class MaintenanceCompanyFactory extends Factory
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
            'name'    => [
                'en' => $fakerEn->company() . ' Auto Repair',
                'ar' => 'شركة ' . $fakerAr->word() . ' لصيانة المركبات',
            ],
            'phone'   => $fakerEn->phoneNumber(),
            'address' => [
                'en' => $fakerEn->address(),
                'ar' => 'العنوان: ' . $fakerAr->city() . ' - ' . $fakerAr->streetName(),
            ],
        ];
    }
}
