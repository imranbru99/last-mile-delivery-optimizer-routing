<?php

declare(strict_types=1);

namespace App\Services\Routing\Drivers;

use App\Services\Routing\Contracts\RoutingEngine;
use App\Services\Routing\DTOs\OptimizationRequest;
use App\Services\Routing\DTOs\OptimizationResult;
use App\Services\Routing\NativeHeuristicSolver;
use Illuminate\Support\Facades\Log;

/**
 * Google Routes / Route Optimization API placeholder driver.
 * Requires GOOGLE_MAPS_API_KEY; falls back to native solver when unset.
 */
final class GoogleRoutesEngine implements RoutingEngine
{
    public function __construct(
        private readonly NativeHeuristicSolver $fallback,
    ) {}

    public function optimize(OptimizationRequest $request): OptimizationResult
    {
        $apiKey = (string) config('routing.google_key', env('GOOGLE_MAPS_API_KEY', ''));

        if ($apiKey === '') {
            Log::info('Google Routes key missing; using native heuristic solver.');

            return $this->fallback->optimize($request);
        }

        // Production installs should call Routes Preferred / Fleet Routing here.
        // Until credentials and quotas are configured, keep deterministic native results.
        return $this->fallback->optimize($request);
    }
}
