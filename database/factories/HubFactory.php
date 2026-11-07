<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Business;
use App\Models\Hub;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Hub>
 */
class HubFactory extends Factory
{
    protected $model = Hub::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'name' => fake()->company().' Hub',
            'code' => strtoupper(fake()->unique()->bothify('HUB-###')),
            'type' => fake()->randomElement(['hub', 'warehouse', 'cross_dock']),
            'address' => fake()->address(),
            'lat' => fake()->latitude(23.7, 23.9),
            'lng' => fake()->longitude(90.3, 90.5),
            'capacity_json' => [
                'max_parcels' => fake()->numberBetween(500, 5000),
            ],
            'is_active' => true,
        ];
    }
}
