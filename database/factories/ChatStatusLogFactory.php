<?php

namespace Database\Factories;

use App\Models\ChatStatusLog;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatStatusLogFactory extends Factory
{
    protected $model = ChatStatusLog::class;

    public function definition(): array
    {
        $statuses = ['new', 'f1', 'f2', '50D', 'close', 'cold lead'];
        $oldStatus = $this->faker->randomElement($statuses);

        return [
            'lead_id' => Lead::factory(),
            'old_status' => $oldStatus,
            'new_status' => $this->faker->randomElement(array_diff($statuses, [$oldStatus])),
            'folder_name' => $this->faker->word,
            'changed_by' => User::factory(), // This will create a user if none exists
            'notes' => $this->faker->optional(0.7)->sentence,
        ];
    }
}
