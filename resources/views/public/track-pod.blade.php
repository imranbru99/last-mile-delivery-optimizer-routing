@extends('layouts.public')

@section('title', 'Proof of delivery')

@section('content')
    <div class="mx-auto max-w-xl px-4 py-16 sm:px-6 lg:px-8">
        <h1 class="font-display text-2xl font-bold text-[var(--lmdo-ink)]">POD — {{ $shipment->tracking_code }}</h1>
        <ul class="mt-6 space-y-4">
            @forelse ($pods as $pod)
                <li class="lmdo-card text-sm">
                    <p class="font-semibold">{{ ucfirst($pod->method) }}</p>
                    @if ($pod->recipient_name)
                        <p class="text-[var(--lmdo-muted)]">Signed by {{ $pod->recipient_name }}</p>
                    @endif
                    <p class="text-xs text-[var(--lmdo-muted)]">{{ optional($pod->captured_at)->toDayDateTimeString() }}</p>
                </li>
            @empty
                <li class="text-sm text-[var(--lmdo-muted)]">No proof captured yet.</li>
            @endforelse
        </ul>
    </div>
@endsection
