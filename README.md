# Warehouse Management REST API

A RESTful API for managing warehouse operations, built with **Laravel 12**. The project focuses on clean API architecture, authentication, role-based authorization, resource transformation, validation, database relationships, and realistic warehouse stock management.

> **Status:** Version 1 — Completed

## Tech Stack

* **Laravel 12**
* **PHP 8.2+**
* **Laravel Sanctum**
* **MySQL**
* **REST API**
* **Postman**

## Features

### Authentication

* User registration
* User login
* User logout
* Authenticated user profile
* Laravel Sanctum token authentication
* Secure password hashing
* JSON API authentication responses

### Role-Based Authorization

The API uses three user roles:

| Role       | Description                  |
| ---------- | ---------------------------- |
| **Admin**  | Full system access           |
| **Staff**  | Warehouse operational access |
| **Viewer** | Read-only access             |

New users registered through the API are assigned the `viewer` role by default.

Users cannot assign themselves a different role during registration.

### Category Management

* List categories
* View category details
* Create category
* Update category
* Delete category
* Request validation
* API Resource transformation
* Role-based authorization

#### Category Permissions

| Action          | Admin | Staff | Viewer |
| --------------- | :---: | :---: | :----: |
| View categories |  Yes  |  Yes  |   Yes  |
| Create category |  Yes  |  Yes  |   No   |
| Update category |  Yes  |  Yes  |   No   |
| Delete category |  Yes  |   No  |   No   |

### Product Management

* List products
* View product details
* Create product
* Update product
* Delete product
* Category relationship
* Supplier relationship
* Request validation
* API Resource transformation
* Role-based authorization

### Supplier Management

* List suppliers
* View supplier details
* Create supplier
* Update supplier
* Delete supplier
* Request validation
* API Resource transformation
* Role-based authorization

### Stock Management

The stock management module handles warehouse inventory movements.

#### Stock In

Adds stock to a product and records the movement history.

```http
POST /api/stock-movements/in
```

#### Stock Out

Removes stock from a product while preventing the stock quantity from becoming negative.

```http
POST /api/stock-movements/out
```

#### Stock Adjustment

Adjusts the product stock to a specific final quantity.

```http
POST /api/stock-movements/adjustment
```

#### Stock Movement History

Provides a paginated history of stock movements.

```http
GET /api/stock-movements
```

Each stock movement records:

* Product
* User who performed the operation
* Movement type
* Quantity
* Stock before
* Stock after
* Note
* Timestamp

Stock operations use database transactions and row locking to maintain data consistency during concurrent requests.

## API Endpoints

### Authentication

| Method | Endpoint        | Description            | Authentication |
| ------ | --------------- | ---------------------- | -------------- |
| `POST` | `/api/register` | Register a new user    | Public         |
| `POST` | `/api/login`    | Authenticate user      | Public         |
| `POST` | `/api/logout`   | Revoke current token   | Required       |
| `GET`  | `/api/me`       | Get authenticated user | Required       |

### Categories

| Method   | Endpoint               | Description          | Roles                |
| -------- | ---------------------- | -------------------- | -------------------- |
| `GET`    | `/api/categories`      | Get all categories   | Admin, Staff, Viewer |
| `GET`    | `/api/categories/{id}` | Get category details | Admin, Staff, Viewer |
| `POST`   | `/api/categories`      | Create category      | Admin, Staff         |
| `PUT`    | `/api/categories/{id}` | Update category      | Admin, Staff         |
| `PATCH`  | `/api/categories/{id}` | Update category      | Admin, Staff         |
| `DELETE` | `/api/categories/{id}` | Delete category      | Admin                |

### Products

| Method   | Endpoint             | Description         | Roles                |
| -------- | -------------------- | ------------------- | -------------------- |
| `GET`    | `/api/products`      | Get all products    | Admin, Staff, Viewer |
| `GET`    | `/api/products/{id}` | Get product details | Admin, Staff, Viewer |
| `POST`   | `/api/products`      | Create product      | Admin, Staff         |
| `PUT`    | `/api/products/{id}` | Update product      | Admin, Staff         |
| `PATCH`  | `/api/products/{id}` | Update product      | Admin, Staff         |
| `DELETE` | `/api/products/{id}` | Delete product      | Admin, Staff         |

### Suppliers

| Method   | Endpoint              | Description          | Roles                |
| -------- | --------------------- | -------------------- | -------------------- |
| `GET`    | `/api/suppliers`      | Get all suppliers    | Admin, Staff, Viewer |
| `GET`    | `/api/suppliers/{id}` | Get supplier details | Admin, Staff, Viewer |
| `POST`   | `/api/suppliers`      | Create supplier      | Admin, Staff         |
| `PUT`    | `/api/suppliers/{id}` | Update supplier      | Admin, Staff         |
| `PATCH`  | `/api/suppliers/{id}` | Update supplier      | Admin, Staff         |
| `DELETE` | `/api/suppliers/{id}` | Delete supplier      | Admin, Staff         |

### Stock Management

| Method | Endpoint                          | Description                | Roles                |
| ------ | --------------------------------- | -------------------------- | -------------------- |
| `GET`  | `/api/stock-movements`            | Get stock movement history | Admin, Staff, Viewer |
| `POST` | `/api/stock-movements/in`         | Add stock                  | Admin, Staff         |
| `POST` | `/api/stock-movements/out`        | Remove stock               | Admin, Staff         |
| `POST` | `/api/stock-movements/adjustment` | Adjust stock               | Admin, Staff         |

## Authentication

This API uses **Laravel Sanctum** for token-based authentication.

After a successful login, the API returns an access token:

```json
{
    "success": true,
    "message": "Login successful.",
    "data": {
        "user": {
            "id": 1,
            "name": "Admin Warehouse",
            "email": "admin@warehouse.test",
            "role": "admin"
        },
        "token": "YOUR_ACCESS_TOKEN"
    }
}
```

The token must be included in protected requests:

```http
Authorization: Bearer YOUR_ACCESS_TOKEN
Accept: application/json
```

## Registration

### Request

```http
POST /api/register
Content-Type: application/json
Accept: application/json
```

```json
{
    "name": "Warehouse User",
    "email": "user@warehouse.test",
    "password": "password",
    "password_confirmation": "password"
}
```

A newly registered user automatically receives the `viewer` role.

Registration does **not** issue an authentication token. Users must log in separately to obtain an access token.

## Login

### Request

```http
POST /api/login
Content-Type: application/json
Accept: application/json
```

```json
{
    "email": "user@warehouse.test",
    "password": "password"
}
```

A successful login returns a Sanctum access token.

## Stock Management

### Stock In

```http
POST /api/stock-movements/in
Authorization: Bearer YOUR_ACCESS_TOKEN
Content-Type: application/json
Accept: application/json
```

Request body:

```json
{
    "product_id": 1,
    "quantity": 10,
    "note": "Initial stock"
}
```

The operation increases the current product stock and records the stock movement.

Example:

```text
Stock Before: 0
Quantity:     10
Stock After:  10
```

### Stock Out

```http
POST /api/stock-movements/out
Authorization: Bearer YOUR_ACCESS_TOKEN
Content-Type: application/json
Accept: application/json
```

Request body:

```json
{
    "product_id": 1,
    "quantity": 3,
    "note": "Product sold"
}
```

The API prevents stock from becoming negative.

If the requested quantity exceeds the available stock, the API returns:

```text
422 Unprocessable Entity
```

with an `Insufficient stock` error.

### Stock Adjustment

```http
POST /api/stock-movements/adjustment
Authorization: Bearer YOUR_ACCESS_TOKEN
Content-Type: application/json
Accept: application/json
```

Request body:

```json
{
    "product_id": 1,
    "quantity": 5,
    "note": "Physical stock count"
}
```

The `quantity` represents the **final stock quantity**, not the amount to add or subtract.

Example:

```text
Current Stock: 7
Adjustment:    5
Final Stock:   5
```

### Stock History

```http
GET /api/stock-movements
Authorization: Bearer YOUR_ACCESS_TOKEN
Accept: application/json
```

The endpoint returns paginated stock movement records.

Example movement history:

```text
ID   TYPE          QTY   BEFORE   AFTER
1    in            10      0       10
2    out            3      10        7
3    adjustment    5       7        5
```

## Validation

The API uses Laravel's built-in validation system.

Example validation response:

```json
{
    "success": false,
    "message": "Validation error.",
    "errors": {
        "name": [
            "The name field is required."
        ]
    }
}
```

HTTP status:

```text
422 Unprocessable Entity
```

Stock operations also validate available inventory before processing stock-out requests.

## HTTP Status Codes

| Status Code | Meaning               |
| ----------: | --------------------- |
|       `200` | Request successful    |
|       `201` | Resource created      |
|       `401` | Unauthenticated       |
|       `403` | Forbidden             |
|       `404` | Resource not found    |
|       `422` | Validation error      |
|       `500` | Internal server error |

## Architecture

The project follows Laravel's standard application structure with separation between controllers, middleware, resources, models, and routes.

```text
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── AuthController.php
│   │       ├── CategoryController.php
│   │       ├── ProductController.php
│   │       ├── SupplierController.php
│   │       └── StockMovementController.php
│   │
│   ├── Middleware/
│   │   └── RoleMiddleware.php
│   │
│   └── Resources/
│       ├── CategoryResource.php
│       ├── ProductResource.php
│       ├── SupplierResource.php
│       └── StockMovementResource.php
│
├── Models/
│   ├── User.php
│   ├── Category.php
│   ├── Product.php
│   ├── Supplier.php
│   └── StockMovement.php
│
database/
├── migrations/
└── seeders/
│
routes/
└── api.php
```

## Database Relationships

The current database design represents the core warehouse management domain:

```text
Category
   │
   └── hasMany
          │
          ▼
       Product
          │
          ├── belongsTo Supplier
          │
          └── hasMany StockMovement
                         │
                         └── belongsTo User
```

### Main Entities

```text
User
 └── Stock Movements

Category
 └── Products

Supplier
 └── Products

Product
 └── Stock Movements

StockMovement
 ├── Product
 └── User
```

## Data Consistency

Stock operations use database transactions to ensure that product stock and stock movement history remain synchronized.

The stock management implementation also uses row-level locking when updating product stock:

```php
Product::lockForUpdate()
```

This helps prevent inconsistent stock values when multiple stock operations are processed concurrently.

## Testing

API endpoints are tested using **Postman**.

Current testing coverage includes:

* Authentication
* Sanctum token authentication
* Role-based authorization
* Category CRUD
* Product CRUD
* Supplier CRUD
* Stock In
* Stock Out
* Stock Adjustment
* Stock movement history
* Insufficient stock validation
* Request validation
* HTTP status codes
* Protected API endpoints

All core V1 features have been manually tested and verified through Postman.

## Development Progress

### Version 1 — Completed

* [x] Laravel project setup
* [x] REST API structure
* [x] Laravel Sanctum authentication
* [x] User registration
* [x] User login
* [x] User logout
* [x] Authenticated user endpoint
* [x] User roles
* [x] Role middleware
* [x] Category CRUD
* [x] Category API Resource
* [x] Category validation
* [x] Category authorization
* [x] Product CRUD
* [x] Product API Resource
* [x] Product validation
* [x] Product authorization
* [x] Supplier CRUD
* [x] Supplier API Resource
* [x] Supplier validation
* [x] Supplier authorization
* [x] Stock In
* [x] Stock Out
* [x] Stock Adjustment
* [x] Stock movement history
* [x] Stock validation
* [x] Stock transactions
* [x] Stock row locking
* [x] Stock API Resource
* [x] Postman testing

## Future Development

Version 1 is considered complete.

Potential features for future versions may include:

* Purchase Order Management
* Warehouse Management
* Multi-Warehouse Inventory
* Stock Transfer
* Low Stock Alerts
* Inventory Reports
* Advanced Search and Filtering
* Dashboard Statistics
* User Management
* Activity Logs
* API Documentation
* Automated Feature Tests

## Installation

Clone the repository:

```bash
git clone <repository-url>
```

Navigate to the project directory:

```bash
cd Warehouse-Management
```

Install PHP dependencies:

```bash
composer install
```

Create the environment file:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

Configure the database credentials in `.env`.

Run database migrations:

```bash
php artisan migrate
```

Run database seeders:

```bash
php artisan db:seed
```

Start the development server:

```bash
php artisan serve
```

The API will be available at:

```text
http://127.0.0.1:8000/api
```

## Environment Configuration

Example database configuration:

```env
APP_NAME="Warehouse Management API"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=warehouse_management
DB_USERNAME=root
DB_PASSWORD=
```

Never commit the `.env` file or expose sensitive credentials in the repository.

## Project Goals

This project is designed to demonstrate practical implementation of a warehouse management backend using a modern Laravel REST API architecture.

The main goals are:

* Build a structured and maintainable REST API
* Implement secure authentication and authorization
* Apply role-based access control
* Design relational database structures
* Implement reusable API Resources
* Apply proper request validation
* Develop realistic warehouse business logic
* Maintain consistent inventory data
* Provide a strong foundation for future frontend integration

## Author

**Muhammad Syafi'i**

Full Stack Web Developer

Specializing in:

* Laravel
* React
* REST API Development
* Database-Driven Applications
* Full Stack Web Development
