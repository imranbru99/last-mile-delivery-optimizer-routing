<?php

declare(strict_types=1);

namespace App\Filament\Finance\Resources;

use App\Filament\Concerns\ModuleAware;
use App\Filament\Finance\Resources\InvoiceResource\Pages\CreateInvoice;
use App\Filament\Finance\Resources\InvoiceResource\Pages\EditInvoice;
use App\Filament\Finance\Resources\InvoiceResource\Pages\ListInvoices;
use App\Models\Invoice;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    use ModuleAware;

    public static string $module = 'invoicing';

    protected static ?string $model = Invoice::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Invoices';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('invoice_number')->required()->maxLength(64),
            Select::make('status')->options([
                'draft' => 'Draft',
                'issued' => 'Issued',
                'paid' => 'Paid',
                'void' => 'Void',
            ])->required(),
            TextInput::make('subtotal')->numeric()->required(),
            TextInput::make('tax')->numeric()->default(0),
            TextInput::make('total')->numeric()->required(),
            TextInput::make('currency')->default('BDT')->maxLength(3),
            DateTimePicker::make('issued_at'),
            DateTimePicker::make('due_at'),
            Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')->searchable()->sortable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('total')->money(fn (Invoice $r) => $r->currency),
                TextColumn::make('issued_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('pdfEn')
                        ->label('PDF (EN)')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->url(fn (Invoice $record): string => route('lmdo.invoices.pdf', ['invoice' => $record, 'locale' => 'en']))
                        ->openUrlInNewTab(),
                    Action::make('pdfBn')
                        ->label('PDF (BN)')
                        ->url(fn (Invoice $record): string => route('lmdo.invoices.pdf', ['invoice' => $record, 'locale' => 'bn']))
                        ->openUrlInNewTab(),
                    Action::make('pdfAr')
                        ->label('PDF (AR)')
                        ->url(fn (Invoice $record): string => route('lmdo.invoices.pdf', ['invoice' => $record, 'locale' => 'ar']))
                        ->openUrlInNewTab(),
                ])->label('Download PDF'),
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInvoices::route('/'),
            'create' => CreateInvoice::route('/create'),
            'edit' => EditInvoice::route('/{record}/edit'),
        ];
    }
}
