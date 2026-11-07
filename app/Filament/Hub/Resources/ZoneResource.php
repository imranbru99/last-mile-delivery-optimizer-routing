<?php

declare(strict_types=1);

namespace App\Filament\Hub\Resources;

use App\Filament\Concerns\ModuleAware;
use App\Filament\Hub\Resources\ZoneResource\Pages\CreateZone;
use App\Filament\Hub\Resources\ZoneResource\Pages\EditZone;
use App\Filament\Hub\Resources\ZoneResource\Pages\ListZones;
use App\Models\Zone;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ZoneResource extends Resource
{
    use ModuleAware;

    public static string $module = 'territories';

    protected static ?string $model = Zone::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static ?string $navigationLabel = 'Zones';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Zone')->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('code')->required()->maxLength(64),
                Select::make('hub_id')
                    ->relationship('hub', 'name')
                    ->searchable()
                    ->preload(),
                ColorPicker::make('color')->default('#0F766E'),
                TextInput::make('pricing_multiplier')->numeric()->default(1),
                Toggle::make('is_active')->default(true),
                Textarea::make('polygon_json')
                    ->label('Polygon JSON')
                    ->rows(8)
                    ->columnSpanFull()
                    ->formatStateUsing(function (mixed $state): ?string {
                        if ($state === null || $state === '') {
                            return null;
                        }

                        if (is_array($state)) {
                            return json_encode($state, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
                        }

                        return is_string($state) ? $state : null;
                    })
                    ->dehydrateStateUsing(function (?string $state): ?array {
                        if (blank($state)) {
                            return null;
                        }

                        $decoded = json_decode($state, true);

                        return is_array($decoded) ? $decoded : null;
                    }),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->searchable()->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('hub.name')->label('Hub')->sortable(),
                ColorColumn::make('color'),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('pricing_multiplier')->numeric(2)->toggleable(),
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
            'index' => ListZones::route('/'),
            'create' => CreateZone::route('/create'),
            'edit' => EditZone::route('/{record}/edit'),
        ];
    }
}
