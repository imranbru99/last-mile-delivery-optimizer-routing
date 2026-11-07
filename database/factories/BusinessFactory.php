<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Business>
 */
class BusinessFactory extends Factory
{
    protected $model = Business::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'email' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'business_type' => fake()->randomElement(['courier', 'grocery', 'chauffeur', 'custom']),
            'primary_color' => '#0F766E',
            'secondary_color' => '#134E4A',
            'locale' => 'en',
            'currency' => 'BDT',
            'timezone' => 'Asia/Dhaka',
            'distance_unit' => 'km',
            'country_code' => 'BD',
            'city' => fake()->randomElement(['Dhaka', 'Chattogram', 'Sylhet']),
            'address' => fake()->address(),
            'settings' => [],
            'is_active' => true,
            'plan' => 'trial',
            'trial_ends_at' => now()->addDays(14),
        ];
    }
}
