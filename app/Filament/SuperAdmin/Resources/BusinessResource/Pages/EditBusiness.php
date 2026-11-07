<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources\BusinessResource\Pages;

use App\Filament\SuperAdmin\Resources\BusinessResource;
use App\Support\Modules\ModuleManager;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditBusiness extends EditRecord
{
    protected static string $resource = BusinessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        if ($this->record->wasChanged('business_type')) {
            app(ModuleManager::class)->applyPreset($this->record->business_type, $this->record);
        }
    }
}
