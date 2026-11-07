<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Jobs\OptimizeRoutesJob;
use App\Models\DeliveryRoute;
use App\Services\Routing\RoutingManager;
use Illuminate\Http\JsonResponse;

class RoutesController extends Controller
{
    public function optimize(DeliveryRoute $deliveryRoute, RoutingManager $routing): JsonResponse
    {
        $meta = $deliveryRoute->optimization_meta ?? [];
        $meta['status'] = 'queued';
        $meta['progress'] = 0;
        $meta['queued_at'] = now()->toIso8601String();
        $meta['engine'] = $routing->engine()->name();
        $deliveryRoute->forceFill(['optimization_meta' => $meta])->save();

        OptimizeRoutesJob::dispatch($deliveryRoute->id);

        return response()->json([
            'queued' => true,
            'route_id' => $deliveryRoute->id,
            'route_code' => $deliveryRoute->code,
            'optimization' => $deliveryRoute->fresh()->optimization_meta,
        ], 202);
    }
}
