<?php

declare(strict_types=1);

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Shipment;
use App\Services\Performance\DrutogotiPageCache;
use App\Services\PublicSite\PublicTrackingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

final class TrackController extends Controller
{
    public function index(Request $request, DrutogotiPageCache $cache, PublicTrackingService $tracking, ?Business $business = null): View
    {
        $code = $request->string('code')->toString();
        $phone = $request->string('phone')->toString();

        $payload = null;
        $error = null;
        $podSignedUrl = null;

        if ($code !== '' || $phone !== '') {
            $cacheKey = 'track.id.'.md5(implode('|', [
                (string) ($business?->id),
                $code,
                $phone,
            ]));

            $shipmentId = $cache->remember($cacheKey, 60, function () use ($tracking, $code, $phone, $business): ?int {
                return $tracking->findShipment($code, $phone, $business?->id)?->id;
            });

            if ($shipmentId === null) {
                $error = 'No shipment found for that tracking code or phone number.';
            } else {
                $shipment = Shipment::query()->find($shipmentId);

                if ($shipment === null) {
                    $error = 'No shipment found for that tracking code or phone number.';
                } else {
                    $payload = $tracking->present($shipment);

                    if ($payload['pod_summary'] ?? null) {
                        $podSignedUrl = URL::temporarySignedRoute(
                            'public.track.pod',
                            now()->addHours(2),
                            ['shipment' => $shipment->id]
                        );
                    }
                }
            }
        }

        return view('public.track', [
            'business' => $business,
            'code' => $code,
            'phone' => $phone,
            'payload' => $payload,
            'error' => $error,
            'podSignedUrl' => $podSignedUrl,
        ]);
    }

    public function pod(Request $request, Shipment $shipment): View
    {
        $shipment->load(['proofOfDeliveries' => fn ($q) => $q->latest('captured_at')]);

        return view('public.track-pod', [
            'shipment' => $shipment,
            'pods' => $shipment->proofOfDeliveries,
        ]);
    }
}
