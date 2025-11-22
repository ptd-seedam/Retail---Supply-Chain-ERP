# Role & Permission System - Quick Reference Guide

## 🚀 Getting Started

### Initialize the System

```bash
# Run migrations
php artisan migrate

# Seed default roles and permissions
php artisan db:seed --class=RolePermissionSeeder

# Clear cache
php artisan cache:clear
```

## 🔐 Using in Code

### Check Roles

```php
// Single role
if (auth()->user()->hasRole('admin')) { }

// Any role (OR)
if (auth()->user()->hasAnyRole(['admin', 'manager'])) { }

// All roles (AND)
if (auth()->user()->hasAllRoles(['admin', 'manager'])) { }
```

### Check Permissions

```php
// Single permission
if (auth()->user()->hasPermission('view-users')) { }

// Any permission (OR)
if (auth()->user()->hasAnyPermission(['view-users', 'edit-users'])) { }

// All permissions (AND)
if (auth()->user()->hasAllPermissions(['view-users', 'edit-users'])) { }
```

### Get User Data

```php
// Get all role slugs
$roles = auth()->user()->getRoleSlugs();
// Output: ['admin', 'manager']

// Get all permissions
$permissions = auth()->user()->getAllPermissions();
// Output: ['view-users', 'create-products', 'view-reports']
```

### Global Helper Functions

```php
// Check role (globally)
if (hasRole('admin')) { }

// Check permission (globally)
if (hasPermission('view-reports')) { }

// Get user's roles
$roles = userRoles(); // ['admin', 'manager']

// Get user's permissions
$permissions = userPermissions(); // ['view-users', 'create-products']
```

## 🛣️ Protecting Routes

### Single Role

```php
Route::get('/admin', function () {
    // Only admins
})->middleware('role:admin');
```

### Multiple Roles (OR logic)

```php
Route::get('/manage', function () {
    // Admin or Manager
})->middleware('role:admin,manager');
```

### By Permission

```php
Route::post('/users', function () {
    // Must have create-users permission
})->middleware('permission:create-users');
```

### Combined

```php
Route::delete('/users/{id}', function () {
    // Must be admin AND have delete-users
})->middleware(['role:admin', 'permission:delete-users']);
```

## 📡 API Endpoints

### Get All Roles

```http
GET /api/v1/roles
Authorization: Bearer {token}
```

### Create Role (Admin only)

```http
POST /api/v1/roles
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Supervisor",
  "slug": "supervisor",
  "description": "Supervisor role",
  "priority": 30,
  "is_active": true,
  "permissions": [1, 2, 3]
}
```

### Get Permissions

```http
GET /api/v1/permissions
Authorization: Bearer {token}
```

### Get Permissions by Module

```http
GET /api/v1/permissions/grouped
Authorization: Bearer {token}
```

### Assign Roles to User

```http
POST /api/v1/roles/assign-to-user/{userId}
Authorization: Bearer {token}
Content-Type: application/json

{
  "role_ids": [1, 2]
}
```

### Get User's Roles & Permissions

```http
GET /api/v1/users/{userId}/roles-permissions
Authorization: Bearer {token}
```

## 👥 Default Roles

| Role        | Priority | Permissions                 | Purpose            |
| ----------- | -------- | --------------------------- | ------------------ |
| **Admin**   | 100      | All                         | Full system access |
| **Manager** | 50       | View, Edit, Create, Reports | Module management  |
| **Staff**   | 10       | View, Create                | Basic operations   |
| **Viewer**  | 1        | Reports                     | Read-only access   |

## 🔖 Default Permissions

### User Management

-   `view-users` - View users
-   `create-users` - Create users
-   `edit-users` - Edit users
-   `delete-users` - Delete users

### Role Management

-   `view-roles` - View roles
-   `create-roles` - Create roles
-   `edit-roles` - Edit roles
-   `delete-roles` - Delete roles

### Core Module

-   `view-warehouses`, `create-warehouses`, `edit-warehouses`, `delete-warehouses`
-   `view-products`, `create-products`, `edit-products`, `delete-products`

### HRM Module

-   `view-employees`, `create-employees`, `edit-employees`, `delete-employees`
-   `view-salaries`, `create-salaries`, `edit-salaries`, `delete-salaries`

### CRM Module

-   `view-customers`, `create-customers`, `edit-customers`, `delete-customers`

### Ecommerce Module

-   `view-orders`, `create-orders`, `edit-orders`, `delete-orders`

### Reporting

-   `view-reports` - View reports
-   `export-reports` - Export reports

## 📝 Working with Roles in Code

### Assign Role to User

```php
use App\Models\User;
use App\Models\Role;

$user = User::find(1);
$role = Role::where('slug', 'manager')->first();

// Assign single role
$user->assignRole($role);

// Assign multiple roles
$user->syncRoles([1, 2, 3]);
```

### Remove Role

```php
$user->removeRole($role);
```

### Get User's Roles with Permissions

```php
$roles = $user->roles()->with('permissions')->get();

foreach ($roles as $role) {
    echo "Role: " . $role->name;
    foreach ($role->permissions as $perm) {
        echo "  - " . $perm->name;
    }
}
```

## 🔍 Using RoleService

```php
use App\Services\RoleService;

$roleService = app(RoleService::class);

// Get all roles
$roles = $roleService->getAllRoles();

// Get role by slug
$admin = $roleService->getRoleBySlug('admin');

// Create permission
$permission = $roleService->createPermission([
    'name' => 'Approve Orders',
    'slug' => 'approve-orders',
    'module' => 'ecommerce',
    'action' => 'approve'
]);

// Assign permission to role
$roleService->assignPermissionToRole($admin, $permission);
```

## 📦 JWT Token Claims

User tokens automatically include roles and permissions:

```json
{
    "sub": 1,
    "iat": 1700000000,
    "exp": 1700003600,
    "roles": ["admin", "manager"],
    "permissions": [
        "view-users",
        "create-products",
        "view-reports",
        "approve-orders"
    ]
}
```

## ⚙️ Configuration Files

-   **Models**: `app/Models/Role.php`, `app/Models/Permission.php`, `app/Models/User.php`
-   **Service**: `app/Services/RoleService.php`
-   **Middleware**: `app/Http/Middleware/CheckRole.php`, `CheckPermission.php`
-   **Controller**: `app/Http/Controllers/RoleController.php`
-   **Helpers**: `app/Helpers/AuthorizationHelper.php`
-   **Routes**: `routes/roles.php`
-   **Migrations**: `database/migrations/2025_11_22_*.php`
-   **Seeder**: `database/seeders/RolePermissionSeeder.php`

## 🛠️ Common Tasks

### Create New Permission

```php
$permission = Permission::create([
    'name' => 'Approve Reports',
    'slug' => 'approve-reports',
    'description' => 'Can approve pending reports',
    'module' => 'reports',
    'action' => 'approve'
]);

$admin_role->grantPermission($permission);
```

### Create Custom Role

```php
$supervisor = Role::create([
    'name' => 'Supervisor',
    'slug' => 'supervisor',
    'description' => 'Supervisor with reporting access',
    'priority' => 30,
    'is_active' => true
]);

// Assign specific permissions
$supervisor->permissions()->attach([1, 2, 3, 4, 5]);
```

### Check Multiple Conditions

```php
if (
    auth()->user()->hasRole('admin') ||
    (auth()->user()->hasRole('manager') && auth()->user()->hasPermission('approve-orders'))
) {
    // Allow
}
```

## 📊 Database Schema

```
roles
├── id (PK)
├── name
├── slug (UNIQUE)
├── description
├── priority
├── is_active
└── timestamps & soft deletes

permissions
├── id (PK)
├── name
├── slug (UNIQUE)
├── description
├── module
├── action
└── timestamps & soft deletes

role_user (M:M)
├── user_id (FK)
├── role_id (FK)
├── assigned_at
└── assigned_by_id (FK)

permission_role (M:M)
├── permission_id (FK)
├── role_id (FK)
└── assigned_at
```

## 🐛 Troubleshooting

### "Class not found" error

```bash
composer dump-autoload
php artisan cache:clear
```

### Middleware not working

Check `bootstrap/app.php` has middleware registered:

```php
$middleware->alias([
    'role' => \App\Http\Middleware\CheckRole::class,
    'permission' => \App\Http\Middleware\CheckPermission::class,
]);
```

### Helper functions not available

Ensure `composer.json` has:

```json
"autoload": {
    "files": ["app/Helpers/AuthorizationHelper.php"]
}
```

Then run: `composer dump-autoload`

## 📚 Documentation

-   Full documentation: `docs/ROLE_PERMISSION_SYSTEM.md`
-   Implementation summary: `docs/ROLE_IMPLEMENTATION_SUMMARY.md`
-   This guide: `docs/QUICK_REFERENCE.md`

---

**Last Updated**: 2025-11-22  
**Version**: 1.0  
**Status**: ✅ Production Ready
