# PHP_Laravel12_Select2_Implement_Using_Vue.JS

A simple and clean Laravel 12 project demonstrating **Vue 3 integration with Select2** for **multiple tag selection**, using a **Many-to-Many** relationship between Products and Tags.

---

## Project Overview

This project shows how to:

* Use Laravel 12 as a backend API
* Integrate Vue 3 using Vite
* Implement Select2 (jQuery-based) inside Vue
* Handle Many-to-Many relationships (Products ↔ Tags)
* Perform basic CRUD operations

The UI is intentionally simple and beginner-friendly.

---

## Tech Stack

* Backend: Laravel 12
* Frontend: Vue.js 3
* UI Enhancement: Select2 (jQuery)
* Database: MySQL
* Build Tool: Vite

---

## Prerequisites

Make sure you have the following installed:

* PHP 8.0 or higher
* Composer
* Node.js and npm
* MySQL

---

## Installation Steps

### Step 1: Create Laravel Project

```bash
composer create-project laravel/laravel laravel-select2-simple
cd laravel-select2-simple
```

---

### Step 2: Install Vue 3

```bash
npm install vue@3
```

---

### Step 3: Database Configuration

Update your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_select2
DB_USERNAME=root
DB_PASSWORD=
```

Create the database:

```bash
mysql -u root -p -e "CREATE DATABASE laravel_select2;"
```

---

### Step 4: Create Migrations

```bash
php artisan make:migration create_tags_table
php artisan make:migration create_products_table
```

Tables created:

* `tags`
* `products`
* `product_tag` (pivot table)

---

### Step 5: Create Models

* Tag model with `belongsToMany` relationship
* Product model with `belongsToMany` relationship

These models manage the many-to-many association cleanly.

---

### Step 6: Run Migrations

```bash
php artisan migrate
```

---

### Step 7: Seed Sample Tags

```bash
php artisan make:seeder TagSeeder
php artisan db:seed
```

This will insert sample tags like Electronics, Books, Clothing, etc.

---

### Step 8: Create API Controllers

```bash
php artisan make:controller TagController --api
php artisan make:controller ProductController --api
```

Controllers handle:

* Fetching tags
* Creating products with multiple tags
* Listing products with tags
* Deleting products

---

### Step 9: Define Routes

**API Routes (`routes/api.php`)**

```text
GET    /api/tags
GET    /api/products
POST   /api/products
DELETE /api/products/{product}
```

---

### Step 10: Vite Configuration

Vite is configured with:

* Laravel Vite plugin
* Vue plugin

This enables Vue components to compile correctly.

---

### Step 11: Main Vue Component

The `App.vue` file includes:

* Product form
* Select2 multiple tag selector
* Product listing
* Delete functionality

Select2 is initialized after Vue mounts using jQuery.

---

### Step 12: App Entry File

`resources/js/app.js` mounts the Vue application:

* Uses `createApp`
* Mounts `App.vue` to `#app`

---

### Step 13: Blade Template

The `welcome.blade.php` file:

* Loads jQuery and Select2 via CDN
* Includes Vite assets
* Applies basic custom styling

---

### Step 14: CSS Styling

Basic CSS reset and layout styles are defined in:

```text
resources/css/app.css
```

---

### Step 15: Install & Build Assets

```bash
npm install
npm run build
```

---

### Step 16: Run the Application

```bash
php artisan serve
```

Visit in browser:

```text
http://localhost:8000
```

---

## Application Features

* Add products with name and price
* Select multiple tags using Select2
* Display products with assigned tags
* Delete products
* Clean and simple UI

---

## Project Structure

```text
laravel-select2-simple/
├── app/
│   ├── Http/Controllers/
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   ├── js/
│   └── css/
├── routes/
│   ├── web.php
│   └── api.php
└── vite.config.js
```

## screenshot
<img width="1915" height="473" alt="image" src="https://github.com/user-attachments/assets/bde749b5-33a3-4e47-8353-cf5b9fc99cd7" />

<img width="1894" height="582" alt="image" src="https://github.com/user-attachments/assets/996c16fa-7712-4e6e-8510-8d27bf05b38c" />






