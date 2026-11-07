<?php

declare(strict_types=1);

namespace App\Filament\Operations\Pages;

use App\Filament\Concerns\ModuleAware;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Schema;
use ImranDevBd\AttendanceHub\Models\AttendanceLog;

class AttendanceRecent extends Page implements HasTable
{
    use InteractsWithTable;
    use ModuleAware;

    public static string $module = 'attendance';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static ?string $navigationLabel = 'Staff attendance';

    protected static ?string $title = 'Recent attendance punches';

    protected static ?int $navigationSort = 25;

    protected string $view = 'filament.operations.pages.attendance-recent';

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
                TextColumn::make('employee_id')->label('Employee'),
                TextColumn::make('punch_type')->badge(),
                TextColumn::make('punched_at')->dateTime()->sortable(),
            ])
            ->defaultSort('punched_at', 'desc')
            ->poll('30s');
    }
}
