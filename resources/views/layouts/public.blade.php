<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
          dark: localStorage.getItem('lmdo-dark') === '1' || (!localStorage.getItem('lmdo-dark') && window.matchMedia('(prefers-color-scheme: dark)').matches),
          lang: 'en',
          toggleDark() { this.dark = !this.dark; localStorage.setItem('lmdo-dark', this.dark ? '1' : '0'); }
      }"
      x-bind:class="{ 'dark': dark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Last-Mile Delivery Optimizer') — Route Planning</title>
    <meta name="description" content="@yield('meta_description', 'Modular last-mile delivery platform with VRP route optimization, live tracking, and role-based operations panels.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Syne:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen font-sans antialiased text-[var(--lmdo-ink)] bg-[var(--lmdo-bg)] selection:bg-[var(--lmdo-teal)] selection:text-white">
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="lmdo-gradient absolute inset-0 opacity-100 dark:opacity-90"></div>
        <div class="absolute -top-32 right-0 h-96 w-96 rounded-full bg-[var(--lmdo-teal)]/10 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/4 h-80 w-80 rounded-full bg-[var(--lmdo-ink)]/5 blur-3xl dark:bg-[var(--lmdo-teal)]/5"></div>
    </div>

    @include('public.partials.nav')

    <main>
        @yield('content')
    </main>

    @include('public.partials.footer')

    @stack('scripts')
</body>
</html>
