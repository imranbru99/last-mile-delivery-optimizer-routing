<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\PublicSite\PublicTrackingService;
use Illuminate\Http\JsonResponse;

class TrackingController extends Controller
{
    public function show(string $trackingCode, PublicTrackingService $tracking): JsonResponse
    {
        $shipment = $tracking->findShipment($trackingCode, null, null);

        if ($shipment === null) {
            return response()->json([
                'message' => 'Shipment not found for the provided tracking code.',
            ], 404);
        }

        $payload = $tracking->present($shipment);

        return response()->json([
            'tracking_code' => $shipment->tracking_code,
            'status' => $payload['status'],
            'status_label' => $payload['status_label'],
            'eta' => $payload['eta']?->toIso8601String(),
            'eta_label' => $payload['eta_label'],
            'driver_location' => $payload['driver_location'],
            'route_progress' => $payload['route_progress'],
            'pod_summary' => $payload['pod_summary'],
            'map' => $payload['map'],
            'route_code' => $payload['route_code'],
        ]);
    }
}
