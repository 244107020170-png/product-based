<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\MatchPlayer;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Booking::with('field')
            ->where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc');

        // Filter by status
        $statusFilter = $request->get('status', 'semua');
        if ($statusFilter && $statusFilter !== 'semua') {
            // Map label → DB value(s)
            $map = [
                'selesai'     => ['selesai', 'completed'],
                'akan_datang' => ['confirmed', 'pending', 'waiting_payment', 'waiting_confirmation', 'paid'],
                'dibatalkan'  => ['cancelled', 'expired', 'rejected'],
            ];
            if (isset($map[$statusFilter])) {
                $query->whereIn('status', $map[$statusFilter]);
            }
        }

        // Filter by sort (waktu)
        $sortWaktu = $request->get('sort_waktu', 'terbaru');
        if ($sortWaktu === 'terlama') {
            $query->reorder('date', 'asc')->orderBy('start_time', 'asc');
        }

        // Filter by sort (harga)
        $sortHarga = $request->get('sort_harga', 'teratas');

        $bookings = $query->get();

        // Sort by price in memory if needed
        if ($sortHarga === 'teratas') {
            $bookings = $bookings->sortByDesc(fn($b) => optional($b->field)->price_per_hour ?? 0)->values();
        } elseif ($sortHarga === 'terbawah') {
            $bookings = $bookings->sortBy(fn($b) => optional($b->field)->price_per_hour ?? 0)->values();
        }

        // Fetch confirmed match joins (paid, confirmed by host)
        $matchJoins = MatchPlayer::where('user_id', $user->id)
            ->where('payment_status', \App\Enums\PaymentStatus::PAID)
            ->whereHas('match')
            ->with('match.field')
            ->orderByDesc('confirmed_at')
            ->get();

        // Stats — include match joins in counts
        $allBookings  = Booking::where('user_id', $user->id)->get();
        $totalSemua   = $allBookings->count() + $matchJoins->count();
        $totalSelesai = $allBookings->whereIn('status', ['selesai', 'completed'])->count()
            + $matchJoins->filter(function ($mp) {
                return $mp->match && $mp->match->date < now()->toDateString();
            })->count();
        $totalAkan    = $allBookings->whereIn('status', ['confirmed', 'pending', 'waiting_payment', 'waiting_confirmation', 'paid'])->count()
            + $matchJoins->filter(function ($mp) {
                return $mp->match && $mp->match->date >= now()->toDateString();
            })->count();
        $totalDibatal = $allBookings->whereIn('status', ['cancelled', 'expired', 'rejected'])->count();

        // Total pengeluaran — responsive to active filter
        $totalPengeluaran = $bookings
            ->reject(fn($b) => in_array($b->status, ['cancelled', 'expired', 'rejected']))
            ->sum(function ($booking) {
                $field = $booking->field;
                if (!$field) return 0;
                $start = \Carbon\Carbon::parse($booking->start_time);
                $end   = \Carbon\Carbon::parse($booking->end_time);
                $hours = max(1, $start->diffInHours($end));
                return $field->price_per_hour * $hours;
            });

        return view('history.index', compact(
            'bookings',
            'matchJoins',
            'totalSemua',
            'totalSelesai',
            'totalAkan',
            'totalDibatal',
            'totalPengeluaran',
            'statusFilter',
            'sortWaktu',
            'sortHarga',
        ));
    }
}
