@extends('layouts.public')

@section('title', 'Features')

@section('content')
    @include('public.partials.page-header', [
        'eyebrow' => 'Capabilities',
        'title' => 'Features by category',
        'subtitle' => 'Every capability maps to a module key in the central registry — categories A–K plus core platform modules.',
    ])

    <div class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($groups as $group)
                <a href="{{ route('public.features.category', $group['key']) }}" class="lmdo-card group block transition hover:border-[var(--lmdo-teal)]/40">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-xs text-[var(--lmdo-teal)]">{{ $group['letter'] }}</span>
                        <span class="text-xs text-[var(--lmdo-muted)]">{{ $group['modules']->count() }} modules</span>
                    </div>
                    <h2 class="mt-2 font-display text-xl font-semibold text-[var(--lmdo-ink)] group-hover:text-[var(--lmdo-teal)]">{{ $group['label'] }}</h2>
                    <ul class="mt-4 space-y-1 text-sm text-[var(--lmdo-muted)]">
                        @foreach ($group['modules']->take(4) as $mod)
                            <li>{{ $mod['name'] }}</li>
                        @endforeach
                        @if ($group['modules']->count() > 4)
                            <li class="text-[var(--lmdo-teal)]">+ {{ $group['modules']->count() - 4 }} more</li>
                        @endif
                    </ul>
                </a>
            @endforeach
        </div>
    </div>
@endsection
