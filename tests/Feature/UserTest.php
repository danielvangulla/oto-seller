<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker;

    public function userTest()
    {
        return [
            'name' => 'User Test',
            'email' => 'User@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
    }

    public function testApiRegister()
    {
        $user = $this->userTest();
        $response = $this->postJson('/api/register', $user);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'access_token',
                    'token_type',
                    'expires_in',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'name' => $user['name'],
            'email' => $user['email'],
        ]);
    }

    public function testApiLogin()
    {
        $user = $this->userTest();
        $response = $this->postJson('/api/login', $user);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'access_token',
                    'token_type',
                    'expires_in',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'name' => $user['name'],
            'email' => $user['email'],
        ]);

        $data = User::where('email', $user['email'])->first();
        if ($data) {
            $data->delete();
        }
    }
}
