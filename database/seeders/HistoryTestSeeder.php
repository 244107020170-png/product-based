<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class HistoryTestSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Buat / ambil user player test ──────────────────────────
        $userId = DB::table('users')->where('email', 'player@test.com')->value('id');

        if (!$userId) {
            $userId = DB::table('users')->insertGetId([
                'name'              => 'Namtan Player',
                'email'             => 'player@test.com',
                'password'          => Hash::make('password'),
                'role'              => 'player',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
            $this->command->info("User dibuat: player@test.com / password");
        } else {
            $this->command->info("User sudah ada (id={$userId})");
        }

        // ── 2. Buat / ambil owner ──────────────────────────────────────
        $ownerId = DB::table('users')->where('email', 'owner@test.com')->value('id');

        if (!$ownerId) {
            $ownerId = DB::table('users')->insertGetId([
                'name'              => 'Owner Lapangan',
                'email'             => 'owner@test.com',
                'password'          => Hash::make('password'),
                'role'              => 'owner',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }

        // ── 3. Buat beberapa lapangan (fields) ────────────────────────
        $fields = [
            [
                'name'          => 'Lapangan Voli - Veteran Muda',
                'description'   => 'Lapangan voli indoor berstandar nasional dengan lantai parket.',
                'location'      => 'Lowokwaru, Malang',
                'price_per_hour'=> 120000,
                'owner_id'      => $ownerId,
            ],
            [
                'name'          => 'Lapangan Futsal - Soekarno Hatta',
                'description'   => 'Lapangan futsal sintetis dengan pencahayaan LED full.',
                'location'      => 'Sukun, Malang',
                'price_per_hour'=> 150000,
                'owner_id'      => $ownerId,
            ],
            [
                'name'          => 'GOR Badminton Tidar',
                'description'   => 'Gedung olahraga badminton dengan 6 lapangan.',
                'location'      => 'Klojen, Malang',
                'price_per_hour'=> 80000,
                'owner_id'      => $ownerId,
            ],
        ];

        $fieldIds = [];
        foreach ($fields as $field) {
            $existing = DB::table('fields')->where('name', $field['name'])->value('id');
            if ($existing) {
                $fieldIds[] = $existing;
            } else {
                $fieldIds[] = DB::table('fields')->insertGetId(array_merge($field, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        $this->command->info(count($fieldIds) . " lapangan siap");

        // ── 4. Hapus booking lama untuk user ini agar tidak duplikat ──
        DB::table('bookings')->where('user_id', $userId)->delete();

        // ── 5. Insert booking dengan berbagai status ───────────────────
        $bookings = [
            // SELESAI
            [
                'user_id'    => $userId,
                'field_id'   => $fieldIds[0],
                'date'       => Carbon::now()->subDays(10)->toDateString(),
                'start_time' => '19:00:00',
                'end_time'   => '20:00:00',
                'status'     => 'selesai',
            ],
            [
                'user_id'    => $userId,
                'field_id'   => $fieldIds[1],
                'date'       => Carbon::now()->subDays(7)->toDateString(),
                'start_time' => '08:00:00',
                'end_time'   => '10:00:00',
                'status'     => 'selesai',
            ],
            [
                'user_id'    => $userId,
                'field_id'   => $fieldIds[2],
                'date'       => Carbon::now()->subDays(5)->toDateString(),
                'start_time' => '15:00:00',
                'end_time'   => '17:00:00',
                'status'     => 'selesai',
            ],
            // AKAN DATANG (confirmed)
            [
                'user_id'    => $userId,
                'field_id'   => $fieldIds[0],
                'date'       => Carbon::now()->addDays(2)->toDateString(),
                'start_time' => '19:00:00',
                'end_time'   => '20:00:00',
                'status'     => 'confirmed',
            ],
            [
                'user_id'    => $userId,
                'field_id'   => $fieldIds[1],
                'date'       => Carbon::now()->addDays(5)->toDateString(),
                'start_time' => '10:00:00',
                'end_time'   => '12:00:00',
                'status'     => 'confirmed',
            ],
            // DIBATALKAN
            [
                'user_id'    => $userId,
                'field_id'   => $fieldIds[2],
                'date'       => Carbon::now()->subDays(3)->toDateString(),
                'start_time' => '07:00:00',
                'end_time'   => '09:00:00',
                'status'     => 'cancelled',
            ],
            [
                'user_id'    => $userId,
                'field_id'   => $fieldIds[0],
                'date'       => Carbon::now()->subDays(1)->toDateString(),
                'start_time' => '14:00:00',
                'end_time'   => '16:00:00',
                'status'     => 'cancelled',
            ],
            // PENDING
            [
                'user_id'    => $userId,
                'field_id'   => $fieldIds[1],
                'date'       => Carbon::now()->addDay()->toDateString(),
                'start_time' => '18:00:00',
                'end_time'   => '20:00:00',
                'status'     => 'pending',
            ],
        ];

        $now = now();
        foreach ($bookings as &$b) {
            $b['created_at'] = $now;
            $b['updated_at'] = $now;
        }

        DB::table('bookings')->insert($bookings);

        $this->command->info(count($bookings) . " booking berhasil diinsert");
        $this->command->newLine();
        $this->command->line("─────────────────────────────────────────");
        $this->command->line("  Login dengan:");
        $this->command->line("  Email    : player@test.com");
        $this->command->line("  Password : password");
        $this->command->line("  URL      : /history");
        $this->command->line("─────────────────────────────────────────");
    }
}
