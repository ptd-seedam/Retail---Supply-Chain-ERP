# API Quick Reference Guide

## Base URL

```
http://localhost:8000/api/v1
```

## Authentication

All endpoints (except `/auth/*`) require JWT token in header:

```
Authorization: Bearer {token}
```

---

## Quick Endpoints Reference

### Authentication

| Method | Endpoint         | Description       |
| ------ | ---------------- | ----------------- |
| POST   | `/auth/register` | Register new user |
| POST   | `/auth/login`    | Login user        |
| GET    | `/auth/me`       | Get current user  |
| POST   | `/auth/logout`   | Logout            |
| POST   | `/auth/refresh`  | Refresh token     |

### Core Module

| Method | Endpoint                          | Description              |
| ------ | --------------------------------- | ------------------------ |
| GET    | `/warehouses`                     | List warehouses          |
| POST   | `/warehouses`                     | Create warehouse         |
| GET    | `/warehouses/{id}`                | Get warehouse            |
| PUT    | `/warehouses/{id}`                | Update warehouse         |
| DELETE | `/warehouses/{id}`                | Delete warehouse         |
| GET    | `/warehouses-active`              | Get active warehouses    |
| GET    | `/products`                       | List products            |
| POST   | `/products`                       | Create product           |
| GET    | `/products/{id}`                  | Get product              |
| PUT    | `/products/{id}`                  | Update product           |
| DELETE | `/products/{id}`                  | Delete product           |
| GET    | `/products-active`                | Get active products      |
| GET    | `/products/category/{categoryId}` | Get products by category |
| GET    | `/categories`                     | List categories          |
| POST   | `/categories`                     | Create category          |
| GET    | `/categories/{id}`                | Get category             |
| PUT    | `/categories/{id}`                | Update category          |
| DELETE | `/categories/{id}`                | Delete category          |
| GET    | `/categories/tree`                | Get category tree        |
| GET    | `/categories/{parentId}/children` | Get child categories     |
| GET    | `/categories-active`              | Get active categories    |

### HRM Module

| Method | Endpoint                     | Description                 |
| ------ | ---------------------------- | --------------------------- |
| GET    | `/employees`                 | List employees              |
| POST   | `/employees`                 | Create employee             |
| GET    | `/employees/{id}`            | Get employee                |
| PUT    | `/employees/{id}`            | Update employee             |
| DELETE | `/employees/{id}`            | Delete employee             |
| GET    | `/employees-active`          | Get active employees        |
| GET    | `/employees-on-leave`        | Get on-leave employees      |
| GET    | `/employees/department/{id}` | Get employees by department |
| GET    | `/employees/shift/{id}`      | Get employees by shift      |
| GET    | `/salaries`                  | List salaries               |
| POST   | `/salaries`                  | Create salary               |
| GET    | `/salaries/{id}`             | Get salary                  |
| PUT    | `/salaries/{id}`             | Update salary               |
| DELETE | `/salaries/{id}`             | Delete salary               |
| POST   | `/salaries/{id}/approve`     | Approve salary              |
| POST   | `/salaries/{id}/mark-paid`   | Mark salary as paid         |
| GET    | `/salaries-pending`          | Get pending salaries        |
| GET    | `/salaries/employee/{id}`    | Get employee salaries       |
| GET    | `/salaries/{year}/{month}`   | Get salaries by month       |

### CRM Module

| Method | Endpoint                | Description              |
| ------ | ----------------------- | ------------------------ |
| GET    | `/customers`            | List customers           |
| POST   | `/customers`            | Create customer          |
| GET    | `/customers/{id}`       | Get customer             |
| PUT    | `/customers/{id}`       | Update customer          |
| DELETE | `/customers/{id}`       | Delete customer          |
| GET    | `/customers-active`     | Get active customers     |
| GET    | `/customers-suspended`  | Get suspended customers  |
| GET    | `/customers-high-value` | Get high-value customers |
| GET    | `/customers/group/{id}` | Get customers by group   |

### Ecommerce Module

| Method | Endpoint                       | Description           |
| ------ | ------------------------------ | --------------------- |
| GET    | `/orders`                      | List orders           |
| POST   | `/orders`                      | Create order          |
| GET    | `/orders/{id}`                 | Get order             |
| PUT    | `/orders/{id}`                 | Update order          |
| DELETE | `/orders/{id}`                 | Delete order          |
| POST   | `/orders/{id}/complete`        | Complete order        |
| POST   | `/orders/{id}/cancel`          | Cancel order          |
| GET    | `/orders/{id}/calculate-total` | Calculate order total |
| GET    | `/orders-pending`              | Get pending orders    |
| GET    | `/orders/status/{status}`      | Get orders by status  |
| GET    | `/orders/customer/{id}`        | Get customer orders   |
| GET    | `/orders/{orderId}/items`      | List order items      |
| POST   | `/orders/{orderId}/items`      | Add item to order     |
| GET    | `/items/{id}`                  | Get order item        |
| PUT    | `/items/{id}`                  | Update order item     |
| DELETE | `/items/{id}`                  | Delete order item     |

---

## Response Format

### Success Response

```json
{
    "status": true,
    "message": "Operation message",
    "data": {
        // response payload
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

### Error Response

```json
{
    "status": false,
    "message": "Error message",
    "errors": {
        "field": ["Error description"]
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

### Paginated Response

```json
{
    "status": true,
    "message": "List message",
    "data": {
        "items": [
            {
                /* resource */
            }
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

---

## Common Request Parameters

### Pagination

-   `page` (integer): Page number (default: 1)
-   `per_page` (integer): Items per page (default: 15)

Example:

```
GET /api/v1/products?page=2&per_page=20
```

### Filtering (where applicable)

Use query parameters to filter results:

```
GET /api/v1/products/category/1
GET /api/v1/customers/group/5
```

---

## Status Codes

| Code | Meaning          |
| ---- | ---------------- |
| 200  | OK               |
| 201  | Created          |
| 204  | No Content       |
| 400  | Bad Request      |
| 401  | Unauthorized     |
| 403  | Forbidden        |
| 404  | Not Found        |
| 422  | Validation Error |
| 500  | Server Error     |

---

## Common Request Bodies

### Warehouse

```json
{
    "code": "WH001",
    "name": "Main Warehouse",
    "location": "123 Main St",
    "description": "Main storage",
    "status": "active"
}
```

### Product

```json
{
    "sku": "PRD001",
    "barcode": "1234567890",
    "name": "Product Name",
    "description": "Description",
    "category_id": 1,
    "cost_price": 100.0,
    "selling_price": 150.0,
    "reorder_level": 5,
    "status": "active"
}
```

### Category

```json
{
    "name": "Electronics",
    "description": "Electronic products",
    "parent_id": null,
    "sort_order": 1,
    "status": "active"
}
```

### Employee

```json
{
    "user_id": 1,
    "employee_code": "EMP001",
    "department_id": 1,
    "shift_id": 1,
    "phone": "0123456789",
    "address": "Address",
    "hire_date": "2025-01-01",
    "status": "active"
}
```

### Salary

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

### Customer

```json
{
    "code": "CUST001",
    "name": "Customer Name",
    "email": "email@example.com",
    "phone": "0123456789",
    "address": "Address",
    "group_id": 1,
    "credit_limit": 10000000,
    "status": "active"
}
```

### Order

```json
{
    "order_number": "ORD001",
    "customer_id": 1,
    "warehouse_id": 1,
    "discount_amount": 100000,
    "tax_amount": 50000,
    "promotion_id": null,
    "notes": "Notes",
    "items": [
        {
            "product_id": 1,
            "quantity": 5,
            "unit_price": 799.99
        }
    ]
}
```

---

## Example Requests

### Register User

```bash
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Login

```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### Create Warehouse

```bash
curl -X POST http://localhost:8000/api/v1/warehouses \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "code": "WH001",
    "name": "Main Warehouse",
    "location": "123 Main St",
    "description": "Main storage",
    "status": "active"
  }'
```

### List Products

```bash
curl -X GET "http://localhost:8000/api/v1/products?page=1&per_page=15" \
  -H "Authorization: Bearer {token}"
```

### Create Product

```bash
curl -X POST http://localhost:8000/api/v1/products \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "sku": "PRD001",
    "name": "Laptop",
    "cost_price": 500,
    "selling_price": 799.99,
    "category_id": 1,
    "status": "active"
  }'
```

### Create Order

```bash
curl -X POST http://localhost:8000/api/v1/orders \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "order_number": "ORD001",
    "customer_id": 1,
    "warehouse_id": 1,
    "items": [
      {
        "product_id": 1,
        "quantity": 5,
        "unit_price": 799.99
      }
    ]
  }'
```

### Complete Order

```bash
curl -X POST http://localhost:8000/api/v1/orders/1/complete \
  -H "Authorization: Bearer {token}"
```

### Approve Salary

```bash
curl -X POST http://localhost:8000/api/v1/salaries/1/approve \
  -H "Authorization: Bearer {token}"
```

---

## Error Examples

### Validation Error (422)

```json
{
    "status": false,
    "message": "Validation error",
    "errors": {
        "email": ["Email đã tồn tại"],
        "code": ["Mã kho là bắt buộc"]
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

### Unauthorized (401)

```json
{
    "status": false,
    "message": "Unauthenticated",
    "timestamp": "2025-11-17T10:00:00Z"
}
```

### Not Found (404)

```json
{
    "status": false,
    "message": "Resource not found",
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

## Notes

-   All timestamps are in UTC (ISO 8601 format)
-   All monetary values are in the base currency unit
-   Soft deletes are used: deleted resources retain `deleted_at` timestamp
-   All resources track audit columns: `created_by_id`, `updated_by_id`, `deleted_by_id`
-   Pagination default is 15 items per page
-   All endpoints require JWT authentication except `/auth/register` and `/auth/login`

---

**Last Updated:** November 17, 2025
