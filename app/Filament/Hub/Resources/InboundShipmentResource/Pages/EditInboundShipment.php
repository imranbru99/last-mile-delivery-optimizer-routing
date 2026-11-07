<?php

declare(strict_types=1);

namespace App\Filament\Hub\Resources\InboundShipmentResource\Pages;

use App\Filament\Hub\Resources\InboundShipmentResource;
use Filament\Resources\Pages\EditRecord;

class EditInboundShipment extends EditRecord
{
    protected static string $resource = InboundShipmentResource::class;
}
