@extends('layouts.public')

@section('title', 'Route optimizer demo')

@section('content')
    @include('public.partials.page-header', [
        'eyebrow' => 'Interactive demo',
        'title' => 'Route optimizer preview',
        'subtitle' => 'Sample stops around Dhaka on OpenStreetMap — illustrates the route_optimization module map layer.',
    ])

    <div class="relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] mb-16 w-screen">
        <div id="lmdo-demo-map" class="h-[70vh] min-h-[400px] w-full border-y border-[var(--lmdo-border)]"></div>
    </div>

    <div class="mx-auto max-w-3xl px-4 pb-20 text-sm text-[var(--lmdo-muted)] sm:px-6">
        <p>VRP solving, traffic overlays, and re-optimization run in the operations panel. This public page is a lightweight Leaflet showcase for marketing and onboarding tours.</p>
    </div>
@endsection

@push('head')
    <meta name="lmdo-map-demo" content="full">
@endpush
