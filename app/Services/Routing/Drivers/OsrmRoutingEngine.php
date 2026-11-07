<?php

declare(strict_types=1);

namespace App\Services\Routing\Drivers;

use App\Services\Routing\Contracts\RoutingEngine;
use App\Services\Routing\DTOs\OptimizationRequest;
use App\Services\Routing\DTOs\OptimizationResult;
use App\Services\Routing\NativeHeuristicSolver;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * OSRM-backed distance matrix with native VRP sequencing.
 * Falls back to haversine + native solver when OSRM is unreachable.
 */
final class OsrmRoutingEngine implements RoutingEngine
{
    public function __construct(
        private readonly NativeHeuristicSolver $fallback,
    ) {}

    public function optimize(OptimizationRequest $request): OptimizationResult
    {
        $matrix = $this->tryDistanceMatrix($request);

        if ($matrix === null) {
            Log::warning('OSRM unreachable; falling back to native heuristic solver.');

            return $this->fallback->optimize($request);
        }

        $result = $this->fallback->optimize($request->withDistanceMatrix($matrix));

        return new OptimizationResult(
            orderedStopIds: $result->orderedStopIds,
            totalDistanceKm: $result->totalDistanceKm,
            baselineDistanceKm: $result->baselineDistanceKm,
            distanceSavedKm: $result->distanceSavedKm,
            distanceSavedPercent: $result->distanceSavedPercent,
            unassignedStopIds: $result->unassignedStopIds,
            meta: array_merge($result->meta, ['engine' => 'osrm', 'matrix_source' => 'osrm_table']),
        );
    }

    /**
     * @return array<string, array<string, float>>|null
     */
    private function tryDistanceMatrix(OptimizationRequest $request): ?array
    {
        $points = [
            ['id' => '__depot__', 'lat' => $request->depotLat, 'lng' => $request->depotLng],
        ];

        foreach ($request->stops as $stop) {
            $points[] = ['id' => $stop->id, 'lat' => $stop->lat, 'lng' => $stop->lng];
        }

        if (count($points) < 2) {
            return [];
        }

        $base = rtrim((string) config('routing.osrm_url', 'https://router.project-osrm.org'), '/');
        $coords = collect($points)
            ->map(fn (array $p): string => $p['lng'].','.$p['lat'])
            ->implode(';');

        try {
            $response = Http::timeout(8)
                ->get("{$base}/table/v1/driving/{$coords}", [
                    'annotations' => 'distance,duration',
                ]);

            if (! $response->successful()) {
                return null;
            }

            $distances = $response->json('distances');

            if (! is_array($distances)) {
                return null;
            }

            /** @var array<string, array<string, float>> $normalized */
            $normalized = [];

            foreach ($distances as $i => $row) {
                $fromId = $points[(int) $i]['id'];
                foreach ((array) $row as $j => $meters) {
                    $toId = $points[(int) $j]['id'];
                    $normalized[$fromId][$toId] = ((float) $meters) / 1000;
                }
            }

            return $normalized;
        } catch (\Throwable $e) {
            Log::debug('OSRM table request failed: '.$e->getMessage());

            return null;
        }
    }
}
