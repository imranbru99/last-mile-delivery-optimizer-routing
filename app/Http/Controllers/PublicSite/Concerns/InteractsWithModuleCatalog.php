<?php

declare(strict_types=1);

namespace App\Http\Controllers\PublicSite\Concerns;

use Illuminate\Support\Collection;

trait InteractsWithModuleCatalog
{
    protected function moduleRegistry(): Collection
    {
        return collect(config('modules.registry', []));
    }

    /**
     * @return array<string, array{label: string, letter: string}>
     */
    protected function moduleCategories(): array
    {
        return config('modules.categories', []);
    }

    /**
     * @return array<string, array{name: string, modules: list<string>}>
     */
    protected function industryPresets(): array
    {
        return config('modules.presets', []);
    }

    protected function categoryLabel(string $category): string
    {
        return $this->moduleCategories()[$category]['label'] ?? ucfirst($category);
    }

    protected function categoryLetter(string $category): string
    {
        return $this->moduleCategories()[$category]['letter'] ?? '';
    }
}
