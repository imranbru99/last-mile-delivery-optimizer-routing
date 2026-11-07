<?php

declare(strict_types=1);

namespace App\Filament\Merchant\Resources\MerchantShipmentResource\Pages;

use App\Filament\Merchant\Resources\MerchantShipmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMerchantShipments extends ListRecords
{
    protected static string $resource = MerchantShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
