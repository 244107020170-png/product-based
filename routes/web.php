<?php

use App\Http\Controllers\ProfileController;
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

    Route::get('/matches', function () {
        return view('matches.index');
    });

    /* PROFILE */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';
