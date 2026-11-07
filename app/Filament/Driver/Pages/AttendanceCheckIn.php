<?php

declare(strict_types=1);

namespace App\Filament\Driver\Pages;

use App\Filament\Concerns\ModuleAware;
use App\Services\Attendance\DriverAttendanceService;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class AttendanceCheckIn extends Page implements HasForms
{
    use InteractsWithForms;
    use ModuleAware;

    public static string $module = 'attendance';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedFingerPrint;

    protected static ?string $navigationLabel = 'Check in';

    protected static ?string $title = 'Attendance check-in';

    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.driver.pages.attendance-check-in';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    /** @var array<string, mixed>|null */
    public ?array $lastResult = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('lat')->numeric()->label('Latitude'),
                TextInput::make('lng')->numeric()->label('Longitude'),
            ])
            ->statePath('data');
    }

    public function checkIn(DriverAttendanceService $attendance): void
    {
        $user = auth()->user();
        if (! $user) {
            return;
        }

        $state = $this->form->getState();
        $lat = isset($state['lat']) ? (float) $state['lat'] : null;
        $lng = isset($state['lng']) ? (float) $state['lng'] : null;

        $this->lastResult = $attendance->appCheckIn($user, $lat, $lng);

        Notification::make()
            ->title($this->lastResult['ok'] ? 'Check-in recorded' : 'Check-in failed')
            ->success($this->lastResult['ok'] ?? false)
            ->send();
    }
}
