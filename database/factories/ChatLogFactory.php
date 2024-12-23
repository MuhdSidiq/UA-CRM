<?php

namespace Database\Factories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ChatLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lead_id' => Lead::factory(),
            'message_id' => $this->faker->unique()->numberBetween(1000000, 9999999),
            'sender_type' => $this->faker->randomElement(['agent', 'customer']),
            'sender_id' => $this->faker->numerify('user#########'),
            'content' => $this->faker->realText(100),
            'message_type' => $this->faker->randomElement(['text', 'photo', 'document', 'voice']),
            'file_url' => $this->faker->optional(0.3)->url,
            'sent_at' => $this->faker->dateTimeThisMonth,
        ];
    }
}
