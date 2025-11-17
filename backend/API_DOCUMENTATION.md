# Retail Supply Chain ERP - API Documentation

## Table of Contents

1. [Overview](#overview)
2. [Authentication](#authentication)
3. [Response Format](#response-format)
4. [Core Module APIs](#core-module-apis)
5. [HRM Module APIs](#hrm-module-apis)
6. [CRM Module APIs](#crm-module-apis)
7. [Ecommerce Module APIs](#ecommerce-module-apis)
8. [Error Handling](#error-handling)

---

## Overview

This is a comprehensive REST API for the Retail & Supply Chain ERP system. All endpoints are versioned (v1) and require JWT authentication (except for authentication endpoints).

**Base URL:** `http://localhost:8000/api/v1`

**API Version:** v1

**Database:** MySQL

**Authentication Method:** JWT (JSON Web Token)

---

## Authentication

### Register User

Creates a new user account.

**Endpoint:** `POST /auth/register`

**Request Body:**

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "SecurePassword123!",
    "password_confirmation": "SecurePassword123!"
}
```

**Response (201):**

```json
{
    "status": true,
    "message": "Đăng ký thành công",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "email_verified_at": null,
            "created_at": "2025-11-17T10:00:00Z"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "expires_in": 3600
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

### Login

Authenticates user and returns JWT token.

**Endpoint:** `POST /auth/login`

**Request Body:**

```json
{
    "email": "john@example.com",
    "password": "SecurePassword123!"
}
```

**Response (200):**

```json
{
    "status": true,
    "message": "Đăng nhập thành công",
    "data": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "expires_in": 3600
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

### Get Current User

Returns authenticated user profile.

**Endpoint:** `GET /auth/me`

**Headers:**

```
Authorization: Bearer {TOKEN}
```

**Response (200):**

```json
{
    "status": true,
    "message": "User profile",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "email_verified_at": null
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

### Logout

Invalidates the current JWT token.

**Endpoint:** `POST /auth/logout`

**Headers:**

```
Authorization: Bearer {TOKEN}
```

**Response (200):**

```json
{
    "status": true,
    "message": "Đăng xuất thành công",
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

### Refresh Token

Generates a new JWT token.

**Endpoint:** `POST /auth/refresh`

**Headers:**

```
Authorization: Bearer {TOKEN}
```

**Response (200):**

```json
{
    "status": true,
    "message": "Token refreshed",
    "data": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "expires_in": 3600
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

## Response Format

All API responses follow a standardized JSON format:

### Success Response (200, 201)

```json
{
    "status": true,
    "message": "Operation successful",
    "data": {
        // Response payload
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

### Paginated Response

```json
{
    "status": true,
    "message": "List retrieved",
    "data": {
        "items": [
            {
                /* resource objects */
            }
        ],
        "pagination": {
            "total": 50,
            "count": 15,
            "per_page": 15,
            "current_page": 1,
            "last_page": 4,
            "from": 1,
            "to": 15
        }
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

### Error Response (4xx, 5xx)

```json
{
    "status": false,
    "message": "Error description",
    "errors": {
        "field_name": ["Error message"]
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

## Core Module APIs

### Warehouses

#### List All Warehouses

**Endpoint:** `GET /warehouses`

**Query Parameters:**

-   `page` (int, optional): Page number for pagination (default: 1)
-   `per_page` (int, optional): Items per page (default: 15)

**Response (200):** Paginated list of warehouses

---

#### Get Warehouse Details

**Endpoint:** `GET /warehouses/{id}`

**Response (200):**

```json
{
    "status": true,
    "message": "Kho hàng",
    "data": {
        "warehouse": {
            "id": 1,
            "code": "WH001",
            "name": "Warehouse Main",
            "location": "123 Main Street",
            "description": "Main warehouse location",
            "status": "active",
            "created_by_id": 1,
            "updated_by_id": 1,
            "deleted_by_id": null,
            "created_at": "2025-11-17T10:00:00Z",
            "updated_at": "2025-11-17T10:00:00Z",
            "deleted_at": null
        }
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

#### Create Warehouse

**Endpoint:** `POST /warehouses`

**Request Body:**

```json
{
    "code": "WH002",
    "name": "Warehouse North",
    "location": "456 North Avenue",
    "description": "North warehouse location",
    "status": "active"
}
```

**Validation Rules:**
| Field | Rules | Vietnamese Message |
|-------|-------|-------------------|
| code | required, string, max:50, unique | Mã kho là bắt buộc, Mã kho đã tồn tại |
| name | required, string, max:191 | Tên kho là bắt buộc |
| location | nullable, string, max:255 | - |
| description | nullable, string | - |
| status | nullable, in:active,inactive | - |

**Response (201):** Created warehouse object

---

#### Update Warehouse

**Endpoint:** `PUT/PATCH /warehouses/{id}`

**Request Body:** (Same as Create, but all fields optional)

**Response (200):** Updated warehouse object

---

#### Delete Warehouse

**Endpoint:** `DELETE /warehouses/{id}`

**Response (200):**

```json
{
    "status": true,
    "message": "Xóa kho thành công",
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

#### Get Active Warehouses

**Endpoint:** `GET /warehouses-active`

**Response (200):** List of active warehouses

---

### Products

#### List All Products

**Endpoint:** `GET /products`

**Query Parameters:**

-   `page` (int, optional): Page number
-   `per_page` (int, optional): Items per page

**Response (200):** Paginated list of products

---

#### Get Product Details

**Endpoint:** `GET /products/{id}`

**Response (200):**

```json
{
    "status": true,
    "message": "Sản phẩm",
    "data": {
        "product": {
            "id": 1,
            "sku": "PRD001",
            "barcode": "123456789012",
            "name": "Laptop Dell",
            "description": "High-performance laptop",
            "category_id": 1,
            "cost_price": 500.0,
            "selling_price": 799.99,
            "reorder_level": 5,
            "status": "active",
            "created_by_id": 1,
            "updated_by_id": 1,
            "created_at": "2025-11-17T10:00:00Z",
            "updated_at": "2025-11-17T10:00:00Z"
        }
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

#### Create Product

**Endpoint:** `POST /products`

**Request Body:**

```json
{
    "sku": "PRD002",
    "barcode": "123456789013",
    "name": "Laptop HP",
    "description": "Professional laptop",
    "category_id": 1,
    "cost_price": 450.0,
    "selling_price": 749.99,
    "reorder_level": 10,
    "status": "active"
}
```

**Validation Rules:**
| Field | Rules | Vietnamese Message |
|-------|-------|-------------------|
| sku | required, string, max:50, unique | SKU là bắt buộc, SKU đã tồn tại |
| barcode | nullable, string, max:100, unique | - |
| name | required, string, max:255 | Tên sản phẩm là bắt buộc |
| description | nullable, string | - |
| category_id | nullable, exists:categories,id | - |
| cost_price | required, numeric, min:0 | Giá vốn là bắt buộc |
| selling_price | required, numeric, min:0 | Giá bán là bắt buộc |
| reorder_level | nullable, integer, min:0 | - |
| status | nullable, in:active,inactive | - |

**Response (201):** Created product object

---

#### Update Product

**Endpoint:** `PUT/PATCH /products/{id}`

**Request Body:** (Same as Create, but all fields optional)

**Response (200):** Updated product object

---

#### Delete Product

**Endpoint:** `DELETE /products/{id}`

**Response (200):** Success message

---

#### Get Products by Category

**Endpoint:** `GET /products/category/{categoryId}`

**Response (200):** List of products in category

---

#### Get Active Products

**Endpoint:** `GET /products-active`

**Response (200):** List of active products

---

### Categories

#### List All Categories

**Endpoint:** `GET /categories`

**Query Parameters:**

-   `page` (int, optional): Page number
-   `per_page` (int, optional): Items per page

**Response (200):** Paginated list of categories

---

#### Get Category Details

**Endpoint:** `GET /categories/{id}`

**Response (200):**

```json
{
    "status": true,
    "message": "Danh mục",
    "data": {
        "category": {
            "id": 1,
            "name": "Electronics",
            "description": "Electronic products",
            "parent_id": null,
            "sort_order": 1,
            "status": "active",
            "created_by_id": 1,
            "updated_by_id": 1,
            "created_at": "2025-11-17T10:00:00Z",
            "updated_at": "2025-11-17T10:00:00Z"
        }
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

#### Create Category

**Endpoint:** `POST /categories`

**Request Body:**

```json
{
    "name": "Computers",
    "description": "Computer products",
    "parent_id": null,
    "sort_order": 1,
    "status": "active"
}
```

**Validation Rules:**
| Field | Rules | Vietnamese Message |
|-------|-------|-------------------|
| name | required, string, max:191, unique | Tên danh mục là bắt buộc |
| description | nullable, string | - |
| parent_id | nullable, exists:categories,id | - |
| sort_order | nullable, integer | - |
| status | nullable, in:active,inactive | - |

**Response (201):** Created category object

---

#### Update Category

**Endpoint:** `PUT/PATCH /categories/{id}`

**Request Body:** (Same as Create)

**Response (200):** Updated category object

---

#### Delete Category

**Endpoint:** `DELETE /categories/{id}`

**Response (200):** Success message

---

#### Get Category Tree

**Endpoint:** `GET /categories/tree`

**Response (200):**

```json
{
    "status": true,
    "message": "Cây danh mục",
    "data": {
        "tree": [
            {
                "id": 1,
                "name": "Electronics",
                "children": [
                    {
                        "id": 2,
                        "name": "Computers",
                        "children": []
                    }
                ]
            }
        ]
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

#### Get Category Children

**Endpoint:** `GET /categories/{parentId}/children`

**Response (200):** List of child categories

---

#### Get Active Categories

**Endpoint:** `GET /categories-active`

**Response (200):** List of active categories

---

## HRM Module APIs

### Employees

#### List All Employees

**Endpoint:** `GET /employees`

**Query Parameters:**

-   `page` (int, optional): Page number
-   `per_page` (int, optional): Items per page

**Response (200):** Paginated list of employees

---

#### Get Employee Details

**Endpoint:** `GET /employees/{id}`

**Response (200):**

```json
{
    "status": true,
    "message": "Nhân viên",
    "data": {
        "employee": {
            "id": 1,
            "user_id": 1,
            "employee_code": "EMP001",
            "department_id": 1,
            "shift_id": 1,
            "phone": "0123456789",
            "address": "123 Main Street",
            "hire_date": "2025-01-01",
            "status": "active",
            "created_by_id": 1,
            "updated_by_id": 1,
            "created_at": "2025-11-17T10:00:00Z",
            "updated_at": "2025-11-17T10:00:00Z"
        }
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

#### Create Employee

**Endpoint:** `POST /employees`

**Request Body:**

```json
{
    "user_id": 2,
    "employee_code": "EMP002",
    "department_id": 1,
    "shift_id": 1,
    "phone": "0987654321",
    "address": "456 North Avenue",
    "hire_date": "2025-06-01",
    "status": "active"
}
```

**Validation Rules:**
| Field | Rules | Vietnamese Message |
|-------|-------|-------------------|
| user_id | required, exists:users,id | Người dùng là bắt buộc, Người dùng không tồn tại |
| employee_code | required, string, max:50, unique | Mã nhân viên là bắt buộc, Mã nhân viên đã tồn tại |
| department_id | nullable, exists:departments,id | - |
| shift_id | nullable, exists:shifts,id | - |
| phone | nullable, string, max:20 | - |
| address | nullable, string, max:255 | - |
| hire_date | nullable, date | - |
| status | nullable, in:active,inactive,on_leave | - |

**Response (201):** Created employee object

---

#### Update Employee

**Endpoint:** `PUT/PATCH /employees/{id}`

**Request Body:** (Same as Create)

**Response (200):** Updated employee object

---

#### Delete Employee

**Endpoint:** `DELETE /employees/{id}`

**Response (200):** Success message

---

#### Get Employees by Department

**Endpoint:** `GET /employees/department/{departmentId}`

**Response (200):** List of employees in department

---

#### Get Employees by Shift

**Endpoint:** `GET /employees/shift/{shiftId}`

**Response (200):** List of employees in shift

---

#### Get Active Employees

**Endpoint:** `GET /employees-active`

**Response (200):** List of active employees

---

#### Get On-Leave Employees

**Endpoint:** `GET /employees-on-leave`

**Response (200):** List of on-leave employees

---

### Salaries

#### List All Salaries

**Endpoint:** `GET /salaries`

**Query Parameters:**

-   `page` (int, optional): Page number
-   `per_page` (int, optional): Items per page

**Response (200):** Paginated list of salaries

---

#### Get Salary Details

**Endpoint:** `GET /salaries/{id}`

**Response (200):**

```json
{
    "status": true,
    "message": "Bảng lương",
    "data": {
        "salary": {
            "id": 1,
            "employee_id": 1,
            "year": 2025,
            "month": 11,
            "base_salary": 5000000,
            "bonus": 500000,
            "deductions": 100000,
            "total_salary": 5400000,
            "status": "pending",
            "payment_date": null,
            "created_by_id": 1,
            "updated_by_id": 1,
            "created_at": "2025-11-17T10:00:00Z",
            "updated_at": "2025-11-17T10:00:00Z"
        }
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

#### Create Salary

**Endpoint:** `POST /salaries`

**Request Body:**

```json
{
    "employee_id": 1,
    "year": 2025,
    "month": 11,
    "base_salary": 5000000,
    "bonus": 500000,
    "deductions": 100000,
    "status": "pending"
}
```

**Validation Rules:**
| Field | Rules | Vietnamese Message |
|-------|-------|-------------------|
| employee_id | required, exists:employees,id | - |
| year | required, integer, min:2000 | - |
| month | required, integer, min:1, max:12 | - |
| base_salary | required, numeric, min:0 | - |
| bonus | nullable, numeric, min:0 | - |
| deductions | nullable, numeric, min:0 | - |
| status | nullable, in:pending,approved,paid | - |

**Response (201):** Created salary object

---

#### Update Salary

**Endpoint:** `PUT/PATCH /salaries/{id}`

**Request Body:** (Same as Create)

**Response (200):** Updated salary object

---

#### Delete Salary

**Endpoint:** `DELETE /salaries/{id}`

**Response (200):** Success message

---

#### Approve Salary

**Endpoint:** `POST /salaries/{id}/approve`

**Response (200):**

```json
{
    "status": true,
    "message": "Phê duyệt bảng lương thành công",
    "data": {
        "salary": {
            /* updated salary object */
        }
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

#### Mark Salary as Paid

**Endpoint:** `POST /salaries/{id}/mark-paid`

**Request Body:**

```json
{
    "payment_date": "2025-11-17"
}
```

**Response (200):**

```json
{
    "status": true,
    "message": "Đánh dấu đã thanh toán thành công",
    "data": {
        "salary": {
            /* updated salary object */
        }
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

#### Get Salaries by Employee

**Endpoint:** `GET /salaries/employee/{employeeId}`

**Response (200):** List of employee salaries

---

#### Get Pending Salaries

**Endpoint:** `GET /salaries-pending`

**Response (200):** List of pending salaries

---

#### Get Salaries by Month

**Endpoint:** `GET /salaries/{year}/{month}`

**Response (200):** List of salaries for specific month

---

## CRM Module APIs

### Customers

#### List All Customers

**Endpoint:** `GET /customers`

**Query Parameters:**

-   `page` (int, optional): Page number
-   `per_page` (int, optional): Items per page

**Response (200):** Paginated list of customers

---

#### Get Customer Details

**Endpoint:** `GET /customers/{id}`

**Response (200):**

```json
{
    "status": true,
    "message": "Khách hàng",
    "data": {
        "customer": {
            "id": 1,
            "code": "CUST001",
            "name": "Acme Corp",
            "email": "contact@acme.com",
            "phone": "0123456789",
            "address": "123 Business Street",
            "group_id": 1,
            "credit_limit": 10000000,
            "current_balance": 5000000,
            "status": "active",
            "created_by_id": 1,
            "updated_by_id": 1,
            "created_at": "2025-11-17T10:00:00Z",
            "updated_at": "2025-11-17T10:00:00Z"
        }
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

#### Create Customer

**Endpoint:** `POST /customers`

**Request Body:**

```json
{
    "code": "CUST002",
    "name": "Tech Solutions Ltd",
    "email": "info@techsol.com",
    "phone": "0987654321",
    "address": "456 Tech Park",
    "group_id": 1,
    "credit_limit": 15000000,
    "status": "active"
}
```

**Validation Rules:**
| Field | Rules | Vietnamese Message |
|-------|-------|-------------------|
| code | required, string, max:50, unique | - |
| name | required, string, max:191 | - |
| email | nullable, email, unique | - |
| phone | nullable, string, max:20, unique | - |
| address | nullable, string, max:255 | - |
| group_id | nullable, exists:customer_groups,id | - |
| credit_limit | nullable, numeric, min:0 | - |
| status | nullable, in:active,inactive,suspended | - |

**Response (201):** Created customer object

---

#### Update Customer

**Endpoint:** `PUT/PATCH /customers/{id}`

**Request Body:** (Same as Create)

**Response (200):** Updated customer object

---

#### Delete Customer

**Endpoint:** `DELETE /customers/{id}`

**Response (200):** Success message

---

#### Get Customers by Group

**Endpoint:** `GET /customers/group/{groupId}`

**Response (200):** List of customers in group

---

#### Get Active Customers

**Endpoint:** `GET /customers-active`

**Response (200):** List of active customers

---

#### Get Suspended Customers

**Endpoint:** `GET /customers-suspended`

**Response (200):** List of suspended customers

---

#### Get High-Value Customers

**Endpoint:** `GET /customers-high-value`

**Response (200):** List of high-value customers

---

## Ecommerce Module APIs

### Orders

#### List All Orders

**Endpoint:** `GET /orders`

**Query Parameters:**

-   `page` (int, optional): Page number
-   `per_page` (int, optional): Items per page

**Response (200):** Paginated list of orders

---

#### Get Order Details

**Endpoint:** `GET /orders/{id}`

**Response (200):**

```json
{
    "status": true,
    "message": "Đơn hàng",
    "data": {
        "order": {
            "id": 1,
            "order_number": "ORD001",
            "customer_id": 1,
            "warehouse_id": 1,
            "status": "pending",
            "discount_amount": 100000,
            "tax_amount": 50000,
            "promotion_id": null,
            "notes": "Urgent delivery",
            "completed_at": null,
            "created_by_id": 1,
            "updated_by_id": 1,
            "created_at": "2025-11-17T10:00:00Z",
            "updated_at": "2025-11-17T10:00:00Z"
        }
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

#### Create Order

**Endpoint:** `POST /orders`

**Request Body:**

```json
{
    "order_number": "ORD002",
    "customer_id": 1,
    "warehouse_id": 1,
    "status": "pending",
    "discount_amount": 100000,
    "tax_amount": 50000,
    "promotion_id": null,
    "notes": "Regular delivery",
    "items": [
        {
            "product_id": 1,
            "quantity": 5,
            "unit_price": 799.99
        },
        {
            "product_id": 2,
            "quantity": 3,
            "unit_price": 499.99
        }
    ]
}
```

**Validation Rules:**
| Field | Rules | Vietnamese Message |
|-------|-------|-------------------|
| order_number | required, string, max:100, unique | Số đơn hàng là bắt buộc |
| customer_id | nullable, exists:customers,id | - |
| warehouse_id | nullable, exists:warehouses,id | - |
| status | nullable, in:pending,processing,completed,cancelled,refunded | - |
| discount_amount | nullable, numeric, min:0 | - |
| tax_amount | nullable, numeric, min:0 | - |
| promotion_id | nullable, exists:promotions,id | - |
| notes | nullable, string | - |
| items | required, array, min:1 | Đơn hàng phải có ít nhất 1 sản phẩm |
| items._.product_id | required, exists:products,id | Sản phẩm không tồn tại |
| items._.quantity | required, integer, min:1 | Số lượng là bắt buộc |
| items.\*.unit_price | required, numeric, min:0 | Giá bán là bắt buộc |

**Response (201):** Created order object

---

#### Update Order

**Endpoint:** `PUT/PATCH /orders/{id}`

**Request Body:** (Same as Create)

**Response (200):** Updated order object

---

#### Delete Order

**Endpoint:** `DELETE /orders/{id}`

**Response (200):** Success message

---

#### Complete Order

**Endpoint:** `POST /orders/{id}/complete`

**Response (200):**

```json
{
    "status": true,
    "message": "Hoàn thành đơn hàng",
    "data": {
        "order": {
            /* updated order object */
        }
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

#### Cancel Order

**Endpoint:** `POST /orders/{id}/cancel`

**Response (200):**

```json
{
    "status": true,
    "message": "Hủy đơn hàng",
    "data": {
        "order": {
            /* updated order object */
        }
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

#### Calculate Order Total

**Endpoint:** `GET /orders/{id}/calculate-total`

**Response (200):**

```json
{
    "status": true,
    "message": "Tính toán tổng tiền",
    "data": {
        "total": {
            "subtotal": 5000000,
            "discount": 100000,
            "tax": 50000,
            "total": 4950000
        }
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

#### Get Orders by Status

**Endpoint:** `GET /orders/status/{status}`

**Parameters:**

-   `status` (string): pending, processing, completed, cancelled, or refunded

**Response (200):** List of orders with specified status

---

#### Get Orders by Customer

**Endpoint:** `GET /orders/customer/{customerId}`

**Response (200):** List of customer orders

---

#### Get Pending Orders

**Endpoint:** `GET /orders-pending`

**Response (200):** List of pending orders

---

### Order Items

#### List Order Items

**Endpoint:** `GET /orders/{orderId}/items`

**Query Parameters:**

-   `page` (int, optional): Page number
-   `per_page` (int, optional): Items per page

**Response (200):** Paginated list of order items

---

#### Get Order Item Details

**Endpoint:** `GET /items/{itemId}`

**Response (200):**

```json
{
    "status": true,
    "message": "Chi tiết sản phẩm",
    "data": {
        "item": {
            "id": 1,
            "order_id": 1,
            "product_id": 1,
            "quantity": 5,
            "unit_price": 799.99,
            "subtotal": 3999.95,
            "created_by_id": 1,
            "updated_by_id": 1,
            "created_at": "2025-11-17T10:00:00Z",
            "updated_at": "2025-11-17T10:00:00Z"
        }
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

#### Add Item to Order

**Endpoint:** `POST /orders/{orderId}/items`

**Request Body:**

```json
{
    "product_id": 3,
    "quantity": 2,
    "unit_price": 599.99
}
```

**Validation Rules:**
| Field | Rules | Vietnamese Message |
|-------|-------|-------------------|
| product_id | required, exists:products,id | - |
| quantity | required, integer, min:1 | - |
| unit_price | required, numeric, min:0 | - |

**Response (201):** Created order item object

---

#### Update Order Item

**Endpoint:** `PUT/PATCH /items/{itemId}`

**Request Body:** (Same as Add Item)

**Response (200):** Updated order item object

---

#### Delete Order Item

**Endpoint:** `DELETE /items/{itemId}`

**Response (200):** Success message

---

## Error Handling

### Common HTTP Status Codes

| Code | Description                                            |
| ---- | ------------------------------------------------------ |
| 200  | OK - Request successful                                |
| 201  | Created - Resource created successfully                |
| 204  | No Content - Successful request with no response body  |
| 400  | Bad Request - Invalid request parameters               |
| 401  | Unauthorized - Missing or invalid authentication       |
| 403  | Forbidden - Authenticated but insufficient permissions |
| 404  | Not Found - Resource not found                         |
| 422  | Unprocessable Entity - Validation error                |
| 500  | Internal Server Error - Server error                   |

---

### Validation Error Response

**Status Code:** 422

```json
{
    "status": false,
    "message": "Validation error",
    "errors": {
        "email": ["Email đã tồn tại"],
        "phone": ["Số điện thoại không hợp lệ"]
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

### Authentication Error Response

**Status Code:** 401

```json
{
    "status": false,
    "message": "Unauthenticated",
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

### Not Found Error Response

**Status Code:** 404

```json
{
    "status": false,
    "message": "Resource not found",
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

## Best Practices

1. **Always include Authorization header** for protected endpoints:

    ```
    Authorization: Bearer {JWT_TOKEN}
    ```

2. **Use appropriate HTTP methods:**

    - GET: Retrieve data
    - POST: Create new resource
    - PUT/PATCH: Update resource
    - DELETE: Delete resource

3. **Pagination:** Use `page` and `per_page` query parameters for list endpoints

    ```
    GET /api/v1/products?page=2&per_page=20
    ```

4. **Check timestamps:** All responses include `timestamp` for client-side logging

5. **Soft deletes:** Deleted resources are soft-deleted by default (not permanently removed)

6. **Audit trail:** All resources track `created_by_id`, `updated_by_id`, and `deleted_by_id`

7. **Timezone:** All timestamps are in UTC (ISO 8601 format)

---

## Support

For API issues or questions, contact the development team.

**Last Updated:** November 17, 2025
