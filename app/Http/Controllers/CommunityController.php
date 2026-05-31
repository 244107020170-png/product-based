<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Field;
use App\Models\Matchs;
use App\Notifications\CommunityJoined;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'sport_category' => 'required|string|max:100',
            'city'           => 'required|string|max:100',
            'description'    => 'required|string|max:1000',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'whatsapp_link'  => 'required|string|max:500',
            'instagram_link' => 'nullable|string|max:500',
        ]);

        $photo = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo')->store('community-photos', 'public');
        }

        $community = Community::create([
            'created_by'     => Auth::id(),
            'name'           => $validated['name'],
            'sport_category' => $validated['sport_category'],
            'city'           => $validated['city'],
            'description'    => $validated['description'],
            'photo'          => $photo,
            'whatsapp_link'  => $validated['whatsapp_link'],
            'instagram_link' => $validated['instagram_link'] ?? null,
        ]);

        // Auto-join the creator as first member
        $community->members()->attach(Auth::id());

        return redirect()->route('matches.index', ['tab' => 'komunitas'])
            ->with('success', 'Komunitas berhasil dibuat!');
    }

    public function join(Community $community)
    {
        $user = Auth::user();

        if ($community->members()->where('user_id', $user->id)->exists()) {
            return response()->json(['joined' => true, 'message' => 'Anda sudah bergabung.']);
        }

        $community->members()->attach($user->id);

        if ($community->creator && $community->creator->id !== $user->id) {
            $community->creator->notify(new CommunityJoined($community, $user));
        }

        return response()->json([
            'joined'   => true,
            'count'    => $community->members()->count(),
            'whatsapp' => $community->whatsapp_link,
            'message'  => 'Berhasil bergabung!',
        ]);
    }

    public function leave(Community $community)
    {
        $user = Auth::user();

        if ($community->created_by === $user->id) {
            return response()->json(['left' => false, 'message' => 'Pembuat komunitas tidak bisa keluar.']);
        }

        $community->members()->detach($user->id);

        return response()->json([
            'left'    => true,
            'count'   => $community->members()->count(),
            'message' => 'Berhasil keluar dari komunitas.',
        ]);
    }

    public function sportCategories()
    {
        $fromMatches = Matchs::whereNotNull('sport')
            ->distinct('sport')
            ->pluck('sport');

        $fromFields = Field::whereNotNull('type')
            ->distinct('type')
            ->pluck('type');

        $fromCommunities = Community::whereNotNull('sport_category')
            ->distinct('sport_category')
            ->pluck('sport_category');

        $all = collect()
            ->merge($fromMatches)
            ->merge($fromFields)
            ->merge($fromCommunities)
            ->unique()
            ->sort()
            ->values();

        return response()->json($all);
    }

    public function myCommunities()
    {
        $user = Auth::user();
        $communities = $user->communities()->withCount('members')->get();
        return response()->json($communities);
    }
}
