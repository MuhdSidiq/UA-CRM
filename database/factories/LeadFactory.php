<?php

namespace Database\Factories;

use App\Models\TelegramAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['new', 'f1', 'f2', '50D', 'close', 'cold lead'];

        return [
            'telegram_account_id' => TelegramAccount::factory(),
            'telegram_chat_id' => $this->faker->unique()->numerify('chat###########'),
            'telegram_username' => $this->faker->userName,
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone_number' => $this->faker->phoneNumber,
            'country' => $this->faker->country,
            'status' => $this->faker->randomElement($statuses),
            'username' => $this->faker->userName,
            'platform' => $this->faker->randomElement(['Android', 'iOS', 'Web', 'Desktop']),
            'first_message_date' => $this->faker->dateTimeBetween('-3 months', '-1 month'),
            'last_message_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
