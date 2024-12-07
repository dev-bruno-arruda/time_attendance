<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\UserSeeder;
use Tests\TestCase;


class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    public function test_login_successful()
    {
        
        $user = User::where('role', 'admin')->first();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }

    public function test_login_validation_error()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'not-an-email',
            'password' => '',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_login_invalid_credentials()
    {
        $user = User::where('role', 'admin')->first();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422);

        $response->assertJson([
            'message' => 'Login fail',
            'status' => 'error',
        ]);
    }
}
