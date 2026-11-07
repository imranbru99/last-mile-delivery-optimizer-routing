<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Business;
use App\Models\DeliveryRoute;
use App\Models\RouteStop;
use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RouteStop>
 */
class RouteStopFactory extends Factory
{
    protected $model = RouteStop::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'route_id' => DeliveryRoute::factory(),
            'shipment_id' => null,
            'sequence' => fake()->numberBetween(1, 20),
            'type' => fake()->randomElement(['pickup', 'delivery', 'break', 'hub']),
            'status' => 'pending',
            'eta_at' => now()->addHours(1),
            'ata_at' => null,
            'service_time_min' => 5,
            'lat' => fake()->latitude(23.7, 23.9),
            'lng' => fake()->longitude(90.3, 90.5),
            'address_snapshot' => [],
        ];
    }

    public function deliveryForShipment(Shipment $shipment, DeliveryRoute $route, int $sequence): static
    {
        $address = $shipment->deliveryAddress;

        return $this->state(fn (): array => [
            'business_id' => $shipment->business_id,
            'route_id' => $route->id,
            'shipment_id' => $shipment->id,
            'sequence' => $sequence,
            'type' => 'delivery',
            'lat' => $address?->lat,
            'lng' => $address?->lng,
            'address_snapshot' => [
                'line1' => $address?->line1,
                'city' => $address?->city,
            ],
        ]);
    }
}
