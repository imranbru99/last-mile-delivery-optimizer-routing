<?php

declare(strict_types=1);

namespace App\Filament\Fleet\Resources\FuelLogResource\Pages;

use App\Filament\Fleet\Resources\FuelLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFuelLog extends EditRecord
{
    protected static string $resource = FuelLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
