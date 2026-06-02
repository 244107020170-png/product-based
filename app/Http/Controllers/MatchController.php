<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\Community;
use App\Models\MatchPlayer;
use App\Models\Matchs;
use App\Models\Booking;
use App\Notifications\PaymentClaimed;
use App\Notifications\PaymentConfirmed;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class MatchController extends Controller
{
    private function buildCards()
    {
        $matches = Matchs::with(['field', 'paidPlayers', 'creator'])
            ->where('type', 'public')
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('time')
            ->get()
            ->filter(function ($match) {
                return $match->paidPlayers->count() < (int) $match->max_player;
            });

        return $matches->map(function (Matchs $match) {
            $fieldName = $match->field?->name ?? 'Lapangan';
            $sport = $match->sport ?: $this->detectSport($match->title . ' ' . $fieldName);
            $playersJoined = $match->paidPlayers->count();
            $neededPlayers = max(0, (int) $match->max_player - $playersJoined);

            $dateFormatted = \Carbon\Carbon::parse($match->date)
                ->locale('id')
                ->translatedFormat('l, j F Y');
            $timeFormatted = $this->formatMatchTime($match->time);

            return [
                'id' => $match->id,
                'title' => $match->title,
                'sport' => $sport,
                'venue' => $fieldName,
                'neededPlayers' => $neededPlayers,
                'playersJoined' => $playersJoined,
                'maxPlayers' => $match->max_player,
                'schedule' => $dateFormatted . ' jam ' . $timeFormatted,
                'image' => $this->sportImage($sport),
                'contributionPerPlayer' => $match->contribution_per_player,
                'creator_gender' => $match->creator?->gender,
            ];
        })->values();
    }

    public function index()
    {
        $user = auth()->user();
        
        $cards = $this->buildCards();
        $upcomingBookings = \App\Models\Booking::where('user_id', $user->id)
            ->where('status', \App\Enums\BookingStatus::CONFIRMED)
            ->where(function($q) {
                $q->where('date', '>', now()->toDateString())
                  ->orWhere(function($q2) {
                      $q2->where('date', '=', now()->toDateString())
                         ->where('start_time', '>', now()->toTimeString());
                  });
            })
            ->with('field')
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(10)
            ->get();

        // User's created teams
        $myTeams = Matchs::with('players')
            ->where('created_by', $user->id)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('time')
            ->take(5)
            ->get();

        // User skill level (calculate from bookings, matches & reviews)
        $totalBookings = \App\Models\Booking::where('user_id', $user->id)
            ->whereIn('status', [
                \App\Enums\BookingStatus::CONFIRMED,
                \App\Enums\BookingStatus::COMPLETED,
            ])
            ->count();
        
        $totalMatches = MatchPlayer::where('user_id', $user->id)
            ->count();

        $totalReviews = \App\Models\Review::where('user_id', $user->id)->count();

        $totalPoints = ($totalBookings * 1) + ($totalMatches * 2) + ($totalReviews * 3);

        $_tiers = [['min' => 0, 'max' => 5], ['min' => 6, 'max' => 20], ['min' => 21, 'max' => PHP_INT_MAX]];
        $_currentTier = $_tiers[0];
        $_nextTier = $_tiers[1];
        foreach ($_tiers as $i => $t) {
            if ($totalPoints >= $t['min']) { $_currentTier = $t; $_nextTier = $_tiers[$i + 1] ?? null; }
        }
        $_progressPct = 100;
        if ($_nextTier) {
            $_range = $_currentTier['max'] - $_currentTier['min'];
            $_tierProgress = max(0, $totalPoints - $_currentTier['min']);
            $_progressPct = $_range > 0 ? min(100, round(($_tierProgress / $_range) * 100)) : 100;
        }

        $userSkill = [
            'totalBookings'  => $totalBookings,
            'totalMatches'   => $totalMatches,
            'totalReviews'   => $totalReviews,
            'totalPoints'    => $totalPoints,
            'level'          => $this->getUserLevel($totalPoints),
            'progressPct'    => $_progressPct,
        ];

        // ---- Communities (pinned: user's own first) ----
        $communities = Community::withCount('members')
            ->orderByRaw('created_by = ? desc', [$user->id])
            ->orderBy('members_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $myCommunityIds = $user->communities()->pluck('community_id')->toArray();

        $communitySports = Community::distinct('sport_category')->pluck('sport_category')->toArray();
        $matchSports = Matchs::whereNotNull('sport')->distinct('sport')->pluck('sport')->toArray();
        $fieldSports = Booking::where('user_id', $user->id)
            ->whereHas('field', function($q) {
                $q->whereNotNull('type');
            })
            ->with('field')
            ->get()
            ->pluck('field.type')
            ->filter()
            ->unique()
            ->toArray();
        $allSportCategories = collect()
            ->merge($communitySports)
            ->merge($matchSports)
            ->merge($fieldSports)
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        // Community recommendations based on user's sport activity
        $userSportPref = $user->sport_preference ? array_map('trim', explode(',', $user->sport_preference)) : [];
        $userBookingSports = Booking::where('user_id', $user->id)
            ->whereIn('status', [\App\Enums\BookingStatus::CONFIRMED, \App\Enums\BookingStatus::COMPLETED])
            ->with('field')
            ->get()
            ->pluck('field.type')
            ->filter()
            ->unique()
            ->toArray();
        $userActiveSports = array_unique(array_merge($userSportPref, $userBookingSports));

        $recommendedCommunities = collect();
        if (!empty($userActiveSports)) {
            $recommendedCommunities = Community::withCount('members')
                ->whereIn('sport_category', $userActiveSports)
                ->orderBy('members_count', 'desc')
                ->take(3)
                ->get();
        }

        return view('matches.index', compact(
            'cards', 'upcomingBookings', 'myTeams', 'userSkill',
            'communities', 'myCommunityIds', 'allSportCategories', 'recommendedCommunities',
        ));
    }

    private function getUserLevel($points): string
    {
        if ($points >= 21) return 'Pro';
        if ($points >= 6) return 'Aktif';
        return 'Pemula';
    }

    public function freshCards()
    {
        return response()->json($this->buildCards());
    }

    public function create()
    {
        $fields = \App\Models\Field::all();
        $sportOptions = collect(['Futsal', 'Badminton', 'Basket', 'Voli', 'Tennis', 'Golf', 'Renang', 'Panahan', 'Lari', 'Sepeda', 'Tinju', 'Bela Diri', 'Yoga', 'Fitness', 'Hiking', 'Padel', 'Baseball', 'Rugby', 'Senam']);
        return view('matches.create', compact('fields', 'sportOptions'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        // Check if user has phone number
        $user = auth()->user();
        if (!$user->phone) {
            return redirect()->route('matches.create')
                ->with('error', 'Mohon isi nomor WhatsApp di profil terlebih dahulu sebelum membuat pertandingan.');
        }

        $validated = $request->validate([
            'title' => [
                'required', 'string', 'max:255',
                Rule::unique('matches', 'title')->where('type', 'public'),
            ],
            'sport' => 'required|string|max:100',
            'field_id' => 'required|exists:fields,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'max_player' => 'required|integer|min:2',
            'type' => 'required|in:public,private',
        ]);

        $validated['created_by'] = auth()->id();

        session(['pending_match' => $validated]);

        $startTime = Carbon::createFromFormat('H:i', $validated['time']);
        $endTime = $startTime->copy()->addHours(2);

        return redirect()->route('booking.show', [
            'field' => $validated['field_id'],
            'date' => $validated['date'],
            'start_time' => $startTime->format('H:i'),
            'end_time' => $endTime->format('H:i'),
        ]);
    }

    public function show(Matchs $match)
    {
        $match->load(['field', 'creator', 'players', 'paidPlayers', 'participantEntries.user']);
        $sport = $match->sport ?: $this->detectSport($match->title . ' ' . ($match->field?->name ?? ''));
        $image = $this->sportImage($sport);
        
        $hasJoined = $match->players->contains(auth()->id());
        $isCreator = $match->created_by === auth()->id();
        $participant = $match->participantEntries->firstWhere('user_id', auth()->id());
        
        return view('matches.show', compact('match', 'sport', 'image', 'hasJoined', 'isCreator', 'participant'));
    }

    public function join(Matchs $match)
    {
        if (! $match->isPublic()) {
            return back()->with('error', 'Pertandingan pribadi tidak bisa diikuti.');
        }

        if ($match->created_by === auth()->id()) {
            return back()->with('error', 'Host tidak bisa bergabung dalam pertandingan sendiri.');
        }

        if ($match->players->contains(auth()->id())) {
            return back()->with('error', 'Kamu sudah bergabung dalam tim ini!');
        }

        if ($match->players->count() >= $match->max_player) {
            return back()->with('error', 'Tim sudah penuh!');
        }

        MatchPlayer::create([
            'match_id' => $match->id,
            'user_id' => auth()->id(),
            'contribution_amount' => $match->contribution_per_player,
            'payment_status' => PaymentStatus::WAITING,
        ]);

        return redirect()->route('matches.show', $match->id)
            ->with('success', 'Berhasil bergabung! Silakan lakukan pembayaran melalui QR di bawah.');
    }

    public function markParticipantPaid(Matchs $match)
    {
        $participant = $match->participantEntries()->where('user_id', auth()->id())->first();

        if (! $participant) {
            abort(404);
        }

        if (! $participant->isWaiting()) {
            return back()->with('error', 'Pembayaran tidak dapat diproses.');
        }

        $participant->update([
            'paid_at' => now(),
            'contribution_amount' => $match->contribution_per_player,
        ]);

        $match->creator->notify(new PaymentClaimed($participant));

        return back()->with('success', 'Pembayaran berhasil dilaporkan. Tunggu konfirmasi host.');
    }

    public function confirmParticipantPayment(Matchs $match, MatchPlayer $participant)
    {
        if ($match->created_by !== auth()->id()) {
            abort(403);
        }

        if ($participant->match_id !== $match->id) {
            abort(404);
        }

        if (! $participant->isWaiting()) {
            return back()->with('error', 'Status peserta tidak dalam antrian konfirmasi.');
        }

        $participant->update([
            'payment_status' => PaymentStatus::PAID,
            'confirmed_at' => now(),
        ]);

        $participant->user->notify(new PaymentConfirmed($match));

        auth()->user()->notifications()
            ->where('data->match_id', $match->id)
            ->where('data->user_id', $participant->user_id)
            ->where('data->type', 'payment_claimed')
            ->delete();

        return back()->with('success', 'Pembayaran peserta berhasil dikonfirmasi.');
    }

    public function rejectParticipantPayment(Matchs $match, MatchPlayer $participant)
    {
        if ($match->created_by !== auth()->id()) {
            abort(403);
        }

        if ($participant->match_id !== $match->id) {
            abort(404);
        }

        if (! $participant->isWaiting()) {
            return back()->with('error', 'Status peserta tidak dalam antrian konfirmasi.');
        }

        $participant->update([
            'payment_status' => PaymentStatus::WAITING,
            'paid_at' => null,
            'confirmed_at' => null,
        ]);

        auth()->user()->notifications()
            ->where('data->match_id', $match->id)
            ->where('data->user_id', $participant->user_id)
            ->where('data->type', 'payment_claimed')
            ->delete();

        return back()->with('success', 'Pembayaran peserta dikembalikan ke status waiting.');
    }

    private function detectSport(string $value): string
    {
        $text = strtolower($value);

        if (str_contains($text, 'basket')) return 'Basket';
        if (str_contains($text, 'futsal')) return 'Futsal';
        if (str_contains($text, 'badminton') || str_contains($text, 'bulu')) return 'Badminton';
        if (str_contains($text, 'voli') || str_contains($text, 'volley')) return 'Voli';
        if (str_contains($text, 'tenis') || str_contains($text, 'tennis')) return 'Tennis';

        return 'Olahraga';
    }

    private function formatMatchTime(?string $time): string
    {
        if (! $time) {
            return '--.--';
        }

        foreach (['H:i:s', 'H:i'] as $format) {
            try {
                return Carbon::createFromFormat($format, $time)->format('H.i');
            } catch (\Throwable) {
                //
            }
        }

        try {
            return Carbon::parse($time)->format('H.i');
        } catch (\Throwable) {
            return '--.--';
        }
    }

    private function sportImage(string $sport): string
    {
        return match ($sport) {
            'Basket' => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?auto=format&fit=crop&w=1200&q=80',
            'Futsal' => 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?auto=format&fit=crop&w=1200&q=80',
            'Badminton' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=1200&q=80',
            'Voli' => 'https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?auto=format&fit=crop&w=1200&q=80',
            'Tennis' => 'https://images.unsplash.com/photo-1622279457486-28f232ff1a48?auto=format&fit=crop&w=1200&q=80',
            default => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?auto=format&fit=crop&w=1200&q=80',
        };
    }
}
