<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Business;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ShipmentEvent>
 */
class ShipmentEventFactory extends Factory
{
    protected $model = ShipmentEvent::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'shipment_id' => Shipment::factory(),
            'type' => fake()->randomElement(['created', 'assigned', 'picked_up', 'delivered']),
            'payload' => [],
            'user_id' => null,
            'happened_at' => now(),
        ];
    }
}
