<?php

declare(strict_types=1);

namespace App\Services\Routing\DTOs;

final readonly class OptimizationResult
{
    /**
     * @param  list<string>  $orderedStopIds
     * @param  list<string>  $unassignedStopIds
     * @param  array<string, mixed>  $meta
     */
    public function __construct(
        public array $orderedStopIds,
        public float $totalDistanceKm,
        public float $baselineDistanceKm,
        public float $distanceSavedKm,
        public float $distanceSavedPercent,
        public array $unassignedStopIds = [],
        public array $meta = [],
    ) {}

    public function distanceWasSaved(): bool
    {
        return $this->distanceSavedKm > 0.0001;
    }
}
