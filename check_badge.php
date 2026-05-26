<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::first();
if (!$user) { echo "No user found\n"; exit; }

echo "User ID: " . $user->id . "\n";
echo "Points (db): " . ($user->points ?? 0) . "\n";

$bookings = App\Models\Booking::where('user_id', $user->id)
    ->whereIn('status', ['selesai', 'confirmed', 'pending'])
    ->count();
echo "Bookings (selesai/confirmed/pending): $bookings\n";

$allBookings = App\Models\Booking::where('user_id', $user->id)->count();
echo "Total bookings: $allBookings\n";

$statuses = App\Models\Booking::where('user_id', $user->id)->pluck('status')->unique()->toArray();
echo "Statuses: " . implode(', ', $statuses) . "\n";

$matches = Illuminate\Support\Facades\DB::table('match_players')
    ->where('user_id', $user->id)
    ->count();
echo "Match players: $matches\n";
echo "Badge points: " . ($bookings + $matches) . "\n";
