<?php

declare(strict_types=1);

namespace App\Filament\Fleet\Resources\FuelLogResource\Pages;

use App\Filament\Fleet\Resources\FuelLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFuelLogs extends ListRecords
{
    protected static string $resource = FuelLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
