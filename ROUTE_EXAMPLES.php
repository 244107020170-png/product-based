<?php

/**
 * EXAMPLE: ROUTE PROTECTION IMPLEMENTATION
 * 
 * File ini menunjukkan best practice untuk setup routes dengan role-based middleware
 * Copy examples ini ke routes/web.php sesuai kebutuhan anda
 */

use Illuminate\Support\Facades\Route;

// ============================================================================
// EXAMPLE 1: BASIC ROLE PROTECTION (SINGLE ROUTE)
// ============================================================================

Route::get('/admin/reports/download', [AdminReportController::class, 'download'])
    ->middleware('role.admin')
    ->name('admin.reports.download');


// ============================================================================
// EXAMPLE 2: ROLE PROTECTION (ROUTE GROUP)
// ============================================================================

Route::middleware('role.admin')->group(function () {
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
});


// ============================================================================
// EXAMPLE 3: ROLE PROTECTION WITH PREFIX (RECOMMENDED)
// ============================================================================

Route::prefix('admin')
    ->middleware(['auth', 'role.admin'])
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::resource('users', AdminUserController::class);
        Route::resource('fields', AdminFieldController::class);
        Route::resource('bookings', AdminBookingController::class);
        Route::resource('matches', AdminMatchController::class);
    });


// ============================================================================
// EXAMPLE 4: OWNER ROUTES WITH OWNERSHIP VALIDATION
// ============================================================================

Route::prefix('owner')
    ->middleware(['auth', 'role.owner'])
    ->group(function () {
        Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('owner.dashboard');
        
        // Lapangan management
        Route::get('/lapangan', [OwnerFieldController::class, 'index'])->name('owner.lapangan.index');
        Route::get('/lapangan/create', [OwnerFieldController::class, 'create'])->name('owner.lapangan.create');
        Route::post('/lapangan', [OwnerFieldController::class, 'store'])->name('owner.lapangan.store');
        
        // Routes dengan ownership validation - hanya owner field bisa akses
        Route::middleware('ownership:field')->group(function () {
            Route::get('/lapangan/{field}/edit', [OwnerFieldController::class, 'edit'])->name('owner.lapangan.edit');
            Route::put('/lapangan/{field}', [OwnerFieldController::class, 'update'])->name('owner.lapangan.update');
            Route::delete('/lapangan/{field}', [OwnerFieldController::class, 'destroy'])->name('owner.lapangan.destroy');
        });
        
        // Booking management
        Route::get('/booking', [OwnerBookingController::class, 'index'])->name('owner.booking.index');
        Route::get('/booking/{booking}', [OwnerBookingController::class, 'show'])->name('owner.booking.show');
    });


// ============================================================================
// EXAMPLE 5: PLAYER SPECIFIC ROUTES (OPTIONAL)
// ============================================================================

Route::middleware(['auth', 'role.player'])->group(function () {
    // Match management - player bisa join matches
    Route::get('/matches', [PlayerMatchController::class, 'index'])->name('player.matches.index');
    Route::post('/matches/{match}/join', [PlayerMatchController::class, 'join'])->name('player.matches.join');
    Route::post('/matches/{match}/leave', [PlayerMatchController::class, 'leave'])->name('player.matches.leave');
    
    // Bookings management
    Route::get('/bookings', [PlayerBookingController::class, 'index'])->name('player.bookings.index');
    Route::get('/booking/{field}', [PlayerBookingController::class, 'show'])->name('player.booking.show');
});


// ============================================================================
// EXAMPLE 6: COMBINED AUTH CHECKS
// ============================================================================

// Route yang bisa diakses admin AND owner saja
Route::middleware(['auth'])->group(function () {
    Route::get('/statistics', [StatisticsController::class, 'index'])
        ->middleware('can:view-statistics')  // Custom authorization
        ->name('statistics.index');
});

// Multiple middleware stack
Route::prefix('owner')
    ->middleware(['auth', 'role.owner'])
    ->group(function () {
        Route::put('/lapangan/{field}', [OwnerFieldController::class, 'update'])
            ->middleware('ownership:field')  // Tambahan validation
            ->name('owner.lapangan.update');
    });


// ============================================================================
// EXAMPLE 7: ERROR HANDLING PATTERN
// ============================================================================

// Jika route protected tapi user tidak authorized
// Laravel otomatis menampilkan 403 error
// Customize di resources/views/errors/403.blade.php

// Jika user belum login
// Laravel redirect ke /login (atau sesuai config auth.php)


// ============================================================================
// EXAMPLE 8: SMART REDIRECTS BASED ON ROLE
// ============================================================================

Route::get('/home', function () {
    $user = auth()->user();
    
    return match($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'owner' => redirect()->route('owner.dashboard'),
        'player' => redirect()->route('player.matches.index'),
        default => redirect()->route('welcome'),
    };
})->middleware('auth')->name('home');


// ============================================================================
// EXAMPLE 9: EXPLICIT ROLE CHECKS IN ROUTES (NOT RECOMMENDED)
// ============================================================================

// ❌ TIDAK DIREKOMENDASIKAN - langsung logic di route
Route::get('/test', function () {
    if (auth()->user()?->role !== 'admin') {
        abort(403);
    }
    return view('admin.test');
});

// ✅ DIREKOMENDASIKAN - gunakan middleware
Route::get('/test', function () {
    return view('admin.test');
})->middleware('role.admin');


// ============================================================================
// EXAMPLE 10: TESTING ROUTES
// ============================================================================

// Routes untuk testing - HAPUS DI PRODUCTION
if (app()->environment('local')) {
    Route::prefix('test-auth')->group(function () {
        Route::get('/login-as-admin', function () {
            auth()->login(App\Models\User::where('role', 'admin')->first());
            return redirect()->route('dashboard');
        });
        
        Route::get('/login-as-owner', function () {
            auth()->login(App\Models\User::where('role', 'owner')->first());
            return redirect()->route('dashboard');
        });
        
        Route::get('/login-as-player', function () {
            auth()->login(App\Models\User::where('role', 'player')->first());
            return redirect()->route('dashboard');
        });
    });
}
