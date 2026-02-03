<?php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = User::class;

    public function definition(): array
    { // Faker عربي/إنجليزي
        $fakerAr = \Faker\Factory::create('ar_SA');
        $fakerEn = $this->faker;

        $nameEn = $fakerEn->name();
        $nameAr = $fakerAr->name();

        return [
            // Spatie translatable: نخزن JSON
            'name'              => [
                'en' => $nameEn,
                'ar' => $nameAr,
            ],
            'email'             => $fakerEn->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => bcrypt('password'),
            'remember_token'    => Str::random(10),
            'role'              => 'driver', // خليها default وغيّريها بالـ states
            'is_active'         => true,
            'deleted_at'        => null,
        ];
    }
    public function admin(): static
    {
        return $this->state(fn() => [
            'role' => 'admin',
        ]);
    }

// A "state" to make a user inactive
    public function inactive(): static
    {
        return $this->state(fn() => [
            'is_active' => false,
        ]);
    }
    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

}
