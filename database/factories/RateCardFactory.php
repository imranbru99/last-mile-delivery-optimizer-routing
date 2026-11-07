<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Business;
use App\Models\RateCard;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<RateCard>
 */
class RateCardFactory extends Factory
{
    protected $model = RateCard::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'name' => 'Standard Delivery',
            'code' => strtoupper(Str::random(6)),
            'currency' => 'BDT',
            'base_fee' => 50,
            'per_kg_fee' => 10,
            'weight_tiers' => [
                ['min_kg' => 0, 'max_kg' => 5, 'flat_fee' => 0, 'per_kg' => 8],
                ['min_kg' => 5, 'max_kg' => null, 'flat_fee' => 20, 'per_kg' => 12],
            ],
            'zone_rules' => [],
            'is_active' => true,
            'valid_from' => now()->subMonth(),
            'valid_until' => null,
        ];
    }

    public function forBusiness(Business $business): static
    {
        return $this->state(fn (): array => ['business_id' => $business->id]);
    }
}
