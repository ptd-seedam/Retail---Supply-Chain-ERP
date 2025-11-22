<?php

use Illuminate\Support\Facades\Route;
use Modules\Ecommerce\Http\Controllers\OrderController;
use Modules\Ecommerce\Http\Controllers\OrderItemController;
// use Modules\Ecommerce\Http\Controllers\ReportController;

Route::middleware(['auth:api'])->prefix('v1')->group(function () {
    // Orders
    Route::group(['middleware' => 'permission:view-ecommerce'], function () {
        Route::get('orders', [OrderController::class, 'index'])->name('order.index');
        Route::get('orders/filter/pending', [OrderController::class, 'getPending'])->name('order.pending');
        Route::get('orders/status/{status}', [OrderController::class, 'getByStatus'])->name('order.status');
        Route::get('orders/customer/{customerId}', [OrderController::class, 'getByCustomer'])->name('order.customer');
        Route::get('orders/{id}/calculate-total', [OrderController::class, 'calculateTotal'])->name('order.calculate_total');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('order.show');
    });
    Route::post('orders', [OrderController::class, 'store'])->name('order.store')->middleware('permission:create-ecommerce');
    Route::put('orders/{order}', [OrderController::class, 'update'])->name('order.update')->middleware('permission:edit-ecommerce');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('order.destroy')->middleware('permission:delete-ecommerce');
    Route::post('orders/{id}/complete', [OrderController::class, 'complete'])->name('order.complete')->middleware('permission:edit-ecommerce');
    Route::post('orders/{id}/cancel', [OrderController::class, 'cancel'])->name('order.cancel')->middleware('permission:edit-ecommerce');

    // Order Items (nested resource)
    Route::group(['middleware' => 'permission:view-ecommerce'], function () {
        Route::get('orders/{order}/items', [OrderItemController::class, 'index'])->name('order.items.index');
        Route::get('items/{item}', [OrderItemController::class, 'show'])->name('order.items.show');
    });
    Route::post('orders/{order}/items', [OrderItemController::class, 'store'])->name('order.items.store')->middleware('permission:create-ecommerce');
    Route::put('items/{item}', [OrderItemController::class, 'update'])->name('order.items.update')->middleware('permission:edit-ecommerce');
    Route::delete('items/{item}', [OrderItemController::class, 'destroy'])->name('order.items.destroy')->middleware('permission:delete-ecommerce');

    // Reports (temporarily disabled - will be fixed in next iteration)
    // Route::prefix('reports')->name('reports.')->group(function () {
    //     Route::get('/', [ReportController::class, 'report'])->name('index');
    //     Route::get('summary', [ReportController::class, 'summaryReport'])->name('summary');
    //     Route::get('detailed', [ReportController::class, 'detailedReport'])->name('detailed');
    //     Route::get('full', [ReportController::class, 'fullReport'])->name('full');
    // });
});
