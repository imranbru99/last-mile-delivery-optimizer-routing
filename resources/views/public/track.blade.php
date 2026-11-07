@extends('layouts.public')

@section('title', 'Track shipment')

@section('content')
    @include('public.partials.page-header', [
        'eyebrow' => 'Customer tracking',
        'title' => 'Track your delivery',
        'subtitle' => $business
            ? 'Tracking portal for '.$business->name
            : 'Enter a tracking code or recipient phone number to see live status, ETA, and map progress.',
    ])

    <div class="mx-auto max-w-3xl px-4 pb-20 sm:px-6 lg:px-8">
        <form
            class="lmdo-card space-y-4"
            method="get"
            action="{{ $business ? route('public.track.business', $business) : route('public.track') }}"
        >
            <label class="block text-sm">
                <span class="mb-1 block text-xs font-medium text-[var(--lmdo-muted)]">Tracking code</span>
                <input type="text" name="code" value="{{ old('code', $code) }}" placeholder="SWI00000001" class="lmdo-input w-full" autocomplete="off">
            </label>
            <label class="block text-sm">
                <span class="mb-1 block text-xs font-medium text-[var(--lmdo-muted)]">Recipient phone (optional)</span>
                <input type="tel" name="phone" value="{{ old('phone', $phone) }}" placeholder="+8801..." class="lmdo-input w-full" autocomplete="tel">
            </label>
            <button type="submit" class="lmdo-btn-primary w-full">Track shipment</button>
        </form>

        @if ($error)
            <p class="mt-6 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 dark:border-amber-900/40 dark:bg-amber-950/30 dark:text-amber-100" role="alert">
                {{ $error }}
            </p>
        @endif

        @if ($payload)
            @php
                $map = $payload['map'] ?? [];
                $hasMap = ($map['delivery'] ?? null) || ($map['driver'] ?? null);
            @endphp
            <div class="lmdo-card mt-8 space-y-6">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-[var(--lmdo-muted)]">Tracking</p>
                        <p class="font-display text-xl font-bold text-[var(--lmdo-ink)]">{{ $payload['shipment']->tracking_code }}</p>
                    </div>
                    <span class="rounded-full bg-[var(--lmdo-teal)]/10 px-3 py-1 text-sm font-semibold text-[var(--lmdo-teal-deep)]">
                        {{ $payload['status_label'] }}
                    </span>
                </div>

                <dl class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs text-[var(--lmdo-muted)]">Estimated arrival</dt>
                        <dd class="text-sm font-medium">{{ $payload['eta_label'] ?? 'Pending' }}</dd>
                    </div>
                    @if ($payload['route_code'] ?? null)
                        <div>
                            <dt class="text-xs text-[var(--lmdo-muted)]">Route</dt>
                            <dd class="text-sm font-medium">{{ $payload['route_code'] }}</dd>
                        </div>
                    @endif
                    @if ($payload['route_progress'] ?? null)
                        <div class="sm:col-span-2">
                            <dt class="text-xs text-[var(--lmdo-muted)]">Route progress</dt>
                            <dd class="mt-1 text-sm font-medium">
                                {{ $payload['route_progress']['completed'] }} / {{ $payload['route_progress']['total'] }} stops
                                ({{ $payload['route_progress']['percent'] }}%)
                            </dd>
                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-[var(--lmdo-border)]">
                                <div class="h-full bg-[var(--lmdo-teal)]" style="width: {{ $payload['route_progress']['percent'] }}%"></div>
                            </div>
                        </div>
                    @endif
                </dl>

                @if ($payload['pod_summary'] ?? null)
                    <div class="rounded-lg border border-[var(--lmdo-border)] p-4 text-sm">
                        <p class="font-semibold text-[var(--lmdo-ink)]">Proof of delivery</p>
                        <p class="mt-1 text-[var(--lmdo-muted)]">
                            {{ ucfirst($payload['pod_summary']['method']) }}
                            @if ($payload['pod_summary']['recipient_name'])
                                — {{ $payload['pod_summary']['recipient_name'] }}
                            @endif
                            @if ($payload['pod_summary']['captured_at'])
                                <span class="block text-xs">Captured {{ \Illuminate\Support\Carbon::parse($payload['pod_summary']['captured_at'])->diffForHumans() }}</span>
                            @endif
                        </p>
                        @if ($podSignedUrl)
                            <a href="{{ $podSignedUrl }}" class="mt-2 inline-block text-xs font-medium text-[var(--lmdo-teal-deep)] underline">View POD details (signed link)</a>
                        @endif
                    </div>
                @endif

                @if ($hasMap)
                    <div id="lmdo-track-map" class="h-72 w-full overflow-hidden rounded-xl border border-[var(--lmdo-border)]"></div>
                @endif

                @if ($payload['driver_location'] ?? null)
                    <p class="text-xs text-[var(--lmdo-muted)]">
                        Driver location updated
                        {{ \Illuminate\Support\Carbon::parse($payload['driver_location']['recorded_at'])->diffForHumans() }}
                        @ {{ number_format($payload['driver_location']['speed'], 0) }} km/h
                    </p>
                @endif
            </div>
        @endif
    </div>
@endsection

@if ($payload && (($payload['map']['delivery'] ?? null) || ($payload['map']['driver'] ?? null)))
    @push('head')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
    @endpush
    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const el = document.getElementById('lmdo-track-map');
                if (! el || typeof L === 'undefined') return;

                const mapData = @json($payload['map']);
                const center = mapData.driver ?? mapData.delivery ?? { lat: 23.8103, lng: 90.4125 };
                const map = L.map(el).setView([center.lat, center.lng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap'
                }).addTo(map);

                const bounds = [];
                if (mapData.pickup) {
                    const m = L.marker([mapData.pickup.lat, mapData.pickup.lng]).addTo(map).bindPopup('Pickup');
                    bounds.push([mapData.pickup.lat, mapData.pickup.lng]);
                }
                if (mapData.delivery) {
                    const m = L.marker([mapData.delivery.lat, mapData.delivery.lng]).addTo(map)
                        .bindPopup(mapData.delivery.label ?? 'Delivery');
                    bounds.push([mapData.delivery.lat, mapData.delivery.lng]);
                }
                if (mapData.driver) {
                    const m = L.circleMarker([mapData.driver.lat, mapData.driver.lng], {
                        radius: 9,
                        color: '#0f766e',
                        fillColor: '#14b8a6',
                        fillOpacity: 0.9,
                    }).addTo(map).bindPopup('Driver');
                    bounds.push([mapData.driver.lat, mapData.driver.lng]);
                }
                if (bounds.length > 1) {
                    map.fitBounds(bounds, { padding: [32, 32] });
                }
                setTimeout(() => map.invalidateSize(), 150);
            });
        </script>
    @endpush
@endif
