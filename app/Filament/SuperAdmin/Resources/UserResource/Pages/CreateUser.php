<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources\UserResource\Pages;

use App\Filament\SuperAdmin\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $role = $this->record->role_hint;

        if (filled($role)) {
            $this->record->syncRoles([$role]);
        }
    }
}
