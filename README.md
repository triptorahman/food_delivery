# Food Delivery API

A Laravel-based backend for a food delivery platform, creating restaurant delivery zone, order assignment and authentication.

## Requirements

- PHP >= 8.2
- Composer
- MySQL
- Laravel = 12.x

## Setup Instructions

1. **Clone the repository**
   ```
   git clone <your-repo-url>
   cd food_delivery
   ```
2. **Install dependencies**
   ```
   composer install
   npm install
   ```
3. **Copy and configure environment**
   ```
   cp .env.example .env
   # Edit .env for your database and mail settings
   ```
4. **Generate application key**
   ```
   php artisan key:generate
   ```
5. **Run migrations and seeders**
   ```
   php artisan migrate --seed
   ```

6. **Start the development server**
   ```
   php artisan serve
   ```

## Testing & Coverage

- Run tests:
  ```
  php artisan test
  ```
-

## Main Features

- User authentication (Sanctum)
- User Registration [Role Wise ('admin', 'customer', 'restaurant_owner', 'delivery_man')]
- Restaurant Create [For Restuadent Owner]
- Restaurant Delivery Zone Update [For Restuadent Owner]
- Order Create [For Customer]
- Delivery assignment
- Delivery Accept & Reject [For Delivery Man]
- Notifications

## Folder Structure

- `app/Models` — Eloquent models
- `app/Http/Controllers/Api/` — API controllers
- `database/migrations` — Table definitions
- `database/factories` — Model factories for testing
- `routes/api.php` — API routes
- `tests/Feature` — Feature tests (HTTP)
- `tests/Unit` — Unit tests (logic)

## Useful Commands

- Clear cache:
  ```
  php artisan optimize:clear
  ```
