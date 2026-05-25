<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminTestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'admin.test@example.com';

        if (User::where('email', $email)->exists()) {
            $this->command->info("Admin test user already exists: {$email}");
            return;
        }

        User::create([
            'name' => 'Admin Test',
            'username' => 'admin_test',
            'email' => $email,
            'phone' => '081299988877',
            'role' => 'admin',
            'gender' => 'laki-laki',
            'password' => Hash::make('password'),
            'sport_preference' => 'futsal',
        ]);

        $this->command->info("Created admin test user: {$email} (password: password)");
    }
}
