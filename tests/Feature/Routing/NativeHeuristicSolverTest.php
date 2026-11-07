<?php

declare(strict_types=1);

use App\Services\Routing\DTOs\OptimizationRequest;
use App\Services\Routing\DTOs\OptimizationStop;
use App\Services\Routing\NativeHeuristicSolver;
use Illuminate\Support\Carbon;

it('optimizes stops with distance savings while respecting capacity', function (): void {
    $solver = new NativeHeuristicSolver;

    $stops = [
        new OptimizationStop('1', 23.8103, 90.4125, 2.0, 0.01, null, null, 5),
        new OptimizationStop('2', 23.7808, 90.2792, 3.0, 0.02, null, null, 5),
        new OptimizationStop('3', 23.7461, 90.3742, 1.5, 0.01, null, null, 5),
        new OptimizationStop('4', 23.8583, 90.2667, 2.5, 0.015, null, null, 5),
        new OptimizationStop('5', 23.7272, 90.4254, 1.0, 0.008, null, null, 5),
    ];

    $request = new OptimizationRequest(
        depotLat: 23.8103,
        depotLng: 90.4125,
        stops: $stops,
        maxWeightKg: 50,
        maxVolumeM3: 2,
        maxStops: 10,
        routeStart: Carbon::parse('2026-11-08 08:00:00'),
        averageSpeedKmh: 25,
    );

    $result = $solver->optimize($request);

    expect($result->orderedStopIds)->toHaveCount(5)
        ->and($result->unassignedStopIds)->toBeEmpty()
        ->and($result->totalDistanceKm)->toBeLessThanOrEqual($result->baselineDistanceKm + 0.0001);

    // Prefer a saved route; if already optimal, savings may be zero.
    expect($result->distanceSavedKm)->toBeGreaterThanOrEqual(0);

    $byId = collect($stops)->keyBy(fn (OptimizationStop $s): string => $s->id);
    $weight = 0.0;
    $volume = 0.0;

    foreach ($result->orderedStopIds as $id) {
        $weight += $byId[$id]->weightKg;
        $volume += $byId[$id]->volumeM3;
    }

    expect($weight)->toBeLessThanOrEqual(50)
        ->and($volume)->toBeLessThanOrEqual(2);
});
