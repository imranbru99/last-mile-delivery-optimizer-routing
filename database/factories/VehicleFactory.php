<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Business;
use App\Models\Hub;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'name' => fake()->words(2, true),
            'plate_number' => strtoupper(fake()->unique()->bothify('DHA-##-####')),
            'type' => fake()->randomElement(['bike', 'van', 'truck']),
            'capacity_weight_kg' => fake()->randomFloat(2, 50, 1500),
            'capacity_volume_m3' => fake()->randomFloat(2, 0.5, 12),
            'capacity_stops' => fake()->numberBetween(10, 80),
            'status' => 'available',
            'hub_id' => null,
            'documents' => [],
        ];
    }

    public function forHub(Hub $hub): static
    {
        return $this->state(fn (): array => [
            'business_id' => $hub->business_id,
            'hub_id' => $hub->id,
        ]);
    }
}
