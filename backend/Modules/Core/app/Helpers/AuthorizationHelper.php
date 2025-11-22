<?php

use Illuminate\Support\Facades\Auth;

/**
 * Authorization Helper Functions
 *
 * Global helper functions for role and permission checking
 */

/**
 * Check if authenticated user has a specific role
 */
if (!function_exists('hasRole')) {
    function hasRole(string $roleSlug): bool
    {
        if (!Auth::check()) {
            return false;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->hasRole($roleSlug);
    }
}

/**
 * Check if authenticated user has any of the given roles
 */
if (!function_exists('hasAnyRole')) {
    function hasAnyRole(array $roleSlugs): bool
    {
        if (!Auth::check()) {
            return false;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->hasAnyRole($roleSlugs);
    }
}

/**
 * Check if authenticated user has all of the given roles
 */
if (!function_exists('hasAllRoles')) {
    function hasAllRoles(array $roleSlugs): bool
    {
        if (!Auth::check()) {
            return false;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->hasAllRoles($roleSlugs);
    }
}

/**
 * Check if authenticated user has a specific permission
 */
if (!function_exists('hasPermission')) {
    function hasPermission(string $permissionSlug): bool
    {
        if (!Auth::check()) {
            return false;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->hasPermission($permissionSlug);
    }
}

/**
 * Check if authenticated user has any of the given permissions
 */
if (!function_exists('hasAnyPermission')) {
    function hasAnyPermission(array $permissionSlugs): bool
    {
        if (!Auth::check()) {
            return false;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->hasAnyPermission($permissionSlugs);
    }
}

/**
 * Check if authenticated user has all of the given permissions
 */
if (!function_exists('hasAllPermissions')) {
    function hasAllPermissions(array $permissionSlugs): bool
    {
        if (!Auth::check()) {
            return false;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->hasAllPermissions($permissionSlugs);
    }
}

/**
 * Get all permissions for authenticated user
 */
if (!function_exists('userPermissions')) {
    function userPermissions(): array
    {
        if (!Auth::check()) {
            return [];
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->getAllPermissions();
    }
}

/**
 * Get all roles for authenticated user
 */
if (!function_exists('userRoles')) {
    function userRoles(): array
    {
        if (!Auth::check()) {
            return [];
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->getRoleSlugs();
    }
}
