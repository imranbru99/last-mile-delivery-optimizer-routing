<?php

declare(strict_types=1);

namespace App\Filament\Support\Pages;

use App\Filament\Concerns\ModuleAware;
use App\Jobs\LeadGenerationJob;
use App\Models\Lead;
use App\Support\Tenancy\TenantContext;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class LeadGeneration extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use ModuleAware;

    public static string $module = 'crm';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Lead generation';

    protected static ?string $title = 'AI directory lead generation';

    protected static ?int $navigationSort = 5;

    protected string $view = 'filament.support.pages.lead-generation';

    public ?string $searchQuery = '';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('searchQuery')
                ->label('Directory / keyword query')
                ->placeholder('e.g. courier companies in Dhaka')
                ->required()
                ->maxLength(255),
        ]);
    }

    public function runScrape(): void
    {
        $query = trim((string) $this->searchQuery);
        if ($query === '') {
            return;
        }

        $business = app(TenantContext::class)->business() ?? auth()->user()?->business;
        if (! $business) {
            Notification::make()->title('No business context')->danger()->send();

            return;
        }

        LeadGenerationJob::dispatch($business->id, $query, (string) ($business->locale ?? 'en'));

        Notification::make()
            ->title('Lead generation queued')
            ->body('Results will appear in the table shortly.')
            ->success()
            ->send();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Lead::query()->latest())
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->toggleable(),
                TextColumn::make('phone')->toggleable(),
                TextColumn::make('source')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('created_at')->since()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('15s');
    }
}
