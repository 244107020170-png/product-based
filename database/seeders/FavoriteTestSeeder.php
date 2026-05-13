<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Favorite;
use App\Models\User;
use App\Models\Field;

class FavoriteTestSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'player@test.com')->first();

        if (!$user) {
            $this->command->error('User player@test.com tidak ditemukan. Jalankan HistoryTestSeeder dulu.');
            return;
        }

        $fields = Field::all();

        if ($fields->isEmpty()) {
            $this->command->error('Tidak ada field di DB. Jalankan HistoryTestSeeder dulu.');
            return;
        }

        // Hapus favorit lama user ini
        Favorite::where('user_id', $user->id)->delete();

        // Tambah semua field yang ada sebagai favorit
        foreach ($fields as $field) {
            Favorite::create([
                'user_id'  => $user->id,
                'field_id' => $field->id,
            ]);
        }

        $count = Favorite::where('user_id', $user->id)->count();
        $this->command->info("{$count} favorit berhasil diinsert untuk {$user->email}");
        $this->command->line("URL: /favorit");
    }
}
