<?php

declare(strict_types=1);

namespace App\Filament\Hub\Resources;

use App\Filament\Concerns\ModuleAware;
use App\Filament\Hub\Resources\HubResource\Pages\CreateHub;
use App\Filament\Hub\Resources\HubResource\Pages\EditHub;
use App\Filament\Hub\Resources\HubResource\Pages\ListHubs;
use App\Models\Hub;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HubResource extends Resource
{
    use ModuleAware;

    public static string $module = 'hubs';

    protected static ?string $model = Hub::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?string $navigationLabel = 'Hubs';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Hub details')->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('code')->required()->maxLength(64),
                Select::make('type')
                    ->options([
                        'hub' => 'Hub',
                        'warehouse' => 'Warehouse',
                        'cross_dock' => 'Cross-dock',
                    ])
                    ->required()
                    ->default('hub'),
                TextInput::make('capacity_json.max_parcels')
                    ->label('Max parcels (capacity)')
                    ->numeric()
                    ->default(1000),
                Toggle::make('is_active')->default(true),
            ])->columns(2),
            Section::make('Location')->schema([
                Textarea::make('address')->columnSpanFull(),
                TextInput::make('lat')->numeric()->label('Latitude'),
                TextInput::make('lng')->numeric()->label('Longitude'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->searchable()->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('type')->badge(),
                TextColumn::make('capacity_json.max_parcels')->label('Capacity')->numeric()->toggleable(),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('address')->limit(24)->toggleable(),
                TextColumn::make('updated_at')->since()->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHubs::route('/'),
            'create' => CreateHub::route('/create'),
            'edit' => EditHub::route('/{record}/edit'),
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
