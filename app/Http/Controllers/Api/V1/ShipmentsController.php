<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreShipmentRequest;
use App\Models\Shipment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShipmentsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $shipments = Shipment::query()
            ->with(['pickupAddress', 'deliveryAddress'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest('id')
            ->paginate(min(100, (int) $request->integer('per_page', 25)));

        return response()->json($shipments);
    }

    public function store(StoreShipmentRequest $request): JsonResponse
    {
        $shipment = Shipment::query()->create([
            ...$request->validated(),
            'status' => $request->input('status', 'pending'),
            'service_level' => $request->input('service_level', 'standard'),
            'currency' => $request->input('currency', 'BDT'),
        ]);

        return response()->json($shipment->load(['pickupAddress', 'deliveryAddress']), 201);
    }

    public function show(Shipment $shipment): JsonResponse
    {
        return response()->json($shipment->load(['pickupAddress', 'deliveryAddress', 'events']));
    }
}
