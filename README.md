# Food Delivery API

A Laravel-based backend for a food delivery platform, creating restaurant delivery zone, order assignment and authentication.

## Requirements

- PHP >= 8.2
- Composer
- MySQL = 8.0
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


## Useful Commands

- Clear cache:
  ```
  php artisan optimize:clear
  ```

- Migration and Seed:
  ```
  php artisan migration:refresh --seed
  ```


  # API Endpoints

Below are the main API endpoints, their descriptions, and sample request bodies:

## Authentication
- **POST /api/auth/register** — Register a new user
   ```json
   {
      "name": "Test Restaurant Owner",
      "email": "restaurantowner@gmail.com",
      "password": "secret123",
      "role": "restaurant_owner"
    }
   ```
- **POST /api/auth/login** — Login and get token
   ```json
   {
        "email": "restaurantowner@gmail.com",
        "password": "restaurantowner123"
    }
   ```
- **POST /api/auth/logout** — Logout (requires auth)

## Restaurant
- **POST /api/restaurants** — Create restaurant (owner only)
   ```json
   {
    "name": "Rampura Bridge FoodCart",
    "address": "Rampura Bridge",
    "phone": "1234567890"
  }
   ```

## Delivery Zones
- **POST /api/restaurants/{restaurant}/zones** — Create delivery zone for a restaurant
   ```json
   {
    "type": "radius",
    "center_lat": 23.76803,
    "center_lng": 90.42334,
    "radius_km": 5
  }
   ```

## Orders
- **POST /api/orders** — Place a new order (customer only)
   ```json
   {
      "restaurant_id": 1,
      "delivery_address": "House 12, Road 7, Dhanmondi",
      "lat": 23.766467,
      "lng": 90.424041
    }
   ```

## Deliveryman Actions
- **POST /api/deliveryman/location/update** — Update location
   ```json
   {
      "lat": 23.766467,
      "lng": 90.424041
    }
   ```
- **POST /api/deliveryman/status/update** — Update status
   ```json
   {
      "status": "available"
   }
   ```
   Valid values: "offline", "available", "busy"

## Assignment Actions
- **POST /api/assignments/{assignment}/accept** — Accept assignment
- **POST /api/assignments/{assignment}/reject** — Reject assignment
- **POST /api/assignments/{assignment}/complete** — Complete assignment


# Postman Collection

This project includes a Postman collection (`Food Delivery.postman_collection.json`) and environment variables (`Food Delivery ENV Variables.postman_environment.json`) for easy API testing and development.

**How to use:**
1. Open Postman.
2. Import the collection file: `Food Delivery.postman_collection.json`.
3. Import the environment file: `Food Delivery ENV Variables.postman_environment.json`.
4. Select the environment in Postman and run requests with pre-configured variables and endpoints.

This makes it easy for developers and testers to try all API endpoints quickly and consistently.
