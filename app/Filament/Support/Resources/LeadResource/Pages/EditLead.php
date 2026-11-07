<?php

declare(strict_types=1);

namespace App\Filament\Support\Resources\LeadResource\Pages;

use App\Filament\Support\Resources\LeadResource;
use Filament\Resources\Pages\EditRecord;

class EditLead extends EditRecord
{
    protected static string $resource = LeadResource::class;
}
