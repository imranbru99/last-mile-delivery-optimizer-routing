<?php

declare(strict_types=1);

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

final class DemoController extends Controller
{
    public function routeOptimizer(): View
    {
        return view('public.demo.route-optimizer');
    }
}
