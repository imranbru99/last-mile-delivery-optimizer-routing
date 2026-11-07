<?php

declare(strict_types=1);

use App\Models\Business;
use App\Models\Shipment;
use App\Support\Tenancy\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('isolates shipments by tenant when tenant context is set', function (): void {
    $businessA = Business::factory()->create(['name' => 'Tenant A']);
    $businessB = Business::factory()->create(['name' => 'Tenant B']);

    Shipment::factory()->forBusiness($businessA)->count(3)->create();
    Shipment::factory()->forBusiness($businessB)->count(2)->create();

    /** @var TenantContext $context */
    $context = app(TenantContext::class);
    $context->set($businessA);

    $visible = Shipment::query()->pluck('business_id')->unique()->values()->all();

    expect($visible)->toBe([(int) $businessA->id])
        ->and(Shipment::query()->count())->toBe(3);

    $context->set($businessB);

    expect(Shipment::query()->count())->toBe(2)
        ->and(Shipment::query()->where('business_id', $businessA->id)->count())->toBe(0);
});
