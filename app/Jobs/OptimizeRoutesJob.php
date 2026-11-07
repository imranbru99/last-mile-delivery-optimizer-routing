<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\DeliveryRoute;
use App\Models\RouteStop;
use App\Services\Routing\DTOs\OptimizationRequest;
use App\Services\Routing\DTOs\OptimizationStop;
use App\Services\Routing\RoutingManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class OptimizeRoutesJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly int $deliveryRouteId,
    ) {}

    public function handle(RoutingManager $routing): void
    {
        $route = DeliveryRoute::query()->with(['stops.shipment.deliveryAddress', 'vehicle', 'hub'])->find($this->deliveryRouteId);

        if ($route === null) {
            return;
        }

        $meta = $route->optimization_meta ?? [];
        $meta['status'] = 'running';
        $meta['progress'] = 10;
        $meta['started_at'] = now()->toIso8601String();
        $route->forceFill(['optimization_meta' => $meta])->save();

        $depotLat = (float) ($route->hub?->lat ?? $route->stops->first()?->lat ?? 0);
        $depotLng = (float) ($route->hub?->lng ?? $route->stops->first()?->lng ?? 0);

        $stops = [];
        foreach ($route->stops as $stop) {
            if ($stop->type !== 'delivery' && $stop->type !== 'pickup') {
                continue;
            }

            $stops[] = new OptimizationStop(
                id: (string) $stop->id,
                lat: (float) ($stop->lat ?? $stop->shipment?->deliveryAddress?->lat ?? 0),
                lng: (float) ($stop->lng ?? $stop->shipment?->deliveryAddress?->lng ?? 0),
                weightKg: (float) ($stop->shipment?->weight_kg ?? 0),
                volumeM3: (float) ($stop->shipment?->volume_m3 ?? 0),
                timeWindowStart: $stop->shipment?->time_window_start,
                timeWindowEnd: $stop->shipment?->time_window_end,
                serviceTimeMin: (int) $stop->service_time_min,
            );
        }

        $meta['progress'] = 40;
        $route->forceFill(['optimization_meta' => $meta])->save();

        $vehicle = $route->vehicle;
        $request = new OptimizationRequest(
            depotLat: $depotLat,
            depotLng: $depotLng,
            stops: $stops,
            maxWeightKg: (float) ($vehicle?->capacity_weight_kg ?? 99999),
            maxVolumeM3: (float) ($vehicle?->capacity_volume_m3 ?? 99999),
            maxStops: (int) ($vehicle?->capacity_stops ?? count($stops)),
            averageSpeedKmh: (float) config('routing.speed_kmh', 25),
        );

        $result = $routing->engine()->optimize($request);

        $meta['progress'] = 80;
        $meta['result'] = [
            'total_distance_km' => $result->totalDistanceKm,
            'baseline_distance_km' => $result->baselineDistanceKm,
            'distance_saved_km' => $result->distanceSavedKm,
            'distance_saved_percent' => $result->distanceSavedPercent,
            'ordered_stop_ids' => $result->orderedStopIds,
            'unassigned_stop_ids' => $result->unassignedStopIds,
            'engine_meta' => $result->meta,
        ];
        $route->forceFill(['optimization_meta' => $meta])->save();

        DB::transaction(function () use ($route, $result): void {
            foreach ($result->orderedStopIds as $index => $stopId) {
                RouteStop::query()
                    ->where('route_id', $route->id)
                    ->whereKey((int) $stopId)
                    ->update(['sequence' => $index + 1]);
            }

            $route->forceFill([
                'total_distance_km' => $result->totalDistanceKm,
                'total_stops' => count($result->orderedStopIds),
                'optimization_meta' => array_merge($route->optimization_meta ?? [], [
                    'status' => 'completed',
                    'progress' => 100,
                    'completed_at' => now()->toIso8601String(),
                ]),
            ])->save();
        });
    }
}
