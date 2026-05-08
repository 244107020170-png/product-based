<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Favorite;
use App\Models\Field;
use App\Models\User;

class FavoriteFieldsSeeder extends Seeder
{
    public function run(): void
    {
        $user  = User::where('email', 'player@test.com')->first();
        $owner = User::where('email', 'owner@test.com')->first();

        if (!$user || !$owner) {
            $this->command->error('Jalankan HistoryTestSeeder dulu.');
            return;
        }

        // Tambah lapangan dengan kategori olahraga beragam
        $newFields = [
            [
                'name'           => 'Lapangan Futsal - Soekarno Hatta',
                'description'    => 'Lapangan futsal premium sintetis indoor.',
                'location'       => 'Sukun, Malang',
                'price_per_hour' => 150000,
                'owner_id'       => $owner->id,
            ],
            [
                'name'           => 'GOR Basket - Arjosari',
                'description'    => 'Lapangan basket indoor full AC.',
                'location'       => 'Blimbing, Malang',
                'price_per_hour' => 100000,
                'owner_id'       => $owner->id,
            ],
            [
                'name'           => 'GOR Badminton Tidar',
                'description'    => 'Lapangan badminton dengan 6 court.',
                'location'       => 'Klojen, Malang',
                'price_per_hour' => 80000,
                'owner_id'       => $owner->id,
            ],
            [
                'name'           => 'Kolam Renang Blimbing',
                'description'    => 'Kolam renang olimpik 50 meter.',
                'location'       => 'Blimbing, Malang',
                'price_per_hour' => 30000,
                'owner_id'       => $owner->id,
            ],
            [
                'name'           => 'Lapangan Voli - Sawojajar',
                'description'    => 'Lapangan voli outdoor terawat.',
                'location'       => 'Sawojajar, Malang',
                'price_per_hour' => 60000,
                'owner_id'       => $owner->id,
            ],
        ];

        $createdFieldIds = [];
        foreach ($newFields as $fieldData) {
            $existing = Field::where('name', $fieldData['name'])->first();
            if ($existing) {
                $createdFieldIds[] = $existing->id;
            } else {
                $f = Field::create(array_merge($fieldData, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
                $createdFieldIds[] = $f->id;
            }
        }

        // Tambah semua ke favorit user
        Favorite::where('user_id', $user->id)->delete();

        $allFieldIds = array_merge(
            Field::whereIn('name', [
                'Lapangan Voli - Veteran Muda',
                'Lapangan Futsal - Soekarno Hatta',
                'GOR Basket - Arjosari',
                'GOR Badminton Tidar',
                'Kolam Renang Blimbing',
                'Lapangan Voli - Sawojajar',
            ])->pluck('id')->toArray(),
            $createdFieldIds
        );
        $allFieldIds = array_unique($allFieldIds);

        foreach ($allFieldIds as $fid) {
            Favorite::firstOrCreate(['user_id' => $user->id, 'field_id' => $fid]);
        }

        $total = Favorite::where('user_id', $user->id)->count();
        $this->command->info("{$total} favorit dari berbagai olahraga diinsert!");
        $this->command->line("Akses: /favorit setelah login sebagai player@test.com");
    }
}
