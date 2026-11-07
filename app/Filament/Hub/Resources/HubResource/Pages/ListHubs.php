<?php

declare(strict_types=1);

namespace App\Filament\Hub\Resources\HubResource\Pages;

use App\Filament\Hub\Resources\HubResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHubs extends ListRecords
{
    protected static string $resource = HubResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
