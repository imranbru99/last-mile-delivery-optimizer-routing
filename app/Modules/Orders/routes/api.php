<?php

declare(strict_types=1);

/*
| Module-scoped API aliases live under /api/v1/orders/*.
| Canonical public API remains in routes/api.php.
*/

use App\Http\Controllers\Api\V1\ShipmentsController;
use Illuminate\Support\Facades\Route;

Route::get('/shipments', [ShipmentsController::class, 'index'])->name('shipments.index');
Route::post('/shipments', [ShipmentsController::class, 'store'])->name('shipments.store');
Route::get('/shipments/{shipment}', [ShipmentsController::class, 'show'])->name('shipments.show');
