<?php

namespace App\Http\Controllers;

use App\Models\Matchs;

class MatchController extends Controller
{
    public function index()
    {
        $matches = Matchs::with(['field', 'players'])
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        $cards = $matches->map(function (Matchs $match) {
            $fieldName = $match->field?->name ?? 'Lapangan';
            $sport = $this->detectSport($match->title . ' ' . $fieldName);
            $playersJoined = $match->players->count();
            $neededPlayers = max(0, (int) $match->max_player - $playersJoined);

            return [
                'id' => $match->id,
                'sport' => $sport,
                'venue' => $fieldName,
                'neededPlayers' => $neededPlayers,
                'schedule' => 'Main tiap ' . \Carbon\Carbon::parse($match->date)->locale('id')->translatedFormat('l') . ' jam ' . \Carbon\Carbon::createFromFormat('H:i:s', $match->time)->format('H.i'),
                'image' => $this->sportImage($sport),
            ];
        })->values();

        return view('matches.index', compact('cards'));
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
