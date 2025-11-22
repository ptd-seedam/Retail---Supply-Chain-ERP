<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\WarehouseController;
use Modules\Core\Http\Controllers\ProductController;
use Modules\Core\Http\Controllers\CategoryController;
// use Modules\Core\Http\Controllers\ReportController;

Route::middleware(['auth:api'])->prefix('v1')->group(function () {
    // Warehouses
    Route::group(['middleware' => 'permission:view-core'], function () {
        Route::get('warehouses', [WarehouseController::class, 'index'])->name('warehouse.index');
        Route::get('warehouses/filter/active', [WarehouseController::class, 'getActive'])->name('warehouse.active');
        Route::get('warehouses/{warehouse}', [WarehouseController::class, 'show'])->name('warehouse.show');
    });
    Route::post('warehouses', [WarehouseController::class, 'store'])->name('warehouse.store')->middleware('permission:create-core');
    Route::put('warehouses/{warehouse}', [WarehouseController::class, 'update'])->name('warehouse.update')->middleware('permission:edit-core');
    Route::delete('warehouses/{warehouse}', [WarehouseController::class, 'destroy'])->name('warehouse.destroy')->middleware('permission:delete-core');

    // Products
    Route::group(['middleware' => 'permission:view-core'], function () {
        Route::get('products', [ProductController::class, 'index'])->name('product.index');
        Route::get('products/filter/active', [ProductController::class, 'getActive'])->name('product.active');
        Route::get('products/category/{categoryId}', [ProductController::class, 'getByCategory'])->name('product.category');
        Route::get('products/{product}', [ProductController::class, 'show'])->name('product.show');
    });
    Route::post('products', [ProductController::class, 'store'])->name('product.store')->middleware('permission:create-core');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('product.update')->middleware('permission:edit-core');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('product.destroy')->middleware('permission:delete-core');

    // Categories
    Route::group(['middleware' => 'permission:view-core'], function () {
        Route::get('categories', [CategoryController::class, 'index'])->name('category.index');
        Route::get('categories/filter/tree', [CategoryController::class, 'getCategoryTree'])->name('category.tree');
        Route::get('categories/filter/active', [CategoryController::class, 'getActive'])->name('category.active');
        Route::get('categories/{parentId}/children', [CategoryController::class, 'getChildren'])->name('category.children');
        Route::get('categories/{category}', [CategoryController::class, 'show'])->name('category.show');
    });
    Route::post('categories', [CategoryController::class, 'store'])->name('category.store')->middleware('permission:create-core');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('category.update')->middleware('permission:edit-core');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('category.destroy')->middleware('permission:delete-core');

    // Reports (temporarily disabled - will be fixed in next iteration)
    // Route::prefix('reports')->name('reports.')->group(function () {
    //     Route::get('/', [ReportController::class, 'report'])->name('index');
    //     Route::get('summary', [ReportController::class, 'summaryReport'])->name('summary');
    //     Route::get('detailed', [ReportController::class, 'detailedReport'])->name('detailed');
    //     Route::get('full', [ReportController::class, 'fullReport'])->name('full');
    // });
});
