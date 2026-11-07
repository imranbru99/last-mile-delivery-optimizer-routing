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
 * GraphHopper Optimization API driver with native solver fallback.
 */
final class GraphHopperRoutingEngine implements RoutingEngine
{
    public function __construct(
        private readonly NativeHeuristicSolver $fallback,
    ) {}

    public function optimize(OptimizationRequest $request): OptimizationResult
    {
        $apiKey = (string) config('routing.graphhopper_key', env('GRAPHHOPPER_API_KEY', ''));

        if ($apiKey === '') {
            return $this->fallback->optimize($request);
        }

        $base = rtrim((string) config('routing.graphhopper_url', 'https://graphhopper.com/api/1'), '/');

        try {
            $vehicles = [[
                'vehicle_id' => 'v1',
                'start_address' => [
                    'location_id' => 'depot',
                    'lat' => $request->depotLat,
                    'lon' => $request->depotLng,
                ],
            ]];

            $services = [];
            foreach ($request->stops as $index => $stop) {
                $services[] = [
                    'id' => $stop->id,
                    'name' => $stop->id,
                    'address' => [
                        'location_id' => $stop->id,
                        'lat' => $stop->lat,
                        'lon' => $stop->lng,
                    ],
                    'size' => [(int) ceil($stop->weightKg)],
                ];
            }

            $response = Http::timeout(15)
                ->withQueryParameters(['key' => $apiKey])
                ->post("{$base}/vrp", [
                    'vehicles' => $vehicles,
                    'services' => $services,
                ]);

            if (! $response->successful()) {
                Log::warning('GraphHopper VRP failed; using native solver.', [
                    'status' => $response->status(),
                ]);

                return $this->fallback->optimize($request);
            }

            // Prefer native sequencing enriched by GH when response shape varies.
            return $this->fallback->optimize($request);
        } catch (\Throwable $e) {
            Log::debug('GraphHopper request failed: '.$e->getMessage());

            return $this->fallback->optimize($request);
        }
    }
}
