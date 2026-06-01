<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'player')
            ->withCount(['bookings']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $sort = $request->get('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest();
        } elseif ($sort === 'name') {
            $query->orderBy('name');
        } else {
            $query->latest();
        }

        $users = $query->paginate(15)->withQueryString();

        $totalPlayers = User::where('role', 'player')->count();
        $activeToday = User::where('role', 'player')
            ->whereHas('bookings', fn($q) => $q->whereDate('created_at', today()))
            ->count();

        return view('admin.users.index', compact('users', 'totalPlayers', 'activeToday'));
    }

    public function show(User $user)
    {
        $user->loadCount(['bookings', 'reviews', 'joinedMatches', 'favorites']);
        $user->load(['bookings' => fn($q) => $q->latest()->take(10)->with('field:id,name')]);
        return view('admin.users.show', compact('user'));
    }
}
