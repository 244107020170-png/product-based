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
        return view('dashboard'); // player
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
        })->name('owner.dashboard');

        Route::get('/fields', function () {
            return view('owner.fields');
        });

        Route::get('/bookings', function () {
            return view('owner.bookings');
        });

    });

    /* PLAYER */
    Route::get('/fields', function () {
        return view('fields.index');
    });

    Route::get('/booking/{field}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/bookings', [BookingController::class, 'index'])->name('booking.index');

    Route::get('/matches', [ActivityController::class, 'index'])->name('activity.index');
    Route::get('/cari-tim', [MatchController::class, 'index'])->name('matches.index');

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
