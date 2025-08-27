<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MatanYadaev\EloquentSpatial\EloquentSpatial;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RestaurantOwner;
use App\Http\Middleware\Customer;
use App\Http\Middleware\DeliveryMan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        EloquentSpatial::setDefaultSrid(Srid::WGS84);

        Route::aliasMiddleware('restaurant.owner', RestaurantOwner::class);
        Route::aliasMiddleware('customer', Customer::class);
        Route::aliasMiddleware('delivery.man', DeliveryMan::class);
    }
}
