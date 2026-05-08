<?php

namespace Database\Seeders;

use App\Models\Field;
use App\Models\MatchPlayer;
use App\Models\Matchs;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeamSwipeSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::firstOrCreate(
            ['email' => 'owner.swipe@example.com'],
            [
                'name' => 'Owner Swipe',
                'password' => Hash::make('password'),
            ]
        );

        $players = collect([
            ['name' => 'Rio', 'email' => 'rio.swipe@example.com'],
            ['name' => 'Dito', 'email' => 'dito.swipe@example.com'],
            ['name' => 'Ayu', 'email' => 'ayu.swipe@example.com'],
            ['name' => 'Nia', 'email' => 'nia.swipe@example.com'],
            ['name' => 'Rian', 'email' => 'rian.swipe@example.com'],
            ['name' => 'Rafi', 'email' => 'rafi.swipe@example.com'],
            ['name' => 'Anya', 'email' => 'anya.swipe@example.com'],
            ['name' => 'Bimo', 'email' => 'bimo.swipe@example.com'],
            ['name' => 'Sasa', 'email' => 'sasa.swipe@example.com'],
            ['name' => 'Tyo', 'email' => 'tyo.swipe@example.com'],
            ['name' => 'Lina', 'email' => 'lina.swipe@example.com'],
            ['name' => 'Damar', 'email' => 'damar.swipe@example.com'],
        ])->map(function ($player) {
            return User::firstOrCreate(
                ['email' => $player['email']],
                [
                    'name' => $player['name'],
                    'password' => Hash::make('password'),
                ]
            );
        });

        $fieldRows = [
            ['name' => 'GOR Bimasakti Malang', 'location' => 'Malang Kota', 'price_per_hour' => 140000],
            ['name' => 'Champion Futsal Malang', 'location' => 'Lowokwaru', 'price_per_hour' => 110000],
            ['name' => 'GOR Bulu Tangkis Tidar', 'location' => 'Tidar', 'price_per_hour' => 100000],
            ['name' => 'Lapangan Voli Veteran', 'location' => 'Klojen', 'price_per_hour' => 90000],
            ['name' => 'Tennis Court Soekarno', 'location' => 'Soekarno Hatta', 'price_per_hour' => 130000],
        ];

        $fields = collect($fieldRows)->map(function ($field) use ($owner) {
            return Field::firstOrCreate(
                ['name' => $field['name']],
                [
                    'location' => $field['location'],
                    'price_per_hour' => $field['price_per_hour'],
                    'description' => 'Lapangan untuk cari tim dan public match',
                    'owner_id' => $owner->id,
                ]
            );
        })->keyBy('name');

        $baseTitles = [
            'GOR Bimasakti Malang' => ['Basket Fun Match', 'Basket Sparring', 'Basket 3x3', 'Basket Pagi', 'Basket Malam'],
            'Champion Futsal Malang' => ['Futsal Mabar', 'Futsal Sparring', 'Mini Soccer Fun', 'Futsal Malam', 'Futsal Kantor'],
            'GOR Bulu Tangkis Tidar' => ['Badminton Double Mix', 'Badminton Latihan', 'Tepok Bulu', 'Badminton Pagi', 'Badminton Santai'],
            'Lapangan Voli Veteran' => ['Voli Sore', 'Voli Pagi', 'Voli Sparring', 'Voli Santai', 'Voli Weekend'],
            'Tennis Court Soekarno' => ['Tennis Rally', 'Tennis Match Up', 'Tennis Pagi', 'Tennis Santai', 'Tennis Sore'],
        ];

        $matchRows = [];
        for ($i = 0; $i < 150; $i++) {
            $field = $fieldRows[array_rand($fieldRows)];
            $titles = $baseTitles[$field['name']];
            $title = $titles[array_rand($titles)] . ' Part ' . rand(1, 100);
            $max = in_array('Futsal', explode(' ', $title)) ? rand(10, 14) : (in_array('Basket', explode(' ', $title)) ? rand(6, 10) : rand(4, 12));

            $matchRows[] = [
                'title' => $title,
                'field' => $field['name'],
                'dayOffset' => rand(1, 60),
                'time' => str_pad(rand(6, 22), 2, '0', STR_PAD_LEFT) . ':' . (rand(0, 1) ? '30' : '00') . ':00',
                'max' => $max,
            ];
        }

        foreach ($matchRows as $index => $row) {
            $match = Matchs::updateOrCreate(
                [
                    'title' => $row['title'],
                    'field_id' => $fields[$row['field']]->id,
                    'date' => Carbon::today()->addDays($row['dayOffset'])->toDateString(),
                ],
                [
                    'time' => $row['time'],
                    'max_player' => $row['max'],
                    'created_by' => $owner->id,
                ]
            );

            MatchPlayer::where('match_id', $match->id)->delete();

            $joinedCount = rand(0, $row['max'] - 1);
            if ($joinedCount > 0) {
                $players->shuffle()->take($joinedCount)->each(function ($player) use ($match) {
                    MatchPlayer::create([
                        'match_id' => $match->id,
                        'user_id' => $player->id,
                    ]);
                });
            }
        }
    }
}
