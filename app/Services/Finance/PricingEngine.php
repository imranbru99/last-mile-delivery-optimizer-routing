<?php

declare(strict_types=1);

namespace App\Services\Finance;

use App\Models\RateCard;
use App\Models\Zone;
use Illuminate\Support\Carbon;

final class PricingEngine
{
    /**
     * @return array{subtotal: float, currency: string, breakdown: array<string, mixed>}
     */
    public function quote(
        RateCard $rateCard,
        float $weightKg,
        ?Zone $zone = null,
        ?int $zoneId = null,
    ): array {
        $weightKg = max(0.0, $weightKg);
        $base = (float) $rateCard->base_fee;
        $weightCharge = $this->weightCharge($rateCard, $weightKg);

        if ($weightCharge === null) {
            $weightCharge = $weightKg * (float) $rateCard->per_kg_fee;
        }

        $subtotal = $base + $weightCharge;
        $multiplier = $this->zoneMultiplier($rateCard, $zone, $zoneId);
        $subtotal = round($subtotal * $multiplier, 2);

        return [
            'subtotal' => $subtotal,
            'currency' => $rateCard->currency,
            'breakdown' => [
                'base_fee' => $base,
                'weight_kg' => $weightKg,
                'weight_charge' => round($weightCharge, 2),
                'zone_multiplier' => $multiplier,
                'rate_card_id' => $rateCard->id,
                'zone_id' => $zone?->id ?? $zoneId,
            ],
        ];
    }

    public function findActiveRateCard(int $businessId, ?string $code = null): ?RateCard
    {
        $query = RateCard::query()
            ->where('business_id', $businessId)
            ->where('is_active', true);

        $today = Carbon::today();

        $query->where(function ($q) use ($today): void {
            $q->whereNull('valid_from')->orWhere('valid_from', '<=', $today);
        })->where(function ($q) use ($today): void {
            $q->whereNull('valid_until')->orWhere('valid_until', '>=', $today);
        });

        if ($code !== null) {
            $query->where('code', $code);
        }

        return $query->orderByDesc('id')->first();
    }

    private function weightCharge(RateCard $rateCard, float $weightKg): ?float
    {
        $tiers = $rateCard->weight_tiers;
        if (! is_array($tiers) || $tiers === []) {
            return null;
        }

        foreach ($tiers as $tier) {
            if (! is_array($tier)) {
                continue;
            }

            $min = (float) ($tier['min_kg'] ?? 0);
            $max = $tier['max_kg'] ?? null;
            $max = $max === null ? null : (float) $max;

            if ($weightKg < $min) {
                continue;
            }

            if ($max !== null && $weightKg > $max) {
                continue;
            }

            $flat = (float) ($tier['flat_fee'] ?? 0);
            $perKg = (float) ($tier['per_kg'] ?? 0);

            return $flat + ($weightKg * $perKg);
        }

        return null;
    }

    private function zoneMultiplier(RateCard $rateCard, ?Zone $zone, ?int $zoneId): float
    {
        $rules = $rateCard->zone_rules;
        if (is_array($rules) && $zoneId !== null && isset($rules[(string) $zoneId])) {
            return (float) $rules[(string) $zoneId];
        }

        if ($zone !== null) {
            return (float) ($zone->pricing_multiplier ?? 1.0);
        }

        if ($zoneId !== null) {
            $resolved = Zone::query()->withoutGlobalScopes()->find($zoneId);

            return $resolved ? (float) ($resolved->pricing_multiplier ?? 1.0) : 1.0;
        }

        return 1.0;
    }
}
