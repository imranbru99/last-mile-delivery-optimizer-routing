<?php

declare(strict_types=1);

namespace App\Filament\Finance\Resources;

use App\Filament\Concerns\ModuleAware;
use App\Filament\Finance\Resources\ExpenseResource\Pages\CreateExpense;
use App\Filament\Finance\Resources\ExpenseResource\Pages\EditExpense;
use App\Filament\Finance\Resources\ExpenseResource\Pages\ListExpenses;
use App\Models\Expense;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExpenseResource extends Resource
{
    use ModuleAware;

    public static string $module = 'expenses';

    protected static ?string $model = Expense::class;

    protected static ?string $navigationLabel = 'Expenses';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static ?int $navigationSort = 50;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('category')->required()->maxLength(64),
            TextInput::make('description')->required()->maxLength(255),
            TextInput::make('amount')->numeric()->required(),
            TextInput::make('currency')->default('BDT')->maxLength(3),
            DateTimePicker::make('incurred_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category')->badge(),
                TextColumn::make('description')->limit(40),
                TextColumn::make('amount')->money(fn (Expense $record) => $record->currency)->sortable(),
                TextColumn::make('incurred_at')->dateTime()->sortable(),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExpenses::route('/'),
            'create' => CreateExpense::route('/create'),
            'edit' => EditExpense::route('/{record}/edit'),
        ];
    }
}
