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
        $fieldsQuery = \App\Models\Field::with('owner');

        // LAPANGAN TERDEKAT - Handle nearby filter
        if (request('nearby') == 1) {
            $userLat = request('lat');
            $userLng = request('lng');
            $userCity = $user->city ?? null;

            if ($userLat && $userLng) {
                $fields = $fieldsQuery->get()->sortBy(function($f) use ($userLat, $userLng) {
                    if ($f->latitude && $f->longitude) {
                        $lat1 = deg2rad($userLat);
                        $lon1 = deg2rad($userLng);
                        $lat2 = deg2rad($f->latitude);
                        $lon2 = deg2rad($f->longitude);
                        $dlat = $lat2 - $lat1;
                        $dlon = $lon2 - $lon1;
                        $a = sin($dlat/2)**2 + cos($lat1)*cos($lat2)*sin($dlon/2)**2;
                        return 6371 * 2 * atan2(sqrt($a), sqrt(1-$a));
                    }
                    return 999999;
                })->values();
            } else {
                $allFields = $fieldsQuery->get();
                if ($userCity) {
                    $fields = $allFields->filter(function($f) use ($userCity) {
                        return $f->location && str_contains(strtolower($f->location), strtolower($userCity));
                    })->values();
                    if ($fields->isEmpty()) {
                        $nearbyMessage = 'Tidak ada lapangan ditemukan di kota "' . e($userCity) . '".';
                    }
                } else {
                    $nearbyMessage = 'Kamu belum mengisi kota. Silakan isi kota di ';
                    $fields = collect();
                }
            }
        } else {
            $fields = $fieldsQuery->get();
        }

        $isNearby = request('nearby') == 1;
        $nearbyMessage = $nearbyMessage ?? null;
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

        $upcomingJoin = \App\Models\MatchPlayer::where('match_players.user_id', $user->id)
            ->where('match_players.payment_status', \App\Enums\PaymentStatus::PAID)
            ->whereHas('match', function ($q) {
                $q->where('date', '>=', now()->toDateString());
            })
            ->with('match.field')
            ->join('matches', 'match_players.match_id', '=', 'matches.id')
            ->orderBy('matches.date')
            ->orderBy('matches.time')
            ->select('match_players.*')
            ->first();

        $confirmedMatchNotifs = \App\Models\MatchPlayer::where('user_id', $user->id)
            ->where('payment_status', \App\Enums\PaymentStatus::PAID)
            ->whereHas('match')
            ->with('match.field')
            ->latest('confirmed_at')
            ->get();

        // SMART MATCH RECOMMENDATION
        $userBookingSports = \App\Models\Booking::where('user_id', $user->id)
            ->whereIn('status', [\App\Enums\BookingStatus::CONFIRMED, \App\Enums\BookingStatus::COMPLETED])
            ->with('field')
            ->get()
            ->pluck('field.type')
            ->filter()
            ->countBy()
            ->sortDesc()
            ->keys()
            ->toArray();

        $userMatchSports = \App\Models\MatchPlayer::where('user_id', $user->id)
            ->with('match')
            ->get()
            ->pluck('match.sport')
            ->filter()
            ->countBy()
            ->sortDesc()
            ->keys()
            ->toArray();

        $userSportPref = $user->sport_preference ? array_map('trim', explode(',', $user->sport_preference)) : [];

        $allSports = array_unique(array_merge($userBookingSports, $userMatchSports, $userSportPref));

        $recommendedMatches = collect();
        $recommendedBadge = '';

        $hasActivity = \App\Models\Booking::where('user_id', $user->id)->exists() ||
                       \App\Models\MatchPlayer::where('user_id', $user->id)->exists();

        if ($hasActivity && !empty($allSports)) {
            $recommendedMatches = \App\Models\Matchs::with('field')
                ->where('type', 'public')
                ->where('date', '>=', now()->toDateString())
                ->where(function($q) use ($allSports) {
                    foreach ($allSports as $s) {
                        $q->orWhere('sport', $s);
                    }
                })
                ->orderBy('date')
                ->take(3)
                ->get();

            if ($recommendedMatches->isNotEmpty()) {
                $recommendedBadge = 'Recommended Based On Your Activity';
            }
        }

        if ($recommendedMatches->isEmpty()) {
            $recommendedMatches = \App\Models\Matchs::with('field')
                ->where('type', 'public')
                ->where('date', '>=', now()->toDateString())
                ->withCount('players')
                ->orderBy('players_count', 'desc')
                ->orderBy('date')
                ->take(3)
                ->get();

            if ($recommendedMatches->isNotEmpty()) {
                $recommendedBadge = 'Popular Choice';
            }
        }

        // Check if user has active bookings
        $hasActiveBooking = \App\Models\Booking::where('user_id', $user->id)
            ->whereIn('status', [
                \App\Enums\BookingStatus::CONFIRMED,
                \App\Enums\BookingStatus::WAITING_PAYMENT,
                \App\Enums\BookingStatus::WAITING_CONFIRMATION,
            ])
            ->exists();

        if (!$hasActiveBooking && $recommendedMatches->isNotEmpty() && !$recommendedBadge) {
            $recommendedBadge = 'Recommended Match For You';
        }

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
        $favoriteIds = \App\Models\Favorite::where('user_id', $user->id)->pluck('field_id')->toArray();
        
        return view('fields.index', compact(
            'fields', 'upcomingBooking', 'upcomingJoin', 'confirmedMatchNotifs',
            'recommendedMatches', 'pesanLagiFields', 'favoriteFields', 'previousFieldIds',
            'recommendedBadge', 'favoriteIds', 'isNearby', 'nearbyMessage'
        ));
    }
})->middleware(['auth'])->name('dashboard');

Route::get('/explore', function () {
    return view('pages.preview');
})->name('explore');

Route::get('/preview-help', function () {
    return view('pages.help');
})->name('preview.help');

Route::get('/lapangan', function () {
    return view('pages.lapangan');
})->name('lapangan');

Route::get('/komunitas', function () {
    return view('pages.komunitas');
})->name('komunitas');

Route::get('/bantuan', function () {
    return view('pages.welp');
})->name('bantuan');

Route::get('/tentang-kami', function () {
    return view('pages.about');
})->name('about');

Route::get('/ketentuan-layanan', function () {
    return view('pages.layanan');
})->name('layanan');

Route::get('/hubungi-kami', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/kebijakan-privasi', function () {
    return view('pages.kebijakanpriv');
})->name('kebijakanpriv');


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

    /* REVIEWS */
    Route::post('/reviews/store', [App\Http\Controllers\ReviewController::class, 'store'])->name('review.store');
    Route::get('/reviews/check/{field}', [App\Http\Controllers\ReviewController::class, 'checkEligibility'])->name('review.check');
    Route::get('/reviews/check-any', [App\Http\Controllers\ReviewController::class, 'checkAnyEligibility'])->name('review.check-any');
    Route::get('/reviews/latest/{field}', [App\Http\Controllers\ReviewController::class, 'latest'])->name('review.latest');

    /* PARTNER FINDER - Get potential partners data */
    Route::get('/partner/data', function () {
        $sport = request('sport');
        $skill = request('skill');
        $query = \App\Models\User::where('open_partner', true)
            ->where('id', '!=', auth()->id());

        if ($sport) {
            $query->where('sport_preference', 'like', '%' . $sport . '%');
        }
        if ($skill) {
            $query->where('skill_level', $skill);
        }

        $partners = $query->get()->map(function ($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'avatar' => $u->avatarUrl(),
                'sport_preference' => $u->sport_preference,
                'skill_level' => $u->skill_level,
                'phone' => $u->phone,
            ];
        });

        return response()->json($partners);
    })->name('partner.data');

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
