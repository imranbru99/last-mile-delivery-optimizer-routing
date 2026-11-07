<?php

declare(strict_types=1);

namespace App\Filament\Fleet\Resources\FuelLogResource\Pages;

use App\Filament\Fleet\Resources\FuelLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFuelLog extends CreateRecord
{
    protected static string $resource = FuelLogResource::class;
}
