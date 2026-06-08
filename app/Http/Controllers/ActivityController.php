<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;
use Carbon\Carbon;

class ActivityController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $uid  = $user->id;

        // ── Level & Points ───────────────────────────────────────────
        $totalBookings = Booking::where('user_id', $uid)
            ->whereIn('status', ['selesai', 'confirmed', 'pending'])
            ->count();

        $totalMatches = DB::table('match_players')
            ->where('user_id', $uid)
            ->count();

        $totalReviews = \App\Models\Review::where('user_id', $uid)->count();

        // Poin: booking=1, match=2, review=3
        $totalPoints = ($totalBookings * 1) + ($totalMatches * 2) + ($totalReviews * 3);

        $levels = [
            ['name' => 'Pemula', 'min' => 0,  'max' => 5],
            ['name' => 'Aktif',   'min' => 6,  'max' => 20],
            ['name' => 'Pro',      'min' => 21, 'max' => PHP_INT_MAX],
        ];

        $currentLevel = $levels[0];
        $nextLevel    = $levels[1];
        foreach ($levels as $i => $level) {
            if ($totalPoints >= $level['min']) {
                $currentLevel = $level;
                $nextLevel    = $levels[$i + 1] ?? null;
            }
        }

        if ($nextLevel) {
            // Progress = poin saat ini / target next level × 100
            // Contoh: 6 poin mau Pro (21) → 6/21 = 28%
            $progressPct  = min(100, round(($totalPoints / max(1, $nextLevel['min'])) * 100));
            // Minimal tampil 5% kalau sudah punya poin supaya bar tidak kosong
            if ($totalPoints > 0 && $progressPct < 5) $progressPct = 5;
            $pointsToNext = max(0, $nextLevel['min'] - $totalPoints);
        } else {
            $progressPct  = 100;
            $pointsToNext = 0;
        }

        // ── Aktivitas Terakhir ────────────────────────────────────────
        $activities = collect();

        // Booking activities
        $bookings = Booking::with('field')
            ->where('user_id', $uid)
            ->latest('created_at')
            ->take(10)
            ->get();

        foreach ($bookings as $b) {
            $activities->push([
                'type'       => 'booking',
                'label'      => 'Kamu memesan ' . ($b->field?->name ?? 'Lapangan'),
                'points'     => +1,
                'sport'      => $this->detectSport($b->field?->name ?? ''),
                'created_at' => $b->created_at,
            ]);
        }

        // Match join activities
        $matchRows = DB::table('match_players as mp')
            ->join('matches as m', 'm.id', '=', 'mp.match_id')
            ->join('fields as f', 'f.id', '=', 'm.field_id')
            ->where('mp.user_id', $uid)
            ->select('f.name as field_name', 'mp.created_at')
            ->orderByDesc('mp.created_at')
            ->take(10)
            ->get();

        foreach ($matchRows as $row) {
            $activities->push([
                'type'       => 'match',
                'label'      => 'Kamu mengikuti pertandingan umum ' . $this->detectSport($row->field_name),
                'points'     => +2,
                'sport'      => $this->detectSport($row->field_name),
                'created_at' => Carbon::parse($row->created_at),
            ]);
        }

        // Review activities
        $reviews = \App\Models\Review::with('field')
            ->where('user_id', $uid)
            ->latest('created_at')
            ->take(10)
            ->get();

        foreach ($reviews as $rv) {
            $activities->push([
                'type'       => 'review',
                'label'      => 'Kamu memberi ulasan untuk ' . ($rv->field?->name ?? 'Lapangan'),
                'points'     => +3,
                'sport'      => $this->detectSport($rv->field?->name ?? ''),
                'created_at' => $rv->created_at,
            ]);
        }

        // Sort all by date desc, take last 8
        $activities = $activities->sortByDesc('created_at')->take(8)->values();

        return view('activity.index', compact(
            'totalPoints',
            'currentLevel',
            'nextLevel',
            'progressPct',
            'pointsToNext',
            'activities',
        ));
    }

    private function detectSport(string $name): string
    {
        $n = strtolower($name);
        if (str_contains($n, 'futsal'))                                   return 'Futsal';
        if (str_contains($n, 'voli') || str_contains($n, 'volley'))      return 'Voli';
        if (str_contains($n, 'badminton') || str_contains($n, 'bulu'))   return 'Badminton';
        if (str_contains($n, 'basket') || str_contains($n, 'basketball'))return 'Basket';
        if (str_contains($n, 'renang') || str_contains($n, 'kolam'))     return 'Renang';
        if (str_contains($n, 'tennis'))                                   return 'Tennis';
        return 'Olahraga';
    }
}
