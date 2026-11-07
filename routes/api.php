<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\ShipmentsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('/health', fn () => response()->json([
        'ok' => true,
        'service' => 'last-mile-delivery-optimizer',
        'version' => '1.0.0',
    ]));

    Route::middleware(['auth:sanctum', 'tenant'])->group(function (): void {
        Route::middleware('module:orders')->group(function (): void {
            Route::get('/shipments', [ShipmentsController::class, 'index']);
            Route::post('/shipments', [ShipmentsController::class, 'store']);
            Route::get('/shipments/{shipment}', [ShipmentsController::class, 'show']);
        });
    });
});
