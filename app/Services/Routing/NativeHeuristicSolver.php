<?php

declare(strict_types=1);

namespace App\Services\Routing;

use App\Services\Routing\Contracts\RoutingEngine;
use App\Services\Routing\DTOs\OptimizationRequest;
use App\Services\Routing\DTOs\OptimizationResult;
use App\Services\Routing\DTOs\OptimizationStop;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

final class NativeHeuristicSolver implements RoutingEngine
{
    public function optimize(OptimizationRequest $request): OptimizationResult
    {
        $stops = $request->stops;
        $baselineOrder = array_map(fn (OptimizationStop $s): string => $s->id, $stops);
        $baselineDistance = $this->routeDistanceKm(
            $request->depotLat,
            $request->depotLng,
            $baselineOrder,
            $stops,
            $request->distanceMatrixKm,
        );

        $assigned = $this->nearestNeighbor($request);
        $optimizedOrder = $this->twoOpt(
            $request->depotLat,
            $request->depotLng,
            $assigned,
            $stops,
            $request->distanceMatrixKm,
        );

        $totalDistance = $this->routeDistanceKm(
            $request->depotLat,
            $request->depotLng,
            $optimizedOrder,
            $stops,
            $request->distanceMatrixKm,
        );

        $allIds = array_map(fn (OptimizationStop $s): string => $s->id, $stops);
        $unassigned = array_values(array_diff($allIds, $optimizedOrder));

        $saved = max(0, $baselineDistance - $totalDistance);
        $savedPercent = $baselineDistance > 0 ? ($saved / $baselineDistance) * 100 : 0.0;

        return new OptimizationResult(
            orderedStopIds: $optimizedOrder,
            totalDistanceKm: round($totalDistance, 3),
            baselineDistanceKm: round($baselineDistance, 3),
            distanceSavedKm: round($saved, 3),
            distanceSavedPercent: round($savedPercent, 2),
            unassignedStopIds: $unassigned,
            meta: [
                'engine' => 'native',
                'algorithm' => 'nearest_neighbor_2opt',
                'assigned_count' => count($optimizedOrder),
                'unassigned_count' => count($unassigned),
            ],
        );
    }

    /**
     * @param  list<OptimizationStop>  $stops
     * @return list<string>
     */
    private function nearestNeighbor(OptimizationRequest $request): array
    {
        $depotLat = $request->depotLat;
        $depotLng = $request->depotLng;
        $remaining = [];
        foreach ($request->stops as $stop) {
            $remaining[$stop->id] = $stop;
        }

        $route = [];
        $weight = 0.0;
        $volume = 0.0;
        $stopCount = 0;
        $currentLat = $depotLat;
        $currentLng = $depotLng;
        $currentTime = $request->routeStart ?? Carbon::now();

        while ($remaining !== [] && $stopCount < $request->maxStops) {
            $bestId = null;
            $bestScore = PHP_FLOAT_MAX;

            foreach ($remaining as $id => $stop) {
                if ($weight + $stop->weightKg > $request->maxWeightKg) {
                    continue;
                }
                if ($volume + $stop->volumeM3 > $request->maxVolumeM3) {
                    continue;
                }

                $fromId = $route === [] ? '__depot__' : (string) end($route);
                $dist = $this->distanceBetween(
                    $fromId,
                    (string) $id,
                    $currentLat,
                    $currentLng,
                    $stop->lat,
                    $stop->lng,
                    $request->distanceMatrixKm,
                );
                $travelMin = ($dist / max($request->averageSpeedKmh, 1)) * 60;
                $arrival = $currentTime->copy()->addMinutes((int) ceil($travelMin));

                $windowPenalty = 0.0;
                if ($stop->timeWindowEnd !== null && $arrival->greaterThan($stop->timeWindowEnd)) {
                    $windowPenalty = 1000.0;
                }
                if ($stop->timeWindowStart !== null && $arrival->lessThan($stop->timeWindowStart)) {
                    $waitMin = $stop->timeWindowStart->diffInMinutes($arrival);
                    $windowPenalty += min(500.0, (float) $waitMin);
                }

                $score = $dist + $windowPenalty;

                if ($score < $bestScore) {
                    $bestScore = $score;
                    $bestId = $id;
                }
            }

            if ($bestId === null) {
                break;
            }

            $chosen = $remaining[$bestId];
            unset($remaining[$bestId]);
            $route[] = $bestId;
            $weight += $chosen->weightKg;
            $volume += $chosen->volumeM3;
            $stopCount++;

            $fromId = count($route) <= 1 ? '__depot__' : (string) $route[count($route) - 2];
            $legKm = $this->distanceBetween(
                $fromId,
                $bestId,
                $currentLat,
                $currentLng,
                $chosen->lat,
                $chosen->lng,
                $request->distanceMatrixKm,
            );
            $travelMin = ($legKm / max($request->averageSpeedKmh, 1)) * 60;
            $currentTime = $this->arrivalAtStop($currentTime, $travelMin, $chosen);
            $currentTime = $currentTime->copy()->addMinutes($chosen->serviceTimeMin);
            $currentLat = $chosen->lat;
            $currentLng = $chosen->lng;
        }

        return $route;
    }

    private function arrivalAtStop(CarbonInterface $depart, float $travelMin, OptimizationStop $stop): CarbonInterface
    {
        $arrival = $depart->copy()->addMinutes((int) ceil($travelMin));

        if ($stop->timeWindowStart !== null && $arrival->lessThan($stop->timeWindowStart)) {
            return $stop->timeWindowStart->copy();
        }

        return $arrival;
    }

    /**
     * @param  list<string>  $order
     * @param  list<OptimizationStop>  $stops
     * @return list<string>
     */
    /**
     * @param  list<string>  $order
     * @param  list<OptimizationStop>  $stops
     * @param  array<string, array<string, float>>|null  $matrix
     * @return list<string>
     */
    private function twoOpt(float $depotLat, float $depotLng, array $order, array $stops, ?array $matrix = null): array
    {
        if (count($order) < 3) {
            return $order;
        }

        $byId = [];
        foreach ($stops as $stop) {
            $byId[$stop->id] = $stop;
        }

        $improved = true;
        $iterations = 0;
        $maxIterations = 200;

        while ($improved && $iterations < $maxIterations) {
            $improved = false;
            $iterations++;
            $count = count($order);

            for ($i = 0; $i < $count - 1; $i++) {
                for ($k = $i + 1; $k < $count; $k++) {
                    $delta = $this->twoOptDelta($depotLat, $depotLng, $order, $byId, $i, $k, $matrix);

                    if ($delta < -0.0001) {
                        // Reverse the closed segment [i, k] to match classic 2-opt edge swap.
                        $order = $this->reverseSegment($order, $i, $k);
                        $improved = true;
                        break 2;
                    }
                }
            }
        }

        return $order;
    }

    /**
     * @param  list<string>  $order
     * @param  array<string, OptimizationStop>  $byId
     * @param  array<string, array<string, float>>|null  $matrix
     */
    private function twoOptDelta(
        float $depotLat,
        float $depotLng,
        array $order,
        array $byId,
        int $i,
        int $k,
        ?array $matrix = null,
    ): float {
        $aId = $i === 0 ? '__depot__' : $order[$i - 1];
        $bId = $order[$i];
        $cId = $order[$k];
        $dId = $k + 1 < count($order) ? $order[$k + 1] : '__depot__';

        $a = $i === 0
            ? [$depotLat, $depotLng]
            : [$byId[$order[$i - 1]]->lat, $byId[$order[$i - 1]]->lng];
        $b = [$byId[$order[$i]]->lat, $byId[$order[$i]]->lng];
        $c = [$byId[$order[$k]]->lat, $byId[$order[$k]]->lng];
        $d = $k + 1 < count($order)
            ? [$byId[$order[$k + 1]]->lat, $byId[$order[$k + 1]]->lng]
            : [$depotLat, $depotLng];

        $current = $this->distanceBetween($aId, $bId, $a[0], $a[1], $b[0], $b[1], $matrix)
            + $this->distanceBetween($cId, $dId, $c[0], $c[1], $d[0], $d[1], $matrix);
        $swapped = $this->distanceBetween($aId, $cId, $a[0], $a[1], $c[0], $c[1], $matrix)
            + $this->distanceBetween($bId, $dId, $b[0], $b[1], $d[0], $d[1], $matrix);

        return $swapped - $current;
    }

    /**
     * @param  list<string>  $order
     * @return list<string>
     */
    private function reverseSegment(array $order, int $start, int $end): array
    {
        $segment = array_reverse(array_slice($order, $start, $end - $start + 1));

        return array_merge(
            array_slice($order, 0, $start),
            $segment,
            array_slice($order, $end + 1),
        );
    }

    /**
     * @param  list<string>  $order
     * @param  list<OptimizationStop>  $stops
     */
    /**
     * @param  list<string>  $order
     * @param  list<OptimizationStop>  $stops
     * @param  array<string, array<string, float>>|null  $matrix
     */
    private function routeDistanceKm(
        float $depotLat,
        float $depotLng,
        array $order,
        array $stops,
        ?array $matrix = null,
    ): float {
        if ($order === []) {
            return 0.0;
        }

        $byId = [];
        foreach ($stops as $stop) {
            $byId[$stop->id] = $stop;
        }

        $total = 0.0;
        $lat = $depotLat;
        $lng = $depotLng;
        $fromId = '__depot__';

        foreach ($order as $id) {
            if (! isset($byId[$id])) {
                continue;
            }
            $stop = $byId[$id];
            $total += $this->distanceBetween($fromId, $id, $lat, $lng, $stop->lat, $stop->lng, $matrix);
            $lat = $stop->lat;
            $lng = $stop->lng;
            $fromId = $id;
        }

        $total += $this->distanceBetween($fromId, '__depot__', $lat, $lng, $depotLat, $depotLng, $matrix);

        return $total;
    }

    private function haversineKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371.0;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Resolve distance using optional matrix, else haversine.
     *
     * @param  array<string, array<string, float>>|null  $matrix
     */
    public function distanceBetween(
        string|int $fromId,
        string|int $toId,
        float $fromLat,
        float $fromLng,
        float $toLat,
        float $toLng,
        ?array $matrix,
    ): float {
        $fromKey = (string) $fromId;
        $toKey = (string) $toId;

        if ($matrix !== null && isset($matrix[$fromKey][$toKey])) {
            return (float) $matrix[$fromKey][$toKey];
        }

        return $this->haversineKm($fromLat, $fromLng, $toLat, $toLng);
    }
}
