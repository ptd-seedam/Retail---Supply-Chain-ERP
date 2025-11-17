# Retail Supply Chain ERP - API Documentation

## 📖 Documentation Overview

Complete API documentation for the Retail & Supply Chain ERP system with comprehensive guides, quick references, and ready-to-use testing tools.

---

## 📑 Documentation Files

### Main Documentation Files

| File                        | Purpose                                        | Best For                         |
| --------------------------- | ---------------------------------------------- | -------------------------------- |
| **API_DOCUMENTATION.md**    | Complete API reference with all endpoints      | Detailed specification lookup    |
| **API_QUICK_REFERENCE.md**  | Quick reference guide with tables and examples | Fast endpoint lookups            |
| **DEVELOPER_GUIDE.md**      | Comprehensive developer guide                  | Getting started & implementation |
| **API_DOCS_SUMMARY.md**     | Documentation summary                          | Overview & navigation            |
| **openapi.json**            | OpenAPI 3.0 specification                      | Swagger UI / API tools           |
| **Postman_Collection.json** | Postman API collection                         | Testing & API exploration        |

---

## 🚀 Quick Navigation

### I want to...

#### **Test the API**

→ Use **Postman_Collection.json**

-   Import into Postman
-   Set environment variables (base_url, token)
-   Start testing immediately

#### **Find a specific endpoint**

→ Check **API_QUICK_REFERENCE.md**

-   Quick endpoint tables
-   Sample requests
-   cURL examples

#### **Learn how the API works**

→ Read **DEVELOPER_GUIDE.md**

-   Setup instructions
-   Authentication flow
-   Code examples
-   Best practices

#### **Get detailed specifications**

→ Reference **API_DOCUMENTATION.md**

-   Complete endpoint documentation
-   Validation rules
-   Request/response examples
-   Error handling

#### **Integrate with my app**

→ Follow **DEVELOPER_GUIDE.md**

-   Client implementation examples
-   Security guidelines
-   Testing advice
-   Troubleshooting

#### **View API structure**

→ Check **openapi.json**

-   OpenAPI 3.0 specification
-   Full endpoint paths
-   Request/response schemas

---

## 📋 API Summary

### Total Endpoints

-   **60+ API endpoints** across 6 modules
-   **5 Authentication endpoints**
-   **20 Core module endpoints**
-   **14 HRM module endpoints**
-   **8 CRM module endpoints**
-   **17 Ecommerce module endpoints**

### Modules Covered

1. **Core** - Warehouses, Products, Categories, Inventory
2. **Auth** - User registration, login, token management
3. **HRM** - Employees, Salaries, Departments
4. **CRM** - Customers, Groups, Loyalty
5. **Ecommerce** - Orders, Order Items, Promotions
6. **CMS** - Banners, News (optional)

---

## 🔐 Authentication

**Method:** JWT (JSON Web Tokens)

**Quick Start:**

```bash
# Register or Login
POST /auth/register
POST /auth/login

# Use token in headers
Authorization: Bearer {token}

# Refresh when needed
POST /auth/refresh
```

---

## 📊 Response Format

All API responses follow a standardized format:

### Success Response

```json
{
    "status": true,
    "message": "Operation message",
    "data": {
        /* resource */
    },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

### Error Response

```json
{
    "status": false,
    "message": "Error message",
    "errors": { "field": ["Error description"] },
    "timestamp": "2025-11-17T10:00:00Z"
}
```

---

## 🛠️ Technology Stack

-   **Framework:** Laravel 10+
-   **Language:** PHP 8.2
-   **Database:** MySQL
-   **Authentication:** JWT
-   **Architecture:** Modular Monolith

---

## 📚 Getting Started

### Step 1: Set Up Environment

```bash
composer install
php artisan migrate
php artisan jwt:secret
php artisan serve
```

### Step 2: Register User

```bash
POST /api/v1/auth/register
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### Step 3: Get Token

Response includes JWT token with `expires_in: 3600`

### Step 4: Use Token

```bash
Authorization: Bearer {token}
GET /api/v1/products
```

---

## 📖 Reading Guide

### For First-Time Users

1. Read **API_DOCS_SUMMARY.md** (this file)
2. Check **DEVELOPER_GUIDE.md** - Getting Started section
3. Try **Postman_Collection.json** for hands-on testing
4. Reference **API_QUICK_REFERENCE.md** as needed

### For Developers

1. Read **DEVELOPER_GUIDE.md** completely
2. Reference **API_DOCUMENTATION.md** for specifications
3. Use **Postman_Collection.json** for testing
4. Check code examples in **DEVELOPER_GUIDE.md**

### For API Integration

1. Start with **DEVELOPER_GUIDE.md** - Client Examples section
2. Check **API_QUICK_REFERENCE.md** for endpoint reference
3. Use **Postman_Collection.json** to test endpoints first
4. Follow security guidelines in **DEVELOPER_GUIDE.md**

---

## ✨ Key Features

-   ✅ **Complete REST API** with 60+ endpoints
-   ✅ **JWT Authentication** with token refresh
-   ✅ **Standardized Response Format** for all endpoints
-   ✅ **Comprehensive Validation** with Vietnamese error messages
-   ✅ **Soft Delete Support** with audit trails
-   ✅ **Pagination** on all list endpoints
-   ✅ **Nested Resources** (Orders with Items)
-   ✅ **Advanced Endpoints** (Approve, Complete, Cancel, etc.)
-   ✅ **Complete Documentation** (6 detailed guides)
-   ✅ **Postman Collection** for easy testing

---

## 🔍 Quick Reference Tables

### HTTP Methods

| Method | Purpose         | Status |
| ------ | --------------- | ------ |
| GET    | Retrieve data   | 200    |
| POST   | Create resource | 201    |
| PUT    | Update resource | 200    |
| DELETE | Delete resource | 200    |

### Status Codes

| Code | Meaning          |
| ---- | ---------------- |
| 200  | OK               |
| 201  | Created          |
| 400  | Bad Request      |
| 401  | Unauthorized     |
| 404  | Not Found        |
| 422  | Validation Error |
| 500  | Server Error     |

### Core Endpoints

```
/warehouses          (CRUD + active list)
/products            (CRUD + category filter + active list)
/categories          (CRUD + tree view + children)
/employees           (CRUD + department/shift filters)
/salaries            (CRUD + approve + mark-paid)
/customers           (CRUD + group filter + status filters)
/orders              (CRUD + complete + cancel + calculate)
/items               (Nested under orders)
```

---

## 🧪 Testing

### Option 1: Postman

1. Import `Postman_Collection.json` into Postman
2. Set environment variables
3. Start testing

### Option 2: cURL

```bash
curl -X GET http://localhost:8000/api/v1/products \
  -H "Authorization: Bearer {token}"
```

### Option 3: Swagger UI

Open `openapi.json` in Swagger UI for interactive documentation

---

## 📋 Checklists

### Before Integration

-   [ ] Read DEVELOPER_GUIDE.md
-   [ ] Test with Postman_Collection.json
-   [ ] Review API_DOCUMENTATION.md
-   [ ] Check error handling in code
-   [ ] Implement token refresh logic
-   [ ] Set up error logging

### During Development

-   [ ] Follow security guidelines
-   [ ] Validate all inputs
-   [ ] Handle errors properly
-   [ ] Use pagination for large lists
-   [ ] Test edge cases
-   [ ] Document your API usage

### Before Production

-   [ ] Use HTTPS only
-   [ ] Implement rate limiting
-   [ ] Set up monitoring
-   [ ] Enable CORS properly
-   [ ] Review error messages
-   [ ] Test all workflows

---

## 🆘 Troubleshooting

### API Returns 401 Unauthorized

-   Check token is included in Authorization header
-   Verify token format: `Bearer {token}`
-   Ensure token is not expired
-   Try refreshing token: `POST /auth/refresh`

### API Returns 422 Validation Error

-   Check all required fields are present
-   Verify field types (string, integer, etc.)
-   Check field constraints (max length, min value)
-   Review Vietnamese error messages

### API Returns 404 Not Found

-   Verify resource ID exists
-   Check endpoint URL spelling
-   Ensure you're using correct HTTP method
-   Verify resource hasn't been deleted

### API Returns 500 Server Error

-   Check server logs
-   Verify database connection
-   Ensure all dependencies are installed
-   Try restarting the server

---

## 📞 Support & Help

### Documentation Questions

-   Check **API_DOCUMENTATION.md** for endpoint details
-   Review **DEVELOPER_GUIDE.md** for implementation help
-   Consult **API_QUICK_REFERENCE.md** for quick answers

### Testing Issues

-   Use **Postman_Collection.json** for reference requests
-   Try cURL examples from **API_QUICK_REFERENCE.md**
-   Check error responses for validation details

### Integration Help

-   Review code examples in **DEVELOPER_GUIDE.md**
-   Check security guidelines
-   Verify authentication flow
-   Test with Postman first

---

## 📄 File Structure

```
backend/
├── API_DOCUMENTATION.md          (Complete endpoint documentation)
├── API_QUICK_REFERENCE.md        (Quick lookup guide)
├── DEVELOPER_GUIDE.md            (Comprehensive developer guide)
├── API_DOCS_SUMMARY.md           (This file)
├── openapi.json                  (OpenAPI 3.0 specification)
├── Postman_Collection.json       (Postman collection)
├── Modules/
│   ├── Core/                     (Warehouse, Product, Category)
│   ├── Auth/                     (Authentication)
│   ├── HRM/                      (Employee, Salary)
│   ├── CRM/                      (Customer)
│   ├── Ecommerce/                (Order, OrderItem)
│   └── CMS/                      (Banner, News)
├── app/
│   ├── Models/
│   └── Http/
│       ├── Controllers/
│       └── Requests/
└── database/
    ├── migrations/
    └── seeders/
```

---

## ✅ Documentation Checklist

-   ✅ Complete endpoint reference
-   ✅ Quick reference guide
-   ✅ Developer setup guide
-   ✅ Code examples (JavaScript, Python)
-   ✅ Authentication guide
-   ✅ Error handling guide
-   ✅ Security guidelines
-   ✅ Testing guide
-   ✅ Postman collection
-   ✅ OpenAPI specification

---

## 🎯 Next Steps

1. **Choose your use case** from the navigation above
2. **Read the appropriate documentation file**
3. **Test with Postman Collection**
4. **Implement in your application**
5. **Follow security guidelines**
6. **Deploy with confidence**

---

## 📅 Documentation Info

-   **Created:** November 17, 2025
-   **Version:** 1.0.0
-   **Status:** Complete
-   **Last Updated:** November 17, 2025

---

## 📝 Document List

### Primary Documentation

1. **API_DOCUMENTATION.md** - 500+ lines, comprehensive endpoint reference
2. **API_QUICK_REFERENCE.md** - 400+ lines, quick lookup tables and examples
3. **DEVELOPER_GUIDE.md** - 800+ lines, complete developer guide with code examples
4. **API_DOCS_SUMMARY.md** - Summary of all documentation

### Reference Files

5. **openapi.json** - OpenAPI 3.0 specification for API tools
6. **Postman_Collection.json** - Pre-configured Postman collection for testing
7. **README_API_DOCS.md** - This navigation guide

---

**Happy API Development! 🚀**
