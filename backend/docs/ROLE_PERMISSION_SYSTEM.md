# Role và Permission System - Tài liệu

## Giới thiệu

Hệ thống phân chia role và permission cho phép quản lý quyền truy cập chi tiết cho từng người dùng trong ứng dụng. Hệ thống hỗ trợ:

-   **4 Role mặc định**: Admin, Manager, Staff, Viewer
-   **30+ Permissions**: Phân chia theo modules (Core, HRM, CRM, Ecommerce, Reports)
-   **Dynamic Role Assignment**: Gán/gỡ role cho user linh hoạt
-   **Permission-based Access Control**: Kiểm soát truy cập dựa trên permission

## Cấu trúc Cơ sở Dữ Liệu

### Các bảng chính:

```
roles
├── id (PK)
├── name (VARCHAR 100) - Tên role
├── slug (VARCHAR 100) - URL-friendly identifier
├── description (TEXT)
├── priority (INT) - Mức ưu tiên (0-1000)
├── is_active (BOOLEAN)
└── timestamps & soft deletes

permissions
├── id (PK)
├── name (VARCHAR 100)
├── slug (VARCHAR 100)
├── description (TEXT)
├── module (VARCHAR 100) - Thuộc module nào (users, core, hrm, crm, ecommerce, reports)
├── action (VARCHAR 50) - Hành động (view, create, edit, delete, export)
└── timestamps & soft deletes

role_user (Many-to-Many)
├── id (PK)
├── user_id (FK)
├── role_id (FK)
├── assigned_at (TIMESTAMP)
├── assigned_by_id (FK - quản lý viên gán role)
└── timestamps

permission_role (Many-to-Many)
├── id (PK)
├── permission_id (FK)
├── role_id (FK)
├── assigned_at (TIMESTAMP)
└── timestamps
```

## Default Roles

### 1. Admin (priority: 100)

-   **Mô tả**: Quản trị viên hệ thống với quyền truy cập đầy đủ
-   **Permissions**: Tất cả permissions
-   **Chủ yếu dùng cho**: Quản trị viên hệ thống

### 2. Manager (priority: 50)

-   **Mô tả**: Quản lý với quyền truy cập và báo cáo theo module
-   **Permissions**: View, Edit, Create, View Reports
-   **Không có**: Delete permissions
-   **Chủ yếu dùng cho**: Quản lý bộ phận

### 3. Staff (priority: 10)

-   **Mô tả**: Nhân viên thường xuyên với quyền truy cập hạn chế
-   **Permissions**: View, Create
-   **Không có**: Edit, Delete permissions
-   **Chủ yếu dùng cho**: Nhân viên nhập liệu, kiểm hàng

### 4. Viewer (priority: 1)

-   **Mô tả**: Người xem báo cáo chỉ có quyền đọc
-   **Permissions**: View Reports, Export Reports
-   **Chủ yếu dùng cho**: Bộ phận tài chính, lãnh đạo, khách hàng

## Default Permissions

### User Management

-   `view-users` - Xem danh sách người dùng
-   `create-users` - Tạo người dùng mới
-   `edit-users` - Chỉnh sửa thông tin người dùng
-   `delete-users` - Xóa người dùng

### Role Management

-   `view-roles` - Xem danh sách role
-   `create-roles` - Tạo role mới
-   `edit-roles` - Chỉnh sửa role
-   `delete-roles` - Xóa role

### Core Module (Warehouses, Products, Categories)

-   `view-warehouses`, `create-warehouses`, `edit-warehouses`, `delete-warehouses`
-   `view-products`, `create-products`, `edit-products`, `delete-products`

### HRM Module (Employees, Salaries)

-   `view-employees`, `create-employees`, `edit-employees`, `delete-employees`
-   `view-salaries`, `create-salaries`, `edit-salaries`, `delete-salaries`

### CRM Module (Customers)

-   `view-customers`, `create-customers`, `edit-customers`, `delete-customers`

### Ecommerce Module (Orders)

-   `view-orders`, `create-orders`, `edit-orders`, `delete-orders`

### Reporting

-   `view-reports` - Xem báo cáo
-   `export-reports` - Xuất báo cáo

## API Endpoints

### 1. Quản lý Role

#### Lấy tất cả role

```
GET /api/v1/roles
Authorization: Bearer {token}
```

**Response:**

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Admin",
      "slug": "admin",
      "description": "Administrator with full system access",
      "priority": 100,
      "is_active": true,
      "permissions": [...]
    }
  ],
  "message": "Roles retrieved successfully"
}
```

#### Lấy role đang hoạt động

```
GET /api/v1/roles/active
Authorization: Bearer {token}
```

#### Lấy role theo ID

```
GET /api/v1/roles/{id}
Authorization: Bearer {token}
```

#### Tạo role mới (Admin only)

```
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

#### Cập nhật role (Admin only)

```
PUT /api/v1/roles/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Supervisor Updated",
  "permissions": [1, 2, 3, 4]
}
```

#### Xóa role (Admin only)

```
DELETE /api/v1/roles/{id}
Authorization: Bearer {token}
```

**Lưu ý**: Không thể xóa role nếu đang gán cho user

### 2. Quản lý Permission

#### Lấy tất cả permission

```
GET /api/v1/permissions
Authorization: Bearer {token}
```

#### Lấy permission nhóm theo module

```
GET /api/v1/permissions/grouped
Authorization: Bearer {token}
```

**Response:**

```json
{
  "success": true,
  "data": {
    "users": [
      {
        "id": 1,
        "name": "View Users",
        "slug": "view-users",
        "module": "users",
        "action": "view"
      },
      ...
    ],
    "core": [...],
    "hrm": [...]
  }
}
```

#### Tạo permission mới (Admin only)

```
POST /api/v1/permissions
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Approve Orders",
  "slug": "approve-orders",
  "description": "Permission to approve pending orders",
  "module": "ecommerce",
  "action": "approve"
}
```

### 3. Gán Role cho User

#### Gán role cho user (Admin only)

```
POST /api/v1/roles/assign-to-user/{userId}
Authorization: Bearer {token}
Content-Type: application/json

{
  "role_ids": [2, 3]
}
```

#### Lấy role và permission của user

```
GET /api/v1/users/{userId}/roles-permissions
Authorization: Bearer {token}
```

**Response:**

```json
{
    "success": true,
    "data": {
        "roles": ["manager", "staff"],
        "permissions": [
            "view-users",
            "view-products",
            "create-orders",
            "view-reports"
        ]
    }
}
```

## Sử dụng trong Code

### 1. Kiểm tra Role

```php
// Single role check
if (auth()->user()->hasRole('admin')) {
    // User is admin
}

// Check multiple roles (OR logic)
if (auth()->user()->hasAnyRole(['admin', 'manager'])) {
    // User is admin or manager
}

// Check all roles (AND logic)
if (auth()->user()->hasAllRoles(['admin', 'manager'])) {
    // User has both admin and manager roles
}

// Get all roles
$roles = auth()->user()->getRoleSlugs();
// Output: ['admin', 'manager']
```

### 2. Kiểm tra Permission

```php
// Single permission check
if (auth()->user()->hasPermission('view-users')) {
    // User can view users
}

// Check multiple permissions (OR logic)
if (auth()->user()->hasAnyPermission(['view-users', 'edit-users'])) {
    // User can view or edit users
}

// Check all permissions (AND logic)
if (auth()->user()->hasAllPermissions(['view-users', 'edit-users'])) {
    // User can both view and edit users
}

// Get all permissions
$permissions = auth()->user()->getAllPermissions();
// Output: ['view-users', 'create-products', 'view-reports']
```

### 3. Sử dụng Helper Functions

```php
// Helper functions (global scope)
if (hasRole('admin')) {
    // User is admin
}

if (hasAnyRole(['admin', 'manager'])) {
    // User is admin or manager
}

if (hasPermission('view-reports')) {
    // User can view reports
}

// Get user's roles and permissions
$roles = userRoles(); // ['admin', 'manager']
$permissions = userPermissions(); // ['view-users', 'create-products', ...]
```

### 4. Middleware cho Route

```php
// Single role check
Route::get('/admin', function () {
    // Only admin can access
})->middleware('role:admin');

// Multiple roles (OR logic)
Route::get('/manage', function () {
    // Admin or Manager
})->middleware('role:admin,manager');

// Permission check
Route::post('/orders', function () {
    // Must have create-orders permission
})->middleware('permission:create-orders');

// Combine role and permission
Route::put('/users/{id}', function () {
    // Must be admin and have edit-users permission
})->middleware(['role:admin', 'permission:edit-users']);
```

### 5. Gán Role cho User

```php
use App\Models\User;
use App\Models\Role;
use App\Services\RoleService;

$user = User::find(1);
$roleService = app(RoleService::class);

// Assign single role
$role = Role::where('slug', 'manager')->first();
$roleService->assignRoleToUser($user, $role);

// Assign multiple roles
$roleIds = [2, 3]; // Manager, Staff
$roleService->assignRolesToUser($user, $roleIds);

// Remove role
$roleService->removeRoleFromUser($user, $role);

// Sync roles (replace existing)
$roleService->assignRolesToUser($user, [2]); // Only Manager
```

### 6. Quản lý Permission

```php
use App\Models\Role;
use App\Models\Permission;
use App\Services\RoleService;

$roleService = app(RoleService::class);

// Create permission
$permission = $roleService->createPermission([
    'name' => 'Approve Orders',
    'slug' => 'approve-orders',
    'module' => 'ecommerce',
    'action' => 'approve'
]);

// Assign permission to role
$role = Role::find(1);
$roleService->assignPermissionToRole($role, $permission);

// Sync permissions for role
$roleService->syncPermissionsForRole($role, [1, 2, 3]);

// Remove permission from role
$roleService->removePermissionFromRole($role, $permission);
```

## JWT Custom Claims

Khi user đăng nhập, token JWT sẽ bao gồm roles và permissions:

```json
{
    "sub": 1,
    "iat": 1700000000,
    "exp": 1700003600,
    "roles": ["admin", "manager"],
    "permissions": ["view-users", "create-products", "view-reports"]
}
```

## Database Seeding

### Chạy seeder để tạo default roles và permissions:

```bash
php artisan db:seed --class=RolePermissionSeeder
```

Hoặc chạy tất cả seeders:

```bash
php artisan db:seed
```

## Migration

### Tạo các bảng:

```bash
php artisan migrate
```

### Rollback migrations:

```bash
php artisan migrate:rollback
```

## Best Practices

1. **Sử dụng slug thay vì ID**: Luôn sử dụng slug (ví dụ: 'admin') thay vì ID khi kiểm tra role/permission
2. **Cache permissions**: Trong production, cache permission list để tăng hiệu suất
3. **Audit log**: Ghi lại ai gán/gỡ role để kiểm tra
4. **Naming convention**:
    - Role slugs: `kebab-case` (admin, super-admin, ...)
    - Permission slugs: `action-resource` (view-users, create-products, ...)
5. **Hierarchical roles**: Sử dụng priority để định nghĩa từng bậc
6. **Delete protection**: Không xóa role đang gán cho user

## Troubleshooting

### Issue: "Undefined type 'App\Models\Role'"

**Solution**: Chạy `composer dump-autoload`

### Issue: Middleware role không hoạt động

**Solution**: Kiểm tra `bootstrap/app.php` để middleware được register

### Issue: Helper functions không tìm thấy

**Solution**: Kiểm tra composer.json có autoload helper file

### Issue: Token không chứa roles/permissions

**Solution**: Logout và login lại để token được tạo lại với custom claims

## Schema Diagram

```
┌─────────────────────┐         ┌──────────────────────┐
│      users          │         │       roles          │
├─────────────────────┤         ├──────────────────────┤
│ id (PK)             │         │ id (PK)              │
│ name                │◄────────│ name                 │
│ email               │    M:M  │ slug (UNIQUE)        │
│ password            │         │ description          │
│ email_verified_at   │         │ priority             │
│ timestamps          │         │ is_active            │
│                     │         │ timestamps           │
│                     │         │ deleted_at           │
└─────────────────────┘         └──────────────────────┘
         ▲                                ▲
         │ role_user                      │ permission_role
         │ (user_id, role_id)             │ (role_id, permission_id)
         │                                │
┌─────────────────────────────────────────────────────────┐
│              Intermediate Tables                        │
├─────────────────────────────────────────────────────────┤
│ role_user                permission_role                │
│ ├─ id (PK)               ├─ id (PK)                     │
│ ├─ user_id (FK)          ├─ permission_id (FK)          │
│ ├─ role_id (FK)          ├─ role_id (FK)                │
│ ├─ assigned_at           ├─ assigned_at                 │
│ ├─ assigned_by_id (FK)   ├─ timestamps                  │
│ └─ timestamps            └─                              │
└─────────────────────────────────────────────────────────┘
                                │
                                │
                        ┌───────▼──────────┐
                        │  permissions     │
                        ├──────────────────┤
                        │ id (PK)          │
                        │ name             │
                        │ slug (UNIQUE)    │
                        │ description      │
                        │ module           │
                        │ action           │
                        │ timestamps       │
                        │ deleted_at       │
                        └──────────────────┘
```

## Kết luận

Hệ thống role và permission cung cấp một giải pháp linh hoạt và mạnh mẽ để quản lý quyền truy cập trong ERP. Sử dụng helper functions và middleware để dễ dàng kiểm soát truy cập tại tất cả các layers của ứng dụng.
