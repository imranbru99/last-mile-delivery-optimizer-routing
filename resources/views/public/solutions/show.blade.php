@extends('layouts.public')

@section('title', $name)

@section('content')
    @include('public.partials.page-header', [
        'eyebrow' => 'Preset · '.$industry,
        'title' => $name,
        'subtitle' => 'Modules enabled when ModuleManager::applyPreset(\''.$industry.'\') runs for a new business.',
    ])

    <div class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
        <p class="mb-6">
            <a href="{{ route('public.solutions.index') }}" class="text-sm text-[var(--lmdo-teal)] hover:underline">&larr; All solutions</a>
        </p>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($modules as $module)
                @include('public.partials.module-card', ['module' => $module])
            @endforeach
        </div>
    </div>
@endsection
