<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreDriverPodRequest;
use App\Models\DriverProfile;
use App\Models\ProofOfDelivery;
use App\Models\Shipment;
use Illuminate\Http\JsonResponse;

class DriverPodController extends Controller
{
    public function store(StoreDriverPodRequest $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        DriverProfile::query()
            ->where('user_id', $user->id)
            ->where('business_id', $user->business_id)
            ->firstOrFail();

        $shipment = Shipment::query()->findOrFail($request->integer('shipment_id'));

        $pod = ProofOfDelivery::query()->create([
            'business_id' => $shipment->business_id,
            'shipment_id' => $shipment->id,
            'route_stop_id' => $request->integer('route_stop_id') ?: null,
            'method' => $request->string('method')->toString(),
            'recipient_name' => $request->input('recipient_name'),
            'lat' => $request->filled('lat') ? $request->float('lat') : null,
            'lng' => $request->filled('lng') ? $request->float('lng') : null,
            'otp_verified' => $request->boolean('otp_verified', $request->string('method')->toString() === 'otp'),
            'captured_at' => now(),
            'meta' => $request->input('meta'),
        ]);

        return response()->json($pod, 201);
    }
}
