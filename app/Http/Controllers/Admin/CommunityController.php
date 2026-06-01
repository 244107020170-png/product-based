<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index(Request $request)
    {
        $query = Community::withCount('members')
            ->with('creator:id,name');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('sport_category', 'like', "%{$search}%");
            });
        }

        if ($sport = $request->get('sport')) {
            $query->where('sport_category', $sport);
        }

        $communities = $query->latest()->paginate(15)->withQueryString();

        $totalCommunities = Community::count();
        $sports = Community::select('sport_category')->distinct()->pluck('sport_category');

        return view('admin.communities.index', compact('communities', 'totalCommunities', 'sports'));
    }

    public function show(Community $community)
    {
        $community->load(['creator:id,name,email', 'members' => fn($q) => $q->take(50)]);
        $community->loadCount('members');
        return view('admin.communities.show', compact('community'));
    }
}
