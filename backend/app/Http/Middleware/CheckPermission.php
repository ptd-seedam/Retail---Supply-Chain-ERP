<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckPermission Middleware
 *
 * Checks if the authenticated user has the required permission
 * Usage in routes: middleware('permission:view-users,create-users')
 */
class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        /** @var \App\Models\User $user */

        /**
         * @phpstan-ignore-next-line
         */
        if (!$user->hasAnyPermission($permissions)) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: You do not have the required permission',
                'required_permissions' => $permissions,
            ], 403);
        }

        return $next($request);
    }
}
