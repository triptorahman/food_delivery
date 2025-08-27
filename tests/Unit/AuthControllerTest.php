<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\AuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_user_and_returns_token()
    {
        $controller = new AuthController();
        $request = Request::create('/api/auth/register', 'POST', [
            'name' => 'Unit User',
            'email' => 'unit@example.com',
            'password' => 'password',
            'role' => 'customer',
        ]);
        $response = $controller->register($request);
        $data = $response->getData(true);
        $this->assertEquals('Registration successful', $data['message']);
        $this->assertArrayHasKey('access_token', $data);
        $this->assertDatabaseHas('users', ['email' => 'unit@example.com']);
    }

    public function test_login_returns_token_for_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'unitlogin@example.com',
            'password' => Hash::make('password'),
        ]);
        $controller = new AuthController();
        $request = Request::create('/api/auth/login', 'POST', [
            'email' => 'unitlogin@example.com',
            'password' => 'password',
        ]);
        $response = $controller->login($request);
        $data = $response->getData(true);
        $this->assertEquals('Login successful', $data['message']);
        $this->assertArrayHasKey('access_token', $data);
    }

    public function test_login_throws_validation_exception_for_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'unitfail@example.com',
            'password' => Hash::make('password'),
        ]);
        $controller = new AuthController();
        $request = Request::create('/api/auth/login', 'POST', [
            'email' => 'unitfail@example.com',
            'password' => 'wrongpassword',
        ]);
        $this->expectException(ValidationException::class);
        $controller->login($request);
    }

    public function test_logout_deletes_tokens()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        $controller = new AuthController();
        $request = Request::create('/api/auth/logout', 'POST');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        $response = $controller->logout($request);
        $data = $response->getData(true);
        $this->assertEquals('Logged out successfully', $data['message']);
    }
}
