<?php

use Illuminate\Support\Facades\Route;
use Modules\CRM\Http\Controllers\CustomerController;

Route::middleware(['auth:api'])->prefix('v1')->group(function () {
    Route::apiResource('customers', CustomerController::class)->names('customer');
    Route::get('customers/group/{groupId}', [CustomerController::class, 'getByGroup'])->name('customer.group');
    Route::get('customers-active', [CustomerController::class, 'getActive'])->name('customer.active');
    Route::get('customers-suspended', [CustomerController::class, 'getSuspended'])->name('customer.suspended');
    Route::get('customers-high-value', [CustomerController::class, 'getHighValue'])->name('customer.high_value');
});
