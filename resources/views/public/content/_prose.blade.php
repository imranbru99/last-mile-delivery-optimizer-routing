@extends('layouts.public')

@section('content')
    @include('public.partials.page-header', [
        'eyebrow' => trim($__env->yieldContent('page_eyebrow', 'Resources')),
        'title' => trim($__env->yieldContent('page_title', 'Page')),
        'subtitle' => trim($__env->yieldContent('page_subtitle', '')) ?: null,
    ])
    <div class="mx-auto max-w-3xl px-4 pb-20 sm:px-6 lg:px-8">
        <div class="lmdo-prose">
            @yield('body')
        </div>
    </div>
@endsection
