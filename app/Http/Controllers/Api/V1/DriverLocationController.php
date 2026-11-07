<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreDriverLocationRequest;
use App\Models\DriverLocation;
use App\Models\DriverProfile;
use Illuminate\Http\JsonResponse;

class DriverLocationController extends Controller
{
    public function store(StoreDriverLocationRequest $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $profile = DriverProfile::query()
            ->where('user_id', $user->id)
            ->where('business_id', $user->business_id)
            ->firstOrFail();

        $location = DriverLocation::query()->create([
            'business_id' => $profile->business_id,
            'driver_id' => $profile->id,
            'lat' => $request->float('lat'),
            'lng' => $request->float('lng'),
            'heading' => $request->filled('heading') ? $request->float('heading') : null,
            'speed' => $request->filled('speed') ? $request->float('speed') : null,
            'accuracy' => $request->filled('accuracy') ? $request->float('accuracy') : null,
            'recorded_at' => now(),
        ]);

        return response()->json($location, 201);
    }
}
