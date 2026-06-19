<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Booking;
use App\Models\Favorite;
use App\Models\MatchPlayer;
use App\Models\Matchs;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /* ================================================================
       SHOW – Halaman profil publik player
    ================================================================ */
    public function show(Request $request): View
    {
        $user = $request->user();

        $historiTim = MatchPlayer::query()
            ->select('match_players.*')
            ->join('matches', 'matches.id', '=', 'match_players.match_id')
            ->where('match_players.user_id', $user->id)
            ->where('matches.type', 'public')
            ->with(['match.field', 'match.creator', 'match.players'])
            ->orderByDesc('matches.date')
            ->orderByDesc('matches.time')
            ->take(5)
            ->get()
            ->pluck('match')
            ->filter()
            ->values();

        $personalBookings = Booking::with('field')
            ->where('user_id', $user->id)
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->take(5)
            ->get();

        $createdMatches = Matchs::with(['field', 'players'])
            ->where('created_by', $user->id)
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->take(5)
            ->get();

        $privateActivities = $this->buildPrivateActivities($personalBookings, $createdMatches);

        $favoriteFields = Favorite::with('field')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $userReviews = Review::with('field')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('profile.show', compact('user', 'historiTim', 'privateActivities', 'favoriteFields', 'userReviews'));
    }

    private function buildPrivateActivities(Collection $bookings, Collection $createdMatches): Collection
    {
        $bookingActivities = $bookings->map(function (Booking $booking) {
            $field = $booking->field;
            $activityAt = Carbon::parse($booking->date->format('Y-m-d') . ' ' . ($booking->start_time ?: '00:00:00'));
            $displayInfo = Booking::statusDisplayInfo($booking->display_status);

            return [
                'name' => 'Booking ' . ($field?->name ?? 'Lapangan'),
                'date' => $activityAt,
                'date_label' => $activityAt->locale('id')->translatedFormat('j F Y'),
                'status' => $displayInfo['label'],
                'status_class' => $displayInfo['class'],
                'location' => $field?->location,
                'sort_at' => $activityAt,
            ];
        });

        $matchActivities = $createdMatches->map(function (Matchs $match) {
            $activityAt = Carbon::parse($match->date . ' ' . ($match->time ?: '00:00:00'));
            $isPast = $activityAt->isPast();

            return [
                'name' => $match->title,
                'date' => $activityAt,
                'date_label' => $activityAt->locale('id')->translatedFormat('j F Y'),
                'status' => $isPast ? 'Selesai' : 'Akan Datang',
                'status_class' => $this->statusClass(null, $isPast),
                'location' => trim(($match->field?->name ?? 'Lapangan') . ($match->field?->location ? ' - ' . $match->field->location : '')),
                'sort_at' => $activityAt,
            ];
        });

        return $bookingActivities
            ->concat($matchActivities)
            ->sortByDesc('sort_at')
            ->take(5)
            ->values();
    }

    private function statusClass(?string $status, bool $isPast): string
    {
        if ($isPast || in_array($status, ['selesai', 'completed'], true)) {
            return 'history-status--selesai';
        }

        if (in_array($status, ['cancelled', 'expired', 'rejected'], true)) {
            return 'history-status--dibatal';
        }

        if (in_array($status, ['confirmed', 'paid'], true)) {
            return 'history-status--akan';
        }

        return 'history-status--pending';
    }

    /* ================================================================
       EDIT – Form edit profil
    ================================================================ */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /* ================================================================
       UPDATE – Simpan perubahan profil
    ================================================================ */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user      = $request->user();
        $validated = $request->validated();

        // --- Upload avatar jika ada ---
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika tersimpan di storage
            if ($user->avatar_profile && str_starts_with($user->avatar_profile, 'avatars/')) {
                Storage::disk('public')->delete($user->avatar_profile);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar_profile'] = $path;
        }

        // --- Upload cover jika ada ---
        if ($request->hasFile('cover')) {
            // Hapus cover lama jika tersimpan di storage
            if ($user->cover_photo && str_starts_with($user->cover_photo, 'covers/')) {
                Storage::disk('public')->delete($user->cover_photo);
            }

            $path = $request->file('cover')->store('covers', 'public');
            $validated['cover_photo'] = $path;
        }

        // Hapus key 'avatar' dari validated (bukan kolom DB)
        unset($validated['avatar']);

        $user->fill($validated);

        // Reset verifikasi email jika email berubah
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.show')
            ->with('status', 'profile-updated');
    }

    /* ================================================================
       DESTROY – Hapus akun
    ================================================================ */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
