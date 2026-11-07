@props(['module'])

<article {{ $attributes->merge(['class' => 'lmdo-card flex flex-col']) }}>
    <div class="flex items-start justify-between gap-2">
        <h3 class="font-display text-base font-semibold text-[var(--lmdo-ink)]">{{ $module['name'] }}</h3>
        <span class="shrink-0 rounded-full bg-[var(--lmdo-teal)]/10 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-[var(--lmdo-teal)]">{{ $module['tier'] }}</span>
    </div>
    <p class="mt-2 flex-1 text-sm text-[var(--lmdo-muted)]">{{ $module['description'] }}</p>
    <dl class="mt-4 space-y-1 border-t border-[var(--lmdo-border)]/50 pt-3 text-xs">
        <div class="flex gap-2">
            <dt class="font-medium text-[var(--lmdo-ink)]">Key</dt>
            <dd class="font-mono text-[var(--lmdo-muted)]">{{ $module['key'] }}</dd>
        </div>
        @if (! empty($module['dependencies']))
            <div>
                <dt class="font-medium text-[var(--lmdo-ink)]">Dependencies</dt>
                <dd class="mt-0.5 font-mono text-[var(--lmdo-muted)]">{{ implode(', ', $module['dependencies']) }}</dd>
            </div>
        @endif
    </dl>
</article>
