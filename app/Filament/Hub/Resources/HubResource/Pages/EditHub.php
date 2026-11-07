<?php

declare(strict_types=1);

namespace App\Filament\Hub\Resources\HubResource\Pages;

use App\Filament\Hub\Resources\HubResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHub extends EditRecord
{
    protected static string $resource = HubResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
