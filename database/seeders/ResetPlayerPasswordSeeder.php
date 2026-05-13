<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPlayerPasswordSeeder extends Seeder
{
    public function run(): void
    {
        $updated = DB::table('users')
            ->where('email', 'player@test.com')
            ->update([
                'password'   => Hash::make('password'),
                'updated_at' => now(),
            ]);

        if ($updated) {
            $this->command->info('Password player@test.com berhasil direset ke: password');
        } else {
            $this->command->error('User tidak ditemukan!');
        }
    }
}
