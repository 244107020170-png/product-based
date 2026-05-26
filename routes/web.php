<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\OwnerFieldController;
use App\Models\Maintenance;
use App\Models\Slot;
use App\Models\Holiday;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

Route::get('/', function () {
    return view('welcome');
})->name('home');

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
        $upcomingBooking = \App\Models\Booking::where('user_id', $user->id)
            ->where('status', \App\Enums\BookingStatus::CONFIRMED)
            ->where(function($q) {
                $q->where('date', '>', now()->toDateString())
                  ->orWhere(function($q2) {
                      $q2->where('date', '=', now()->toDateString())
                         ->where('start_time', '>', now()->toTimeString());
                  });
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->with('field')
            ->first();
        
        $recommendedMatches = \App\Models\Matchs::with('field')->where('date', '>=', now()->toDateString())->inRandomOrder()->limit(3)->get();

        // Pesan Lagi: only show if user has previously booked fields
        $previousFieldIds = \App\Models\Booking::where('user_id', $user->id)
            ->whereNotIn('status', [
                \App\Enums\BookingStatus::CANCELLED,
                \App\Enums\BookingStatus::EXPIRED,
                \App\Enums\BookingStatus::REJECTED,
            ])
            ->pluck('field_id')
            ->unique()
            ->values()
            ->toArray();

        $pesanLagiFields = collect();
        if (count($previousFieldIds) > 0) {
            $pesanLagiFields = \App\Models\Field::whereIn('id', $previousFieldIds)
                ->inRandomOrder()
                ->limit(3)
                ->get();
        }

        $favoriteFields = \App\Models\Favorite::with('field')->where('user_id', $user->id)->limit(3)->get();
        
        return view('fields.index', compact('fields', 'upcomingBooking', 'recommendedMatches', 'pesanLagiFields', 'favoriteFields', 'previousFieldIds')); // player dashboard now shows available fields
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

        // ── STATIC PAGES ──────────────────────────────────────────

        Route::get('/dashboard', function () {
            $user = auth()->user();
            $fieldCount = \App\Models\Field::where('owner_id', $user->id)->count();
            $bookingCount = \App\Models\Booking::whereHas('field', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            })->count();
            $todayBooking = \App\Models\Booking::whereHas('field', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            })->whereDate('date', Carbon::today())->count();
            $monthlyRevenue = \App\Models\Booking::whereHas('field', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            })->whereIn('status', ['completed', 'confirmed'])->count() * 50000;

            return view('owner.dashboard', compact('fieldCount', 'bookingCount', 'todayBooking', 'monthlyRevenue'));
        })->name('owner.dashboard');

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

        // ── FIELD CRUD ────────────────────────────────────────────

        Route::get('/kelolaLapangan', function () {
            $user = auth()->user();
            $fields = \App\Models\Field::where('owner_id', $user->id)->get();
            return view('owner.kelolaLapangan', compact('fields'));
        })->name('owner.kelolaLapangan');

        Route::get('/tambahLapangan', function () {
            return view('owner.tambahLapangan');
        })->name('owner.tambahLapangan');

        Route::get('/fields/{field}/edit', [OwnerFieldController::class, 'edit'])->name('owner.field.edit');
        Route::post('/fields/store', [OwnerFieldController::class, 'store'])->name('owner.field.store');
        Route::put('/fields/{field}/update', [OwnerFieldController::class, 'update'])->name('owner.field.update');
        Route::delete('/fields/{field}', [OwnerFieldController::class, 'destroy'])->name('owner.field.destroy');

        // ── JADWAL & SLOT (AJAX) ──────────────────────────────────

        Route::get('/jadwalDanSlot', function () {
            $user = auth()->user();
            $fields = \App\Models\Field::where('owner_id', $user->id)->get();
            return view('owner.jadwalDanSlot', compact('fields'));
        })->name('owner.jadwalDanSlot');

        Route::get('/slots/data', function () {
            $user = auth()->user();
            $fieldId = request('field_id');
            $date = request('date');

            $slots = Slot::whereHas('field', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            })->when($fieldId, fn($q) => $q->where('field_id', $fieldId))
              ->when($date, fn($q) => $q->where('date', $date))
              ->get();

            $holidays = Holiday::whereHas('field', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            })->when($fieldId, fn($q) => $q->where('field_id', $fieldId))
              ->get();

            return response()->json([
                'slots'    => $slots,
                'holidays' => $holidays,
            ]);
        })->name('owner.slots.data');

        Route::post('/slots/update', function () {
            $fieldId = request('field_id');
            $date = request('date');
            $hour = request('hour');
            $status = request('status');

            $field = \App\Models\Field::findOrFail($fieldId);
            if ($field->owner_id !== auth()->id()) abort(403);

            $slot = Slot::updateOrCreate(
                ['field_id' => $fieldId, 'date' => $date, 'hour' => $hour],
                ['status' => $status]
            );

            return response()->json(['success' => true, 'slot' => $slot]);
        })->name('owner.slots.update');

        Route::post('/holidays/toggle', function () {
            $fieldId = request('field_id');
            $date = request('date');

            $field = \App\Models\Field::findOrFail($fieldId);
            if ($field->owner_id !== auth()->id()) abort(403);

            $holiday = Holiday::where('field_id', $fieldId)->where('date', $date)->first();
            if ($holiday) {
                $holiday->delete();
                return response()->json(['success' => true, 'is_holiday' => false]);
            } else {
                Holiday::create(['field_id' => $fieldId, 'date' => $date]);
                return response()->json(['success' => true, 'is_holiday' => true]);
            }
        })->name('owner.holidays.toggle');

        // ── PEMELIHARAAN KONTROL ──────────────────────────────────

        Route::get('/pemeliharaanKontrol', function () {
            $user = auth()->user();
            $maintenances = \App\Models\Maintenance::whereHas('field', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            })->with('field')->orderBy('created_at', 'desc')->get();
            $fields = \App\Models\Field::where('owner_id', $user->id)->get();
            return view('owner.pemeliharaanKontrol', compact('maintenances', 'fields'));
        })->name('owner.pemeliharaanKontrol');

        Route::post('/maintenances/store', function () {
            $data = request()->validate([
                'field_id'      => 'required|exists:fields,id',
                'task_name'     => 'required|string|max:255',
                'type'          => 'nullable|string|max:100',
                'schedule_date' => 'nullable|date',
                'priority'      => 'nullable|string|max:50',
                'pic_name'      => 'nullable|string|max:255',
                'status'        => 'nullable|string|max:50',
                'notes'         => 'nullable|string',
            ]);

            $field = \App\Models\Field::findOrFail($data['field_id']);
            if ($field->owner_id !== auth()->id()) abort(403);

            $maintenance = \App\Models\Maintenance::create($data);

            return response()->json(['success' => true, 'maintenance' => $maintenance->load('field')]);
        })->name('owner.maintenances.store');

        Route::put('/maintenances/{maintenance}/update', function (\App\Models\Maintenance $maintenance) {
            if ($maintenance->field->owner_id !== auth()->id()) abort(403);

            $data = request()->validate([
                'task_name'     => 'required|string|max:255',
                'type'          => 'nullable|string|max:100',
                'schedule_date' => 'nullable|date',
                'priority'      => 'nullable|string|max:50',
                'pic_name'      => 'nullable|string|max:255',
                'status'        => 'nullable|string|max:50',
                'notes'         => 'nullable|string',
            ]);

            $maintenance->update($data);

            return response()->json(['success' => true, 'maintenance' => $maintenance->load('field')]);
        })->name('owner.maintenances.update');

        Route::delete('/maintenances/{maintenance}', function (\App\Models\Maintenance $maintenance) {
            if ($maintenance->field->owner_id !== auth()->id()) abort(403);
            $maintenance->delete();
            return response()->json(['success' => true]);
        })->name('owner.maintenances.destroy');

        // ── BOOKING STATUS ────────────────────────────────────────

        Route::put('/bookings/{booking}/status', function (\App\Models\Booking $booking) {
            if ($booking->field->owner_id !== auth()->id()) abort(403);

            $data = request()->validate([
                'status' => 'required|string|in:confirmed,completed,cancelled,pending',
            ]);

            $booking->update(['status' => $data['status']]);

            return response()->json(['success' => true, 'booking' => $booking->load('field', 'user')]);
        })->name('owner.booking.status');

        Route::post('/bookings/{booking}/confirm-payment', [BookingController::class, 'confirmPayment'])
            ->name('owner.booking.confirmPayment');

        Route::post('/bookings/{booking}/reject-payment', [BookingController::class, 'rejectPayment'])
            ->name('owner.booking.rejectPayment');
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
    Route::post('/bookings/{booking}/pay', [BookingController::class, 'pay'])->name('booking.pay');

    Route::get('/matches', [ActivityController::class, 'index'])->name('activity.index');
    Route::get('/cari-tim', [MatchController::class, 'index'])->name('matches.index');
    Route::get('/buat-match', [MatchController::class, 'create'])->name('matches.create');
    Route::post('/buat-match', [MatchController::class, 'store'])->name('matches.store');
    Route::get('/cari-tim/{match}', [MatchController::class, 'show'])->name('matches.show');
    Route::post('/cari-tim/{match}/join', [MatchController::class, 'join'])->name('matches.join');
    Route::post('/cari-tim/{match}/participant/payment', [MatchController::class, 'markParticipantPaid'])->name('matches.participant.pay');
    Route::post('/cari-tim/{match}/participant/{participant}/confirm', [MatchController::class, 'confirmParticipantPayment'])->name('matches.participant.confirm');
    Route::post('/cari-tim/{match}/participant/{participant}/reject', [MatchController::class, 'rejectParticipantPayment'])->name('matches.participant.reject');
    Route::get('/tim-saya', function () {
        $teams = \App\Models\Matchs::with('field')->where('created_by', auth()->id())->latest()->get();
        return view('matches.my-teams', compact('teams'));
    })->name('matches.myTeams');

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

    /* NOTIFICATIONS */
    Route::get('/notifications', [App\Http\Controllers\NotificationsController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationsController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationsController::class, 'markAllAsRead'])->name('notifications.markAllRead');

});

require __DIR__.'/auth.php';
