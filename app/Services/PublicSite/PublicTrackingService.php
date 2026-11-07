<?php

declare(strict_types=1);

namespace App\Services\PublicSite;

use App\Models\DriverLocation;
use App\Models\ProofOfDelivery;
use App\Models\RouteStop;
use App\Models\Shipment;
use Illuminate\Support\Str;

final class PublicTrackingService
{
    public function findShipment(?string $trackingCode, ?string $phone, ?int $businessId = null): ?Shipment
    {
        $trackingCode = trim((string) $trackingCode);
        $phoneDigits = preg_replace('/\D+/', '', (string) $phone) ?? '';

        if ($trackingCode === '' && $phoneDigits === '') {
            return null;
        }

        $query = Shipment::query()
            ->with([
                'deliveryAddress',
                'pickupAddress',
                'proofOfDeliveries' => fn ($q) => $q->latest('captured_at')->limit(3),
                'routeStops' => fn ($q) => $q->with('route')->orderBy('sequence'),
            ]);

        if ($businessId !== null) {
            $query->where('business_id', $businessId);
        }

        if ($trackingCode !== '') {
            return $query->where('tracking_code', $trackingCode)->first();
        }

        return $query
            ->whereHas('deliveryAddress', function ($address) use ($phoneDigits): void {
                $address->whereRaw(
                    "REPLACE(REPLACE(REPLACE(REPLACE(phone, ' ', ''), '-', ''), '+', ''), '(', '') LIKE ?",
                    ['%'.$phoneDigits.'%']
                );
            })
            ->latest('id')
            ->first();
    }

    /**
     * @return array<string, mixed>
     */
    public function present(Shipment $shipment): array
    {
        $stops = $shipment->routeStops;
        $activeStop = $stops->first(fn (RouteStop $stop) => in_array($stop->status, ['pending', 'in_progress'], true));
        $eta = $activeStop?->eta_at ?? $shipment->time_window_end;

        $route = $stops->first()?->route;
        $driverId = $route?->driver_id;

        $driverLocation = null;
        if ($driverId) {
            $loc = DriverLocation::query()
                ->where('driver_id', $driverId)
                ->latest('recorded_at')
                ->first();

            if ($loc) {
                $driverLocation = [
                    'lat' => (float) $loc->lat,
                    'lng' => (float) $loc->lng,
                    'speed' => (float) ($loc->speed ?? 0),
                    'recorded_at' => $loc->recorded_at?->toIso8601String(),
                ];
            }
        }

        $completed = $stops->where('status', 'completed')->count();
        $total = $stops->count();
        $routeProgress = $total > 0
            ? [
                'completed' => $completed,
                'total' => $total,
                'percent' => (int) round(($completed / $total) * 100),
            ]
            : null;

        /** @var ProofOfDelivery|null $pod */
        $pod = $shipment->proofOfDeliveries->first();
        $podSummary = $pod ? [
            'method' => $pod->method,
            'recipient_name' => $pod->recipient_name,
            'captured_at' => $pod->captured_at?->toIso8601String(),
            'otp_verified' => (bool) $pod->otp_verified,
        ] : null;

        $delivery = $shipment->deliveryAddress;
        $map = [
            'delivery' => $delivery && $delivery->lat && $delivery->lng
                ? ['lat' => (float) $delivery->lat, 'lng' => (float) $delivery->lng, 'label' => $delivery->formatted ?? $delivery->line1]
                : null,
            'driver' => $driverLocation,
            'pickup' => $shipment->pickupAddress && $shipment->pickupAddress->lat && $shipment->pickupAddress->lng
                ? ['lat' => (float) $shipment->pickupAddress->lat, 'lng' => (float) $shipment->pickupAddress->lng]
                : null,
        ];

        return [
            'shipment' => $shipment,
            'status' => $shipment->status,
            'status_label' => Str::headline((string) $shipment->status),
            'eta' => $eta,
            'eta_label' => $eta?->timezone(config('app.timezone'))->format('M j, g:i A'),
            'driver_location' => $driverLocation,
            'route_progress' => $routeProgress,
            'pod_summary' => $podSummary,
            'map' => $map,
            'route_code' => $route?->code,
        ];
    }
}
