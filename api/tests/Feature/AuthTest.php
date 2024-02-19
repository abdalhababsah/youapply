<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_user_can_register()
    {
        $phone = $this->faker->numerify('078#######');
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'phone' => $phone,
            'password' => 'password',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'phone',
                     'sms_code',
                 ]);

        $this->assertDatabaseHas('users', ['phone' => $phone]);
    }

    /** @test */
    public function a_user_can_verify_sms_code()
    {
        $phone = '0781234567';
        $this->postJson('/api/register', [
            'name' => 'Jane Doe',
            'phone' => $phone,
            'password' => 'password',
        ]);

        $user = User::where('phone', $phone)->first();
        $smsCode = $user->sms_code;

        $response = $this->postJson('/api/verify-sms', [
            'phone' => $phone,
            'sms_code' => $smsCode,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Phone number verified successfully.',
                 ]);
    }

    /** @test */
    public function a_user_can_login()
    {
        $phone = '0782345678';
        $password = 'password';
        $this->postJson('/api/register', [
            'name' => 'Eve Doe',
            'phone' => $phone,
            'password' => $password,
        ]);
        $user = User::where('phone', $phone)->first();
        $user->update(['phone_verified_at' => now()]);

        $response = $this->postJson('/api/login', [
            'phone' => $phone,
            'password' => $password,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'access_token',
                     'token_type',
                 ]);
    }

    /** @test */
    public function a_user_can_logout()
    {
        $phone = '0783456789';
        $this->postJson('/api/register', [
            'name' => 'John Smith',
            'phone' => $phone,
            'password' => 'password',
        ]);
        $user = User::where('phone', $phone)->first();
        $user->update(['phone_verified_at' => now()]);
        $loginResponse = $this->postJson('/api/login', [
            'phone' => $phone,
            'password' => 'password',
        ]);
        $token = $loginResponse->json('access_token');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Logged out']);
    }
}
