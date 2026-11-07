<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Business;
use App\Models\ProofOfDelivery;
use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProofOfDelivery>
 */
class ProofOfDeliveryFactory extends Factory
{
    protected $model = ProofOfDelivery::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'shipment_id' => Shipment::factory(),
            'route_stop_id' => null,
            'method' => fake()->randomElement(['signature', 'photo', 'otp']),
            'recipient_name' => fake()->name(),
            'signature_path' => null,
            'photo_path' => null,
            'otp_verified' => false,
            'lat' => fake()->latitude(23.7, 23.9),
            'lng' => fake()->longitude(90.3, 90.5),
            'captured_at' => now(),
            'meta' => [],
        ];
    }
}
