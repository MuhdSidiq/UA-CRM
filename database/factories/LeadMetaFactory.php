<?php

namespace Database\Factories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class LeadMetaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalMessages = $this->faker->numberBetween(10, 100);
        $agentMessages = floor($totalMessages * 0.4); // 40% from agent

        return [
            'lead_id' => Lead::factory(),
            'first_response_time' => $this->faker->dateTimeBetween('-3 months', '-2 months'),
            'last_response_time' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'total_messages' => $totalMessages,
            'agent_messages' => $agentMessages,
            'customer_messages' => $totalMessages - $agentMessages,
            'average_response_time' => $this->faker->numberBetween(60, 3600), // 1 minute to 1 hour
        ];
    }
}
