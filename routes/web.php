<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\OwnerFieldController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OwnerController as AdminOwnerController;
use App\Http\Controllers\Admin\FieldController as AdminFieldController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\CommunityController as AdminCommunityController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
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
                $q->where('date', '>', now()->toDateString())
                  ->orWhere(function($q2) {
                      $q2->where('date', '=', now()->toDateString())
                         ->where('time', '>', now()->toTimeString());
                  });
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

        $favoriteFields = \App\Models\Favorite::with('field')->where('user_id', $user->id)->get();
        $favoriteIds = \App\Models\Favorite::where('user_id', $user->id)->pluck('field_id')->toArray();

        // FIELD RECOMMENDATION
        $userSports = array_unique(array_merge($userBookingSports, $userMatchSports, $userSportPref));

        $recommendedFields = collect();
        $recommendedFieldBadge = '';

        // Priority 1: items with active promos matching user's sports
        if (!empty($userSports)) {
            $recommendedFields = \App\Models\Field::where('is_available', true)
                ->whereIn('type', $userSports)
                ->whereHas('discounts', function ($q) { $q->active(); })
                ->orderBy('featured', 'desc')
                ->orderBy('rating', 'desc')
                ->take(4)
                ->get();

            if ($recommendedFields->isNotEmpty()) {
                $recommendedFieldBadge = 'Promo Spesial';
            }
        }

        // Priority 2: popular items (most booked)
        if ($recommendedFields->isEmpty()) {
            $recommendedFields = \App\Models\Field::withCount('bookings')
                ->where('is_available', true)
                ->orderBy('bookings_count', 'desc')
                ->orderBy('featured', 'desc')
                ->orderBy('rating', 'desc')
                ->take(4)
                ->get();

            if ($recommendedFields->isNotEmpty()) {
                $recommendedFieldBadge = 'Popular Choice';
            }
        }

        // Priority 3: activity-based (user's preferred sports)
        if ($recommendedFields->isEmpty() && !empty($userSports)) {
            $recommendedFields = \App\Models\Field::whereIn('type', $userSports)
                ->where('is_available', true)
                ->orderBy('featured', 'desc')
                ->orderBy('review_count', 'desc')
                ->orderBy('rating', 'desc')
                ->take(4)
                ->get();

            if ($recommendedFields->isNotEmpty()) {
                $recommendedFieldBadge = 'Recommended For You';
            }
        }

        // Priority 4: fallback — any available fields
        if ($recommendedFields->isEmpty()) {
            $recommendedFields = \App\Models\Field::withCount('bookings')
                ->where('is_available', true)
                ->orderBy('bookings_count', 'desc')
                ->orderBy('featured', 'desc')
                ->orderBy('rating', 'desc')
                ->take(4)
                ->get();
        }

        // Sort fields: featured+promo -> featured only -> promo only -> normal
        $promoFieldIds = \App\Models\Discount::active()
            ->with('fields')->get()
            ->flatMap(fn($d) => $d->fields->pluck('id'))
            ->unique()->toArray();
        $fields = $fields->sortByDesc(function ($f) use ($promoFieldIds) {
            $hp = in_array($f->id, $promoFieldIds);
            $ft = $f->featured ?? false;
            if ($ft && $hp) return 3;
            if ($ft)        return 2;
            if ($hp)        return 1;
            return 0;
        })->values();
        
        // ---- Dynamic Review / Recommendation Card ----
        $reviewedFieldIds = \App\Models\Review::where('user_id', $user->id)->pluck('field_id')->toArray();

        $pendingReviewBooking = \App\Models\Booking::where('user_id', $user->id)
            ->whereIn('status', [\App\Enums\BookingStatus::CONFIRMED, \App\Enums\BookingStatus::COMPLETED])
            ->where(function($q) {
                $q->where('date', '<', now()->toDateString())
                  ->orWhere(function($q2) {
                      $q2->where('date', '=', now()->toDateString())
                         ->where('end_time', '<=', now()->toTimeString());
                  });
            })
            ->whereNotIn('field_id', $reviewedFieldIds)
            ->with('field')
            ->orderBy('date', 'desc')
            ->orderBy('end_time', 'desc')
            ->first();

        $recommendationField = null;
        if (!$pendingReviewBooking) {
            $recommendationField = \App\Models\Field::withCount('bookings')
                ->where('is_available', true)
                ->orderBy('bookings_count', 'desc')
                ->orderBy('rating', 'desc')
                ->orderBy('featured', 'desc')
                ->first();
        }

        // ---- Community Recommendations (based on user's sport activity) ----
        $communitySports = \App\Models\Booking::where('user_id', $user->id)
            ->whereIn('status', [\App\Enums\BookingStatus::CONFIRMED, \App\Enums\BookingStatus::COMPLETED])
            ->with('field')
            ->get()
            ->pluck('field.type')
            ->filter()
            ->unique()
            ->toArray();
        $userSportPref = $user->sport_preference ? array_map('trim', explode(',', $user->sport_preference)) : [];
        $userActiveSports = array_unique(array_merge($userSportPref, $communitySports));
        $recommendedCommunities = collect();
        if (!empty($userActiveSports)) {
            $recommendedCommunities = \App\Models\Community::withCount('members')
                ->whereIn('sport_category', $userActiveSports)
                ->orderBy('members_count', 'desc')
                ->take(3)
                ->get();
        }
        $myCommunityIds = $user->communities()->pluck('community_id')->toArray();
        
        return view('fields.index', compact(
            'fields', 'upcomingBooking', 'upcomingJoin', 'confirmedMatchNotifs',
            'recommendedMatches', 'pesanLagiFields', 'favoriteFields', 'previousFieldIds',
            'recommendedBadge', 'favoriteIds', 'isNearby', 'nearbyMessage', 'recommendedFields',
            'recommendedFieldBadge', 'pendingReviewBooking', 'recommendationField',
            'recommendedCommunities', 'myCommunityIds',
        ));
    }
})->middleware(['auth'])->name('dashboard');

Route::get('/rekomendasi', function () {
    $promoFields = \App\Models\Field::where('is_available', true)
        ->whereHas('discounts', fn($q) => $q->active())
        ->with(['discounts' => fn($q) => $q->active()->orderBy('value', 'desc')])
        ->orderBy('featured', 'desc')
        ->orderBy('rating', 'desc')
        ->take(10)
        ->get();

    $popularFields = \App\Models\Field::withCount('bookings')
        ->where('is_available', true)
        ->orderBy('bookings_count', 'desc')
        ->orderBy('featured', 'desc')
        ->orderBy('rating', 'desc')
        ->take(6)
        ->get();

    // Attach recent bookers for each popular field
    $fieldIds = $popularFields->pluck('id');
    $recentBookings = \App\Models\Booking::whereIn('field_id', $fieldIds)
        ->whereIn('status', ['confirmed', 'completed'])
        ->with('user')
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy('field_id');
    $popularFields->each(function ($field) use ($recentBookings) {
        $field->recentBookers = $recentBookings->get($field->id, collect())
            ->pluck('user')
            ->unique('id')
            ->take(4);
    });

    $sportCategories = \App\Models\Field::where('is_available', true)
        ->whereNotNull('type')
        ->distinct()
        ->pluck('type')
        ->map(fn($t) => ['key' => $t, 'label' => ucfirst($t)])
        ->values();

    // ---- Communities ----
    $user = Auth::user();
    $communitySports = \App\Models\Community::whereNotNull('sport_category')
        ->distinct('sport_category')->pluck('sport_category')->toArray();
    $matchSports = \App\Models\Matchs::whereNotNull('sport')
        ->distinct('sport')->pluck('sport')->toArray();
    $fieldTypes = \App\Models\Field::whereNotNull('type')
        ->where('is_available', true)
        ->distinct('type')->pluck('type')->toArray();
    $allSportCategories = collect()
        ->merge($communitySports)
        ->merge($matchSports)
        ->merge($fieldTypes)
        ->unique()
        ->sort()
        ->values()
        ->toArray();
    $communities = \App\Models\Community::withCount('members')
        ->orderByRaw('created_by = ? desc', [$user ? $user->id : 0])
        ->orderBy('members_count', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();
    $myCommunityIds = $user ? $user->communities()->pluck('community_id')->toArray() : [];

    return view('pages.rekomendasi', compact(
        'promoFields', 'popularFields', 'sportCategories',
        'communities', 'myCommunityIds', 'allSportCategories',
    ));
})->name('recommendation.index');

Route::get('/explore', function () {
    return view('pages.preview');
})->name('explore');

Route::get('/preview-help', function () {
    return view('pages.help');
})->name('preview.help');

Route::get('/lapangan', function () {
    $query = \App\Models\Field::where('is_available', true);

    if ($search = request('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('location', 'like', "%{$search}%");
        });
    }

    if ($type = request('type')) {
        $query->where('type', $type);
    }

    $sort = request('sort', 'terbaru');
    match ($sort) {
        'termurah' => $query->orderBy('price_per_hour'),
        'ternilai' => $query->orderBy('rating', 'desc'),
        'terlama'  => $query->orderBy('created_at'),
        default    => $query->orderBy('created_at', 'desc'),
    };

    $fields = $query->paginate(12)->withQueryString();
    $types = \App\Models\Field::where('is_available', true)->select('type')->distinct()->pluck('type');

    return view('pages.lapangan', compact('fields', 'types'));
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
    Route::prefix('admin')->middleware('role.admin')->name('admin.')->group(function () {
        Route::get('/', fn() => redirect()->route('admin.dashboard'))->name('home');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');

        Route::get('/owners', [AdminOwnerController::class, 'index'])->name('owners');
        Route::get('/owners/{owner}', [AdminOwnerController::class, 'show'])->name('owners.show');

        Route::get('/fields', [AdminFieldController::class, 'index'])->name('fields');
        Route::get('/fields/{field}', [AdminFieldController::class, 'show'])->name('fields.show');

        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings');
        Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');

        Route::get('/payments', [PaymentController::class, 'index'])->name('payments');

        Route::get('/communities', [AdminCommunityController::class, 'index'])->name('communities');
        Route::get('/communities/{community}', [AdminCommunityController::class, 'show'])->name('communities.show');

        Route::get('/system', [SystemController::class, 'index'])->name('system');

        Route::post('/logout', function () {
            Auth::guard('web')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect('/admin/login');
        })->name('logout');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports');

        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
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

            $reviewStats = \App\Models\Review::whereHas('field', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            });
            $avgRating = round($reviewStats->avg('rating') ?? 0, 1);
            $totalReviews = $reviewStats->count();
            $recentReviews = (clone $reviewStats)->with('user', 'field')->latest()->take(5)->get();

            return view('owner.dashboard', compact(
                'fieldCount', 'bookingCount', 'todayBooking', 'monthlyRevenue',
                'avgRating', 'totalReviews', 'recentReviews',
            ));
        })->name('owner.dashboard');

        Route::get('/history', function () {
            return redirect()->route('owner.kelolaLapangan');
        })->name('owner.history');

        Route::get('/kelolaBooking', function () {
            $user = auth()->user();

            // Auto-complete confirmed bookings whose time has passed
            \App\Models\Booking::where('status', 'confirmed')
                ->whereHas('field', fn($q) => $q->where('owner_id', $user->id))
                ->where(function ($q) {
                    $q->where('date', '<', now()->toDateString())
                      ->orWhere(function ($q2) {
                          $q2->where('date', '=', now()->toDateString())
                             ->where('end_time', '<=', now()->toTimeString());
                      });
                })
                ->update(['status' => 'completed']);

            // Revert any completed bookings that are still in the future back to confirmed
            \App\Models\Booking::where('status', 'completed')
                ->whereHas('field', fn($q) => $q->where('owner_id', $user->id))
                ->where(function ($q) {
                    $q->where('date', '>', now()->toDateString())
                      ->orWhere(function ($q2) {
                          $q2->where('date', '=', now()->toDateString())
                             ->where('end_time', '>', now()->toTimeString());
                      });
                })
                ->update(['status' => 'confirmed']);

            // Auto-confirm legacy waiting_confirmation bookings (old flow migration)
            \App\Models\Booking::where('status', 'waiting_confirmation')
                ->whereHas('field', fn($q) => $q->where('owner_id', $user->id))
                ->with('field')
                ->get()
                ->each(function ($booking) {
                    try {
                        $ctrl = app(\App\Http\Controllers\BookingController::class);
                        $ctrl->assignCourtAndConfirm($booking, false);
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::warning('Gagal auto-confirm legacy booking #' . $booking->id . ': ' . $e->getMessage());
                    }
                });

            $bookings = \App\Models\Booking::whereHas('field', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            })->with(['field', 'user'])->orderBy('date', 'desc')->orderBy('start_time', 'desc')->get();
            return view('owner.kelolaBooking', compact('bookings'));
        })->name('owner.kelolaBooking');

        Route::get('/promosiDiskon', function () {
            $user = auth()->user();
            $discounts = \App\Models\Discount::where('owner_id', $user->id)
                ->with('fields')->orderBy('created_at', 'desc')->get();
            $activePromos = \App\Models\Discount::where('owner_id', $user->id)
                ->active()->with('fields')->get();
            $totalClaims = \App\Models\Discount::where('owner_id', $user->id)->sum('usage_count');
            $fields = \App\Models\Field::where('owner_id', $user->id)->get();
            return view('owner.promosiDiskon', compact('discounts', 'activePromos', 'totalClaims', 'fields'));
        })->name('owner.promosiDiskon');

        Route::post('/discounts/store', function () {
            $data = request()->validate([
                'field_id'          => 'nullable|exists:fields,id',
                'name'              => 'required|string|max:255',
                'code'              => 'nullable|string|max:50|unique:discounts,code',
                'description'       => 'nullable|string',
                'type'              => 'required|in:percentage,fixed',
                'value'             => 'required|numeric|min:0',
                'min_booking_amount'=> 'nullable|numeric|min:0',
                'usage_limit'       => 'nullable|integer|min:0',
                'start_date'        => 'required|date',
                'end_date'          => 'required|date|after_or_equal:start_date',
            ]);
            $fieldId = $data['field_id'] ?? null;
            unset($data['field_id']);
            $data['owner_id'] = auth()->id();
            $discount = \App\Models\Discount::create($data);
            if ($fieldId) {
                $discount->fields()->sync([$fieldId]);
            } else {
                $allFields = \App\Models\Field::where('owner_id', auth()->id())->pluck('id')->toArray();
                $discount->fields()->sync($allFields);
            }
            return redirect()->route('owner.promosiDiskon')->with('success', 'Promo berhasil dibuat!');
        })->name('owner.discounts.store');

        Route::get('/discounts/{discount}/edit', function (\App\Models\Discount $discount) {
            if ($discount->owner_id !== auth()->id()) abort(403);
            return response()->json($discount);
        })->name('owner.discounts.edit');

        Route::put('/discounts/{discount}/update', function (\App\Models\Discount $discount) {
            if ($discount->owner_id !== auth()->id()) abort(403);
            $data = request()->validate([
                'field_id'          => 'nullable|exists:fields,id',
                'name'              => 'required|string|max:255',
                'code'              => 'nullable|string|max:50|unique:discounts,code,'.$discount->id,
                'description'       => 'nullable|string',
                'type'              => 'required|in:percentage,fixed',
                'value'             => 'required|numeric|min:0',
                'min_booking_amount'=> 'nullable|numeric|min:0',
                'usage_limit'       => 'nullable|integer|min:0',
                'start_date'        => 'required|date',
                'end_date'          => 'required|date|after_or_equal:start_date',
            ]);
            $fieldId = $data['field_id'] ?? null;
            unset($data['field_id']);
            $discount->update($data);
            if ($fieldId) {
                $discount->fields()->sync([$fieldId]);
            } else {
                $allFields = \App\Models\Field::where('owner_id', auth()->id())->pluck('id')->toArray();
                $discount->fields()->sync($allFields);
            }
            return redirect()->route('owner.promosiDiskon')->with('success', 'Promo berhasil diperbarui!');
        })->name('owner.discounts.update');

        Route::delete('/discounts/{discount}', function (\App\Models\Discount $discount) {
            if ($discount->owner_id !== auth()->id()) abort(403);
            $discount->delete();
            return redirect()->route('owner.promosiDiskon')->with('success', 'Promo berhasil dihapus!');
        })->name('owner.discounts.destroy');

        Route::post('/discounts/{discount}/toggle', function (\App\Models\Discount $discount) {
            if ($discount->owner_id !== auth()->id()) abort(403);
            $discount->update(['is_active' => !$discount->is_active]);
            return response()->json(['success' => true, 'is_active' => $discount->fresh()->is_active]);
        })->name('owner.discounts.toggle');

        // ── FIELD CRUD ────────────────────────────────────────────

        Route::get('/kelolaLapangan', function () {
            $user = auth()->user();
            $fields = \App\Models\Field::withCount('reviews', 'bookings')->where('owner_id', $user->id)->get();
            $totalRating = round(\App\Models\Review::whereHas('field', fn($q) => $q->where('owner_id', $user->id))->avg('rating') ?? 0, 1);
            $allReviews = \App\Models\Review::with('user', 'field')
                ->whereHas('field', fn($q) => $q->where('owner_id', $user->id))
                ->latest()->get();
            $avgRating = $totalRating;
            $totalReviews = \App\Models\Review::whereHas('field', fn($q) => $q->where('owner_id', $user->id))->count();
            return view('owner.kelolaLapangan', compact('fields', 'totalRating', 'allReviews', 'avgRating', 'totalReviews'));
        })->name('owner.kelolaLapangan');

        Route::get('/tambahLapangan', function () {
            return view('owner.tambahLapangan');
        })->name('owner.tambahLapangan');

        Route::get('/fields/{field}/edit', [OwnerFieldController::class, 'edit'])->name('owner.field.edit');
        Route::post('/fields/store', [OwnerFieldController::class, 'store'])->name('owner.field.store');
        Route::put('/fields/{field}/update', [OwnerFieldController::class, 'update'])->name('owner.field.update');
        Route::delete('/fields/{field}', [OwnerFieldController::class, 'destroy'])->name('owner.field.destroy');

        Route::post('/fields/{field}/toggle-featured', function (\App\Models\Field $field) {
            if ($field->owner_id !== auth()->id()) abort(403);
            $field->update(['featured' => !$field->featured]);
            return response()->json(['success' => true, 'featured' => $field->fresh()->featured]);
        })->name('owner.field.toggleFeatured');

        // ── JADWAL & SLOT (AJAX) ──────────────────────────────────

        Route::get('/jadwalDanSlot', function () {
            $user = auth()->user();
            $fields = \App\Models\Field::where('owner_id', $user->id)->get()->map(fn($f) => [
                'id' => $f->id,
                'name' => $f->name,
                'type' => $f->type ?? 'Olahraga',
                'number_of_courts' => (int)($f->number_of_courts ?? 1),
            ])->values();
            return view('owner.jadwalDanSlot', compact('fields'));
        })->name('owner.jadwalDanSlot');

        Route::get('/slots/data', function () {
            $user = auth()->user();
            $fieldId = request('field_id');
            $date = request('date');
            $courtNumber = request('court_number');

            $slots = Slot::whereHas('field', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            })->when($fieldId, fn($q) => $q->where('field_id', $fieldId))
              ->when($date, fn($q) => $q->where('date', $date))
              ->when($courtNumber, fn($q) => $q->where('court_number', $courtNumber))
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

        Route::post('/slots/save-all', function () {
            $user = auth()->user();
            $data = request()->all();

            if (!empty($data['slots'])) {
                foreach ($data['slots'] as $s) {
                    $field = \App\Models\Field::findOrFail($s['field_id']);
                    if ($field->owner_id !== $user->id) abort(403);

                    if (isset($s['_delete']) && $s['_delete']) {
                        Slot::where('field_id', $s['field_id'])
                            ->where('court_number', $s['court_number'] ?? 1)
                            ->where('date', $s['date'])
                            ->where('hour', $s['hour'])
                            ->delete();
                    } else {
                        Slot::updateOrCreate(
                            ['field_id' => $s['field_id'], 'court_number' => $s['court_number'] ?? 1, 'date' => $s['date'], 'hour' => $s['hour']],
                            ['status' => $s['status']]
                        );
                    }
                }
            }

            if (!empty($data['holidays'])) {
                foreach ($data['holidays'] as $h) {
                    $field = \App\Models\Field::findOrFail($h['field_id']);
                    if ($field->owner_id !== $user->id) abort(403);

                    if ($h['is_holiday']) {
                        Holiday::updateOrCreate(
                            ['field_id' => $h['field_id'], 'date' => $h['date']],
                            ['is_holiday' => true]
                        );
                        $field = \App\Models\Field::find($h['field_id']);
                        $numCourts = $field ? ($field->number_of_courts ?? 1) : 1;
                        foreach (range(8, 22) as $hour) {
                            for ($court = 1; $court <= $numCourts; $court++) {
                                Slot::updateOrCreate(
                                    ['field_id' => $h['field_id'], 'court_number' => $court, 'date' => $h['date'], 'hour' => $hour],
                                    ['status' => 'tutup']
                                );
                            }
                        }
                    } else {
                        Holiday::where('field_id', $h['field_id'])->where('date', $h['date'])->delete();
                        Slot::where('field_id', $h['field_id'])->where('date', $h['date'])->whereIn('hour', range(8, 22))->delete();
                    }
                }
            }

            return response()->json(['success' => true]);
        })->name('owner.slots.saveAll');

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
                'task_name'     => 'sometimes|string|max:255',
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
                'status' => 'required|string|in:confirmed,completed,cancelled,rejected,pending,waiting_confirmation,paid',
            ]);

            // Validate status transition
            if (!\App\Http\Controllers\BookingController::isValidTransition($booking->status, $data['status'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat mengubah status dari "' . $booking->status . '" ke "' . $data['status'] . '".',
                ], 422);
            }

            // If cancelling or rejecting, revert dibooking slots to tersedia
            if (in_array($data['status'], ['cancelled', 'rejected'])) {
                $field = $booking->field;
                $date = $booking->date instanceof \Carbon\Carbon ? $booking->date->toDateString() : $booking->date;
                $startHour = (int) \Carbon\Carbon::parse($booking->start_time)->format('G');
                $endHour = (int) \Carbon\Carbon::parse($booking->end_time)->format('G');

                $query = \App\Models\Slot::where('field_id', $field->id)
                    ->where('date', $date)
                    ->whereBetween('hour', [$startHour, $endHour - 1])
                    ->where('status', 'dibooking');

                // Only revert the specific court if the booking has one assigned
                if ($booking->court_number) {
                    $query->where('court_number', $booking->court_number);
                }

                $query->update(['status' => 'tersedia']);
            }

            $booking->update(['status' => $data['status']]);

            return response()->json([
                'success' => true,
                'booking' => $booking->load('field', 'user'),
                'message' => 'Status booking berhasil diperbarui.',
            ]);
        })->name('owner.booking.status');

        Route::post('/bookings/{booking}/confirm-payment', [BookingController::class, 'confirmPayment'])
            ->name('owner.booking.confirmPayment');

        Route::post('/bookings/{booking}/reject-payment', [BookingController::class, 'rejectPayment'])
            ->name('owner.booking.rejectPayment');

        // ── BANTUAN OWNER ─────────────────────────────────────────

        Route::get('/bantuan', function () {
            return view('owner.bantuan');
        })->name('owner.bantuan');

        // ── NOTIFIKASI OWNER ──────────────────────────────────────

        Route::get('/notifikasi', function () {
            $notifications = auth()->user()->notifications()
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return view('owner.notifikasi', compact('notifications'));
        })->name('owner.notifikasi');

        Route::post('/notifications/{id}/read', function ($id) {
            $notification = auth()->user()->notifications()->findOrFail($id);
            $notification->markAsRead();

            return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
        })->name('owner.notifications.read');

        Route::post('/notifications/mark-all-read', function () {
            auth()->user()->unreadNotifications->markAsRead();

            return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
        })->name('owner.notifications.markAllRead');

        // ── PENGATURAN (SETTINGS) ──────────────────────────────────

        Route::get('/pengaturan', function () {
            $user = auth()->user();
            $fieldIds = \App\Models\Field::where('owner_id', $user->id)->pluck('id');

            $totalBookings = \App\Models\Booking::whereIn('field_id', $fieldIds)->count();

            $lastWeek = now()->subDays(7);
            $bookingsLastWeek = \App\Models\Booking::whereIn('field_id', $fieldIds)
                ->where('created_at', '>=', $lastWeek)
                ->count();

            $prevWeek = now()->subDays(14);
            $bookingsPrevWeek = \App\Models\Booking::whereIn('field_id', $fieldIds)
                ->whereBetween('created_at', [$prevWeek, $lastWeek])
                ->count();

            $trend = $bookingsPrevWeek > 0
                ? round((($bookingsLastWeek - $bookingsPrevWeek) / $bookingsPrevWeek) * 100)
                : ($bookingsLastWeek > 0 ? 100 : 0);

            $dailyBookings = collect(range(6, 0))->map(function ($i) use ($fieldIds) {
                $day = now()->subDays($i);
                return \App\Models\Booking::whereIn('field_id', $fieldIds)
                    ->whereDate('created_at', $day)
                    ->count();
            });

            $maxDaily = max($dailyBookings->max(), 1);

            return view('owner.pengaturan', compact(
                'totalBookings', 'trend', 'dailyBookings', 'maxDaily'
            ));
        })->name('owner.pengaturan');

        Route::put('/pengaturan', function () {
            $user = auth()->user();
            $data = request()->validate([
                'name'  => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
            ]);

            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->phone = $data['phone'] ?? null;
            $user->address = $data['address'] ?? null;
            $user->save();

            return redirect()->route('owner.pengaturan')->with('success', 'Profil berhasil diperbarui.');
        })->name('owner.pengaturan.update');

        Route::put('/pengaturan/password', function () {
            $user = auth()->user();
            $data = request()->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user->update(['password' => bcrypt($data['password'])]);

            return redirect()->route('owner.pengaturan')->with('success', 'Kata sandi berhasil diperbarui.');
        })->name('owner.pengaturan.password');
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
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::get('/payment/{booking}', [BookingController::class, 'paymentPage'])->name('booking.payment');

    Route::get('/matches', [ActivityController::class, 'index'])->name('activity.index');
    Route::get('/cari-tim', [MatchController::class, 'index'])->name('matches.index');
    Route::get('/cari-tim/fresh', [MatchController::class, 'freshCards'])->name('matches.fresh');
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

    /* KOMUNITAS */
    Route::post('/komunitas', [\App\Http\Controllers\CommunityController::class, 'store'])->name('community.store');
    Route::post('/komunitas/{community}/join', [\App\Http\Controllers\CommunityController::class, 'join'])->name('community.join');
    Route::post('/komunitas/{community}/leave', [\App\Http\Controllers\CommunityController::class, 'leave'])->name('community.leave');
    Route::get('/komunitas/sport-categories', [\App\Http\Controllers\CommunityController::class, 'sportCategories'])->name('community.sportCategories');

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

        $partners = $query->get()->map(function ($u) {
            $b = \App\Models\Booking::where('user_id', $u->id)->whereIn('status', ['selesai','confirmed','pending'])->count();
            $m = \Illuminate\Support\Facades\DB::table('match_players')->where('user_id', $u->id)->count();
            $r = \App\Models\Review::where('user_id', $u->id)->count();
            $pts = ($b * 1) + ($m * 2) + ($r * 3);
            $level = $pts >= 21 ? 'Pro' : ($pts >= 6 ? 'Aktif' : 'Pemula');
            return [
                'id'               => $u->id,
                'name'             => $u->name,
                'avatar'           => $u->avatarUrl(),
                'sport_preference' => $u->sport_preference,
                'level'            => $level,
                'points'           => $pts,
                'phone'            => $u->phone,
            ];
        });

        if ($skill) {
            $partners = $partners->filter(fn($p) => strtolower($p['level']) === strtolower($skill));
            $partners = $partners->values();
        }

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

    /* CLAIM PROMO */
    Route::post('/promo/claim', function () {
        $data = request()->validate([
            'code' => 'required|string|max:50',
        ]);

        $discount = \App\Models\Discount::active()
            ->where('code', $data['code'])
            ->first();

        if (!$discount) {
            return back()->with('error', 'Kode promo tidak valid atau sudah kedaluwarsa.');
        }

        if ($discount->usage_limit && $discount->usage_count >= $discount->usage_limit) {
            return back()->with('error', 'Kode promo sudah mencapai batas pemakaian.');
        }

        $discount->increment('usage_count');

        try {
            $owner = $discount->owner;
            if ($owner) {
                $owner->notify(new \App\Notifications\Owner\OwnerPromoClaimed($discount, auth()->user()));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal notifikasi promo: ' . $e->getMessage());
        }

        return back()->with('success', 'Promo "' . $discount->name . '" berhasil diklaim!');
    })->name('promo.claim');

});

require __DIR__.'/auth.php';
