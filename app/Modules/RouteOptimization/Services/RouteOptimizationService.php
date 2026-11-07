<?php

declare(strict_types=1);

namespace App\Modules\RouteOptimization\Services;

use App\Jobs\OptimizeRoutesJob;
use App\Models\Business;
use App\Models\DeliveryRoute;
use App\Models\Hub;
use App\Models\Shipment;
use App\Services\Routing\DTOs\OptimizationRequest;
use App\Services\Routing\DTOs\OptimizationStop;
use App\Services\Routing\RoutingManager;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final class RouteOptimizationService
{
    public function __construct(
        private readonly RoutingManager $routing,
    ) {}

    /**
     * @param  list<int>  $shipmentIds
     * @return array{route: DeliveryRoute, result: array<string, mixed>}
     */
    public function optimizeNow(
        Business $business,
        Hub $hub,
        array $shipmentIds,
        float $maxWeightKg = 800,
        float $maxVolumeM3 = 8,
        int $maxStops = 40,
        ?string $driver = null,
    ): array {
        $shipments = Shipment::query()
            ->with('deliveryAddress')
            ->where('business_id', $business->id)
            ->whereIn('id', $shipmentIds)
            ->get();

        $stops = $this->toStops($shipments);
        $request = new OptimizationRequest(
            depotLat: (float) $hub->lat,
            depotLng: (float) $hub->lng,
            stops: $stops,
            maxWeightKg: $maxWeightKg,
            maxVolumeM3: $maxVolumeM3,
            maxStops: $maxStops,
            routeStart: Carbon::now(),
        );

        $result = $this->routing->engine($driver)->optimize($request);

        $route = DeliveryRoute::query()->create([
            'business_id' => $business->id,
            'name' => 'Optimized '.$hub->code.' '.now()->format('H:i'),
            'code' => 'OPT-'.$hub->code.'-'.now()->format('YmdHis'),
            'status' => 'planned',
            'hub_id' => $hub->id,
            'planned_date' => now()->toDateString(),
            'total_distance_km' => $result->totalDistanceKm,
            'total_duration_min' => (int) ceil(($result->totalDistanceKm / max((float) config('routing.speed_kmh', 25), 1)) * 60),
            'total_stops' => count($result->orderedStopIds),
            'optimization_meta' => [
                'baseline_km' => $result->baselineDistanceKm,
                'saved_km' => $result->distanceSavedKm,
                'saved_percent' => $result->distanceSavedPercent,
                'meta' => $result->meta,
                'unassigned' => $result->unassignedStopIds,
                'status' => 'completed',
                'progress' => 100,
            ],
        ]);

        $sequence = 1;
        foreach ($result->orderedStopIds as $stopId) {
            $shipment = $shipments->firstWhere('id', (int) $stopId);

            if ($shipment === null) {
                continue;
            }

            $route->stops()->create([
                'business_id' => $business->id,
                'shipment_id' => $shipment->id,
                'sequence' => $sequence++,
                'type' => 'delivery',
                'status' => 'pending',
                'lat' => $shipment->deliveryAddress?->lat,
                'lng' => $shipment->deliveryAddress?->lng,
                'service_time_min' => (int) config('routing.service_time_min', 5),
                'address_snapshot' => [
                    'line1' => $shipment->deliveryAddress?->line1,
                    'city' => $shipment->deliveryAddress?->city,
                ],
            ]);

            $shipment->update(['status' => 'assigned', 'hub_id' => $hub->id]);
        }

        return [
            'route' => $route->fresh('stops') ?? $route,
            'result' => [
                'ordered_stop_ids' => $result->orderedStopIds,
                'total_distance_km' => $result->totalDistanceKm,
                'baseline_distance_km' => $result->baselineDistanceKm,
                'distance_saved_km' => $result->distanceSavedKm,
                'distance_saved_percent' => $result->distanceSavedPercent,
                'unassigned_stop_ids' => $result->unassignedStopIds,
                'meta' => $result->meta,
            ],
        ];
    }

    public function queue(int $deliveryRouteId): void
    {
        OptimizeRoutesJob::dispatch($deliveryRouteId);
    }

    /**
     * @param  Collection<int, Shipment>  $shipments
     * @return list<OptimizationStop>
     */
    private function toStops(Collection $shipments): array
    {
        $stops = [];

        foreach ($shipments as $shipment) {
            $address = $shipment->deliveryAddress;
            if ($address === null || $address->lat === null || $address->lng === null) {
                continue;
            }

            $stops[] = new OptimizationStop(
                id: (string) $shipment->id,
                lat: (float) $address->lat,
                lng: (float) $address->lng,
                weightKg: (float) $shipment->weight_kg,
                volumeM3: (float) $shipment->volume_m3,
                timeWindowStart: $shipment->time_window_start,
                timeWindowEnd: $shipment->time_window_end,
                serviceTimeMin: (int) config('routing.service_time_min', 5),
            );
        }

        return $stops;
    }
}
