<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources\ModuleCatalogResource\Pages;

use App\Filament\SuperAdmin\Resources\ModuleCatalogResource;
use Filament\Resources\Pages\ListRecords;

class ListModuleCatalog extends ListRecords
{
    protected static string $resource = ModuleCatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
