<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources\BusinessResource\Pages;

use App\Filament\SuperAdmin\Resources\BusinessResource;
use App\Support\Modules\ModuleManager;
use Filament\Resources\Pages\CreateRecord;

class CreateBusiness extends CreateRecord
{
    protected static string $resource = BusinessResource::class;

    protected function afterCreate(): void
    {
        $preset = $this->record->business_type;

        if (filled($preset)) {
            app(ModuleManager::class)->applyPreset($preset, $this->record);
        }
    }
}
