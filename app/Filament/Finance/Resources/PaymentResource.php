<?php

declare(strict_types=1);

namespace App\Filament\Finance\Resources;

use App\Filament\Concerns\ModuleAware;
use App\Filament\Finance\Resources\PaymentResource\Pages\CreatePayment;
use App\Filament\Finance\Resources\PaymentResource\Pages\EditPayment;
use App\Filament\Finance\Resources\PaymentResource\Pages\ListPayments;
use App\Models\Payment;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    use ModuleAware;

    public static string $module = 'payments';

    protected static ?string $model = Payment::class;

    protected static ?string $navigationLabel = 'Payments';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('invoice_id')->relationship('invoice', 'invoice_number')->required()->searchable(),
            TextInput::make('amount')->numeric()->required(),
            TextInput::make('currency')->default('BDT')->maxLength(3),
            Select::make('status')->options([
                'pending' => 'Pending',
                'completed' => 'Completed',
                'failed' => 'Failed',
                'refunded' => 'Refunded',
            ])->required(),
            Select::make('gateway')->options([
                'manual' => 'Manual',
                'stripe_stub' => 'Stripe (stub)',
                'bkash_stub' => 'bKash (stub)',
            ])->required(),
            TextInput::make('reference')->maxLength(255),
            DateTimePicker::make('paid_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice.invoice_number')->label('Invoice')->searchable(),
                TextColumn::make('amount')->money(fn (Payment $record) => $record->currency),
                TextColumn::make('gateway')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('paid_at')->dateTime(),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayments::route('/'),
            'create' => CreatePayment::route('/create'),
            'edit' => EditPayment::route('/{record}/edit'),
        ];
    }
}
