<?php

declare(strict_types=1);

namespace App\Filament\Operations\Resources\RouteResource\Pages;

use App\Filament\Operations\Resources\RouteResource;
use App\Jobs\OptimizeRoutesJob;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditRoute extends EditRecord
{
    protected static string $resource = RouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('optimize')
                ->label('Optimize route')
                ->icon(Heroicon::OutlinedSparkles)
                ->requiresConfirmation()
                ->action(fn () => OptimizeRoutesJob::dispatch($this->getRecord()->id)),
            DeleteAction::make(),
        ];
    }
}
