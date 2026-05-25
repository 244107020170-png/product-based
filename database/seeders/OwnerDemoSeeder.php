<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class OwnerDemoSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. OWNER ──────────────────────────────────────────────────
        $ownerId = DB::table('users')->where('email', 'owner.demo@test.com')->value('id');

        if (!$ownerId) {
            $ownerId = DB::table('users')->insertGetId([
                'name'              => 'Owner Demo',
                'email'             => 'owner.demo@test.com',
                'password'          => Hash::make('password'),
                'role'              => 'owner',
                'phone'             => '081234567890',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
            $this->command->info('Owner dibuat: owner.demo@test.com / password');
        }

        // ── 2. PLAYER UNTUK BOOKING ───────────────────────────────────
        $playerId = DB::table('users')->where('email', 'player.demo@test.com')->value('id');

        if (!$playerId) {
            $playerId = DB::table('users')->insertGetId([
                'name'              => 'Player Demo',
                'email'             => 'player.demo@test.com',
                'password'          => Hash::make('password'),
                'role'              => 'player',
                'phone'             => '085712345678',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }

        // ── 3. LAPANGAN ───────────────────────────────────────────────
        $fields = [
            ['name' => 'Lapangan Futsal A', 'type' => 'Futsal', 'location' => 'Lowokwaru, Malang', 'price' => 120000, 'open' => '08:00', 'close' => '22:00'],
            ['name' => 'Lapangan Futsal B', 'type' => 'Futsal', 'location' => 'Sukun, Malang', 'price' => 150000, 'open' => '08:00', 'close' => '22:00'],
            ['name' => 'Lapangan Basket', 'type' => 'Basket', 'location' => 'Klojen, Malang', 'price' => 180000, 'open' => '09:00', 'close' => '21:00'],
            ['name' => 'Lapangan Badminton', 'type' => 'Badminton', 'location' => 'Blimbing, Malang', 'price' => 80000, 'open' => '07:00', 'close' => '22:00'],
        ];

        $fieldIds = [];
        foreach ($fields as $f) {
            $existing = DB::table('fields')->where('name', $f['name'])->where('owner_id', $ownerId)->value('id');
            if ($existing) {
                $fieldIds[] = $existing;
            } else {
                $fieldIds[] = DB::table('fields')->insertGetId([
                    'name'           => $f['name'],
                    'type'           => $f['type'],
                    'description'    => 'Lapangan ' . $f['type'] . ' berkualitas dengan fasilitas lengkap.',
                    'location'       => $f['location'],
                    'price_per_hour' => $f['price'],
                    'open_time'      => $f['open'],
                    'close_time'     => $f['close'],
                    'owner_id'       => $ownerId,
                    'image'          => null,
                    'facilities'     => json_encode(['WiFi', 'Toilet', 'Parkir', 'Mushala']),
                    'rating'         => 4.5 + (array_rand([0, 1, 2, 3]) * 0.1),
                    'review_count'   => rand(10, 40),
                    'is_available'   => true,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }
        }
        $this->command->info(count($fieldIds) . ' lapangan dibuat');

        // ── 4. SLOT ───────────────────────────────────────────────────
        // Buat slot untuk hari ini dan 7 hari ke depan
        $today = Carbon::today();
        for ($d = 0; $d < 14; $d++) {
            $date = $today->copy()->addDays($d);
            $dateStr = $date->toDateString();

            // Skip beberapa hari sebagai hari libur (hari ke-5, ke-10)
            $isHoliday = in_array($d, [5, 10]);

            foreach ($fieldIds as $fid) {
                if ($isHoliday) {
                    // Tandai holiday
                    DB::table('holidays')->updateOrInsert(
                        ['field_id' => $fid, 'date' => $dateStr],
                        ['is_holiday' => true, 'created_at' => now(), 'updated_at' => now()]
                    );
                    continue;
                }

                // Slot per jam 08:00 - 21:00
                for ($h = 8; $h <= 21; $h++) {
                    $status = 'tersedia';

                    // Booking yang sudah ada untuk field ini di tanggal ini
                    $existingBookings = DB::table('bookings')
                        ->where('field_id', $fid)
                        ->where('date', $dateStr)
                        ->get();

                    foreach ($existingBookings as $b) {
                        $bStart = (int) Carbon::parse($b->start_time)->format('H');
                        $bEnd = (int) Carbon::parse($b->end_time)->format('H');
                        if ($h >= $bStart && $h < $bEnd) {
                            $status = 'dibooking';
                            break;
                        }
                    }

                    // Beberapa slot random untuk perbaikan (hanya 2-3 per field)
                    if ($d < 3 && in_array($h, [12, 13]) && ($fid === $fieldIds[0])) {
                        $status = 'perbaikan';
                    }

                    DB::table('slots')->updateOrInsert(
                        ['field_id' => $fid, 'date' => $dateStr, 'hour' => $h],
                        ['status' => $status, 'created_at' => now(), 'updated_at' => now()]
                    );
                }
            }
        }
        $this->command->info('Slot & holiday berhasil dibuat');

        // ── 5. BOOKING ────────────────────────────────────────────────
        $bookings = [
            // Selesai (kemarin)
            [
                'user_id'    => $playerId,
                'field_id'   => $fieldIds[0],
                'date'       => $today->copy()->subDay()->toDateString(),
                'start_time' => '10:00:00',
                'end_time'   => '11:00:00',
                'status'     => 'completed',
            ],
            // Telah dikonfirmasi (besok)
            [
                'user_id'    => $playerId,
                'field_id'   => $fieldIds[1],
                'date'       => $today->copy()->addDay()->toDateString(),
                'start_time' => '14:00:00',
                'end_time'   => '16:00:00',
                'status'     => 'confirmed',
            ],
            // Menunggu konfirmasi (3 hari lagi)
            [
                'user_id'    => $playerId,
                'field_id'   => $fieldIds[2],
                'date'       => $today->copy()->addDays(3)->toDateString(),
                'start_time' => '09:00:00',
                'end_time'   => '10:00:00',
                'status'     => 'pending',
            ],
            // Dibatalkan
            [
                'user_id'    => $playerId,
                'field_id'   => $fieldIds[0],
                'date'       => $today->copy()->subDays(2)->toDateString(),
                'start_time' => '16:00:00',
                'end_time'   => '18:00:00',
                'status'     => 'cancelled',
            ],
            // Selesai (5 hari lalu)
            [
                'user_id'    => $playerId,
                'field_id'   => $fieldIds[3],
                'date'       => $today->copy()->subDays(5)->toDateString(),
                'start_time' => '08:00:00',
                'end_time'   => '09:00:00',
                'status'     => 'completed',
            ],
        ];

        $now = now();
        foreach ($bookings as $b) {
            $existing = DB::table('bookings')
                ->where('user_id', $b['user_id'])
                ->where('field_id', $b['field_id'])
                ->where('date', $b['date'])
                ->where('start_time', $b['start_time'])
                ->first();

            if (!$existing) {
                DB::table('bookings')->insert(array_merge($b, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }
        $this->command->info(count($bookings) . ' booking dibuat');

        // ── 6. MAINTENANCE ────────────────────────────────────────────
        DB::table('maintenances')->whereIn('field_id', $fieldIds)->delete();

        $maintenances = [
            ['field_id' => $fieldIds[0], 'task_name' => 'Perbaikan Lampu Lapangan A', 'type' => 'Elektrikal', 'schedule_date' => $today->copy()->addDays(2), 'priority' => 'Tinggi', 'pic_name' => 'Budi Setiawan', 'status' => 'Menunggu'],
            ['field_id' => $fieldIds[0], 'task_name' => 'Pengecekan Jaring', 'type' => 'Lapangan', 'schedule_date' => $today->copy()->addDays(1), 'priority' => 'Sedang', 'pic_name' => 'Andi Permana', 'status' => 'Dikerjakan'],
            ['field_id' => $fieldIds[1], 'task_name' => 'Kalibrasi Scoreboard', 'type' => 'Elektrikal', 'schedule_date' => $today->copy()->subDays(1), 'priority' => 'Rendah', 'pic_name' => 'Rizky', 'status' => 'Selesai'],
            ['field_id' => $fieldIds[1], 'task_name' => 'Kebersihan Lapangan B', 'type' => 'Kebersihan', 'schedule_date' => $today->copy()->addDays(3), 'priority' => 'Sedang', 'pic_name' => 'Dewi', 'status' => 'Menunggu'],
            ['field_id' => $fieldIds[2], 'task_name' => 'Pengecatan Ulang Garis', 'type' => 'Lapangan', 'schedule_date' => $today->copy()->addDays(5), 'priority' => 'Rendah', 'pic_name' => 'Siti', 'status' => 'Menunggu'],
            ['field_id' => $fieldIds[2], 'task_name' => 'Perbaiki Papan Skor', 'type' => 'Elektrikal', 'schedule_date' => $today->copy()->subDays(2), 'priority' => 'Tinggi', 'pic_name' => 'Budi Setiawan', 'status' => 'Dikerjakan'],
            ['field_id' => $fieldIds[3], 'task_name' => 'Ganti Lampu Ruang Ganti', 'type' => 'Elektrikal', 'schedule_date' => $today->copy()->subDays(7), 'priority' => 'Sedang', 'pic_name' => 'Andi Permana', 'status' => 'Selesai'],
            ['field_id' => $fieldIds[3], 'task_name' => 'Pembersihan Drainase', 'type' => 'Kebersihan', 'schedule_date' => $today->copy()->addDays(4), 'priority' => 'Sedang', 'pic_name' => 'Rizky', 'status' => 'Menunggu'],
        ];

        foreach ($maintenances as $m) {
            DB::table('maintenances')->insert(array_merge($m, [
                'notes' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
        $this->command->info(count($maintenances) . ' tugas maintenance dibuat');

        $this->command->newLine();
        $this->command->line('──────────────────────────────────────────────');
        $this->command->line('  Owner Demo: owner.demo@test.com / password');
        $this->command->line('  Player Demo: player.demo@test.com / password');
        $this->command->line('  Akses: /owner/dashboard');
        $this->command->line('──────────────────────────────────────────────');
    }
}
