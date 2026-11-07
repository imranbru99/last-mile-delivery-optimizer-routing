<footer class="mt-20 border-t border-[var(--lmdo-border)]/60 bg-[var(--lmdo-surface)]/60">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <p class="font-display text-lg font-semibold text-[var(--lmdo-ink)]">Last-Mile Delivery Optimizer</p>
                <p class="mt-2 text-sm text-[var(--lmdo-muted)]">Route planning, dispatch, and live operations for modern delivery networks.</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-[var(--lmdo-teal)]">Product</p>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a href="{{ route('public.features.index') }}" class="lmdo-footer-link">Features</a></li>
                    <li><a href="{{ route('public.modules.index') }}" class="lmdo-footer-link">Modules</a></li>
                    <li><a href="{{ route('public.pricing') }}" class="lmdo-footer-link">Pricing</a></li>
                    <li><a href="{{ route('public.demo.route-optimizer') }}" class="lmdo-footer-link">Route demo</a></li>
                </ul>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-[var(--lmdo-teal)]">Company</p>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a href="{{ route('public.about') }}" class="lmdo-footer-link">About</a></li>
                    <li><a href="{{ route('public.blog') }}" class="lmdo-footer-link">Blog</a></li>
                    <li><a href="{{ route('public.contact') }}" class="lmdo-footer-link">Contact</a></li>
                    <li><a href="{{ route('public.security') }}" class="lmdo-footer-link">Security</a></li>
                </ul>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-[var(--lmdo-teal)]">Support</p>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a href="{{ route('public.help') }}" class="lmdo-footer-link">Help center</a></li>
                    <li><a href="{{ route('public.faq') }}" class="lmdo-footer-link">FAQ</a></li>
                    <li><a href="{{ route('public.privacy') }}" class="lmdo-footer-link">Privacy</a></li>
                    <li><a href="{{ route('public.terms') }}" class="lmdo-footer-link">Terms</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-10 flex flex-col gap-2 border-t border-[var(--lmdo-border)]/40 pt-8 text-xs text-[var(--lmdo-muted)] sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} <a href="https://imrandev.bd" class="text-[var(--lmdo-teal)] hover:underline">Imran Ahmed</a>. All rights reserved.</p>
            <p>Built with Laravel 13 · Filament 5 · OpenStreetMap / Leaflet</p>
        </div>
    </div>
</footer>
