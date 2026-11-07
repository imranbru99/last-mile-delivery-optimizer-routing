<?php

declare(strict_types=1);

namespace App\Filament\Fleet\Resources\VehicleResource\Pages;

use App\Filament\Fleet\Resources\VehicleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVehicle extends CreateRecord
{
    protected static string $resource = VehicleResource::class;
}
