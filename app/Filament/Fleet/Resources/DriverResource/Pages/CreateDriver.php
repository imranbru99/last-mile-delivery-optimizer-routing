<?php

declare(strict_types=1);

namespace App\Filament\Fleet\Resources\DriverResource\Pages;

use App\Filament\Fleet\Resources\DriverResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDriver extends CreateRecord
{
    protected static string $resource = DriverResource::class;
}
