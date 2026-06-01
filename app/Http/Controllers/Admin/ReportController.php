<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);

        $userGrowth = User::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $ownerGrowth = User::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->where('role', 'owner')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $bookingsPerCity = Booking::select('fields.location', DB::raw('COUNT(*) as total'))
            ->join('fields', 'bookings.field_id', '=', 'fields.id')
            ->whereYear('bookings.created_at', $year)
            ->groupBy('fields.location')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $bookingsPerSport = Field::select('type', DB::raw('COUNT(*) as total'))
            ->join('bookings', 'fields.id', '=', 'bookings.field_id')
            ->whereYear('bookings.created_at', $year)
            ->groupBy('fields.type')
            ->orderByDesc('total')
            ->pluck('total', 'type');

        $revenuePerMonth = Booking::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereIn('status', ['confirmed', 'completed'])
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $totalRevenue = Booking::whereIn('status', ['confirmed', 'completed'])
            ->whereYear('created_at', $year)
            ->with('field.discounts')
            ->get()
            ->sum('total_price');

        return view('admin.reports.index', compact(
            'year', 'userGrowth', 'ownerGrowth',
            'bookingsPerCity', 'bookingsPerSport',
            'revenuePerMonth', 'totalRevenue'
        ));
    }
}
