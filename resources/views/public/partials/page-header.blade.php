<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
    <p class="text-xs font-semibold uppercase tracking-widest text-[var(--lmdo-teal)]">{{ $eyebrow ?? 'Platform' }}</p>
    <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-[var(--lmdo-ink)] sm:text-4xl lg:text-5xl">{{ $title }}</h1>
    @isset($subtitle)
        <p class="mt-4 max-w-2xl text-base text-[var(--lmdo-muted)] sm:text-lg">{{ $subtitle }}</p>
    @endisset
</div>
