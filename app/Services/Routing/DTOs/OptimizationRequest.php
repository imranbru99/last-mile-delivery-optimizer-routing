<?php

declare(strict_types=1);

namespace App\Services\Routing\DTOs;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

final readonly class OptimizationRequest
{
    /**
     * @param  list<OptimizationStop>  $stops
     * @param  array<string, array<string, float>>|null  $distanceMatrixKm  keyed by stop id (depot = "__depot__")
     */
    public function __construct(
        public float $depotLat,
        public float $depotLng,
        public array $stops,
        public float $maxWeightKg,
        public float $maxVolumeM3,
        public int $maxStops,
        public ?CarbonInterface $routeStart = null,
        public float $averageSpeedKmh = 25.0,
        public ?array $distanceMatrixKm = null,
    ) {}

    public static function fromArray(array $data): self
    {
        $stops = array_map(
            fn (array $stop): OptimizationStop => OptimizationStop::fromArray($stop),
            $data['stops'] ?? [],
        );

        return new self(
            depotLat: (float) ($data['depot_lat'] ?? 0),
            depotLng: (float) ($data['depot_lng'] ?? 0),
            stops: $stops,
            maxWeightKg: (float) ($data['max_weight_kg'] ?? 0),
            maxVolumeM3: (float) ($data['max_volume_m3'] ?? 0),
            maxStops: (int) ($data['max_stops'] ?? 0),
            routeStart: isset($data['route_start']) ? Carbon::parse($data['route_start']) : null,
            averageSpeedKmh: (float) ($data['average_speed_kmh'] ?? 25),
            distanceMatrixKm: $data['distance_matrix_km'] ?? null,
        );
    }

    /**
     * @param  array<string, array<string, float>>  $matrix
     */
    public function withDistanceMatrix(array $matrix): self
    {
        return new self(
            depotLat: $this->depotLat,
            depotLng: $this->depotLng,
            stops: $this->stops,
            maxWeightKg: $this->maxWeightKg,
            maxVolumeM3: $this->maxVolumeM3,
            maxStops: $this->maxStops,
            routeStart: $this->routeStart,
            averageSpeedKmh: $this->averageSpeedKmh,
            distanceMatrixKm: $matrix,
        );
    }
}
