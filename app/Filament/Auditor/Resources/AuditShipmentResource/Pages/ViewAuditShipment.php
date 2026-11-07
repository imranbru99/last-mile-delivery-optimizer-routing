<?php

declare(strict_types=1);

namespace App\Filament\Auditor\Resources\AuditShipmentResource\Pages;

use App\Filament\Auditor\Resources\AuditShipmentResource;
use Filament\Resources\Pages\ViewRecord;

class ViewAuditShipment extends ViewRecord
{
    protected static string $resource = AuditShipmentResource::class;
}
