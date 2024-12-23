<?php

namespace Database\Seeders;

use App\Models\ChatLog;
use App\Models\ChatStatusLog;
use App\Models\Lead;
use App\Models\LeadMeta;
use App\Models\TelegramAccount;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Sidiq',
            'email' => 'sidiq@dev.my',
            'password' => bcrypt('password'),
        ]);

    }
}
