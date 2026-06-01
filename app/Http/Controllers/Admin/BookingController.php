<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user:id,name,email', 'field:id,name,owner_id,location', 'field.owner:id,name']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('field', fn($fq) => $fq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        if ($ownerId = $request->get('owner_id')) {
            $query->whereHas('field', fn($fq) => $fq->where('owner_id', $ownerId));
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();

        $totalBookings = Booking::count();
        $owners = User::where('role', 'owner')->pluck('name', 'id');

        return view('admin.bookings.index', compact('bookings', 'totalBookings', 'owners'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'field.owner', 'field.discounts', 'review']);
        return view('admin.bookings.show', compact('booking'));
    }
}
