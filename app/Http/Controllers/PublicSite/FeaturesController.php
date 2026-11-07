<?php

declare(strict_types=1);

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicSite\Concerns\InteractsWithModuleCatalog;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class FeaturesController extends Controller
{
    use InteractsWithModuleCatalog;

    public function index(): View
    {
        $grouped = $this->moduleRegistry()
            ->groupBy('category')
            ->map(fn ($items, $cat) => [
                'key' => $cat,
                'label' => $this->categoryLabel($cat),
                'letter' => $this->categoryLetter($cat),
                'modules' => $items->values(),
            ])
            ->sortBy(fn ($g) => $g['letter'] === '—' ? '0' : $g['letter']);

        return view('public.features.index', [
            'groups' => $grouped,
        ]);
    }

    public function category(string $category): View
    {
        if (! isset($this->moduleCategories()[$category])) {
            throw new NotFoundHttpException;
        }

        $modules = $this->moduleRegistry()
            ->where('category', $category)
            ->values();

        return view('public.features.category', [
            'category' => $category,
            'label' => $this->categoryLabel($category),
            'letter' => $this->categoryLetter($category),
            'modules' => $modules,
        ]);
    }
}
