<?php

use Illuminate\Support\Facades\Route;
use Modules\CRM\Http\Controllers\CustomerController;
// use Modules\CRM\Http\Controllers\ReportController;

Route::middleware(['auth:api'])->prefix('v1')->group(function () {
    // Customers
    Route::group(['middleware' => 'permission:view-crm'], function () {
        Route::get('customers', [CustomerController::class, 'index'])->name('customer.index');
        Route::get('customers/filter/active', [CustomerController::class, 'getActive'])->name('customer.active');
        Route::get('customers/filter/suspended', [CustomerController::class, 'getSuspended'])->name('customer.suspended');
        Route::get('customers/filter/high-value', [CustomerController::class, 'getHighValue'])->name('customer.high_value');
        Route::get('customers/group/{groupId}', [CustomerController::class, 'getByGroup'])->name('customer.group');
        Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customer.show');
    });
    Route::post('customers', [CustomerController::class, 'store'])->name('customer.store')->middleware('permission:create-crm');
    Route::put('customers/{customer}', [CustomerController::class, 'update'])->name('customer.update')->middleware('permission:edit-crm');
    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('customer.destroy')->middleware('permission:delete-crm');

    // Reports (temporarily disabled - will be fixed in next iteration)
    // Route::prefix('reports')->name('reports.')->group(function () {
    //     Route::get('/', [ReportController::class, 'report'])->name('index');
    //     Route::get('summary', [ReportController::class, 'summaryReport'])->name('summary');
    //     Route::get('detailed', [ReportController::class, 'detailedReport'])->name('detailed');
    //     Route::get('full', [ReportController::class, 'fullReport'])->name('full');
    // });
});
