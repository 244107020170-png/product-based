<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Field;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'owner')
            ->withCount(['fields'])
            ->withAvg('fields', 'rating');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $owners = $query->latest()->paginate(15)->withQueryString();

        $totalOwners = User::where('role', 'owner')->count();
        $activeToday = User::where('role', 'owner')
            ->whereHas('fields.bookings', fn($q) => $q->whereDate('created_at', today()))
            ->count();

        return view('admin.owners.index', compact('owners', 'totalOwners', 'activeToday'));
    }

    public function show(User $owner)
    {
        abort_if($owner->role !== 'owner', 404);
        $owner->loadCount(['fields']);
        $owner->load(['fields' => fn($q) => $q->withCount('bookings')]);
        return view('admin.owners.show', compact('owner'));
    }
}
