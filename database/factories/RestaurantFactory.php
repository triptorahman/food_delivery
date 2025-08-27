<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Restaurant;
use MatanYadaev\EloquentSpatial\Objects\Point;

/**
 * @extends Factory<Restaurant>
 */
class RestaurantFactory extends Factory
{
    protected $model = Restaurant::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'owner_id' => 1,
            'location' => new Point(40.7128, -74.0060),
        ];
    }
}
