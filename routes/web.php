<?php

declare(strict_types=1);

use App\Http\Controllers\Finance\InvoicePdfDownloadController;
use App\Http\Controllers\PublicSite\ContentController;
use App\Http\Controllers\PublicSite\DemoController;
use App\Http\Controllers\PublicSite\FeaturesController;
use App\Http\Controllers\PublicSite\HomeController;
use App\Http\Controllers\PublicSite\ModulesController;
use App\Http\Controllers\PublicSite\PricingController;
use App\Http\Controllers\PublicSite\SolutionsController;
use App\Http\Controllers\PublicSite\TrackController;
use Illuminate\Support\Facades\Route;

Route::name('public.')->group(function (): void {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/features', [FeaturesController::class, 'index'])->name('features.index');
    Route::get('/features/category/{category}', [FeaturesController::class, 'category'])->name('features.category');

    Route::get('/modules', [ModulesController::class, 'index'])->name('modules.index');

    Route::get('/solutions', [SolutionsController::class, 'index'])->name('solutions.index');
    Route::get('/solutions/{industry}', [SolutionsController::class, 'show'])->name('solutions.show');

    Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

    Route::get('/track', [TrackController::class, 'index'])->name('track');
    Route::get('/track/pod/{shipment}', [TrackController::class, 'pod'])
        ->middleware('signed')
        ->name('track.pod');
    Route::get('/track/{business:slug}', [TrackController::class, 'index'])->name('track.business');

    Route::get('/demo/route-optimizer', [DemoController::class, 'routeOptimizer'])->name('demo.route-optimizer');

    Route::get('/contact', [ContentController::class, 'contact'])->name('contact');
    Route::get('/about', [ContentController::class, 'about'])->name('about');
    Route::get('/faq', [ContentController::class, 'faq'])->name('faq');
    Route::get('/privacy', [ContentController::class, 'privacy'])->name('privacy');
    Route::get('/terms', [ContentController::class, 'terms'])->name('terms');
    Route::get('/security', [ContentController::class, 'security'])->name('security');
    Route::get('/blog', [ContentController::class, 'blog'])->name('blog');
    Route::get('/help', [ContentController::class, 'help'])->name('help');
});

Route::middleware(['auth'])->group(function (): void {
    Route::get('/finance/invoices/{invoice}/pdf/{locale}', InvoicePdfDownloadController::class)
        ->whereIn('locale', ['en', 'bn', 'ar'])
        ->name('lmdo.invoices.pdf');
});
