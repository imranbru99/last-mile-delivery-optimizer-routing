<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Business;
use App\Models\DriverLocation;
use App\Models\DriverProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DriverLocation>
 */
class DriverLocationFactory extends Factory
{
    protected $model = DriverLocation::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'driver_id' => DriverProfile::factory(),
            'lat' => fake()->latitude(23.7, 23.9),
            'lng' => fake()->longitude(90.3, 90.5),
            'speed' => fake()->randomFloat(2, 0, 60),
            'heading' => fake()->randomFloat(2, 0, 359),
            'recorded_at' => now(),
            'accuracy' => fake()->randomFloat(2, 3, 25),
        ];
    }
}
