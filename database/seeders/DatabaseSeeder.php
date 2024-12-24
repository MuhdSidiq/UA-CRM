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
        $users = [
            [
                'name' => 'Nur Syahirah Adlina',
                'email' => 'nursyahirahadlinasalim@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Muhammad Sidiq',
                'email' => 'sidiq@test.my',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Nik M Imran',
                'email' => 'nik.m.imran@gmail.com',
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($users as $userData) {
            User::factory()->create($userData);
        }

    }
}
