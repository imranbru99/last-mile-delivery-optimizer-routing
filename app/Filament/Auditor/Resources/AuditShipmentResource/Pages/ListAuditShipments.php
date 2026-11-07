<?php

declare(strict_types=1);

namespace App\Filament\Auditor\Resources\AuditShipmentResource\Pages;

use App\Filament\Auditor\Resources\AuditShipmentResource;
use Filament\Resources\Pages\ListRecords;

class ListAuditShipments extends ListRecords
{
    protected static string $resource = AuditShipmentResource::class;
}
