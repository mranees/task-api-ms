<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Tasks API Microservice

A RESTful API microservice for managing tasks built with Laravel 12 and Laravel Sanctum for authentication.

## Table of Contents

- [Installation](#installation)
- [Database Setup](#database-setup)
- [API Documentation](#api-documentation)
  - [Authentication](#authentication)
  - [Tasks](#tasks)
  - [User Roles & Privileges](#user-roles--privileges)
- [About Laravel](#about-laravel)
- [Contributing](#contributing)

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL 5.7 or higher
- Git

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/mranees/tasks-api-ms.git
   cd tasks-api-ms
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Create environment file**
   ```bash
   cp .env.example .env
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Configure database**

   Edit the `.env` file and update the database configuration:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=tasks_api_ms
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

   The API will be available at `http://localhost:8000`

## Database Setup

### Migrations

The application includes the following database tables:

#### Users Table
- `id` - Primary key
- `name` - User's full name
- `email` - User's email (unique)
- `password` - Hashed password
- `is_admin` - Boolean flag for admin privileges (default: false)
- `email_verified_at` - Email verification timestamp
- `remember_token` - Token for "remember me" functionality
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

#### Tasks Table
- `id` - Primary key
- `title` - Task title
- `status` - Task status (pending, in-progress, completed)
- `user_id` - Foreign key to users table
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

### Seeders

The application includes a database seeder that creates sample data for testing:

**Run seeders:**
```bash
php artisan db:seed
```

**Seeded Data:**

1. **Admin User**
   - Name: `admin User`
   - Email: `admin@ex.co`
   - Password: `12341234`
   - Role: Admin

2. **Regular Users** (3 users)
   - Generated with faker data
   - Password: `password`
   - Role: Regular user

3. **Sample Tasks** (10 tasks)
   - Randomly assigned to users
   - Various statuses (pending, in-progress, completed)

**To reseed the database:**
```bash
php artisan migrate:fresh --seed
```

## API Documentation

### Base URL

```
http://localhost:8000/api
```

### Authentication

This API uses **Laravel Sanctum** for token-based authentication. Include the token in the `Authorization` header for protected endpoints:

```
Authorization: Bearer {token}
```

#### Register

Create a new user account.

**Endpoint:** `POST /register`

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (201 Created):**
```json
{
  "message": "User Registered Successfully.",
  "user": "john@example.com",
  "Accept": "application/json",
  "Content-Type": "application/json",
  "token": "1|abcdefghijklmnopqrstuvwxyz"
}
```

#### Login

Authenticate and receive an authentication token.

**Endpoint:** `POST /login`

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (200 OK):**
```json
{
  "message": "User Login Successfully.",
  "user": "john@example.com",
  "token": "1|abcdefghijklmnopqrstuvwxyz"
}
```

#### Logout

Invalidate the current authentication token.

**Endpoint:** `POST /logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "message": "User Logout successfully."
}
```

#### Get Current User

Retrieve the authenticated user's information.

**Endpoint:** `GET /user`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "email_verified_at": null,
  "is_admin": false,
  "created_at": "2024-01-15T10:30:00.000000Z",
  "updated_at": "2024-01-15T10:30:00.000000Z"
}
```

### Tasks

All task endpoints require authentication.

#### List Tasks

Retrieve a paginated list of tasks.

**Endpoint:** `GET /tasks`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `page` (optional): Page number for pagination (default: 1)

**Response (200 OK):**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Complete project documentation",
      "status": "in-progress",
      "user_id": 1,
      "created_at": "2024-01-15T10:30:00.000000Z",
      "updated_at": "2024-01-15T10:30:00.000000Z"
    },
    {
      "id": 2,
      "title": "Review pull requests",
      "status": "pending",
      "user_id": 1,
      "created_at": "2024-01-15T11:00:00.000000Z",
      "updated_at": "2024-01-15T11:00:00.000000Z"
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/tasks?page=1",
    "last": "http://localhost:8000/api/tasks?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "path": "http://localhost:8000/api/tasks",
    "per_page": 5,
    "to": 2,
    "total": 2
  }
}
```

**Note:** Regular users see, edit and delete only their own tasks. Admin users see, update, delete all tasks.

#### Create Task

Create a new task.

**Endpoint:** `POST /tasks`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "title": "Fix bug in authentication",
  "status": "pending"
}
```

**Response (201 Created):**
```json
{
  "message": "Task created successfully.",
  "id": 3,
  "title": "Fix bug in authentication",
  "status": "pending",
  "user_id": 1,
  "created_at": "2024-01-15T12:00:00.000000Z",
  "updated_at": "2024-01-15T12:00:00.000000Z"
}
```

#### Get Task

Retrieve a specific task by ID.

**Endpoint:** `GET /tasks/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "id": 1,
  "title": "Complete project documentation",
  "status": "in-progress",
  "user_id": 1,
  "created_at": "2024-01-15T10:30:00.000000Z",
  "updated_at": "2024-01-15T10:30:00.000000Z"
}
```

**Error (403 Forbidden):**
```json
{
  "message": "This action is unauthorized."
}
```

#### Update Task

Update an existing task.

**Endpoint:** `PUT /tasks/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "title": "Complete project documentation - Updated",
  "status": "completed"
}
```

**Response (201 Created):**
```json
{
  "message": "Task updated successfully.",
  "id": 1,
  "title": "Complete project documentation - Updated",
  "status": "completed",
  "user_id": 1,
  "created_at": "2024-01-15T10:30:00.000000Z",
  "updated_at": "2024-01-15T13:00:00.000000Z"
}
```

#### Delete Task

Delete a task.

**Endpoint:** `DELETE /tasks/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "message": "Task deleted successfully."
}
```

### Task Status Values

Valid status values for tasks:
- `pending` - Task is pending
- `in-progress` - Task is currently being worked on
- `completed` - Task is completed

### Error Responses

#### 401 Unauthorized

```json
{
  "message": "Unauthenticated."
}
```

#### 403 Forbidden

```json
{
  "message": "This action is unauthorized."
}
```

#### 404 Not Found

```json
{
  "message": "Not found."
}
```

#### 422 Unprocessable Entity

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "title": ["The title field is required."],
    "status": ["The status must be one of: pending, in-progress, completed."]
  }
}
```

## User Roles & Privileges

The API supports two user roles with different privilege levels:

### Regular User

A regular user has the following privileges:

| Action | Permission |
|--------|-----------|
| Register | ✅ Yes |
| Login | ✅ Yes |
| View own tasks | ✅ Yes |
| View other users' tasks | ❌ No |
| Create tasks | ✅ Yes |
| Update own tasks | ✅ Yes |
| Update other users' tasks | ❌ No |
| Delete own tasks | ✅ Yes |
| Delete other users' tasks | ❌ No |
| View all tasks list | ❌ No (only sees own tasks) |

**Example:** A regular user can only see and manage their own tasks. When they request the tasks list, they receive only their own tasks paginated.

### Admin User

An admin user has the following privileges:

| Action | Permission |
|--------|-----------|
| Register | ✅ Yes |
| Login | ✅ Yes |
| View own tasks | ✅ Yes |
| View other users' tasks | ✅ Yes |
| Create tasks | ✅ Yes |
| Update own tasks | ✅ Yes |
| Update other users' tasks | ✅ Yes |
| Delete own tasks | ✅ Yes |
| Delete other users' tasks | ✅ Yes |
| View all tasks list | ✅ Yes (sees all tasks) |

**Example:** An admin user can view, update, and delete any task in the system. When they request the tasks list, they receive all tasks from all users.

### How to Create an Admin User

#### Via Database Seeder (Recommended for Development)

Run the seeder which creates an admin user automatically:
```bash
php artisan migrate --seed
```

Admin credentials:
- Email: `admin@ex.co`
- Password: `12341234`

#### Via Artisan Command (Manual)

```bash
php artisan tinker
```

Then in the Tinker shell:
```php
$user = App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('password123'),
    'is_admin' => true,
]);
```

#### Via Direct Database Update

```sql
UPDATE users SET is_admin = 1 WHERE email = 'user@example.com';
```

### Authorization Checks

The API uses Laravel's authorization policies to enforce these privileges. Each endpoint checks the user's role before allowing access:

- **viewAny**: User can view any task (admin) or only their own (regular user)
- **view**: User can view a specific task if they own it or are an admin
- **create**: All authenticated users can create tasks
- **update**: User can update a task if they own it or are an admin
- **delete**: User can delete a task if they own it or are an admin

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
