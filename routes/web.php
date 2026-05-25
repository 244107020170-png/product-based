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
use Carbon\Carbon;

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

    /* ADMIN */
    Route::prefix('admin')->middleware('role.admin')->group(function () {
        Route::get('/dashboard', function () {
            $totalUsers = \App\Models\User::count();
            $totalOwners = \App\Models\User::where('role', 'owner')->count();
            $totalPlayers = \App\Models\User::where('role', 'player')->count();
            $totalFields = \App\Models\Field::count();
            $totalBookings = \App\Models\Booking::count();
            $totalMatches = \App\Models\Matchs::count();

            return view('admin.dashboard', compact(
                'totalUsers',
                'totalOwners', 
                'totalPlayers',
                'totalFields',
                'totalBookings',
                'totalMatches'
            ));
        })->name('admin.dashboard');

        // Admin routes dapat ditambahkan di sini
        // Contoh: Route::resource('users', UserController::class);
    });

    /* OWNER */
    Route::prefix('owner')->middleware('role.owner')->group(function () {

        Route::get('/dashboard', function () {
            $user = auth()->user();
            $fieldCount = \App\Models\Field::where('owner_id', $user->id)->count();
            $bookingCount = \App\Models\Booking::whereHas('field', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            })->count();
            $todayBooking = \App\Models\Booking::whereHas('field', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            })->whereDate('date', Carbon::today())->count();
            $monthlyRevenue = 0; // This would need to be calculated from booking data

            return view('owner.dashboard', compact('fieldCount', 'bookingCount', 'todayBooking', 'monthlyRevenue'));
        })->name('owner.dashboard');

        Route::get('/kelolaLapangan', function () {
            $user = auth()->user();
            $fields = \App\Models\Field::where('owner_id', $user->id)->get();
            return view('owner.kelolaLapangan', compact('fields'));
        })->name('owner.kelolaLapangan');

        Route::get('/tambahLapangan', function () {
            return view('owner.tambahLapangan');
        })->name('owner.tambahLapangan');

        Route::get('/jadwalDanSlot', function () {
            $user = auth()->user();
            $fields = \App\Models\Field::where('owner_id', $user->id)->get();
            return view('owner.jadwalDanSlot', compact('fields'));
        })->name('owner.jadwalDanSlot');

        Route::get('/kelolaBooking', function () {
            $user = auth()->user();
            $bookings = \App\Models\Booking::whereHas('field', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            })->with(['field', 'user'])->orderBy('date', 'desc')->orderBy('start_time', 'desc')->get();
            return view('owner.kelolaBooking', compact('bookings'));
        })->name('owner.kelolaBooking');

        Route::get('/promosiDiskon', function () {
            return view('owner.promosiDiskon');
        })->name('owner.promosiDiskon');

        Route::get('/pemeliharaanKontrol', function () {
            return view('owner.pemeliharaanKontrol');
        })->name('owner.pemeliharaanKontrol');
    });

    /* PLAYER */
    // The dashboard now acts as the fields list, so we redirect /fields to dashboard to prevent duplicate pages.
    Route::get('/fields', function () {
        return redirect()->route('dashboard');
    })->name('fields.index');

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
