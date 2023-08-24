<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'access_token',
            'token_type',
            'data' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at',
            ],
        ]);
        // request to api/user with token
        $this->get(route('user'), [
            'Authorization' => 'Bearer ' . $response['access_token'],
        ])->assertStatus(200)
            ->assertJson([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ]);
    }

    public function test_users_can_not_authenticate_with_invalid_email(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'email' => 'invalid-email',
            'password' => 'password',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');

    }
    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
    }
}
