<?php

declare(strict_types=1);

use App\Models\Business;
use App\Support\Modules\ModuleManager;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('syncs the module catalog from config', function (): void {
    $count = app(ModuleManager::class)->syncCatalogToDatabase();

    expect($count)->toBeGreaterThan(70)
        ->and(\App\Models\Module::query()->count())->toBe($count);
});

it('enforces dependency rules when enabling modules', function (): void {
    $business = Business::factory()->create(['business_type' => 'custom']);
    $manager = app(ModuleManager::class);

    // cold_chain depends on assets; assets is off by default.
    expect($manager->isEnabled('assets', $business))->toBeFalse()
        ->and(fn () => $manager->enable('cold_chain', $business))
        ->toThrow(\RuntimeException::class);
});

it('prevents disabling a module required by another enabled module', function (): void {
    $business = Business::factory()->create();
    $manager = app(ModuleManager::class);

    $manager->applyPreset('custom', $business);

    expect($manager->isEnabled('orders', $business))->toBeTrue()
        ->and(fn () => $manager->disable('orders', $business))
        ->toThrow(RuntimeException::class);
});

it('applies presets with visibly different module sets', function (): void {
    $courier = Business::factory()->create(['name' => 'Courier Co']);
    $fleet = Business::factory()->create(['name' => 'Fleet Co']);
    $manager = app(ModuleManager::class);

    $manager->applyPreset('courier', $courier);
    $manager->applyPreset('chauffeur', $fleet);

    $courierEnabled = collect($manager->enabledMap($courier))->filter()->keys()->sort()->values();
    $fleetEnabled = collect($manager->enabledMap($fleet))->filter()->keys()->sort()->values();

    expect($courierEnabled)->not->toEqual($fleetEnabled)
        ->and($manager->isEnabled('merchant_portal', $courier))->toBeTrue()
        ->and($manager->isEnabled('merchant_portal', $fleet))->toBeFalse()
        ->and($manager->isEnabled('telematics', $fleet))->toBeTrue();
});
