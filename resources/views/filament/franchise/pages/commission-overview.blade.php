<x-filament-panels::page>
    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <p class="text-sm text-gray-500">Delivered shipments</p>
            <p class="mt-2 text-3xl font-semibold">{{ number_format($deliveredCount) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <p class="text-sm text-gray-500">COD collected</p>
            <p class="mt-2 text-3xl font-semibold">{{ number_format($codCollected, 2) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <p class="text-sm text-gray-500">Estimated commission</p>
            <p class="mt-2 text-3xl font-semibold text-teal-600">{{ number_format($estimatedCommission, 2) }}</p>
        </div>
    </div>
</x-filament-panels::page>
