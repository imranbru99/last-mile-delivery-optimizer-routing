<?php

declare(strict_types=1);

namespace App\Filament\Operations\Pages;

use App\Filament\Concerns\ModuleAware;
use App\Models\DeliveryRoute;
use App\Models\Shipment;
use App\Services\Ai\LmdoAiHub;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class AiDispatcherCopilot extends Page implements HasForms
{
    use InteractsWithForms;
    use ModuleAware;

    public static string $module = 'ai_copilot';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCpuChip;

    protected static ?string $navigationLabel = 'AI Dispatcher';

    protected static ?string $title = 'AI Dispatcher Copilot';

    protected static ?int $navigationSort = 15;

    protected string $view = 'filament.operations.pages.ai-dispatcher-copilot';

    /** @var array<int, array{role: string, content: string}> */
    public array $messages = [];

    public string $prompt = '';

    public function mount(): void
    {
        $this->messages[] = [
            'role' => 'assistant',
            'content' => 'Ask about dispatch priorities, route trade-offs, or SLA risk. I use live shipment and route counts from your tenant.',
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('prompt')
                    ->label('Message')
                    ->rows(3)
                    ->required()
                    ->placeholder('Which routes should we prioritize before the evening window?'),
            ]);
    }

    public function send(LmdoAiHub $hub): void
    {
        $text = trim($this->prompt);
        if ($text === '') {
            return;
        }

        $this->messages[] = ['role' => 'user', 'content' => $text];

        $context = [
            'shipments_in_transit' => Shipment::query()->whereIn('status', ['in_transit', 'out_for_delivery', 'assigned'])->count(),
            'routes_active' => DeliveryRoute::query()->whereIn('status', ['dispatched', 'in_progress'])->count(),
            'module' => 'ai_copilot',
        ];

        $reply = $hub->dispatcherChat($text, $context);
        $this->messages[] = ['role' => 'assistant', 'content' => $reply];
        $this->prompt = '';
    }
}
