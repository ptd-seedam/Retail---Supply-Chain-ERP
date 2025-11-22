<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * RoleService
 *
 * Service for managing roles and permissions
 */
class RoleService
{
    /**
     * Get all roles
     */
    public function getAllRoles(): Collection
    {
        return Role::with('permissions')->get();
    }

    /**
     * Get all active roles
     */
    public function getActiveRoles(): Collection
    {
        return Role::active()->with('permissions')->get();
    }

    /**
     * Get role by ID
     */
    public function getRoleById(int $roleId): ?Role
    {
        return Role::with('permissions', 'users')->findOrFail($roleId);
    }

    /**
     * Get role by slug
     */
    public function getRoleBySlug(string $slug): ?Role
    {
        return Role::where('slug', $slug)->with('permissions')->first();
    }

    /**
     * Create a new role
     */
    public function createRole(array $data): Role
    {
        $role = Role::create([
            'name' => $data['name'],
            'slug' => $data['slug'] ?? $this->generateSlug($data['name']),
            'description' => $data['description'] ?? null,
            'priority' => $data['priority'] ?? 0,
            'is_active' => $data['is_active'] ?? true,
        ]);

        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $this->assignPermissionsToRole($role, $data['permissions']);
        }

        return $role;
    }

    /**
     * Update a role
     */
    public function updateRole(Role $role, array $data): Role
    {
        $role->update([
            'name' => $data['name'] ?? $role->name,
            'slug' => $data['slug'] ?? $role->slug,
            'description' => $data['description'] ?? $role->description,
            'priority' => $data['priority'] ?? $role->priority,
            'is_active' => $data['is_active'] ?? $role->is_active,
        ]);

        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $this->syncPermissionsForRole($role, $data['permissions']);
        }

        return $role;
    }

    /**
     * Delete a role
     */
    public function deleteRole(Role $role): bool
    {
        return $role->delete();
    }

    /**
     * Restore a soft-deleted role
     */
    public function restoreRole(int $roleId): ?Role
    {
        $role = Role::withTrashed()->findOrFail($roleId);
        $role->restore();
        return $role;
    }

    /**
     * Assign permission to role
     */
    public function assignPermissionToRole(Role $role, Permission $permission): void
    {
        $role->grantPermission($permission);
    }

    /**
     * Assign multiple permissions to role
     */
    public function assignPermissionsToRole(Role $role, array $permissionIds): void
    {
        $role->permissions()->attach($permissionIds);
    }

    /**
     * Sync permissions for role
     */
    public function syncPermissionsForRole(Role $role, array $permissionIds): void
    {
        $role->permissions()->sync($permissionIds);
    }

    /**
     * Remove permission from role
     */
    public function removePermissionFromRole(Role $role, Permission $permission): void
    {
        $role->revokePermission($permission);
    }

    /**
     * Get all permissions
     */
    public function getAllPermissions(): Collection
    {
        return Permission::orderBy('module')->orderBy('action')->get();
    }

    /**
     * Get permissions grouped by module
     */
    public function getPermissionsGroupedByModule()
    {
        return Permission::groupedByModule();
    }

    /**
     * Get permissions by module
     */
    public function getPermissionsByModule(string $module): Collection
    {
        return Permission::byModule($module)->get();
    }

    /**
     * Create a new permission
     */
    public function createPermission(array $data): Permission
    {
        return Permission::create([
            'name' => $data['name'],
            'slug' => $data['slug'] ?? $this->generateSlug($data['name']),
            'description' => $data['description'] ?? null,
            'module' => $data['module'] ?? null,
            'action' => $data['action'] ?? null,
        ]);
    }

    /**
     * Update a permission
     */
    public function updatePermission(Permission $permission, array $data): Permission
    {
        $permission->update([
            'name' => $data['name'] ?? $permission->name,
            'slug' => $data['slug'] ?? $permission->slug,
            'description' => $data['description'] ?? $permission->description,
            'module' => $data['module'] ?? $permission->module,
            'action' => $data['action'] ?? $permission->action,
        ]);

        return $permission;
    }

    /**
     * Delete a permission
     */
    public function deletePermission(Permission $permission): bool
    {
        return $permission->delete();
    }

    /**
     * Assign role to user
     */
    public function assignRoleToUser(User $user, Role $role): void
    {
        $user->assignRole($role, Auth::id());
    }

    /**
     * Assign multiple roles to user
     */
    public function assignRolesToUser(User $user, array $roleIds): void
    {
        $user->syncRoles($roleIds);
    }

    /**
     * Remove role from user
     */
    public function removeRoleFromUser(User $user, Role $role): void
    {
        $user->removeRole($role);
    }

    /**
     * Get user's roles with permissions
     */
    public function getUserRolesWithPermissions(User $user): array
    {
        return $user->roles()
            ->with('permissions')
            ->get()
            ->toArray();
    }

    /**
     * Get all default roles needed for the system
     */
    public function getDefaultRoles(): array
    {
        return [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrator with full system access',
                'priority' => 100,
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Manager with module-level access',
                'priority' => 50,
            ],
            [
                'name' => 'Staff',
                'slug' => 'staff',
                'description' => 'Regular staff with limited access',
                'priority' => 10,
            ],
            [
                'name' => 'Viewer',
                'slug' => 'viewer',
                'description' => 'Read-only access to reports and dashboards',
                'priority' => 1,
            ],
        ];
    }

    /**
     * Generate slug from name
     */
    private function generateSlug(string $name): string
    {
        return str_replace(' ', '-', strtolower(trim($name)));
    }

    /**
     * Seed default roles and permissions
     */
    public function seedDefaultRoles(): void
    {
        $defaultRoles = $this->getDefaultRoles();

        foreach ($defaultRoles as $roleData) {
            $role = Role::firstOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
        }
    }
}
