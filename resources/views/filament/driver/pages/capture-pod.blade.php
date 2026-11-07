<x-filament-panels::page>
    <form wire:submit="submit" class="max-w-xl space-y-4">
        {{ $this->form }}

        <x-filament::button type="submit">
            Save proof of delivery
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
