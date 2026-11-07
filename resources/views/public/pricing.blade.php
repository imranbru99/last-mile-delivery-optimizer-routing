@extends('layouts.public')

@section('title', 'Pricing')

@section('content')
    @include('public.partials.page-header', [
        'eyebrow' => 'Plans',
        'title' => 'Modular pricing',
        'subtitle' => 'Core platform modules are included. Add starter, growth, scale, and platform tiers per module from the registry.',
    ])

    <div class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-5">
            @foreach ($tiers as $key => $tier)
                <div class="lmdo-card flex flex-col {{ $key === 'growth' ? 'ring-2 ring-[var(--lmdo-teal)]/40' : '' }}">
                    <p class="text-xs font-semibold uppercase tracking-wider text-[var(--lmdo-teal)]">{{ $tier['label'] }}</p>
                    <p class="mt-3 font-display text-4xl font-bold text-[var(--lmdo-ink)]">
                        @if ($tier['monthly'] > 0)
                            ${{ $tier['monthly'] }}
                            <span class="text-base font-normal text-[var(--lmdo-muted)]">/mo add-on</span>
                        @else
                            Included
                        @endif
                    </p>
                    <p class="mt-2 text-sm text-[var(--lmdo-muted)]">
                        {{ $byTier->get($key, 0) }} modules tagged <code class="text-xs">{{ $key }}</code>
                    </p>
                </div>
            @endforeach
        </div>
        <p class="mt-10 text-center text-sm text-[var(--lmdo-muted)]">
            {{ $moduleCount }} total registry modules · SaaS metering via <code class="text-xs">saas_billing</code> (platform tier).
        </p>
    </div>
@endsection
