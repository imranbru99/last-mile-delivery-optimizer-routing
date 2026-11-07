<?php

declare(strict_types=1);

namespace App\Filament\Hub\Resources\ZoneResource\Pages;

use App\Filament\Hub\Resources\ZoneResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditZone extends EditRecord
{
    protected static string $resource = ZoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
