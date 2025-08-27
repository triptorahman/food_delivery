<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{DeliveryZoneController, OrderController, AssignmentController, DriverLocationController, RestaurantController};
use App\Http\Controllers\Api\AuthController;


Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/auth/logout', [AuthController::class, 'logout']);


Route::middleware('auth:sanctum')->group(function () {

    //Create Restaurant
    Route::post('/restaurants', [RestaurantController::class, 'store'])->middleware('restaurant.owner');
    // Zones
    Route::post('/restaurants/{restaurant}/zones', [DeliveryZoneController::class, 'store'])->middleware('restaurant.owner');

    // Orders
    Route::post('/orders', [OrderController::class, 'store'])->middleware('customer');

    // Driver updates location & availability
    Route::post('/deliveryman/location/update', [DriverLocationController::class, 'updateLocation'])->middleware('delivery.man');
    Route::post('/deliveryman/status/update', [DriverLocationController::class, 'updateStatus'])->middleware('delivery.man');

    // Driver accepts / rejects
    Route::post('/assignments/{assignment}/accept', [AssignmentController::class, 'accept'])->middleware('delivery.man');
    Route::post('/assignments/{assignment}/reject', [AssignmentController::class, 'reject'])->middleware('delivery.man');
    Route::post('/assignments/{assignment}/complete', [AssignmentController::class, 'complete'])->middleware('delivery.man');
});
