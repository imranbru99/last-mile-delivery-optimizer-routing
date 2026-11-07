<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\Concerns\SuperAdminOnly;
use App\Filament\SuperAdmin\Resources\ModuleCatalogResource\Pages\ListModuleCatalog;
use App\Models\Module;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ModuleCatalogResource extends Resource
{
    use SuperAdminOnly;

    protected static ?string $model = Module::class;

    protected static ?string $navigationLabel = 'Module catalog';

    protected static ?string $modelLabel = 'Module';

    protected static ?string $pluralModelLabel = 'Module catalog';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?int $navigationSort = 30;

    protected static string|\UnitEnum|null $navigationGroup = 'Platform';

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')->searchable()->sortable()->copyable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('category')->badge()->sortable(),
                TextColumn::make('version')->toggleable(),
                TextColumn::make('pricing_tier')->label('Tier')->badge()->toggleable(),
                IconColumn::make('default_enabled')->label('Default on')->boolean(),
                IconColumn::make('always_on')->boolean()->toggleable(),
                IconColumn::make('is_active')->label('Active')->boolean()->sortable(),
            ])
            ->filters([
                TernaryFilter::make('default_enabled')->label('Default enabled'),
                TernaryFilter::make('is_active')->label('Active'),
            ])
            ->defaultSort('category')
            ->recordActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListModuleCatalog::route('/'),
        ];
    }
}
