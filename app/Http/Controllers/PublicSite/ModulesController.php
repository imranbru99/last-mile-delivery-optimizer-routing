<?php

declare(strict_types=1);

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicSite\Concerns\InteractsWithModuleCatalog;
use Illuminate\View\View;

final class ModulesController extends Controller
{
    use InteractsWithModuleCatalog;

    public function index(): View
    {
        return view('public.modules.index', [
            'modules' => $this->moduleRegistry()->values(),
            'categories' => $this->moduleCategories(),
            'pricingTiers' => config('modules.pricing_tiers', []),
        ]);
    }
}
