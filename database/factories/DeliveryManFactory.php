<?php

namespace Database\Factories;

use App\Models\DeliveryMan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DeliveryManFactory extends Factory
{
    protected $model = DeliveryMan::class;

    public function definition()
    {
        return [
            'phone' => $this->faker->phoneNumber(),
            'status' => $this->faker->randomElement(['offline', 'available', 'busy']),
            'location' => new \MatanYadaev\EloquentSpatial\Objects\Point(
                $this->faker->latitude(),
                $this->faker->longitude()
            ),
            'user_id' => User::factory(),
        ];
    }
}
