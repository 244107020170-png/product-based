<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user:id,name,email', 'field:id,name,owner_id', 'field.owner:id,name']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('field', fn($fq) => $fq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $sort = $request->get('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest('paid_at');
        } else {
            $query->latest('paid_at');
        }

        $payments = $query->paginate(15)->withQueryString();

        $totalRevenue = Booking::whereIn('status', ['confirmed', 'completed'])
            ->with('field.discounts')
            ->get()
            ->sum('total_price');

        $successCount = Booking::whereIn('status', ['confirmed', 'completed'])->count();
        $pendingCount = Booking::whereIn('status', ['waiting_payment', 'pending', 'paid'])->count();
        $cancelledCount = Booking::whereIn('status', ['cancelled', 'expired', 'rejected'])->count();

        return view('admin.payments.index', compact('payments', 'totalRevenue', 'successCount', 'pendingCount', 'cancelledCount'));
    }
}
