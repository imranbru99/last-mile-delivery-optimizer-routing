<?php

declare(strict_types=1);

namespace App\Services\Addresses;

use App\Models\Address;
use App\Services\Ai\LmdoAiHub;

final class AddressCleaningService
{
    public function __construct(private readonly LmdoAiHub $aiHub) {}

    public function cleanRaw(string $raw, string $locale = 'en'): string
    {
        return $this->aiHub->cleanAddress($raw, $locale);
    }

    public function cleanAndPersist(Address $address, ?string $locale = null): Address
    {
        $locale = $locale ?? (string) (auth()->user()?->business?->locale ?? app()->getLocale());
        $source = trim($address->formatted ?? '')
            ?: trim(implode(', ', array_filter([
                $address->line1,
                $address->line2,
                $address->city,
                $address->postal_code,
            ])));

        if ($source === '') {
            return $address;
        }

        $cleaned = $this->cleanRaw($source, $locale);
        $address->forceFill([
            'formatted' => $cleaned,
            'line1' => $cleaned,
            'geocode_status' => 'ai_cleaned',
        ])->save();

        return $address->refresh();
    }
}
