<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;

/**
 * Role Management Routes
 * All routes require authentication (middleware: auth:api)
 */

Route::middleware('auth:api')->group(function () {
    // Role CRUD
    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:view-roles')->name('roles.index');
    Route::get('/roles/active', [RoleController::class, 'getActiveRoles'])->middleware('permission:view-roles')->name('roles.active');
    Route::get('/roles/{id}', [RoleController::class, 'show'])->middleware('permission:view-roles')->name('roles.show');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('role:admin')->name('roles.store');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->middleware('role:admin')->name('roles.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->middleware('role:admin')->name('roles.destroy');

    // Permission Management
    Route::get('/permissions', [RoleController::class, 'getPermissions'])->middleware('permission:view-permissions')->name('permissions.index');
    Route::get('/permissions/grouped', [RoleController::class, 'getPermissionsGrouped'])->middleware('permission:view-permissions')->name('permissions.grouped');
    Route::post('/permissions', [RoleController::class, 'createPermission'])->middleware('role:admin')->name('permissions.store');

    // User Role Assignment
    Route::post('/roles/assign-to-user/{userId}', [RoleController::class, 'assignRolesToUser'])
        ->middleware('role:admin')
        ->name('roles.assign-to-user');

    // Get user's roles and permissions
    Route::get('/users/{userId}/roles-permissions', [RoleController::class, 'getUserRolesPermissions'])->middleware('permission:view-users|view-roles|view-permissions')->name('users.roles-permissions');
});
