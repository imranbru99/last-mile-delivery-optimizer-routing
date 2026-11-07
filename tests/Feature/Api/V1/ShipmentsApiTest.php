<?php

declare(strict_types=1);

use App\Models\Business;
use App\Models\Shipment;
use App\Models\User;
use App\Support\Modules\ModuleManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('returns api health without authentication', function (): void {
    $this->getJson('/api/v1/health')
        ->assertOk()
        ->assertJsonPath('ok', true)
        ->assertJsonPath('service', 'last-mile-delivery-optimizer');
});

it('lists only shipments for the authenticated users tenant', function (): void {
    app(ModuleManager::class)->syncCatalogToDatabase();

    $businessA = Business::factory()->create(['name' => 'Tenant A']);
    $businessB = Business::factory()->create(['name' => 'Tenant B']);

    app(ModuleManager::class)->enable('orders', $businessA);
    app(ModuleManager::class)->enable('orders', $businessB);

    $userA = User::factory()->forBusiness($businessA)->create();
    $userB = User::factory()->forBusiness($businessB)->create();

    Shipment::factory()->forBusiness($businessA)->count(2)->create();
    Shipment::factory()->forBusiness($businessB)->count(3)->create();

    Sanctum::actingAs($userA);

    $this->getJson('/api/v1/shipments')
        ->assertOk()
        ->assertJsonCount(2, 'data');

    Sanctum::actingAs($userB);

    $this->getJson('/api/v1/shipments')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});
