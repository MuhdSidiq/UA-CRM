<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ChatStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['new', 'f1', 'f2', '50D', 'close', 'cold lead'];
        $oldStatus = $this->faker->randomElement($statuses);

        return [
            'lead_id' => Lead::factory(),
            'old_status' => $oldStatus,
            'new_status' => $this->faker->randomElement(array_diff($statuses, [$oldStatus])),
            'folder_name' => $this->faker->word,
            'changed_by' => User::factory(),
            'notes' => $this->faker->optional(0.7)->sentence,
        ];
    }
}
