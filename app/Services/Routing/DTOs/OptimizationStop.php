<?php

declare(strict_types=1);

namespace App\Services\Routing\DTOs;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

final readonly class OptimizationStop
{
    public function __construct(
        public string $id,
        public float $lat,
        public float $lng,
        public float $weightKg,
        public float $volumeM3,
        public ?CarbonInterface $timeWindowStart = null,
        public ?CarbonInterface $timeWindowEnd = null,
        public int $serviceTimeMin = 5,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) $data['id'],
            lat: (float) $data['lat'],
            lng: (float) $data['lng'],
            weightKg: (float) ($data['weight_kg'] ?? 0),
            volumeM3: (float) ($data['volume_m3'] ?? 0),
            timeWindowStart: isset($data['time_window_start']) ? Carbon::parse($data['time_window_start']) : null,
            timeWindowEnd: isset($data['time_window_end']) ? Carbon::parse($data['time_window_end']) : null,
            serviceTimeMin: (int) ($data['service_time_min'] ?? 5),
        );
    }
}
