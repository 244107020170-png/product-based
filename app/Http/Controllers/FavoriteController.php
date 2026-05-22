<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    /** Halaman daftar favorit dikelompokkan per kategori olahraga */
    public function index()
    {
        $uid = Auth::id();

        // Ambil semua field yang difavoritkan beserta relasi
        $favorites = Favorite::with('field')
            ->where('user_id', $uid)
            ->latest()
            ->get()
            ->filter(fn($f) => $f->field !== null);

        // Helper: deteksi kategori olahraga dari nama lapangan
        $detectSport = function (string $name): string {
            $n = strtolower($name);
            if (str_contains($n, 'futsal'))                                 return 'Futsal';
            if (str_contains($n, 'voli') || str_contains($n, 'volley'))    return 'Voli';
            if (str_contains($n, 'badminton') || str_contains($n, 'bulu tangkis')) return 'Badminton';
            if (str_contains($n, 'basket') || str_contains($n, 'basketball')) return 'Basket';
            if (str_contains($n, 'renang') || str_contains($n, 'kolam'))   return 'Renang';
            if (str_contains($n, 'tennis') || str_contains($n, 'tenis'))   return 'Tennis';
            if (str_contains($n, 'golf'))                                   return 'Golf';
            return 'Lainnya';
        };

        // Kelompokkan per kategori (urutan kemunculan pertama)
        $grouped = [];
        foreach ($favorites as $fav) {
            $sport = $detectSport($fav->field->name);
            $grouped[$sport][] = $fav->field;
        }

        return view('favorite.index', [
            'grouped'      => $grouped,
            'totalFav'     => $favorites->count(),
            'favoriteIds'  => $favorites->pluck('field_id')->toArray(),
        ]);
    }

    /** Toggle favorit — AJAX friendly */
    public function toggle(Request $request)
    {
        $uid     = Auth::id();
        $fieldId = $request->input('field_id');

        if (!$fieldId) {
            return response()->json(['error' => 'field_id required'], 422);
        }

        $existing = Favorite::where('user_id', $uid)->where('field_id', $fieldId)->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
        } else {
            Favorite::create(['user_id' => $uid, 'field_id' => $fieldId]);
            $status = 'added';
        }

        return response()->json([
            'status'  => $status,
            'favorited' => $status === 'added',
        ]);
    }

    /** Hapus satu favorit (fallback non-AJAX) */
    public function destroy(int $fieldId)
    {
        Favorite::where('user_id', Auth::id())
            ->where('field_id', $fieldId)
            ->delete();

        return redirect()->route('favorite.index');
    }
}
