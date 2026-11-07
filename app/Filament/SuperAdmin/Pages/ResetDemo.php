<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Pages;

use App\Filament\Concerns\SuperAdminOnly;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Artisan;

class ResetDemo extends Page
{
    use SuperAdminOnly;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowPath;

    protected static ?string $navigationLabel = 'Reset demo';

    protected static ?string $title = 'Reset demo data';

    protected static ?int $navigationSort = 60;

    protected static string|\UnitEnum|null $navigationGroup = 'Platform';

    protected string $view = 'filament.super-admin.pages.reset-demo';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('resetDemo')
                ->label('Run demo:reset')
                ->icon(Heroicon::OutlinedArrowPath)
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Reset demo data?')
                ->modalDescription('Re-seeds demo businesses, users, and operational sample data. This may take a minute.')
                ->action(function (): void {
                    Artisan::call('demo:reset');

                    Notification::make()
                        ->title('Demo data reset')
                        ->body(trim(Artisan::output()) ?: 'Seeder finished successfully.')
                        ->success()
                        ->send();
                }),
            Action::make('resetDemoFresh')
                ->label('Fresh migrate + seed')
                ->icon(Heroicon::OutlinedTrash)
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Run migrate:fresh --seed?')
                ->modalDescription('Destructive: drops all tables, migrates, and seeds. Use only in local/demo environments.')
                ->visible(fn (): bool => app()->environment(['local', 'demo', 'testing']))
                ->action(function (): void {
                    Artisan::call('demo:reset', ['--fresh' => true]);

                    Notification::make()
                        ->title('Database refreshed')
                        ->body(trim(Artisan::output()) ?: 'Migrate fresh + seed completed.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
