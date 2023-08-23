<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
        $user = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $response = $this->post(route('register'), $user);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'access_token',
            'token_type',
            'data' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ],
        ]);
        unset($user['password_confirmation'], $user['password']);
        $this->assertDatabaseHas('users', $user);
        $user = User::where('email', $user['email'])->first();
        $this->assertTrue(Hash::check('password', $user->password));
    }

    public function test_new_registered_user_can_login() : void
    {
        $user = User::factory()->create();
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Logged in successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }
    public function test_new_users_can_not_register_with_duplicated_email(): void
    {
        $user = User::factory()->create();
        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }
}
