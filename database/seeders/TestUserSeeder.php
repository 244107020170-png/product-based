<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test owner user (only if not exists)
        $email = 'owner.test@example.com';

        if (User::where('email', $email)->exists()) {
            $this->command->info("Test user already exists: {$email}");
            return;
        }

        User::create([
            'name' => 'Owner Test',
            'username' => 'owner_test',
            'email' => $email,
            'phone' => '081234567890',
            'role' => 'owner',
            'gender' => 'laki-laki',
            'password' => Hash::make('password'),
            'sport_preference' => 'futsal',
            // points omitted to use model/database default
        ]);

        $this->command->info("Created test user: {$email}");
    }
}
