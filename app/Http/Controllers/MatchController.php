<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\MatchPlayer;
use App\Models\Matchs;
use App\Notifications\PaymentClaimed;
use Carbon\Carbon;

class MatchController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Only show upcoming public matches in "Cari tim" page, sorted by date
        $matches = Matchs::with(['field', 'players', 'creator'])
            ->where('type', 'public')
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        $cards = $matches->map(function (Matchs $match) {
            $fieldName = $match->field?->name ?? 'Lapangan';
            $sport = $this->detectSport($match->title . ' ' . $fieldName);
            $playersJoined = $match->players->count();
            $neededPlayers = max(0, (int) $match->max_player - $playersJoined);

            $dateFormatted = \Carbon\Carbon::parse($match->date)
                ->locale('id')
                ->translatedFormat('l, j F Y');
            $timeFormatted = \Carbon\Carbon::createFromFormat('H:i:s', $match->time)->format('H.i');

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

        // User's upcoming bookings (confirmed or waiting)
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

        // User skill level (calculate from bookings & matches)
        $totalBookings = \App\Models\Booking::where('user_id', $user->id)
            ->whereIn('status', [
                \App\Enums\BookingStatus::CONFIRMED,
                \App\Enums\BookingStatus::COMPLETED,
            ])
            ->count();
        
        $totalMatches = MatchPlayer::where('user_id', $user->id)
            ->count();

        $userSkill = [
            'totalBookings' => $totalBookings,
            'totalMatches' => $totalMatches,
            'level' => $this->getUserLevel($totalBookings + $totalMatches),
            'progress' => min(($totalBookings + $totalMatches) % 5, 4),
        ];

        return view('matches.index', compact('cards', 'upcomingBookings', 'myTeams', 'userSkill'));
    }

    private function getUserLevel($total): string
    {
        if ($total < 3) return 'Pemula';
        if ($total < 8) return 'Pemain Biasa';
        if ($total < 15) return 'Pemain Aktif';
        if ($total < 25) return 'Pemain Berpengalaman';
        return 'Master';
    }

    public function create()
    {
        $fields = \App\Models\Field::all();
        return view('matches.create', compact('fields'));
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
            'title' => 'required|string|max:255',
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
        $match->load(['field', 'creator', 'players', 'participantEntries.user']);
        $sport = $this->detectSport($match->title . ' ' . ($match->field?->name ?? ''));
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

        return back()->with('success', 'Berhasil bergabung dengan tim! Silakan lanjutkan pembayaran.');
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

        $participant->update(['paid_at' => now()]);

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
