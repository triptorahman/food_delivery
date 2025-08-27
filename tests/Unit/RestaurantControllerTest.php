<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\RestaurantController;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RestaurantControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_restaurant_for_owner()
    {
        $user = User::factory()->create(['role' => 'restaurant_owner']);
        Auth::shouldReceive('id')->andReturn($user->id);
        $controller = new RestaurantController();
        $request = Request::create('/api/restaurants', 'POST', [
            'name' => 'Test Restaurant',
            'address' => '123 Main St',
            'phone' => '1234567890',
        ]);
        $request->setUserResolver(function () use ($user) { return $user; });
        $response = $controller->store(new Restaurant(), $request);
        $data = $response->getData(true);
        $this->assertTrue($data['success']);
        $this->assertEquals('Restaurant created successfully.', $data['message']);
        $this->assertDatabaseHas('restaurants', ['name' => 'Test Restaurant', 'owner_id' => $user->id]);
    }

    public function test_store_owner_cannot_create_multiple_restaurants()
    {
        $user = User::factory()->create(['role' => 'restaurant_owner']);
        Auth::shouldReceive('id')->andReturn($user->id);
        Restaurant::factory()->create(['owner_id' => $user->id]);
        $controller = new RestaurantController();
        $request = Request::create('/api/restaurants', 'POST', [
            'name' => 'Another Restaurant',
            'address' => '456 Main St',
            'phone' => '9876543210',
        ]);
        $request->setUserResolver(function () use ($user) { return $user; });
        $response = $controller->store(new Restaurant(), $request);
        $data = $response->getData(true);
        $this->assertFalse($data['success']);
        $this->assertEquals('Each owner can only have one restaurant.', $data['message']);
    }

    public function test_store_validation_error()
    {
        $user = User::factory()->create(['role' => 'restaurant_owner']);
        Auth::shouldReceive('id')->andReturn($user->id);
        $controller = new RestaurantController();
        $request = Request::create('/api/restaurants', 'POST', [
            'name' => '',
            'address' => '',
        ]);
        $request->setUserResolver(function () use ($user) { return $user; });
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $controller->store(new Restaurant(), $request);
    }
}
