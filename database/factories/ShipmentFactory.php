<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Address;
use App\Models\Business;
use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Shipment>
 */
class ShipmentFactory extends Factory
{
    protected $model = Shipment::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'tracking_code' => strtoupper(Str::random(12)),
            'merchant_user_id' => null,
            'service_level' => 'standard',
            'priority' => fake()->numberBetween(1, 9),
            'status' => 'pending',
            'pickup_address_id' => Address::factory(),
            'delivery_address_id' => Address::factory(),
            'hub_id' => null,
            'zone_id' => null,
            'weight_kg' => fake()->randomFloat(2, 0.1, 25),
            'volume_m3' => fake()->randomFloat(3, 0.001, 0.5),
            'pieces' => 1,
            'time_window_start' => now()->addHours(2),
            'time_window_end' => now()->addHours(8),
            'cod_amount' => 0,
            'currency' => 'BDT',
            'special_handling' => [],
            'barcode' => null,
            'notes' => null,
        ];
    }

    public function forBusiness(Business $business): static
    {
        return $this->state(fn (): array => [
            'business_id' => $business->id,
            'pickup_address_id' => Address::factory()->state(['business_id' => $business->id]),
            'delivery_address_id' => Address::factory()->state(['business_id' => $business->id]),
        ]);
    }
}
