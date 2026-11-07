<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Business;
use App\Models\CodSettlement;
use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CodSettlement>
 */
class CodSettlementFactory extends Factory
{
    protected $model = CodSettlement::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'shipment_id' => Shipment::factory(),
            'driver_id' => null,
            'amount' => fake()->randomFloat(2, 100, 3000),
            'currency' => 'BDT',
            'status' => 'pending',
            'settled_at' => null,
        ];
    }

    public function forBusiness(Business $business): static
    {
        return $this->state(fn (): array => [
            'business_id' => $business->id,
            'shipment_id' => Shipment::factory()->forBusiness($business),
        ]);
    }
}
