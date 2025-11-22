<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * RoleController
 *
 * API endpoints for managing roles and permissions
 */
class RoleController extends BaseController
{
    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Get all roles
     * GET /api/v1/roles
     */
    public function index(): JsonResponse
    {
        try {
            $roles = $this->roleService->getAllRoles();

            return $this->successResponse(
                $roles,
                'Roles retrieved successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve roles: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get active roles
     * GET /api/v1/roles/active
     */
    public function getActiveRoles(): JsonResponse
    {
        try {
            $roles = $this->roleService->getActiveRoles();

            return $this->successResponse(
                $roles,
                'Active roles retrieved successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve active roles: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get role by ID
     * GET /api/v1/roles/{id}
     */
    public function show(int $id): JsonResponse
    {
        try {
            $role = $this->roleService->getRoleById($id);

            return $this->successResponse(
                $role,
                'Role retrieved successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Role not found: ' . $e->getMessage(),
                404
            );
        }
    }

    /**
     * Create a new role
     * POST /api/v1/roles
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100|unique:roles',
                'slug' => 'nullable|string|max:100|unique:roles',
                'description' => 'nullable|string|max:1000',
                'priority' => 'nullable|integer|min:0|max:1000',
                'is_active' => 'nullable|boolean',
                'permissions' => 'nullable|array',
                'permissions.*' => 'integer|exists:permissions,id',
            ]);

            $role = $this->roleService->createRole($validated);

            return $this->successResponse(
                $role->load('permissions'),
                'Role created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to create role: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Update a role
     * PUT /api/v1/roles/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $role = Role::findOrFail($id);

            $validated = $request->validate([
                'name' => 'nullable|string|max:100|unique:roles,name,' . $id,
                'slug' => 'nullable|string|max:100|unique:roles,slug,' . $id,
                'description' => 'nullable|string|max:1000',
                'priority' => 'nullable|integer|min:0|max:1000',
                'is_active' => 'nullable|boolean',
                'permissions' => 'nullable|array',
                'permissions.*' => 'integer|exists:permissions,id',
            ]);

            $role = $this->roleService->updateRole($role, $validated);

            return $this->successResponse(
                $role->load('permissions'),
                'Role updated successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update role: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Delete a role
     * DELETE /api/v1/roles/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $role = Role::findOrFail($id);

            // Check if role is being used by users
            if ($role->users()->count() > 0) {
                return $this->errorResponse(
                    'Cannot delete role. It is assigned to ' . $role->users()->count() . ' user(s)',
                    400
                );
            }

            $this->roleService->deleteRole($role);

            return $this->successResponse(
                null,
                'Role deleted successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to delete role: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get all permissions
     * GET /api/v1/permissions
     */
    public function getPermissions(): JsonResponse
    {
        try {
            $permissions = $this->roleService->getAllPermissions();

            return $this->successResponse(
                $permissions,
                'Permissions retrieved successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve permissions: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get permissions grouped by module
     * GET /api/v1/permissions/grouped
     */
    public function getPermissionsGrouped(): JsonResponse
    {
        try {
            $permissions = $this->roleService->getPermissionsGroupedByModule();

            return $this->successResponse(
                $permissions,
                'Permissions retrieved successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve permissions: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Create a new permission
     * POST /api/v1/permissions
     */
    public function createPermission(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100|unique:permissions',
                'slug' => 'nullable|string|max:100|unique:permissions',
                'description' => 'nullable|string|max:1000',
                'module' => 'nullable|string|max:100',
                'action' => 'nullable|string|max:50',
            ]);

            $permission = $this->roleService->createPermission($validated);

            return $this->successResponse(
                $permission,
                'Permission created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to create permission: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Assign roles to user
     * POST /api/v1/roles/assign-to-user/{userId}
     */
    public function assignRolesToUser(Request $request, int $userId): JsonResponse
    {
        try {
            $user = User::findOrFail($userId);

            $validated = $request->validate([
                'role_ids' => 'required|array',
                'role_ids.*' => 'integer|exists:roles,id',
            ]);

            $this->roleService->assignRolesToUser($user, $validated['role_ids']);

            return $this->successResponse(
                $user->load('roles'),
                'Roles assigned to user successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to assign roles: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get user's roles and permissions
     * GET /api/v1/users/{userId}/roles-permissions
     */
    public function getUserRolesPermissions(int $userId): JsonResponse
    {
        try {
            $user = User::findOrFail($userId);

            $data = [
                'roles' => $user->getRoleSlugs(),
                'permissions' => $user->getAllPermissions(),
            ];

            return $this->successResponse(
                $data,
                'User roles and permissions retrieved successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve user roles and permissions: ' . $e->getMessage(),
                500
            );
        }
    }
}
