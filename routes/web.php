<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\MatchController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/choose-role', function () {
    return view('auth.register.choose-role');
})->name('choose.role');

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'owner') {
        return redirect('/owner/dashboard');
    } elseif ($user->role === 'admin') {
        return redirect('/admin/dashboard');
    } else {
        $fields = \App\Models\Field::with('owner')->get();
        $upcomingMatch = $user->joinedMatches()->where('date', '>=', now()->toDateString())->orderBy('date')->orderBy('time')->first();
        if(!$upcomingMatch) $upcomingMatch = $user->createdMatches()->where('date', '>=', now()->toDateString())->orderBy('date')->orderBy('time')->first();
        
        $recommendedMatches = \App\Models\Matchs::with('field')->where('date', '>=', now()->toDateString())->inRandomOrder()->limit(3)->get();
        $pesanLagiFields = \App\Models\Field::inRandomOrder()->limit(3)->get();
        $favoriteFields = \App\Models\Favorite::with('field')->where('user_id', $user->id)->limit(3)->get();
        
        return view('fields.index', compact('fields', 'upcomingMatch', 'recommendedMatches', 'pesanLagiFields', 'favoriteFields')); // player dashboard now shows available fields
    }
})->middleware(['auth'])->name('dashboard');

Route::get('/explore', function () {
    return view('pages.preview');
})->name('explore');

Route::get('/preview-help', function () {
    return view('pages.help');
})->name('preview.help');


Route::middleware('auth')->group(function () {

    /* OWNER */
    Route::prefix('owner')->group(function () {

        Route::get('/dashboard', function () {
            return view('owner.dashboard');
        });

        Route::get('/kelolaLapangan', function () {
            return view('owner.kelolaLapangan');
        });

        Route::get('/tambahLapangan', function () {
            return view('owner.tambahLapangan');
        });

        Route::get('/jadwalDanSlot', function () {
            return view('owner.jadwalDanSlot');
        });

        Route::get('/kelolaBooking', function () {
            return view('owner.kelolaBooking');
        });

        Route::get('/promosiDiskon', function () {
            return view('owner.promosiDiskon');
        });

        Route::get('/pemeliharaanKontrol', function () {
            return view('owner.pemeliharaanKontrol');
        });

    });

    /* PLAYER */
    // The dashboard now acts as the fields list, so we redirect /fields to dashboard to prevent duplicate pages.
    Route::get('/fields', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/booking/{field}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/bookings', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'detail'])->name('booking.detail');

    Route::get('/matches', [ActivityController::class, 'index'])->name('activity.index');
    Route::get('/cari-tim', [MatchController::class, 'index'])->name('matches.index');
    Route::get('/buat-match', [MatchController::class, 'create'])->name('matches.create');
    Route::post('/buat-match', [MatchController::class, 'store'])->name('matches.store');
    Route::get('/cari-tim/{match}', [MatchController::class, 'show'])->name('matches.show');
    Route::post('/cari-tim/{match}/join', [MatchController::class, 'join'])->name('matches.join');

    /* FAVORIT */
    Route::get('/favorit', [FavoriteController::class, 'index'])->name('favorite.index');
    Route::post('/favorit/toggle', [FavoriteController::class, 'toggle'])->name('favorite.toggle');
    Route::delete('/favorit/{fieldId}', [FavoriteController::class, 'destroy'])->name('favorite.destroy');

    /* HISTORY */
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');

    /* KEAHLIAN */
    Route::get('/keahlian', [SkillController::class, 'index'])->name('skill.index');

    /* PROFILE */
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';
