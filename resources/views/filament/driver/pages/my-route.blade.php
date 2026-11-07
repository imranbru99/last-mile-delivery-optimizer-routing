<x-filament-panels::page>
    <div class="space-y-4" x-data="{
        register() {
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js').catch(() => {})
            }
        }
    }" x-init="register()">
        <link rel="manifest" href="/manifest.webmanifest" />

        @forelse ($this->routes as $route)
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $route->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $route->code }} · {{ $route->status }}</p>
                    </div>
                    <span class="text-sm font-medium">{{ $route->stops->count() }} stops</span>
                </div>

                <ol class="mt-4 space-y-2">
                    @foreach ($route->stops as $stop)
                        <li class="flex items-start justify-between rounded-lg bg-gray-50 px-3 py-2 text-sm dark:bg-gray-800">
                            <div>
                                <p class="font-medium">#{{ $stop->sequence }} · {{ $stop->type }}</p>
                                <p class="text-gray-500">{{ $stop->status }}
                                    @if ($stop->lat)
                                        · {{ number_format((float) $stop->lat, 5) }}, {{ number_format((float) $stop->lng, 5) }}
                                    @endif
                                </p>
                            </div>
                            @if ($stop->lat && $stop->lng)
                                <a
                                    class="text-teal-700 underline dark:text-teal-300"
                                    href="https://www.openstreetmap.org/directions?engine=fossgis_osrm_car&route={{ $stop->lat }}%2C{{ $stop->lng }}"
                                    target="_blank"
                                    rel="noopener"
                                >Navigate</a>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>
        @empty
            <p class="text-sm text-gray-500">No assigned routes right now.</p>
        @endforelse
    </div>
</x-filament-panels::page>
