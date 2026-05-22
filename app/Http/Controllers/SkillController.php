<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;

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
            ['name' => 'Beginner', 'min' => 0,  'max' => 5,  'icon' => '<svg viewBox="0 0 24 24" fill="none"><path d="M12 22V12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M12 12C12 12 7 11 5 7C3 3 7 2 9 3C11 4 12 7 12 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 12C12 12 17 10 18 6C19 2 15 2 13 3C11 4 12 7 12 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',  'range' => '1-5 Match'],
            ['name' => 'Active',   'min' => 6,  'max' => 20, 'icon' => '<svg viewBox="0 0 24 24" fill="none"><path d="M13 2L3 14H12L11 22L21 10H12L13 2Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"/></svg>',  'range' => '6-20 Match'],
            ['name' => 'Pro',      'min' => 21, 'max' => PHP_INT_MAX, 'icon' => '<svg viewBox="0 0 24 24" fill="none"><path d="M8 21H16M12 17V21M7 4H17V11C17 14.314 14.761 17 12 17C9.239 17 7 14.314 7 11V4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 6H4C4 6 3 10 6 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M17 6H20C20 6 21 10 18 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>', 'range' => '>20 Match'],
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
                'icon'    => $levels[0]['icon'],
                'earned'  => $totalPoints >= 1,
                'current' => $currentLevel['name'] === 'Beginner',
            ],
            [
                'name'    => 'Active',
                'range'   => '6-20 Match',
                'icon'    => $levels[1]['icon'],
                'earned'  => $totalPoints >= 6,
                'current' => $currentLevel['name'] === 'Active',
            ],
            [
                'name'    => 'Pro',
                'range'   => '>20 Match',
                'icon'    => $levels[2]['icon'],
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
