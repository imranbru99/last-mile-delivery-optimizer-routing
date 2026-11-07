<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Business;
use App\Models\Hub;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Zone>
 */
class ZoneFactory extends Factory
{
    protected $model = Zone::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'name' => fake()->streetName().' Zone',
            'code' => strtoupper(fake()->unique()->bothify('ZN-##')),
            'color' => fake()->hexColor(),
            'polygon_json' => null,
            'hub_id' => null,
            'pricing_multiplier' => fake()->randomFloat(2, 0.9, 1.5),
            'is_active' => true,
        ];
    }

    public function forHub(Hub $hub): static
    {
        return $this->state(fn (): array => [
            'business_id' => $hub->business_id,
            'hub_id' => $hub->id,
        ]);
    }

    public function forBusiness(Business $business): static
    {
        return $this->state(fn (): array => ['business_id' => $business->id]);
    }
}
