<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    public function index(Request $request)
    {
        $query = Field::with(['owner:id,name', 'verifier:id,name'])
            ->withCount('bookings');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('owner', fn($oq) => $oq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->get('verification_status')) {
            $query->where('verification_status', $status);
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        $sort = $request->get('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $fields = $query->paginate(15)->withQueryString();

        $totalFields = Field::count();
        $pendingVerification = Field::where('verification_status', 'pending')->count();

        return view('admin.fields.index', compact('fields', 'totalFields', 'pendingVerification'));
    }

    public function show(Field $field)
    {
        $field->load(['owner:id,name,email,phone', 'bookings' => fn($q) => $q->latest()->take(10)->with('user:id,name')]);
        $field->loadCount('bookings', 'reviews', 'favorites', 'matches');
        return view('admin.fields.show', compact('field'));
    }
}
