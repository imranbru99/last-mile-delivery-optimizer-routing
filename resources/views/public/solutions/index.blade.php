@extends('layouts.public')

@section('title', 'Solutions')

@section('content')
    @include('public.partials.page-header', [
        'eyebrow' => 'Industries',
        'title' => 'Solutions & presets',
        'subtitle' => 'One-click module bundles from config/modules.php presets — courier, grocery, 3PL, franchise, and more.',
    ])

    <div class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($presets as $preset)
                <a href="{{ route('public.solutions.show', $preset['key']) }}" class="lmdo-card block hover:border-[var(--lmdo-teal)]/50">
                    <h2 class="font-display text-lg font-semibold text-[var(--lmdo-ink)]">{{ $preset['name'] }}</h2>
                    <p class="mt-2 text-sm text-[var(--lmdo-muted)]">{{ $preset['module_count'] }} modules pre-selected</p>
                    <p class="mt-3 font-mono text-xs text-[var(--lmdo-teal)]">{{ $preset['key'] }}</p>
                </a>
            @endforeach
        </div>
    </div>
@endsection
