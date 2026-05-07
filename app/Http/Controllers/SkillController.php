<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;
use App\Models\Matchs;

class SkillController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $uid  = $user->id;

        // ── Total stats ──────────────────────────────────────────────
        $totalBookings = Booking::where('user_id', $uid)
            ->whereIn('status', ['selesai', 'confirmed', 'pending'])
            ->count();

        $totalMatches = DB::table('match_players')
            ->where('user_id', $uid)
            ->count();

        // ── Points & Level ───────────────────────────────────────────
        // 1 booking = 1 poin, 1 public match = 1 poin
        $totalPoints = $totalBookings + $totalMatches;

        $levels = [
            ['name' => 'Beginner', 'min' => 0,  'max' => 5,  'icon' => '⭐',  'range' => '1-5 Match'],
            ['name' => 'Active',   'min' => 6,  'max' => 20, 'icon' => '🏆',  'range' => '6-20 Match'],
            ['name' => 'Pro',      'min' => 21, 'max' => PHP_INT_MAX, 'icon' => '🎯', 'range' => '>20 Match'],
        ];

        $currentLevel = $levels[0];
        $nextLevel    = $levels[1];
        foreach ($levels as $i => $level) {
            if ($totalPoints >= $level['min']) {
                $currentLevel = $level;
                $nextLevel    = $levels[$i + 1] ?? null;
            }
        }

        // Progress bar % ke level berikutnya
        if ($nextLevel) {
            $progressPct  = min(100, round(($totalPoints / max(1, $nextLevel['min'])) * 100));
            if ($totalPoints > 0 && $progressPct < 5) $progressPct = 5;
            $pointsToNext = max(0, $nextLevel['min'] - $totalPoints);
        } else {
            $progressPct  = 100;
            $pointsToNext = 0;
        }

        // ── Olahraga Favorit ─────────────────────────────────────────
        // Ambil semua booking user → group by sport type dari nama lapangan
        $bookingsWithField = Booking::with('field')
            ->where('user_id', $uid)
            ->whereIn('status', ['selesai', 'confirmed', 'pending'])
            ->get();

        // Group match_players → join matches → join fields
        $matchSportRaw = DB::table('match_players as mp')
            ->join('matches as m', 'm.id', '=', 'mp.match_id')
            ->join('fields as f', 'f.id', '=', 'm.field_id')
            ->where('mp.user_id', $uid)
            ->select('f.name as field_name')
            ->get();

        // Helper: tebak jenis olahraga dari nama lapangan
        $sportColors = [
            'futsal'    => ['color' => '#1a1a6e', 'bg' => '#e8e8f8'],
            'voli'      => ['color' => '#b45309', 'bg' => '#fef3c7'],
            'badminton' => ['color' => '#b45309', 'bg' => '#fef3c7'],
            'bulu tangkis' => ['color' => '#b45309', 'bg' => '#fef3c7'],
            'basket'    => ['color' => '#dc2626', 'bg' => '#fee2e2'],
            'basketball'=> ['color' => '#dc2626', 'bg' => '#fee2e2'],
            'renang'    => ['color' => '#0369a1', 'bg' => '#e0f2fe'],
            'tennis'    => ['color' => '#15803d', 'bg' => '#dcfce7'],
        ];

        $detectSport = function (string $name): string {
            $name = strtolower($name);
            if (str_contains($name, 'futsal'))              return 'Futsal';
            if (str_contains($name, 'voli'))                return 'Voli';
            if (str_contains($name, 'badminton') || str_contains($name, 'bulu tangkis')) return 'Badminton';
            if (str_contains($name, 'basket') || str_contains($name, 'basketball'))      return 'Basketball';
            if (str_contains($name, 'renang'))              return 'Renang';
            if (str_contains($name, 'tennis'))              return 'Tennis';
            return 'Lainnya';
        };

        $sportStats = [];

        foreach ($bookingsWithField as $b) {
            if (!$b->field) continue;
            $sport = $detectSport($b->field->name);
            $sportStats[$sport]['bookings'] = ($sportStats[$sport]['bookings'] ?? 0) + 1;
            $sportStats[$sport]['matches']  = $sportStats[$sport]['matches'] ?? 0;
        }

        foreach ($matchSportRaw as $row) {
            $sport = $detectSport($row->field_name);
            $sportStats[$sport]['bookings'] = $sportStats[$sport]['bookings'] ?? 0;
            $sportStats[$sport]['matches']  = ($sportStats[$sport]['matches'] ?? 0) + 1;
        }

        // Sort by total activity desc
        uasort($sportStats, fn($a, $b) =>
            (($b['bookings'] + $b['matches']) <=> ($a['bookings'] + $a['matches']))
        );

        // Attach color info & compute percentage
        $maxActivity = collect($sportStats)->map(fn($s) => $s['bookings'] + $s['matches'])->max() ?: 1;
        $sports = [];
        $colorKeys = array_keys($sportColors);
        $ci = 0;
        foreach ($sportStats as $sport => $stat) {
            $total = $stat['bookings'] + $stat['matches'];
            $key   = strtolower($sport);
            $colorInfo = $sportColors[$key] ?? ['color' => $colorKeys[$ci % count($colorKeys)], 'bg' => '#f3f4f6'];
            $ci++;
            $sports[] = [
                'name'     => $sport,
                'bookings' => $stat['bookings'],
                'matches'  => $stat['matches'],
                'color'    => $colorInfo['color'],
                'bg'       => $colorInfo['bg'],
                'pct'      => round(($total / $maxActivity) * 100),
            ];
        }

        // ── Badges ───────────────────────────────────────────────────
        $badges = [
            [
                'name'    => 'Beginner',
                'range'   => '1-5 Match',
                'icon'    => '⭐',
                'earned'  => $totalPoints >= 1,
                'current' => $currentLevel['name'] === 'Beginner',
            ],
            [
                'name'    => 'Active',
                'range'   => '6-20 Match',
                'icon'    => '🏆',
                'earned'  => $totalPoints >= 6,
                'current' => $currentLevel['name'] === 'Active',
            ],
            [
                'name'    => 'Pro',
                'range'   => '>20 Match',
                'icon'    => '🎯',
                'earned'  => $totalPoints >= 21,
                'current' => $currentLevel['name'] === 'Pro',
            ],
        ];

        return view('skill.index', compact(
            'totalBookings',
            'totalMatches',
            'totalPoints',
            'currentLevel',
            'nextLevel',
            'progressPct',
            'pointsToNext',
            'sports',
            'badges',
            'levels',
        ));
    }
}
