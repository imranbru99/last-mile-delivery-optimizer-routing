<x-filament-panels::page>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end">
        <div class="flex-1">
            <label class="text-sm font-medium">Query</label>
            <input
                type="text"
                wire:model="searchQuery"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-900"
                placeholder="Keyword for DirectoryLeadScraper / AiCatalog"
            />
        </div>
        <x-filament::button wire:click="runScrape" icon="heroicon-o-sparkles">
            Dispatch scrape job
        </x-filament::button>
    </div>

    {{ $this->table }}
</x-filament-panels::page>
