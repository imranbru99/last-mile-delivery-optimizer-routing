<?php

declare(strict_types=1);

namespace App\Filament\Merchant\Resources\MerchantShipmentResource\Pages;

use App\Filament\Merchant\Resources\MerchantShipmentResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateMerchantShipment extends CreateRecord
{
    protected static string $resource = MerchantShipmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        $data['business_id'] = $user?->business_id;
        $data['merchant_user_id'] = $user?->id;

        return $data;
    }
}
