<?php

use Illuminate\Support\Facades\Route;
use Modules\Ecommerce\Http\Controllers\OrderController;
use Modules\Ecommerce\Http\Controllers\OrderItemController;

Route::middleware(['auth:api'])->prefix('v1')->group(function () {
    Route::apiResource('orders', OrderController::class)->names('order');
    Route::post('orders/{id}/complete', [OrderController::class, 'complete'])->name('order.complete');
    Route::post('orders/{id}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
    Route::get('orders/{id}/calculate-total', [OrderController::class, 'calculateTotal'])->name('order.calculate_total');
    Route::get('orders/status/{status}', [OrderController::class, 'getByStatus'])->name('order.status');
    Route::get('orders/customer/{customerId}', [OrderController::class, 'getByCustomer'])->name('order.customer');
    Route::get('orders-pending', [OrderController::class, 'getPending'])->name('order.pending');

    Route::apiResource('orders.items', OrderItemController::class)->names('order.items')->shallow();
});
