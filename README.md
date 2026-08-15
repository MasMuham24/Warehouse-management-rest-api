# Warehouse Management REST API

A RESTful API for managing warehouse operations, built with **Laravel 12**. The project focuses on clean API architecture, authentication, role-based authorization, resource transformation, validation, and scalable database relationships.

> **Status:** Version 1 — In Development

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

The Category module currently provides:

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

## Category API

### Create Category

```http
POST /api/categories
Authorization: Bearer YOUR_ACCESS_TOKEN
Content-Type: application/json
Accept: application/json
```

Request body:

```json
{
    "name": "Electronics",
    "description": "Electronic equipment stored in the warehouse"
}
```

### Response

```json
{
    "success": true,
    "message": "Category created successfully.",
    "data": {
        "id": 1,
        "name": "Electronics",
        "description": "Electronic equipment stored in the warehouse",
        "created_at": "2026-08-15T07:00:00.000000Z",
        "updated_at": "2026-08-15T07:00:00.000000Z"
    }
}
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

The project follows Laravel's standard application structure with a clear separation between controllers, middleware, resources, models, and routes.

```text
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── AuthController.php
│   │       └── CategoryController.php
│   │
│   ├── Middleware/
│   │   └── RoleMiddleware.php
│   │
│   └── Resources/
│       └── CategoryResource.php
│
├── Models/
│   ├── User.php
│   └── Category.php
│
database/
├── migrations/
└── seeders/
│
routes/
└── api.php
```

## Database Relationships

The current database design is prepared for the warehouse management domain.

Current relationship:

```text
Category
   │
   └── hasMany
          │
          ▼
       Product
```

The `Product` entity will be implemented in the next development stage.

## Planned Features

The following modules are planned for V1:

* [ ] Product Management
* [ ] Supplier Management
* [ ] Stock Management
* [ ] Stock In
* [ ] Stock Out
* [ ] Stock History
* [ ] User Management
* [ ] Role Management
* [ ] Search and Filtering
* [ ] Pagination
* [ ] API Resources
* [ ] Postman Collection
* [ ] API Documentation

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

## Testing

API endpoints can be tested using **Postman**.

Current testing coverage includes:

* Authentication
* Sanctum token authentication
* Role-based authorization
* Category CRUD
* Request validation
* HTTP status codes
* Protected API endpoints

## Development Progress

### Version 1

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
* [ ] Product CRUD
* [ ] Supplier CRUD
* [ ] Stock management
* [ ] Stock history
* [ ] User management
* [ ] Postman collection
* [ ] API documentation

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
