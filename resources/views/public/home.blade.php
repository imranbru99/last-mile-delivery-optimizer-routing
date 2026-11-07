@extends('layouts.public')

@section('title', 'Last-Mile Delivery Optimizer')

@section('content')
    <section class="relative mx-auto max-w-7xl px-4 pt-10 sm:px-6 lg:px-8 lg:pt-16">
        <div class="max-w-3xl">
            <p class="font-display text-sm font-semibold uppercase tracking-[0.2em] text-[var(--lmdo-teal)]">Last-Mile Delivery Optimizer</p>
            <h1 class="mt-4 font-display text-4xl font-extrabold leading-[1.05] tracking-tight text-[var(--lmdo-ink)] sm:text-5xl lg:text-6xl">
                Ship smarter routes from depot to doorstep.
            </h1>
            <p class="mt-5 max-w-xl text-lg text-[var(--lmdo-muted)]">
                A modular operations platform—VRP optimization, dispatch boards, and live fleet maps—scaled per business with {{ $moduleCount }}+ toggleable modules.
            </p>
            <div class="mt-8 flex flex-wrap items-center gap-3">
                <a href="{{ route('public.demo.route-optimizer') }}" class="lmdo-btn-primary">Explore route demo</a>
                <a href="{{ url('/business') }}" class="lmdo-btn-outline">Launch business panel</a>
                <a href="{{ route('public.track') }}" class="lmdo-btn-ghost">Track a parcel</a>
            </div>
        </div>
    </section>

    @module('live_tracking')
    <section class="relative mt-10 w-full" aria-label="Live map preview">
        <div class="relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] w-screen">
            <div id="lmdo-hero-map" class="h-[min(58vh,520px)] w-full border-y border-[var(--lmdo-border)]/80 shadow-[inset_0_1px_0_var(--lmdo-border)]"></div>
            <div class="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-[var(--lmdo-bg)] to-transparent"></div>
        </div>
    </section>
    @endmodule

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="lmdo-stat">
                <p class="lmdo-stat-value">{{ $moduleCount }}+</p>
                <p class="lmdo-stat-label">Modular capabilities</p>
            </div>
            <div class="lmdo-stat">
                <p class="lmdo-stat-value">{{ $presetCount }}</p>
                <p class="lmdo-stat-label">Industry presets</p>
            </div>
            <div class="lmdo-stat">
                <p class="lmdo-stat-value">12</p>
                <p class="lmdo-stat-label">Role-specific panels</p>
            </div>
            <div class="lmdo-stat">
                <p class="lmdo-stat-value">OSM</p>
                <p class="lmdo-stat-label">Leaflet map stack</p>
            </div>
        </div>
    </section>

    @module('orders')
    <section class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="font-display text-2xl font-bold text-[var(--lmdo-ink)] sm:text-3xl">Module highlights</h2>
                <p class="mt-2 text-sm text-[var(--lmdo-muted)]">From <code class="text-xs">config('modules.registry')</code> — enable per tenant in the marketplace.</p>
            </div>
            <a href="{{ route('public.modules.index') }}" class="lmdo-btn-outline text-sm">Browse all modules</a>
        </div>
        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($highlights as $module)
                @include('public.partials.module-card', ['module' => $module])
            @endforeach
        </div>
    </section>
    @endmodule

    @module('customer_tracking')
    <section class="mx-auto max-w-7xl px-4 pb-12 sm:px-6 lg:px-8">
        <div class="lmdo-card flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-lg font-bold text-[var(--lmdo-ink)]">Customer tracking portal</h2>
                <p class="mt-1 text-sm text-[var(--lmdo-muted)]">Live status, ETA, driver position, and POD summary on the public track page.</p>
            </div>
            <a href="{{ route('public.track') }}" class="lmdo-btn-primary shrink-0">Open tracking</a>
        </div>
    </section>
    @endmodule
@endsection

@push('head')
    <meta name="lmdo-map-demo" content="hero">
@endpush
