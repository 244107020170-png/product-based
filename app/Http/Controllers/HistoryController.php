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

        $nonSelesaiStatuses = ['cancelled', 'expired', 'rejected'];

        // ---- Bookings ----
        $query = Booking::with('field', 'review')
            ->where('user_id', $user->id);

        $statusFilter = $request->get('status', 'semua');
        if ($statusFilter && $statusFilter !== 'semua') {
            $map = [
                'selesai'     => ['selesai', 'completed'],
                'akan_datang' => ['confirmed', 'pending', 'waiting_payment', 'waiting_confirmation', 'paid'],
                'dibatalkan'  => ['cancelled', 'expired', 'rejected'],
            ];
            if (isset($map[$statusFilter])) {
                $query->whereIn('status', $map[$statusFilter]);
            }
        }

        $bookings = $query->orderBy('date', 'desc')->orderBy('start_time', 'desc')->get();

        // ---- Match joins (ALL, regardless of payment status) ----
        $matchJoins = MatchPlayer::where('user_id', $user->id)
            ->whereHas('match')
            ->with('match.field')
            ->orderByDesc('confirmed_at')
            ->get();

        $sortWaktu = $request->get('sort_waktu', 'terbaru');
        $sortHarga = $request->get('sort_harga', 'teratas');

        // ---- Build unified sorted collection ----
        $allItems = collect();

        foreach ($bookings as $b) {
            $statusKey = $b->status;
            if (!in_array($statusKey, $nonSelesaiStatuses)) {
                $bookingEnd = \Carbon\Carbon::parse($b->date->format('Y-m-d').' '.$b->end_time);
                if ($bookingEnd->isPast()) {
                    $statusKey = 'selesai';
                }
            }
            $allItems->push([
                'type' => 'booking',
                'original' => $b,
                'sort_date' => $b->date->format('Y-m-d'),
                'sort_time' => $b->start_time,
                'sort_price' => (int) ($b->total_price ?? 0),
                'status_key' => $statusKey,
            ]);
        }

        foreach ($matchJoins as $mj) {
            $cm = $mj->match;
            if (!$cm) continue;
            $isPast = \Carbon\Carbon::parse($cm->date.' '.($cm->time ?? '00:00'))->isPast();
            $statusKey = $isPast ? 'selesai' : 'confirmed';
            $allItems->push([
                'type' => 'match',
                'original' => $mj,
                'sort_date' => $cm->date instanceof \Carbon\Carbon ? $cm->date->format('Y-m-d') : $cm->date,
                'sort_time' => $cm->time ?? '00:00:00',
                'sort_price' => (int) ($mj->contribution_amount ?? 0),
                'status_key' => $statusKey,
            ]);
        }

        // ---- Apply sorting ----
        if ($sortHarga === 'terbawah') {
            $allItems = $allItems->sortBy('sort_price')->values();
        } elseif ($sortHarga === 'teratas') {
            $allItems = $allItems->sortByDesc('sort_price')->values();
        } elseif ($sortWaktu === 'terlama') {
            $allItems = $allItems->sortBy('sort_date')->values();
        } else {
            $allItems = $allItems->sortByDesc('sort_date')->values();
        }

        // ---- Stats (global, unfiltered) ----
        $allBookings = Booking::where('user_id', $user->id)->get();
        $allMatchJoins = MatchPlayer::where('user_id', $user->id)
            ->whereHas('match')
            ->get();
        $now = now();

        $totalSemua = $allBookings->count() + $allMatchJoins->count();

        $totalSelesai = $allBookings->filter(function ($b) use ($nonSelesaiStatuses, $now) {
            if (in_array($b->status, $nonSelesaiStatuses)) return false;
            $end = \Carbon\Carbon::parse($b->date->format('Y-m-d').' '.$b->end_time);
            return $end->isPast();
        })->count() + $allMatchJoins->filter(function ($mp) {
            return $mp->match && $mp->match->date < now()->toDateString();
        })->count();

        $totalAkan = $allBookings->filter(function ($b) use ($nonSelesaiStatuses, $now) {
            if (in_array($b->status, $nonSelesaiStatuses)) return false;
            $end = \Carbon\Carbon::parse($b->date->format('Y-m-d').' '.$b->end_time);
            return !$end->isPast();
        })->count() + $allMatchJoins->filter(function ($mp) {
            return $mp->match && $mp->match->date >= now()->toDateString();
        })->count();

        $totalDibatal = $allBookings->whereIn('status', ['cancelled', 'expired', 'rejected'])->count();

        // Total pengeluaran — responsive to active filter
        $totalPengeluaran = $bookings
            ->reject(fn($b) => in_array($b->status, $nonSelesaiStatuses))
            ->sum(fn($booking) => $booking->total_price);

        // User's reviews
        $userReviews = \App\Models\Review::with('field')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $reviewedFieldIds = $userReviews->pluck('field_id')->toArray();

        return view('history.index', compact(
            'allItems',
            'userReviews',
            'totalSemua',
            'totalSelesai',
            'totalAkan',
            'totalDibatal',
            'totalPengeluaran',
            'statusFilter',
            'sortWaktu',
            'sortHarga',
            'reviewedFieldIds',
        ));
    }
}
