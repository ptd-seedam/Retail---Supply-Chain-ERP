<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\WarehouseController;
use Modules\Core\Http\Controllers\ProductController;
use Modules\Core\Http\Controllers\CategoryController;

Route::middleware(['auth:api'])->prefix('v1')->group(function () {
    Route::apiResource('warehouses', WarehouseController::class)->names('warehouse');
    Route::get('warehouses-active', [WarehouseController::class, 'getActive'])->name('warehouse.active');

    Route::apiResource('products', ProductController::class)->names('product');
    Route::get('products/category/{categoryId}', [ProductController::class, 'getByCategory'])->name('product.category');
    Route::get('products-active', [ProductController::class, 'getActive'])->name('product.active');

    Route::apiResource('categories', CategoryController::class)->names('category');
    Route::get('categories/tree', [CategoryController::class, 'getCategoryTree'])->name('category.tree');
    Route::get('categories/{parentId}/children', [CategoryController::class, 'getChildren'])->name('category.children');
    Route::get('categories-active', [CategoryController::class, 'getActive'])->name('category.active');
});
