# ERP API Developer Guide

## Overview

This document provides developers with comprehensive information for integrating with the Retail Supply Chain ERP API.

### System Architecture

-   **Framework:** Laravel 10+
-   **Language:** PHP 8.2
-   **Database:** MySQL
-   **Authentication:** JWT (JSON Web Tokens)
-   **Architecture:** Modular Monolith with 6 modules

### Modules

1. **Core** - Warehouses, Products, Categories, Inventory
2. **Auth** - User authentication and authorization
3. **HRM** - Human Resource Management (Employees, Salaries)
4. **CRM** - Customer Relationship Management
5. **Ecommerce** - Orders and Order Items
6. **CMS** - Content Management System (optional)

---

## Getting Started

### 1. Environment Setup

**Prerequisites:**

-   PHP 8.2+
-   Composer
-   MySQL 5.7+
-   Git

**Installation:**

```bash
# Clone the repository
git clone https://github.com/ptd-seedam/Retail---Supply-Chain-ERP.git
cd Retail---Supply-Chain-ERP/backend

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret

# Run migrations
php artisan migrate:fresh

# Start development server
php artisan serve
```

The API will be available at `http://localhost:8000/api/v1`

### 2. Authentication Flow

**Step 1: Register User**

```bash
POST /auth/register
```

Request:

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "SecurePassword123!",
    "password_confirmation": "SecurePassword123!"
}
```

Response (201):

```json
{
  "status": true,
  "message": "Đăng ký thành công",
  "data": {
    "user": { ... },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "expires_in": 3600
  }
}
```

**Step 2: Login or Use Token**

```bash
POST /auth/login
```

Request:

```json
{
    "email": "john@example.com",
    "password": "SecurePassword123!"
}
```

**Step 3: Use Token in Requests**
All subsequent requests must include the Authorization header:

```
Authorization: Bearer {token}
```

**Step 4: Refresh Token (Optional)**

```bash
POST /auth/refresh
```

Returns a new token with reset expiration time.

---

## API Patterns & Conventions

### Request/Response Format

#### Standard Success Response

```json
{
  "status": true,
  "message": "Operation successful",
  "data": {
    "resource": { ... }
  },
  "timestamp": "2025-11-17T10:00:00Z"
}
```

#### Paginated Response

```json
{
  "status": true,
  "message": "List retrieved",
  "data": {
    "items": [ ... ],
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

#### Error Response

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

### HTTP Methods & Status Codes

| Method | Purpose          | Status Code |
| ------ | ---------------- | ----------- |
| GET    | Retrieve data    | 200         |
| POST   | Create resource  | 201         |
| PUT    | Replace resource | 200         |
| PATCH  | Partial update   | 200         |
| DELETE | Remove resource  | 200         |

### Pagination

All list endpoints support pagination:

```bash
GET /api/v1/products?page=2&per_page=20
```

Query Parameters:

-   `page` (integer): Page number (default: 1)
-   `per_page` (integer): Items per page (default: 15, max: 100)

---

## Module-Specific Details

### Core Module

#### Warehouse Management

-   **Endpoints:** `/warehouses`, `/warehouses/{id}`
-   **Features:** CRUD operations, status tracking, location management
-   **Required Fields:** `code`, `name`
-   **Optional Fields:** `location`, `description`, `status`

#### Product Management

-   **Endpoints:** `/products`, `/products/{id}`
-   **Features:** SKU/Barcode tracking, pricing, category association
-   **Required Fields:** `sku`, `name`, `cost_price`, `selling_price`
-   **Optional Fields:** `barcode`, `category_id`, `reorder_level`, `description`, `status`
-   **Special Endpoints:**
    -   `GET /products/category/{categoryId}` - Products by category
    -   `GET /products-active` - Active products only

#### Category Management

-   **Endpoints:** `/categories`, `/categories/{id}`
-   **Features:** Hierarchical categories, sort order
-   **Required Fields:** `name`
-   **Optional Fields:** `description`, `parent_id`, `sort_order`, `status`
-   **Special Endpoints:**
    -   `GET /categories/tree` - Full category tree structure
    -   `GET /categories/{parentId}/children` - Child categories
    -   `GET /categories-active` - Active categories only

### HRM Module

#### Employee Management

-   **Endpoints:** `/employees`, `/employees/{id}`
-   **Features:** Employee information, department/shift assignment, hire date tracking
-   **Required Fields:** `user_id`, `employee_code`
-   **Optional Fields:** `department_id`, `shift_id`, `phone`, `address`, `hire_date`, `status`
-   **Status Values:** `active`, `inactive`, `on_leave`
-   **Special Endpoints:**
    -   `GET /employees/department/{departmentId}` - By department
    -   `GET /employees/shift/{shiftId}` - By shift
    -   `GET /employees-active` - Active only
    -   `GET /employees-on-leave` - On-leave only

#### Salary Management

-   **Endpoints:** `/salaries`, `/salaries/{id}`
-   **Features:** Monthly salary tracking, bonus/deductions, approval workflow
-   **Required Fields:** `employee_id`, `year`, `month`, `base_salary`
-   **Optional Fields:** `bonus`, `deductions`, `status`, `payment_date`
-   **Status Workflow:** `pending` → `approved` → `paid`
-   **Special Endpoints:**
    -   `POST /salaries/{id}/approve` - Approve salary
    -   `POST /salaries/{id}/mark-paid` - Mark as paid
    -   `GET /salaries-pending` - Pending salaries
    -   `GET /salaries/employee/{employeeId}` - Employee salaries
    -   `GET /salaries/{year}/{month}` - By month

### CRM Module

#### Customer Management

-   **Endpoints:** `/customers`, `/customers/{id}`
-   **Features:** Customer info, group classification, credit limits, balance tracking
-   **Required Fields:** `code`, `name`
-   **Optional Fields:** `email`, `phone`, `address`, `group_id`, `credit_limit`, `status`
-   **Status Values:** `active`, `inactive`, `suspended`
-   **Special Endpoints:**
    -   `GET /customers/group/{groupId}` - By group
    -   `GET /customers-active` - Active only
    -   `GET /customers-suspended` - Suspended only
    -   `GET /customers-high-value` - High-value customers

### Ecommerce Module

#### Order Management

-   **Endpoints:** `/orders`, `/orders/{id}`
-   **Features:** Order creation with items, status tracking, discount/tax handling
-   **Required Fields:** `order_number`, `items` (array with min 1)
-   **Optional Fields:** `customer_id`, `warehouse_id`, `discount_amount`, `tax_amount`, `promotion_id`, `notes`, `status`
-   **Status Values:** `pending`, `processing`, `completed`, `cancelled`, `refunded`
-   **Item Fields:**
    -   `product_id` (required, exists:products)
    -   `quantity` (required, integer >= 1)
    -   `unit_price` (required, numeric >= 0)
-   **Special Endpoints:**
    -   `POST /orders/{id}/complete` - Mark complete with timestamp
    -   `POST /orders/{id}/cancel` - Cancel order
    -   `GET /orders/{id}/calculate-total` - Calculate order total
    -   `GET /orders/status/{status}` - By status
    -   `GET /orders/customer/{customerId}` - By customer
    -   `GET /orders-pending` - Pending only

#### Order Item Management

-   **Endpoints:** `/orders/{orderId}/items`, `/items/{id}`
-   **Features:** Nested resource under orders, quantity/price tracking
-   **Create/Update:** `POST /orders/{orderId}/items`, `PUT /items/{id}`
-   **Delete:** `DELETE /items/{id}`

---

## Data Models & Relationships

### Core Models

```
Warehouse
├── has many Products (via category)
└── has many InventoryTransactions

Category
├── has parent Category (nullable)
├── has many Products
└── has many child Categories

Product
├── belongs to Category
└── has many OrderItems

InventoryTransaction
├── belongs to Warehouse
├── belongs to Product
└── tracked by type (in/out/transfer)
```

### HRM Models

```
Employee
├── belongs to User
├── belongs to Department
├── belongs to Shift
└── has many Salaries

Salary
├── belongs to Employee
└── tracked by year/month (unique constraint)
```

### CRM Models

```
Customer
├── belongs to CustomerGroup
└── has many LoyaltyPoints

LoyaltyPoint
├── belongs to Customer
└── tracks by type (earn/redeem)
```

### Ecommerce Models

```
Order
├── belongs to Customer (nullable)
├── belongs to Warehouse (nullable)
├── belongs to Promotion (nullable)
└── has many OrderItems (cascade delete)

OrderItem
├── belongs to Order
└── belongs to Product
```

---

## Audit Trail & Soft Deletes

All resources include audit columns:

-   `created_by_id` - User who created the record
-   `updated_by_id` - User who last updated the record
-   `deleted_by_id` - User who deleted the record
-   `created_at` - Creation timestamp
-   `updated_at` - Last update timestamp
-   `deleted_at` - Deletion timestamp (soft delete)

**Important:** Soft-deleted records are excluded from queries by default. To include them:

```php
Model::withTrashed()->get();
```

---

## Error Handling

### HTTP Status Codes

| Code | Scenario                           | Example                             |
| ---- | ---------------------------------- | ----------------------------------- |
| 200  | Successful GET, PUT, PATCH, DELETE | Retrieve, update, delete operations |
| 201  | Successful POST                    | Resource created                    |
| 400  | Malformed request                  | Invalid JSON, missing headers       |
| 401  | Unauthorized                       | Missing/invalid token               |
| 403  | Forbidden                          | Insufficient permissions            |
| 404  | Not found                          | Resource doesn't exist              |
| 422  | Validation error                   | Invalid input data                  |
| 500  | Server error                       | Unexpected application error        |

### Validation Error Example

```json
{
    "status": false,
    "message": "Validation error",
    "errors": {
        "email": ["Email đã tồn tại", "Email phải là địa chỉ email hợp lệ"],
        "phone": ["Số điện thoại không hợp lệ"]
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

### Error Messages (Vietnamese)

All validation errors are returned in Vietnamese. Common messages:

-   `"Trường này là bắt buộc"` - Field is required
-   `"Trường này đã tồn tại"` - Value already exists (uniqueness violation)
-   `"Không tồn tại"` - Referenced resource doesn't exist
-   `"Không hợp lệ"` - Invalid value

---

## Advanced Features

### Bulk Operations

For creating multiple items at once, use nested arrays:

```bash
POST /orders

{
  "order_number": "ORD001",
  "customer_id": 1,
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
    },
    {
      "product_id": 3,
      "quantity": 10,
      "unit_price": 99.99
    }
  ]
}
```

### Nested Filtering

Query for orders from a specific customer:

```bash
GET /orders/customer/1
```

Query for products in a category:

```bash
GET /products/category/5
```

### Status Transitions

Some endpoints enforce valid state transitions:

**Salary Workflow:**

```
pending --[approve]--> approved --[mark-paid]--> paid
```

**Order Workflow:**

```
pending --> processing --> completed
                      \--> cancelled
                      \--> refunded
```

---

## Client Implementation Examples

### JavaScript/Node.js (Axios)

```javascript
import axios from "axios";

const API_URL = "http://localhost:8000/api/v1";

// Create client with base URL
const api = axios.create({
    baseURL: API_URL,
    headers: {
        "Content-Type": "application/json",
    },
});

// Add token to requests
api.interceptors.request.use((config) => {
    const token = localStorage.getItem("token");
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Register
async function register(name, email, password) {
    try {
        const response = await api.post("/auth/register", {
            name,
            email,
            password,
            password_confirmation: password,
        });
        localStorage.setItem("token", response.data.data.token);
        return response.data;
    } catch (error) {
        console.error("Registration failed:", error.response.data);
        throw error;
    }
}

// Get products
async function getProducts(page = 1) {
    try {
        const response = await api.get("/products", {
            params: { page, per_page: 15 },
        });
        return response.data;
    } catch (error) {
        console.error("Failed to fetch products:", error.response.data);
        throw error;
    }
}

// Create order
async function createOrder(orderData) {
    try {
        const response = await api.post("/orders", orderData);
        return response.data;
    } catch (error) {
        console.error("Failed to create order:", error.response.data);
        throw error;
    }
}
```

### Python (Requests)

```python
import requests
import json

API_URL = 'http://localhost:8000/api/v1'

class ERPClient:
    def __init__(self):
        self.session = requests.Session()
        self.session.headers.update({
            'Content-Type': 'application/json'
        })
        self.token = None

    def register(self, name, email, password):
        response = self.session.post(
            f'{API_URL}/auth/register',
            json={
                'name': name,
                'email': email,
                'password': password,
                'password_confirmation': password
            }
        )
        if response.status_code == 201:
            self.token = response.json()['data']['token']
            self._update_headers()
        return response.json()

    def _update_headers(self):
        if self.token:
            self.session.headers.update({
                'Authorization': f'Bearer {self.token}'
            })

    def get_products(self, page=1, per_page=15):
        response = self.session.get(
            f'{API_URL}/products',
            params={'page': page, 'per_page': per_page}
        )
        return response.json()

    def create_order(self, order_data):
        response = self.session.post(
            f'{API_URL}/orders',
            json=order_data
        )
        return response.json()

# Usage
client = ERPClient()
client.register('John Doe', 'john@example.com', 'password123')
products = client.get_products()
print(json.dumps(products, indent=2))
```

---

## Rate Limiting & Performance

Currently, there are no rate limits implemented. For production:

-   Implement rate limiting via middleware
-   Cache frequently accessed data
-   Use pagination for large datasets
-   Consider implementing GraphQL for flexible queries

---

## Security Best Practices

1. **Always use HTTPS in production**
2. **Store tokens securely** (not in localStorage for sensitive data)
3. **Validate all inputs** on client and server
4. **Use environment variables** for configuration
5. **Implement CORS** properly for frontend domains
6. **Keep dependencies updated** regularly
7. **Use strong passwords** (minimum 8 characters, mixed case, numbers, symbols)
8. **Implement role-based access control** (RBAC) as needed
9. **Log all API access** for audit trails
10. **Use API keys** for third-party integrations

---

## Testing

### Manual Testing with cURL

```bash
# Register
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John","email":"john@example.com","password":"pass123","password_confirmation":"pass123"}'

# Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@example.com","password":"pass123"}'

# List products
curl -X GET http://localhost:8000/api/v1/products \
  -H "Authorization: Bearer {token}"

# Create warehouse
curl -X POST http://localhost:8000/api/v1/warehouses \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"code":"WH001","name":"Main Warehouse"}'
```

### Postman Collection

A complete Postman collection is available in `Postman_Collection.json`. To use it:

1. Open Postman
2. Click "Import"
3. Select the collection file
4. Set the `base_url` and `token` environment variables
5. Start testing

---

## Support & Documentation

-   **API Documentation:** See `API_DOCUMENTATION.md` for detailed endpoint documentation
-   **Quick Reference:** See `API_QUICK_REFERENCE.md` for quick lookup
-   **OpenAPI Spec:** See `openapi.json` for API specification
-   **GitHub Issues:** Report bugs and request features on the project repository

---

## Version History

| Version | Release Date | Changes                                                |
| ------- | ------------ | ------------------------------------------------------ |
| 1.0.0   | 2025-11-17   | Initial release with Core, HRM, CRM, Ecommerce modules |

---

**Last Updated:** November 17, 2025
