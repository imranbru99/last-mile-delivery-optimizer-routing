<x-filament-panels::page>
    <div class="flex justify-end">
        <x-filament::button wire:click="refreshChecks" icon="heroicon-o-arrow-path" color="gray" size="sm">
            Refresh
        </x-filament::button>
    </div>

    <div class="mt-4 grid gap-4 sm:grid-cols-2">
        @foreach ($this->checks as $key => $check)
            @php
                $status = $check['status'];
                $border = match ($status) {
                    'ok' => 'border-success-300 dark:border-success-700',
                    'warning' => 'border-warning-300 dark:border-warning-700',
                    default => 'border-danger-300 dark:border-danger-700',
                };
                $badge = match ($status) {
                    'ok' => 'bg-success-50 text-success-700 ring-success-600/20 dark:bg-success-400/10 dark:text-success-400',
                    'warning' => 'bg-warning-50 text-warning-700 ring-warning-600/20 dark:bg-warning-400/10 dark:text-warning-400',
                    default => 'bg-danger-50 text-danger-700 ring-danger-600/20 dark:bg-danger-400/10 dark:text-danger-400',
                };
            @endphp
            <div class="rounded-xl border {{ $border }} bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-start justify-between gap-3">
                    <h3 class="text-sm font-semibold text-gray-950 dark:text-white">
                        {{ $check['label'] }}
                    </h3>
                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $badge }}">
                        {{ strtoupper($status) }}
                    </span>
                </div>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                    {{ $check['detail'] }}
                </p>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
