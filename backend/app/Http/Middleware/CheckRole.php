<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckRole Middleware
 *
 * Checks if the authenticated user has the required role
 * Usage in routes: middleware('role:admin,manager')
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
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
        if (!$user->hasAnyRole($roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: You do not have the required role',
                'required_roles' => $roles,
            ], 403);
        }

        return $next($request);
    }
}
