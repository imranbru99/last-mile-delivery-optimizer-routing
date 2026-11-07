<?php

declare(strict_types=1);

namespace App\Filament\Operations\Resources;

use App\Filament\Concerns\ModuleAware;
use App\Filament\Operations\Resources\RouteResource\Pages\CreateRoute;
use App\Filament\Operations\Resources\RouteResource\Pages\EditRoute;
use App\Filament\Operations\Resources\RouteResource\Pages\ListRoutes;
use App\Jobs\OptimizeRoutesJob;
use App\Models\DeliveryRoute;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RouteResource extends Resource
{
    use ModuleAware;

    public static string $module = 'route_optimization';

    protected static ?string $model = DeliveryRoute::class;

    protected static ?string $modelLabel = 'Route';

    protected static ?string $pluralModelLabel = 'Routes';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static ?string $navigationLabel = 'Routes';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Route')->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('code')->required()->maxLength(64),
                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'planned' => 'Planned',
                        'dispatched' => 'Dispatched',
                        'in_progress' => 'In progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
                DatePicker::make('planned_date'),
                Select::make('driver_id')
                    ->relationship('driver', 'employee_code')
                    ->searchable(),
                Select::make('vehicle_id')
                    ->relationship('vehicle', 'plate_number')
                    ->searchable(),
                Select::make('hub_id')
                    ->relationship('hub', 'name')
                    ->searchable(),
                TextInput::make('total_distance_km')->numeric()->disabled(),
                TextInput::make('total_stops')->numeric()->disabled(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->searchable()->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('planned_date')->date()->sortable(),
                TextColumn::make('driver.employee_code')->label('Driver')->toggleable(),
                TextColumn::make('total_stops')->sortable(),
                TextColumn::make('total_distance_km')->numeric(2)->label('Km'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'planned' => 'Planned',
                        'dispatched' => 'Dispatched',
                        'in_progress' => 'In progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->recordActions([
                Action::make('optimize')
                    ->icon(Heroicon::OutlinedSparkles)
                    ->requiresConfirmation()
                    ->action(fn (DeliveryRoute $record) => OptimizeRoutesJob::dispatch($record->id)),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoutes::route('/'),
            'create' => CreateRoute::route('/create'),
            'edit' => EditRoute::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
