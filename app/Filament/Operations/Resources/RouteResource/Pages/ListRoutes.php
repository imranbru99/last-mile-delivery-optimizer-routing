<?php

declare(strict_types=1);

namespace App\Filament\Operations\Resources\RouteResource\Pages;

use App\Filament\Operations\Resources\RouteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoutes extends ListRecords
{
    protected static string $resource = RouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
