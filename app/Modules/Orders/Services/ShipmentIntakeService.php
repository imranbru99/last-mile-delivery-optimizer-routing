<?php

declare(strict_types=1);

namespace App\Modules\Orders\Services;

use App\Models\Address;
use App\Models\Business;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class ShipmentIntakeService
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function create(Business $business, array $payload, ?int $merchantUserId = null): Shipment
    {
        return DB::transaction(function () use ($business, $payload, $merchantUserId): Shipment {
            $pickup = $this->resolveAddress($business, $payload['pickup'] ?? [], 'Pickup');
            $delivery = $this->resolveAddress($business, $payload['delivery'] ?? [], 'Delivery');

            $shipment = Shipment::query()->create([
                'business_id' => $business->id,
                'tracking_code' => (string) ($payload['tracking_code'] ?? ('LM-'.strtoupper(Str::random(10)))),
                'merchant_user_id' => $merchantUserId ?? ($payload['merchant_user_id'] ?? null),
                'service_level' => (string) ($payload['service_level'] ?? 'standard'),
                'priority' => (int) ($payload['priority'] ?? 5),
                'status' => 'pending',
                'pickup_address_id' => $pickup->id,
                'delivery_address_id' => $delivery->id,
                'weight_kg' => (float) ($payload['weight_kg'] ?? 0),
                'volume_m3' => (float) ($payload['volume_m3'] ?? 0),
                'pieces' => (int) ($payload['pieces'] ?? 1),
                'time_window_start' => $payload['time_window_start'] ?? null,
                'time_window_end' => $payload['time_window_end'] ?? null,
                'cod_amount' => (float) ($payload['cod_amount'] ?? 0),
                'currency' => (string) ($payload['currency'] ?? $business->currency ?? 'BDT'),
                'special_handling' => $payload['special_handling'] ?? null,
                'barcode' => $payload['barcode'] ?? null,
                'notes' => $payload['notes'] ?? null,
            ]);

            ShipmentEvent::query()->create([
                'business_id' => $business->id,
                'shipment_id' => $shipment->id,
                'type' => 'created',
                'payload' => ['source' => $payload['source'] ?? 'intake'],
                'happened_at' => now(),
            ]);

            return $shipment->fresh(['pickupAddress', 'deliveryAddress']) ?? $shipment;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function resolveAddress(Business $business, array $data, string $fallbackLabel): Address
    {
        if (! empty($data['id'])) {
            return Address::query()
                ->where('business_id', $business->id)
                ->findOrFail((int) $data['id']);
        }

        return Address::query()->create([
            'business_id' => $business->id,
            'label' => $data['label'] ?? $fallbackLabel,
            'contact_name' => $data['contact_name'] ?? null,
            'phone' => $data['phone'] ?? null,
            'line1' => (string) ($data['line1'] ?? 'Unknown'),
            'line2' => $data['line2'] ?? null,
            'city' => $data['city'] ?? $business->city,
            'state' => $data['state'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'country_code' => $data['country_code'] ?? $business->country_code ?? 'BD',
            'lat' => $data['lat'] ?? null,
            'lng' => $data['lng'] ?? null,
            'formatted' => $data['formatted'] ?? null,
            'geocode_status' => isset($data['lat'], $data['lng']) ? 'ok' : 'pending',
        ]);
    }
}
