<x-filament-panels::page>
    <div class="space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Search modules</label>
                <input
                    type="search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by name or description…"
                    class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 dark:border-gray-600 dark:bg-gray-800"
                />
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Category</label>
                <select
                    wire:model.live="categoryFilter"
                    class="mt-1 rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 dark:border-gray-600 dark:bg-gray-800"
                >
                    @foreach ($this->categories as $key => $cat)
                        <option value="{{ $key }}">{{ $cat['label'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($this->modules as $module)
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-teal-700 dark:text-teal-300">
                                {{ $module['category_label'] }} · {{ $module['tier_label'] }}
                            </p>
                            <h3 class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $module['name'] }}
                            </h3>
                        </div>
                        @if ($module['always_on'] ?? false)
                            <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                Always on
                            </span>
                        @else
                            <button
                                type="button"
                                wire:click="toggle('{{ $module['key'] }}')"
                                class="rounded-full px-3 py-1 text-xs font-semibold {{ $module['enabled'] ? 'bg-teal-600 text-white' : 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200' }}"
                            >
                                {{ $module['enabled'] ? 'Enabled' : 'Disabled' }}
                            </button>
                        @endif
                    </div>

                    <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">
                        {{ $module['description'] }}
                    </p>

                    @if (! empty($module['dependencies']))
                        <p class="mt-3 text-xs text-gray-500">
                            Depends on: {{ implode(', ', $module['dependencies']) }}
                        </p>
                    @endif

                    @if (! empty($module['unmet']) && ! $module['enabled'])
                        <p class="mt-2 text-xs text-amber-600">
                            Unmet: {{ implode(', ', $module['unmet']) }}
                        </p>
                    @endif

                    @if (! empty($module['dependents']) && $module['enabled'])
                        <p class="mt-2 text-xs text-rose-600">
                            Required by: {{ implode(', ', $module['dependents']) }}
                        </p>
                    @endif

                    <p class="mt-3 text-xs text-gray-500">
                        If disabled: {{ $module['what_you_lose'] ?? 'Related menus, APIs and pages become unavailable.' }}
                    </p>

                    @if (($module['monthly'] ?? 0) > 0)
                        <p class="mt-2 text-sm font-medium text-gray-800 dark:text-gray-100">
                            ${{ number_format($module['monthly'], 0) }}/mo
                        </p>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-500">No modules match your filters.</p>
            @endforelse
        </div>
    </div>
</x-filament-panels::page>
