<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TelegramAccount>
 */
class TelegramAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'phone_number' => $this->faker->e164PhoneNumber(), // Format: +1234567890
            'session_data' => $this->faker->sha256, // Simulated session data
            'status' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }
}
