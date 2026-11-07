<x-filament-panels::page>
    <div class="max-w-2xl space-y-4 text-sm text-gray-600 dark:text-gray-300">
        <p>
            Use the actions above to re-seed demo businesses, users, modules, and sample shipments.
            The hourly scheduler also runs <code class="rounded bg-gray-100 px-1 dark:bg-gray-800">demo:reset</code> in demo environments.
        </p>
        @if (! app()->environment(['local', 'demo', 'testing']))
            <p class="rounded-lg border border-warning-300 bg-warning-50 p-3 text-warning-900 dark:border-warning-700 dark:bg-warning-950 dark:text-warning-100">
                You are not in a local/demo environment. Proceed only if you intend to overwrite seeded data.
            </p>
        @endif
    </div>
</x-filament-panels::page>
