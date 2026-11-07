<?php

declare(strict_types=1);

namespace App\Filament\Merchant\Resources\MerchantShipmentResource\Pages;

use App\Filament\Merchant\Resources\MerchantShipmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMerchantShipment extends EditRecord
{
    protected static string $resource = MerchantShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
