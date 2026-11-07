<?php

declare(strict_types=1);

namespace App\Filament\Hub\Resources\HubResource\Pages;

use App\Filament\Hub\Resources\HubResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHub extends CreateRecord
{
    protected static string $resource = HubResource::class;
}
