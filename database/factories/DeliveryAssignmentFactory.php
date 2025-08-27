<?php

namespace Database\Factories;

use App\Models\DeliveryAssignment;
use App\Models\Order;
use App\Models\DeliveryMan;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryAssignmentFactory extends Factory
{
    protected $model = DeliveryAssignment::class;

    public function definition()
    {
        return [
            'order_id' => Order::factory(),
            'delivery_man_id' => DeliveryMan::factory(),
            'status' => 'pending',
            'responded_at' => null,
        ];
    }
}
