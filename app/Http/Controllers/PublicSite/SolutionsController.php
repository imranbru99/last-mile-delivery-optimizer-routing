<?php

declare(strict_types=1);

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicSite\Concerns\InteractsWithModuleCatalog;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class SolutionsController extends Controller
{
    use InteractsWithModuleCatalog;

    public function index(): View
    {
        $presets = collect($this->industryPresets())
            ->map(fn (array $preset, string $key) => [
                'key' => $key,
                'name' => $preset['name'],
                'module_count' => count($preset['modules'] ?? []),
            ])
            ->values();

        return view('public.solutions.index', [
            'presets' => $presets,
        ]);
    }

    public function show(string $industry): View
    {
        $presets = $this->industryPresets();

        if (! isset($presets[$industry])) {
            throw new NotFoundHttpException;
        }

        $preset = $presets[$industry];
        $registry = $this->moduleRegistry()->keyBy('key');

        $modules = collect($preset['modules'] ?? [])
            ->map(fn (string $key) => $registry->get($key))
            ->filter()
            ->values();

        return view('public.solutions.show', [
            'industry' => $industry,
            'name' => $preset['name'],
            'modules' => $modules,
        ]);
    }
}
