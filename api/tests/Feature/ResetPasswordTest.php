<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function user_can_request_password_reset()
    {
        $user = User::create([
            'name' => 'John Doe',
            'phone' => '1234567890',
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->postJson('/api/password-reset-request', [
            'phone' => $user->phone,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => "Your password reset code is: 123"]);
    }

    /** @test */
    public function user_can_reset_password_with_valid_sms_code()
    {
        $user = User::create([
            'name' => 'Jane Doe',
            'phone' => '0987654321',
            'password' => Hash::make('oldpassword'),
            'sms_code' => '123',
        ]);

        $response = $this->postJson('/api/password-reset', [
            'phone' => $user->phone,
            'sms_code' => '123',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Password has been reset successfully.']);
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword', $user->password));
    }

    protected function setUp(): void
    {
        parent::setUp();
    }
}
