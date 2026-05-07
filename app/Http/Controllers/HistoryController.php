<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

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
                'selesai'     => ['selesai'],
                'akan_datang' => ['confirmed', 'pending'],
                'dibatalkan'  => ['cancelled'],
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
        // We'll sort by field price after loading if needed – for now keep DB default

        $bookings = $query->get();

        // Sort by price in memory if needed
        if ($sortHarga === 'teratas') {
            $bookings = $bookings->sortByDesc(fn($b) => optional($b->field)->price_per_hour ?? 0)->values();
        } elseif ($sortHarga === 'terbawah') {
            $bookings = $bookings->sortBy(fn($b) => optional($b->field)->price_per_hour ?? 0)->values();
        }

        // Stats — hanya 3 status: selesai, akan datang (confirmed+pending), dibatalkan
        $allBookings  = Booking::where('user_id', $user->id)->get();
        $totalSemua   = $allBookings->count();
        $totalSelesai = $allBookings->where('status', 'selesai')->count();
        $totalAkan    = $allBookings->whereIn('status', ['confirmed', 'pending'])->count();
        $totalDibatal = $allBookings->where('status', 'cancelled')->count();

        // Total pengeluaran (selesai + confirmed + pending)
        $totalPengeluaran = $allBookings
            ->whereIn('status', ['selesai', 'confirmed', 'pending'])
            ->sum(function ($booking) {
                if (!$booking->field) return 0;
                $start = \Carbon\Carbon::parse($booking->start_time);
                $end   = \Carbon\Carbon::parse($booking->end_time);
                $hours = max(1, $start->diffInHours($end));
                return $booking->field->price_per_hour * $hours;
            });

        return view('history.index', compact(
            'bookings',
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
