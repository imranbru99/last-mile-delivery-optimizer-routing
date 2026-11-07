<?php

declare(strict_types=1);

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicSite\Concerns\InteractsWithModuleCatalog;
use Illuminate\View\View;

final class PricingController extends Controller
{
    use InteractsWithModuleCatalog;

    public function index(): View
    {
        $tiers = config('modules.pricing_tiers', []);

        $byTier = $this->moduleRegistry()
            ->groupBy('tier')
            ->map(fn ($items) => $items->count());

        return view('public.pricing', [
            'tiers' => $tiers,
            'byTier' => $byTier,
            'moduleCount' => $this->moduleRegistry()->count(),
        ]);
    }
}
