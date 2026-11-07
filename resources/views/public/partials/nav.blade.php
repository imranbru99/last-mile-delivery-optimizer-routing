<header class="sticky top-0 z-50 border-b border-[var(--lmdo-border)]/60 bg-[var(--lmdo-surface)]/80 backdrop-blur-xl">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
        <a href="{{ route('public.home') }}" class="group flex items-center gap-2">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[var(--lmdo-teal)] text-sm font-bold text-white shadow-lg shadow-[var(--lmdo-teal)]/25">LM</span>
            <span class="font-display text-sm font-semibold tracking-tight text-[var(--lmdo-ink)] sm:text-base">
                Last-Mile Optimizer
            </span>
        </a>

        <nav class="hidden items-center gap-1 text-sm font-medium lg:flex" aria-label="Primary">
            <a href="{{ route('public.features.index') }}" class="lmdo-nav-link">Features</a>
            <a href="{{ route('public.modules.index') }}" class="lmdo-nav-link">Modules</a>
            <a href="{{ route('public.solutions.index') }}" class="lmdo-nav-link">Solutions</a>
            <a href="{{ route('public.pricing') }}" class="lmdo-nav-link">Pricing</a>
            <a href="{{ route('public.demo.route-optimizer') }}" class="lmdo-nav-link">Demo</a>
        </nav>

        <div class="flex items-center gap-2 sm:gap-3">
            <div x-data="{ open: false }" class="relative hidden sm:block">
                <button type="button" @click="open = !open" class="lmdo-btn-ghost text-xs" aria-haspopup="listbox">
                    <span x-text="lang === 'en' ? 'EN' : 'BN'"></span>
                    <svg class="h-3 w-3 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" @click.outside="open = false" x-cloak
                     class="absolute right-0 mt-1 min-w-[8rem] rounded-lg border border-[var(--lmdo-border)] bg-[var(--lmdo-surface)] py-1 shadow-xl">
                    <button type="button" @click="lang = 'en'; open = false" class="block w-full px-3 py-1.5 text-left text-xs hover:bg-[var(--lmdo-muted)]/40">English</button>
                    <button type="button" @click="lang = 'bn'; open = false" class="block w-full px-3 py-1.5 text-left text-xs hover:bg-[var(--lmdo-muted)]/40">বাংলা (stub)</button>
                </div>
            </div>

            <button type="button" @click="toggleDark()" class="lmdo-btn-ghost" aria-label="Toggle dark mode">
                <svg x-show="!dark" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                <svg x-show="dark" x-cloak class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </button>

            <a href="{{ url('/business') }}" class="hidden sm:inline-flex lmdo-btn-primary text-xs sm:text-sm">Sign in</a>
        </div>
    </div>
</header>
