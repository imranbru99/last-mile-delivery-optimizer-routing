<?php

declare(strict_types=1);

use App\Models\Business;
use App\Models\RateCard;
use App\Models\Zone;
use App\Services\Finance\PricingEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('calculates zone and weight tier pricing', function (): void {
    $business = Business::factory()->create();
    $zone = Zone::factory()->forBusiness($business)->create([
        'pricing_multiplier' => 1.5,
    ]);

    $rateCard = RateCard::factory()->forBusiness($business)->create([
        'base_fee' => 50,
        'per_kg_fee' => 10,
        'weight_tiers' => [
            ['min_kg' => 0, 'max_kg' => 5, 'flat_fee' => 0, 'per_kg' => 8],
            ['min_kg' => 5, 'max_kg' => null, 'flat_fee' => 20, 'per_kg' => 12],
        ],
    ]);

    $engine = app(PricingEngine::class);

    $threeKg = $engine->quote($rateCard, 3.0, $zone);
    expect($threeKg['subtotal'])->toBe(111.0)
        ->and($threeKg['breakdown']['weight_charge'])->toBe(24.0);

    $sevenKg = $engine->quote($rateCard, 7.0, $zone);
    expect($sevenKg['subtotal'])->toBe(231.0);
});
