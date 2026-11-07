<?php

declare(strict_types=1);

namespace App\Filament\Hub\Pages;

use App\Filament\Concerns\ModuleAware;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Schema;
use ImranDevBd\AttendanceHub\Models\AttendanceLog;

class AttendanceOverview extends Page implements HasTable
{
    use InteractsWithTable;
    use ModuleAware;

    public static string $module = 'attendance';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $navigationLabel = 'Attendance log';

    protected static ?string $title = 'Recent attendance';

    protected static ?int $navigationSort = 8;

    protected string $view = 'filament.hub.pages.attendance-overview';

    public static function canAccess(): bool
    {
        return static::moduleIsEnabled() && Schema::hasTable('attendance_logs');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(AttendanceLog::query())
            ->columns([
                TextColumn::make('employee_id')->label('Employee')->toggleable(),
                TextColumn::make('device_user_id')->label('Device user'),
                TextColumn::make('punch_type')->badge(),
                TextColumn::make('verify_mode')->toggleable(),
                TextColumn::make('punched_at')->dateTime()->sortable(),
            ])
            ->defaultSort('punched_at', 'desc')
            ->poll('30s');
    }
}
