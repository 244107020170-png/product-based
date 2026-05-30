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

        $query = Booking::with('field', 'review')
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
        // Apply same auto-selesai logic as the view:
        // non-cancelled bookings whose end time has passed → selesai
        $now = now();
        $allBookings = Booking::where('user_id', $user->id)->get();
        $totalSemua  = $allBookings->count() + $matchJoins->count();

        $nonSelesaiStatuses = ['cancelled', 'expired', 'rejected'];
        $totalSelesai = $allBookings->filter(function ($b) use ($nonSelesaiStatuses, $now) {
            if (in_array($b->status, $nonSelesaiStatuses)) return false;
            $end = \Carbon\Carbon::parse($b->date->format('Y-m-d').' '.$b->end_time);
            return $end->isPast();
        })->count() + $matchJoins->filter(function ($mp) {
            return $mp->match && $mp->match->date < now()->toDateString();
        })->count();

        $totalAkan = $allBookings->filter(function ($b) use ($nonSelesaiStatuses, $now) {
            if (in_array($b->status, $nonSelesaiStatuses)) return false;
            $end = \Carbon\Carbon::parse($b->date->format('Y-m-d').' '.$b->end_time);
            return !$end->isPast();
        })->count() + $matchJoins->filter(function ($mp) {
            return $mp->match && $mp->match->date >= now()->toDateString();
        })->count();

        $totalDibatal = $allBookings->whereIn('status', ['cancelled', 'expired', 'rejected'])->count();

        // Total pengeluaran — responsive to active filter
        $totalPengeluaran = $bookings
            ->reject(fn($b) => in_array($b->status, ['cancelled', 'expired', 'rejected']))
            ->sum(fn($booking) => $booking->total_price);

        // User's reviews
        $userReviews = \App\Models\Review::with('field')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('history.index', compact(
            'bookings',
            'matchJoins',
            'userReviews',
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
