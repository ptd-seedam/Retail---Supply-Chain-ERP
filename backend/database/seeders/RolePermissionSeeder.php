<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

/**
 * RolePermissionSeeder
 *
 * Seeds the database with default roles and permissions
 */
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default roles
        $this->createRoles();

        // Create default permissions
        $this->createPermissions();

        // Assign permissions to roles
        $this->assignPermissionsToRoles();
    }

    /**
     * Create default roles
     */
    private function createRoles(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrator with full system access',
                'priority' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Manager with module-level access and reporting',
                'priority' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'Staff',
                'slug' => 'staff',
                'description' => 'Regular staff with limited operational access',
                'priority' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Viewer',
                'slug' => 'viewer',
                'description' => 'Read-only access to reports and dashboards',
                'priority' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
        }

        echo "✓ Default roles created\n";
    }

    /**
     * Create default permissions
     */
    private function createPermissions(): void
    {
        $permissions = [
            // User Management
            ['name' => 'View Users', 'slug' => 'view-users', 'module' => 'users', 'action' => 'view'],
            ['name' => 'Create Users', 'slug' => 'create-users', 'module' => 'users', 'action' => 'create'],
            ['name' => 'Edit Users', 'slug' => 'edit-users', 'module' => 'users', 'action' => 'edit'],
            ['name' => 'Delete Users', 'slug' => 'delete-users', 'module' => 'users', 'action' => 'delete'],

            // Role Management
            ['name' => 'View Roles', 'slug' => 'view-roles', 'module' => 'roles', 'action' => 'view'],
            ['name' => 'Create Roles', 'slug' => 'create-roles', 'module' => 'roles', 'action' => 'create'],
            ['name' => 'Edit Roles', 'slug' => 'edit-roles', 'module' => 'roles', 'action' => 'edit'],
            ['name' => 'Delete Roles', 'slug' => 'delete-roles', 'module' => 'roles', 'action' => 'delete'],

            // Core Module - Warehouses
            ['name' => 'View Warehouses', 'slug' => 'view-warehouses', 'module' => 'core', 'action' => 'view'],
            ['name' => 'Create Warehouses', 'slug' => 'create-warehouses', 'module' => 'core', 'action' => 'create'],
            ['name' => 'Edit Warehouses', 'slug' => 'edit-warehouses', 'module' => 'core', 'action' => 'edit'],
            ['name' => 'Delete Warehouses', 'slug' => 'delete-warehouses', 'module' => 'core', 'action' => 'delete'],

            // Core Module - Products
            ['name' => 'View Products', 'slug' => 'view-products', 'module' => 'core', 'action' => 'view'],
            ['name' => 'Create Products', 'slug' => 'create-products', 'module' => 'core', 'action' => 'create'],
            ['name' => 'Edit Products', 'slug' => 'edit-products', 'module' => 'core', 'action' => 'edit'],
            ['name' => 'Delete Products', 'slug' => 'delete-products', 'module' => 'core', 'action' => 'delete'],

            // HRM Module - Employees
            ['name' => 'View Employees', 'slug' => 'view-employees', 'module' => 'hrm', 'action' => 'view'],
            ['name' => 'Create Employees', 'slug' => 'create-employees', 'module' => 'hrm', 'action' => 'create'],
            ['name' => 'Edit Employees', 'slug' => 'edit-employees', 'module' => 'hrm', 'action' => 'edit'],
            ['name' => 'Delete Employees', 'slug' => 'delete-employees', 'module' => 'hrm', 'action' => 'delete'],

            // HRM Module - Salaries
            ['name' => 'View Salaries', 'slug' => 'view-salaries', 'module' => 'hrm', 'action' => 'view'],
            ['name' => 'Create Salaries', 'slug' => 'create-salaries', 'module' => 'hrm', 'action' => 'create'],
            ['name' => 'Edit Salaries', 'slug' => 'edit-salaries', 'module' => 'hrm', 'action' => 'edit'],
            ['name' => 'Delete Salaries', 'slug' => 'delete-salaries', 'module' => 'hrm', 'action' => 'delete'],

            // CRM Module - Customers
            ['name' => 'View Customers', 'slug' => 'view-customers', 'module' => 'crm', 'action' => 'view'],
            ['name' => 'Create Customers', 'slug' => 'create-customers', 'module' => 'crm', 'action' => 'create'],
            ['name' => 'Edit Customers', 'slug' => 'edit-customers', 'module' => 'crm', 'action' => 'edit'],
            ['name' => 'Delete Customers', 'slug' => 'delete-customers', 'module' => 'crm', 'action' => 'delete'],

            // Ecommerce Module - Orders
            ['name' => 'View Orders', 'slug' => 'view-orders', 'module' => 'ecommerce', 'action' => 'view'],
            ['name' => 'Create Orders', 'slug' => 'create-orders', 'module' => 'ecommerce', 'action' => 'create'],
            ['name' => 'Edit Orders', 'slug' => 'edit-orders', 'module' => 'ecommerce', 'action' => 'edit'],
            ['name' => 'Delete Orders', 'slug' => 'delete-orders', 'module' => 'ecommerce', 'action' => 'delete'],

            // Reporting
            ['name' => 'View Reports', 'slug' => 'view-reports', 'module' => 'reports', 'action' => 'view'],
            ['name' => 'Export Reports', 'slug' => 'export-reports', 'module' => 'reports', 'action' => 'export'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(
                ['slug' => $permissionData['slug']],
                $permissionData
            );
        }

        echo "✓ Default permissions created\n";
    }

    /**
     * Assign permissions to roles
     */
    private function assignPermissionsToRoles(): void
    {
        // Admin: All permissions
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            $allPermissions = Permission::all();
            $adminRole->permissions()->sync($allPermissions->pluck('id'));
        }

        // Manager: View and edit permissions (not delete)
        $managerRole = Role::where('slug', 'manager')->first();
        if ($managerRole) {
            $managerPermissions = Permission::where(function ($query) {
                $query->where('action', 'view')
                    ->orWhere('action', 'edit')
                    ->orWhere('action', 'create')
                    ->orWhere('slug', 'view-reports');
            })->pluck('id')->toArray();
            $managerRole->permissions()->sync($managerPermissions);
        }

        // Staff: View and create permissions
        $staffRole = Role::where('slug', 'staff')->first();
        if ($staffRole) {
            $staffPermissions = Permission::where(function ($query) {
                $query->where('action', 'view')
                    ->orWhere('action', 'create');
            })->pluck('id')->toArray();
            $staffRole->permissions()->sync($staffPermissions);
        }

        // Viewer: View and export reports only
        $viewerRole = Role::where('slug', 'viewer')->first();
        if ($viewerRole) {
            $viewerPermissions = Permission::where('module', 'reports')->pluck('id')->toArray();
            $viewerRole->permissions()->sync($viewerPermissions);
        }

        echo "✓ Permissions assigned to roles\n";
    }
}
