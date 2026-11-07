<x-filament-panels::page>
    <form wire:submit="checkIn" class="space-y-4">
        {{ $this->form }}
        <x-filament::button type="submit" icon="heroicon-o-check-circle">
            Record check-in
        </x-filament::button>
    </form>

    @if ($lastResult)
        <pre class="mt-6 max-h-48 overflow-auto rounded-lg bg-gray-100 p-4 text-xs dark:bg-gray-800">{{ json_encode($lastResult, JSON_PRETTY_PRINT) }}</pre>
    @endif
</x-filament-panels::page>
