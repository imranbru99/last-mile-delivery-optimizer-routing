<?php

declare(strict_types=1);

namespace App\Filament\Hub\Resources\InboundShipmentResource\Pages;

use App\Filament\Hub\Resources\InboundShipmentResource;
use Filament\Resources\Pages\ListRecords;

class ListInboundShipments extends ListRecords
{
    protected static string $resource = InboundShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
