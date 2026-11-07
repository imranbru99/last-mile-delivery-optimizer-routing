<?php

declare(strict_types=1);

namespace App\Filament\Hub\Resources\ZoneResource\Pages;

use App\Filament\Hub\Resources\ZoneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListZones extends ListRecords
{
    protected static string $resource = ZoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
