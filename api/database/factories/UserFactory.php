<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      return [
            'name' => $this->faker->name,
            'phone' => '078' . $this->faker->randomNumber(7, true), // Adjust according to your needs
            'password' => bcrypt('password'), // You can use Hash::make('password') as well
            // 'email' => $this->faker->unique()->safeEmail, // Remove or comment out this line if your schema does not include an email field
            'sms_code' => '1234', // Example sms_code, adjust as necessary
            'phone_verified_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
