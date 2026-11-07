<?php

declare(strict_types=1);

namespace App\Filament\Fleet\Resources\VehicleResource\Pages;

use App\Filament\Fleet\Resources\VehicleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVehicles extends ListRecords
{
    protected static string $resource = VehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
