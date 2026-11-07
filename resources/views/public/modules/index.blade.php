@extends('layouts.public')

@section('title', 'Module marketplace')

@section('content')
    @include('public.partials.page-header', [
        'eyebrow' => 'Registry',
        'title' => 'Module marketplace',
        'subtitle' => 'Filter the full catalog synced with Filament Module Marketplace and tenant business_modules rows.',
    ])

    <div class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8"
         x-data="{
             q: '',
             category: 'all',
             tier: 'all',
             categories: @js(collect($categories)->map(fn ($c, $k) => ['key' => $k, 'label' => $c['label']])->values()),
             modules: @js($modules->values()),
             match(m) {
                 if (this.category !== 'all' && m.category !== this.category) return false;
                 if (this.tier !== 'all' && m.tier !== this.tier) return false;
                 if (! this.q.trim()) return true;
                 const hay = (m.name + ' ' + m.key + ' ' + m.description).toLowerCase();
                 return hay.includes(this.q.toLowerCase());
             },
             filtered() { return this.modules.filter(m => this.match(m)); }
         }">
        <div class="flex flex-col gap-4 rounded-2xl border border-[var(--lmdo-border)] bg-[var(--lmdo-surface)]/80 p-4 backdrop-blur sm:flex-row sm:items-end">
            <label class="flex-1 text-sm">
                <span class="mb-1 block text-xs font-medium text-[var(--lmdo-muted)]">Search</span>
                <input type="search" x-model="q" placeholder="Route, COD, fleet…" class="lmdo-input w-full">
            </label>
            <label class="text-sm sm:w-48">
                <span class="mb-1 block text-xs font-medium text-[var(--lmdo-muted)]">Category</span>
                <select x-model="category" class="lmdo-input w-full">
                    <option value="all">All categories</option>
                    <template x-for="c in categories" :key="c.key">
                        <option :value="c.key" x-text="c.label"></option>
                    </template>
                </select>
            </label>
            <label class="text-sm sm:w-40">
                <span class="mb-1 block text-xs font-medium text-[var(--lmdo-muted)]">Tier</span>
                <select x-model="tier" class="lmdo-input w-full">
                    <option value="all">All tiers</option>
                    @foreach ($pricingTiers as $tierKey => $tierInfo)
                        <option value="{{ $tierKey }}">{{ $tierInfo['label'] }}</option>
                    @endforeach
                </select>
            </label>
        </div>

        <p class="mt-4 text-sm text-[var(--lmdo-muted)]">
            Showing <span x-text="filtered().length"></span> of {{ $modules->count() }} modules
        </p>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <template x-for="mod in filtered()" :key="mod.key">
                <article class="lmdo-card">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-display text-base font-semibold text-[var(--lmdo-ink)]" x-text="mod.name"></h3>
                        <span class="shrink-0 rounded-full bg-[var(--lmdo-teal)]/10 px-2 py-0.5 text-[10px] font-semibold uppercase text-[var(--lmdo-teal)]" x-text="mod.tier"></span>
                    </div>
                    <p class="mt-2 text-sm text-[var(--lmdo-muted)]" x-text="mod.description"></p>
                    <p class="mt-3 font-mono text-xs text-[var(--lmdo-muted)]" x-text="mod.key"></p>
                </article>
            </template>
        </div>
    </div>
@endsection
