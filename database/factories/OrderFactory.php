<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;
use MatanYadaev\EloquentSpatial\Objects\Point;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'restaurant_id' => Restaurant::factory(),
            'delivery_address' => $this->faker->address(),
            'delivery_point' => null, // Set as needed
            'status' => 'pending',
            'assigned_delivery_man_id' => null,
        ];
    }
}
