<?php

use Illuminate\Support\Facades\Route;

/**
 * API Routes
 *
 * All API routes are prefixed with /api (added automatically by Laravel)
 * Add additional prefixes as needed
 */

Route::prefix('v1')->group(function () {
    // Role and Permission Management Routes
    require __DIR__ . '/roles.php';

    // Module Routes (Auth, Core, HRM, CRM, Ecommerce, CMS)
    // These are auto-loaded from module route files
});
