<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Field;
use App\Models\Booking;
use App\Models\Community;
use App\Models\Matchs;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPlayers = User::where('role', 'player')->count();
        $totalOwners = User::where('role', 'owner')->count();
        $totalFields = Field::count();
        $totalCommunities = Community::count();
        $totalBookings = Booking::count();
        $totalMatches = Matchs::count();

        $todayBookings = Booking::whereDate('created_at', today())->count();
        $monthBookings = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $revenueBookings = Booking::whereIn('status', ['confirmed', 'completed'])
            ->with('field.discounts')
            ->get();
        $totalRevenue = $revenueBookings->sum('total_price');

        $bookingSuccessRate = $totalBookings > 0
            ? round((Booking::whereIn('status', ['confirmed', 'completed'])->count() / $totalBookings) * 100, 1)
            : 0;

        $activeCommunities = Community::whereHas('members')->count();

        $todayTarget = 550;
        $dailyProgress = $todayBookings > 0 ? min(100, round(($todayBookings / $todayTarget) * 100)) : 0;

        $monthGrowth = null;
        if ($monthBookings > 0) {
            $lastMonth = Booking::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count();
            $monthGrowth = $lastMonth > 0 ? round((($monthBookings - $lastMonth) / $lastMonth) * 100, 1) : 100;
        }

        $bookingsPerMonth = Booking::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $chartData = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $maxVal = 1;
        foreach (range(1, 12) as $m) {
            $val = $bookingsPerMonth[$m] ?? 0;
            $chartData[] = ['month' => $months[$m - 1], 'total' => $val, 'index' => $m];
            if ($val > $maxVal) $maxVal = $val ?: 1;
        }

        $newPlayers = User::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->where('role', 'player')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $newOwners = User::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->where('role', 'owner')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $playerGrowthData = [];
        $ownerGrowthData = [];
        foreach (range(1, 12) as $m) {
            $playerGrowthData[] = $newPlayers[$m] ?? 0;
            $ownerGrowthData[] = $newOwners[$m] ?? 0;
        }

        $popularField = Booking::select('field_id', DB::raw('COUNT(*) as total'))
            ->with('field:id,name')
            ->groupBy('field_id')
            ->orderByDesc('total')
            ->first();
        $popularFieldName = $popularField?->field?->name ?? 'Belum ada data';
        $popularFieldBookings = $popularField?->total ?? 0;

        $topCity = Booking::select('fields.location', DB::raw('COUNT(*) as total'))
            ->join('fields', 'bookings.field_id', '=', 'fields.id')
            ->groupBy('fields.location')
            ->orderByDesc('total')
            ->first();
        $topCityName = $topCity ? explode(',', $topCity->location)[0] : 'Belum ada data';

        $recentBookings = Booking::with(['user:id,name,email', 'field:id,name,owner_id,price_per_hour', 'field.owner:id,name'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($b) {
                $initial = strtoupper(substr($b->user->name ?? 'U', 0, 1) . substr(explode(' ', $b->user->name ?? 'U')[1] ?? '', 0, 1));
                if (empty(trim($initial)) || strlen(trim($initial)) < 1) $initial = strtoupper(substr($b->user->name ?? 'U', 0, 1));
                $colorClasses = ['bg-adm-secondary-container text-adm-on-secondary-container', 'bg-adm-primary-container text-white', 'bg-adm-outline text-white'];
                $colorIdx = crc32($b->id ?? 0) % 3;
                return [
                    'id' => $b->id,
                    'initial' => $initial,
                    'color' => $colorClasses[$colorIdx],
                    'user_name' => $b->user->name ?? 'User',
                    'user_email' => $b->user->email ?? '',
                    'field_name' => $b->field->name ?? 'Lapangan',
                    'owner_name' => $b->field->owner->name ?? 'Pemilik',
                    'time' => $b->created_at->format('H:i') . ' WIB',
                    'date' => $b->created_at->isToday() ? 'Hari ini' : $b->created_at->format('d M'),
                    'amount' => $b->total_price,
                    'status' => $b->status,
                ];
            });

        $activeUsersToday = User::where('role', 'player')
            ->whereHas('bookings', fn($q) => $q->whereDate('created_at', today()))
            ->count();

        $activeOwnersToday = User::where('role', 'owner')
            ->whereHas('fields.bookings', fn($q) => $q->whereDate('created_at', today()))
            ->count();

        $peakTime = '19:00 - 21:00';

        $avgResponseTime = '~4 Menit';

        $growthUsers = User::where('role', 'player')->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();
        $growthOwners = User::where('role', 'owner')->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();

        return view('admin.dashboard', compact(
            'totalPlayers',
            'totalOwners',
            'totalFields',
            'totalRevenue',
            'totalBookings',
            'totalCommunities',
            'totalMatches',
            'todayBookings',
            'monthBookings',
            'bookingSuccessRate',
            'activeCommunities',
            'dailyProgress',
            'todayTarget',
            'monthGrowth',
            'chartData',
            'maxVal',
            'playerGrowthData',
            'ownerGrowthData',
            'popularFieldName',
            'popularFieldBookings',
            'topCityName',
            'recentBookings',
            'activeUsersToday',
            'activeOwnersToday',
            'peakTime',
            'avgResponseTime',
            'growthUsers',
            'growthOwners',
        ));
    }
}
