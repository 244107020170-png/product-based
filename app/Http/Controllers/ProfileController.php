<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Matchs;
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

        try {
            // Histori Tim: match yang DIikuti user (sebagai player)
            $historiTim = Matchs::with(['field', 'creator', 'players'])
                ->whereHas('players', fn ($q2) => $q2->where('users.id', $user->id))
                ->latest()
                ->get();

            // Private Match: match pribadi yang dibuat user sendiri
            $privateMatch = Matchs::with(['field', 'creator', 'players'])
                ->where('created_by', $user->id)
                ->where('type', 'private')
                ->latest()
                ->get();

            // Favorit: lapangan favorit user
            $favoriteFields = \App\Models\Favorite::with('field')
                ->where('user_id', $user->id)
                ->latest()
                ->get();

        } catch (\Exception $e) {
            // Fallback jika match_players belum punya kolom yang benar
            // (migrate dulu: php artisan migrate)
            $historiTim   = Matchs::with(['field', 'creator'])
                ->where('created_by', $user->id)
                ->latest()
                ->get();

            $privateMatch = $historiTim;
            $favoriteFields = collect();
        }

        return view('profile.show', compact('user', 'historiTim', 'privateMatch', 'favoriteFields'));
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
