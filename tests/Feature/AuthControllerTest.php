<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_success()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'role' => 'customer',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'access_token', 'token_type']);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_register_validation_error()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => '123',
            'role' => 'invalid_role',
        ]);
        $response->assertStatus(422);
    }

    public function test_login_success()
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => Hash::make('password'),
        ]);
        $response = $this->postJson('/api/auth/login', [
            'email' => 'login@example.com',
            'password' => 'password',
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'access_token', 'token_type']);
    }

    public function test_login_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'wrong@example.com',
            'password' => Hash::make('password'),
        ]);
        $response = $this->postJson('/api/auth/login', [
            'email' => 'wrong@example.com',
            'password' => 'incorrect',
        ]);
        $response->assertStatus(422);
    }

    public function test_logout_success()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/auth/logout');
        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);
    }
}
