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

        $matchRows = [
            ['title' => 'Basket Sabtu Night', 'field' => 'GOR Bimasakti Malang', 'dayOffset' => 2, 'time' => '20:00:00', 'max' => 10],
            ['title' => 'Basket Fun Match', 'field' => 'GOR Bimasakti Malang', 'dayOffset' => 4, 'time' => '19:00:00', 'max' => 10],
            ['title' => 'Futsal Sparring', 'field' => 'Champion Futsal Malang', 'dayOffset' => 1, 'time' => '21:00:00', 'max' => 12],
            ['title' => 'Futsal Friendly', 'field' => 'Champion Futsal Malang', 'dayOffset' => 5, 'time' => '20:30:00', 'max' => 12],
            ['title' => 'Badminton Double Mix', 'field' => 'GOR Bulu Tangkis Tidar', 'dayOffset' => 3, 'time' => '18:00:00', 'max' => 8],
            ['title' => 'Badminton Latihan', 'field' => 'GOR Bulu Tangkis Tidar', 'dayOffset' => 6, 'time' => '19:30:00', 'max' => 8],
            ['title' => 'Voli Sore Seru', 'field' => 'Lapangan Voli Veteran', 'dayOffset' => 2, 'time' => '17:00:00', 'max' => 12],
            ['title' => 'Voli Weekend', 'field' => 'Lapangan Voli Veteran', 'dayOffset' => 7, 'time' => '16:30:00', 'max' => 12],
            ['title' => 'Tennis Rally Session', 'field' => 'Tennis Court Soekarno', 'dayOffset' => 1, 'time' => '18:30:00', 'max' => 6],
            ['title' => 'Tennis Match Up', 'field' => 'Tennis Court Soekarno', 'dayOffset' => 5, 'time' => '08:00:00', 'max' => 6],
        ];

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

            $joinedCount = min($row['max'] - 1, 3 + ($index % 4));
            $players->take($joinedCount)->each(function ($player) use ($match) {
                MatchPlayer::create([
                    'match_id' => $match->id,
                    'user_id' => $player->id,
                ]);
            });
        }
    }
}
