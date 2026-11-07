<?php

declare(strict_types=1);

namespace App\Filament\Fleet\Resources\DriverResource\Pages;

use App\Filament\Fleet\Resources\DriverResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDriver extends EditRecord
{
    protected static string $resource = DriverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
