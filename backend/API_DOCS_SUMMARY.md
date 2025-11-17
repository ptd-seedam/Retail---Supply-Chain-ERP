# API Documentation Summary

## 📚 Documentation Files Created

### 1. **API_DOCUMENTATION.md** - Complete API Reference

-   Overview and architecture
-   Authentication endpoints (Register, Login, Logout, Refresh, Me)
-   Response format specifications
-   Core Module APIs (Warehouses, Products, Categories)
-   HRM Module APIs (Employees, Salaries)
-   CRM Module APIs (Customers)
-   Ecommerce Module APIs (Orders, Order Items)
-   Error handling guide
-   Best practices

### 2. **API_QUICK_REFERENCE.md** - Quick Lookup Guide

-   Base URL and authentication
-   Quick endpoint reference tables
-   Common HTTP status codes
-   Sample request bodies for each resource
-   cURL examples for common operations
-   Quick reference for pagination and filtering

### 3. **DEVELOPER_GUIDE.md** - Comprehensive Developer Documentation

-   Getting started setup
-   Authentication flow
-   API patterns and conventions
-   Module-specific details
-   Data models and relationships
-   Audit trail and soft deletes
-   Error handling guide
-   Advanced features
-   Client implementation examples (JavaScript, Python)
-   Security best practices
-   Testing guide

### 4. **Postman_Collection.json** - API Testing Collection

-   Complete Postman collection with all endpoints
-   Pre-configured requests with sample data
-   Support for environment variables (base_url, token)
-   Organized by module and endpoint
-   Ready to use for API testing

---

## 🎯 Quick Start

### For API Users:

1. Start with **API_QUICK_REFERENCE.md** for endpoint lookup
2. Check **API_DOCUMENTATION.md** for detailed specifications
3. Use **Postman_Collection.json** to test endpoints

### For Developers:

1. Read **DEVELOPER_GUIDE.md** for setup and implementation
2. Reference **API_DOCUMENTATION.md** for detailed specs
3. Check code examples in **DEVELOPER_GUIDE.md**

---

## 📋 API Endpoints Summary

### Authentication (5 endpoints)

-   `POST /auth/register` - Register new user
-   `POST /auth/login` - User login
-   `GET /auth/me` - Get current user
-   `POST /auth/logout` - Logout
-   `POST /auth/refresh` - Refresh token

### Core Module (20 endpoints)

**Warehouses (6):**

-   List, Create, Get, Update, Delete, Get Active

**Products (7):**

-   List, Create, Get, Update, Delete, Get by Category, Get Active

**Categories (7):**

-   List, Create, Get, Update, Delete, Get Tree, Get Children, Get Active

### HRM Module (14 endpoints)

**Employees (8):**

-   List, Create, Get, Update, Delete, Get by Department, Get by Shift, Get Active, Get On-Leave

**Salaries (11):**

-   List, Create, Get, Update, Delete, Approve, Mark as Paid, Get by Employee, Get Pending, Get by Month

### CRM Module (8 endpoints)

**Customers (8):**

-   List, Create, Get, Update, Delete, Get by Group, Get Active, Get Suspended, Get High-Value

### Ecommerce Module (17 endpoints)

**Orders (10):**

-   List, Create, Get, Update, Delete, Complete, Cancel, Calculate Total, Get by Status, Get by Customer, Get Pending

**Order Items (7):**

-   List, Create, Get, Update, Delete

---

## 🔐 Authentication

**Method:** JWT (JSON Web Tokens)

**Flow:**

1. Register or Login to get JWT token
2. Include token in Authorization header: `Authorization: Bearer {token}`
3. Refresh token using refresh endpoint when needed

**Token Expiration:** 3600 seconds (1 hour)

---

## 📊 Response Format

### Success (200, 201)

```json
{
    "status": true,
    "message": "Operation successful",
    "data": {
        /* resource payload */
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

### Paginated List (200)

```json
{
    "status": true,
    "message": "List retrieved",
    "data": {
        "items": [
            /* array of resources */
        ],
        "pagination": {
            "total": 100,
            "count": 15,
            "per_page": 15,
            "current_page": 1,
            "last_page": 7
        }
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

### Error (4xx, 5xx)

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

## 🛠️ Technology Stack

-   **Framework:** Laravel 10+
-   **Language:** PHP 8.2
-   **Database:** MySQL
-   **Authentication:** JWT (tymon/jwt-auth)
-   **Architecture:** Modular Monolith (nwidart/laravel-modules)

---

## 📦 Module Breakdown

### Core Module

Handles warehouse management, product catalog, inventory, and categories.

**Key Entities:**

-   Warehouses
-   Products
-   Categories (hierarchical)
-   Inventory Transactions

### HRM Module

Manages employees, departments, shifts, and payroll.

**Key Entities:**

-   Employees
-   Departments
-   Shifts
-   Salaries (monthly)

### CRM Module

Manages customer relationships and loyalty.

**Key Entities:**

-   Customers
-   Customer Groups
-   Loyalty Points

### Ecommerce Module

Handles order processing and order items.

**Key Entities:**

-   Orders
-   Order Items
-   Promotions

### Auth Module

User authentication and authorization.

**Key Entities:**

-   Users (with JWT support)

### CMS Module (Optional)

Content management with banners and news.

**Key Entities:**

-   Banners
-   News/Articles

---

## 📈 Data Features

### Audit Trail

All resources track:

-   `created_by_id` - Creator user ID
-   `updated_by_id` - Last updater user ID
-   `deleted_by_id` - Deleter user ID
-   `created_at` - Creation timestamp
-   `updated_at` - Update timestamp
-   `deleted_at` - Soft delete timestamp

### Soft Deletes

-   Resources are soft-deleted (marked as deleted, not removed from DB)
-   Deleted records are excluded from queries by default
-   Use `.withTrashed()` to include soft-deleted records

### Timestamps

-   All timestamps are in UTC
-   Format: ISO 8601 (2025-11-17T10:00:00Z)

---

## 🔄 Common Workflows

### User Registration & Login

1. POST /auth/register (or POST /auth/login)
2. Store returned JWT token
3. Use token in Authorization header for all subsequent requests

### Create Order with Items

1. POST /orders with items array
2. Each item: `product_id`, `quantity`, `unit_price`
3. Minimum 1 item required

### Manage Employee Salary

1. POST /salaries to create monthly salary
2. POST /salaries/{id}/approve to approve
3. POST /salaries/{id}/mark-paid to mark paid
4. GET /salaries-pending for pending approvals

### Product Catalog Management

1. POST /categories to create category hierarchy
2. POST /products with category_id
3. GET /categories/tree to view hierarchy
4. GET /products/category/{id} for category products

---

## 🔍 Pagination

All list endpoints support pagination:

**Query Parameters:**

-   `page` (integer) - Page number (default: 1)
-   `per_page` (integer) - Items per page (default: 15, max: 100)

**Example:**

```
GET /api/v1/products?page=2&per_page=20
```

---

## ⚠️ HTTP Status Codes

| Code | Meaning       | Use Case                           |
| ---- | ------------- | ---------------------------------- |
| 200  | OK            | Successful GET, PUT, PATCH, DELETE |
| 201  | Created       | Successful POST (resource created) |
| 204  | No Content    | Successful request with no body    |
| 400  | Bad Request   | Malformed request                  |
| 401  | Unauthorized  | Missing/invalid token              |
| 403  | Forbidden     | No permission                      |
| 404  | Not Found     | Resource doesn't exist             |
| 422  | Unprocessable | Validation error                   |
| 500  | Server Error  | Unexpected error                   |

---

## 📝 Notes

1. **Base URL:** `http://localhost:8000/api/v1`
2. **All endpoints require JWT authentication** except:
    - `POST /auth/register`
    - `POST /auth/login`
3. **Vietnamese error messages** are returned in all validation errors
4. **Soft delete** is used by default for all delete operations
5. **Pagination** is available for all list endpoints
6. **Timestamps** are always in UTC ISO 8601 format
7. **Token expiration:** 3600 seconds (1 hour)

---

## 🚀 Using the Documentation

### For Testing Endpoints:

1. Use **Postman_Collection.json** with Postman client
2. Or use **cURL** examples from **API_QUICK_REFERENCE.md**
3. Or write custom clients using code examples in **DEVELOPER_GUIDE.md**

### For Integration:

1. Follow **DEVELOPER_GUIDE.md** for setup
2. Check client examples in **DEVELOPER_GUIDE.md**
3. Reference **API_DOCUMENTATION.md** for detailed specs
4. Use **API_QUICK_REFERENCE.md** for quick lookups

### For API Specification:

1. See **openapi.json** for OpenAPI 3.0 specification
2. Use with tools like Swagger UI or Redoc

---

## 📞 Support

For questions or issues:

-   Check the documentation files
-   Review code examples in **DEVELOPER_GUIDE.md**
-   Check **API_DOCUMENTATION.md** for detailed endpoint specs
-   Test endpoints using **Postman_Collection.json**

---

**Documentation Created:** November 17, 2025
**API Version:** 1.0.0
**Status:** Complete and Ready for Use
