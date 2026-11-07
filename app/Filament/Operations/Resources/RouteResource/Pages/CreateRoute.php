<?php

declare(strict_types=1);

namespace App\Filament\Operations\Resources\RouteResource\Pages;

use App\Filament\Operations\Resources\RouteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRoute extends CreateRecord
{
    protected static string $resource = RouteResource::class;
}
