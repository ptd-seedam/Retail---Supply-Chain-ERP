<?php

use Illuminate\Support\Facades\Route;
use Modules\CMS\Http\Controllers\CMSController;
// use Modules\CMS\Http\Controllers\ReportController;

Route::middleware(['auth:api'])->prefix('v1')->group(function () {
    // CMS
    Route::group(['middleware' => 'permission:view-cms'], function () {
        Route::get('cms', [CMSController::class, 'index'])->name('cms.index');
        Route::get('cms/{cm}', [CMSController::class, 'show'])->name('cms.show');
    });
    Route::post('cms', [CMSController::class, 'store'])->name('cms.store')->middleware('permission:create-cms');
    Route::put('cms/{cm}', [CMSController::class, 'update'])->name('cms.update')->middleware('permission:edit-cms');
    Route::delete('cms/{cm}', [CMSController::class, 'destroy'])->name('cms.destroy')->middleware('permission:delete-cms');

    // Reports (temporarily disabled - will be fixed in next iteration)
    // Route::prefix('reports')->name('reports.')->group(function () {
    //     Route::get('/', [ReportController::class, 'report'])->name('index');
    //     Route::get('summary', [ReportController::class, 'summaryReport'])->name('summary');
    //     Route::get('detailed', [ReportController::class, 'detailedReport'])->name('detailed');
    //     Route::get('full', [ReportController::class, 'fullReport'])->name('full');
    // });
});
