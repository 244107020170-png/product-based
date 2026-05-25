# ROLE-BASED MIDDLEWARE & ROUTE PROTECTION GUIDE
## Spies Sport - Laravel 12 Implementation

---

## 📋 TABLE OF CONTENTS
1. [Middleware Overview](#middleware-overview)
2. [Route Protection Examples](#route-protection-examples)
3. [Advanced Usage](#advanced-usage)
4. [Authorization Best Practices](#best-practices)
5. [Error Handling](#error-handling)
6. [Testing](#testing)

---

## 🔐 MIDDLEWARE OVERVIEW

Anda sudah memiliki 4 middleware yang tersedia:

### 1. **role.admin** - `EnsureUserIsAdmin.php`
```php
// Memastikan user memiliki role 'admin'
// Redirect ke login jika belum authenticated
// Abort 403 jika bukan admin
```

### 2. **role.owner** - `EnsureUserIsOwner.php`
```php
// Memastikan user memiliki role 'owner'
// Redirect ke login jika belum authenticated
// Abort 403 jika bukan owner
```

### 3. **role.player** - `EnsureUserIsPlayer.php`
```php
// Memastikan user memiliki role 'player'
// Redirect ke login jika belum authenticated
// Abort 403 jika bukan player
```

### 4. **ownership** - `EnsureOwnership.php`
```php
// Validasi bahwa user adalah pemilik resource
// Gunakan dengan parameter route binding
// Abort 403 jika bukan owner dari resource
```

---

## 📍 ROUTE PROTECTION EXAMPLES

### ✅ BASIC ROLE-BASED PROTECTION

```php
// SINGLE ROUTE PROTECTION
Route::get('/admin/users', [AdminUserController::class, 'index'])
    ->middleware('role.admin')
    ->name('admin.users.index');

// ROUTE GROUP PROTECTION
Route::middleware('role.admin')->group(function () {
    Route::resource('users', AdminUserController::class);
    Route::resource('reports', AdminReportController::class);
});

// PREFIX + MIDDLEWARE (RECOMMENDED)
Route::prefix('admin')
    ->middleware('role.admin')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::resource('users', AdminUserController::class);
    });
```

### ✅ OWNER ROUTES PROTECTION

```php
// CURRENT IMPLEMENTATION - SUDAH DILINDUNGI
Route::prefix('owner')
    ->middleware('role.owner')
    ->group(function () {
        Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('owner.dashboard');
        Route::get('/kelolaLapangan', [OwnerController::class, 'manageLapangan'])->name('owner.kelolaLapangan');
        Route::resource('lapangan', FieldController::class);
    });
```

### ✅ PLAYER ROUTES PROTECTION (OPTIONAL)

```php
// Jika ingin restrict route khusus player
Route::middleware('role.player')->group(function () {
    Route::get('/matches', [MatchController::class, 'index'])->name('matches.index');
    Route::post('/matches/{match}/join', [MatchController::class, 'join'])->name('matches.join');
});

// Atau gunakan untuk verify player yang join match
Route::post('/matches/{match}/join', [MatchController::class, 'join'])
    ->middleware('role.player')
    ->name('matches.join');
```

---

## 🚀 ADVANCED USAGE

### 1. COMBINED MIDDLEWARE (Role + Ownership Validation)

```php
// Protect lapangan owned by user
Route::prefix('owner')
    ->middleware('role.owner')
    ->group(function () {
        // Hanya owner dari lapangan ini bisa edit
        Route::put('/lapangan/{field}', [FieldController::class, 'update'])
            ->middleware('ownership:field')
            ->name('lapangan.update');
            
        Route::delete('/lapangan/{field}', [FieldController::class, 'destroy'])
            ->middleware('ownership:field')
            ->name('lapangan.destroy');
    });
```

### 2. CUSTOM AUTHORIZATION LOGIC

Jika anda ingin logic lebih kompleks, gunakan Laravel Policy:

```php
// app/Policies/FieldPolicy.php
namespace App\Policies;

use App\Models\Field;
use App\Models\User;

class FieldPolicy
{
    public function update(User $user, Field $field): bool
    {
        return $user->id === $field->owner_id;
    }

    public function delete(User $user, Field $field): bool
    {
        return $user->id === $field->owner_id;
    }
}

// routes/web.php - AUTHORIZATION MENGGUNAKAN POLICY
Route::put('/lapangan/{field}', [FieldController::class, 'update'])
    ->middleware('role.owner')
    ->can('update', 'field')
    ->name('lapangan.update');
```

### 3. REDIRECT BASED ON ROLE

```php
// routes/web.php - SMART REDIRECT KE DASHBOARD
Route::get('/dashboard', function () {
    $user = Auth::user();

    // Redirect berdasarkan role
    return match($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'owner' => redirect()->route('owner.dashboard'),
        'player' => redirect()->route('fields.index'),
        default => redirect()->route('home'),
    };
})->middleware('auth')->name('dashboard');
```

---

## ✨ AUTHORIZATION BEST PRACTICES

### 1. USE CONTROLLER AUTHORIZATION METHODS

```php
// app/Http/Controllers/FieldController.php
namespace App\Http\Controllers;

use App\Models\Field;

class FieldController extends Controller
{
    public function edit(Field $field)
    {
        // Check middleware melindungi ini dulu
        // Kemudian cek authorization di controller
        $this->authorize('update', $field);
        
        return view('owner.lapangan.edit', compact('field'));
    }

    public function update(Field $field)
    {
        $this->authorize('update', $field);
        
        // Update logic...
    }
}
```

### 2. ROUTE MODEL BINDING DENGAN AUTHORIZATION

```php
// Model Binding (auto resolve dari route parameter)
Route::put('/lapangan/{field}', [FieldController::class, 'update'])
    ->middleware('role.owner')
    ->name('lapangan.update');

// Controller
public function update(Field $field)
{
    // Laravel otomatis resolve {field} parameter ke model
    // Kemudian anda bisa auth check langsung
    $this->authorize('update', $field);
}
```

### 3. QUERY SCOPING BY CURRENT USER

```php
// Controller - Only query user's own resources
public function index()
{
    // Player hanya lihat booking mereka sendiri
    $bookings = Booking::where('user_id', auth()->id())->get();
    
    // Owner hanya lihat lapangan mereka
    $fields = Field::where('owner_id', auth()->id())->get();
    
    return view('list', compact('bookings', 'fields'));
}
```

---

## ⚠️ ERROR HANDLING

### 1. 403 FORBIDDEN ERROR

Ketika middleware menolak akses, Laravel menampilkan error 403. Customize error page:

```php
// resources/views/errors/403.blade.php
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#FFFEF0] to-[#FFF6D7]">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-[#EB5436] mb-4">403</h1>
        <h2 class="text-2xl font-semibold text-[#00004D] mb-2">Forbidden</h2>
        <p class="text-gray-600 mb-6">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        <a href="{{ route('dashboard') }}" class="bg-[#EB5436] text-white px-6 py-3 rounded-lg hover:bg-[#d93d2a]">
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
```

### 2. GRACEFUL ERROR HANDLING IN BOOTSTRAP

```php
// bootstrap/app.php - Sudah dikonfigurasi
->withExceptions(function (Exceptions $exceptions): void {
    $exceptions->render(function (HttpExceptionInterface $exception, Request $request) {
        if ($exception->getStatusCode() !== 403) {
            return null;
        }

        if (Auth::check()) {
            // Redirect ke dashboard jika user authenticated
            return redirect()->route('dashboard');
        }

        // Redirect ke login jika belum authenticated
        return redirect('/login');
    });
})->create();
```

---

## 🧪 TESTING

### 1. TEST MIDDLEWARE PROTECTION

```php
// tests/Feature/AdminMiddlewareTest.php
namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AdminMiddlewareTest extends TestCase
{
    public function test_admin_can_access_admin_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        
        $response->assertStatus(200);
    }

    public function test_owner_cannot_access_admin_dashboard()
    {
        $owner = User::factory()->create(['role' => 'owner']);
        
        $response = $this->actingAs($owner)->get('/admin/dashboard');
        
        $response->assertStatus(403);
    }

    public function test_player_cannot_access_admin_dashboard()
    {
        $player = User::factory()->create(['role' => 'player']);
        
        $response = $this->actingAs($player)->get('/admin/dashboard');
        
        $response->assertStatus(403);
    }

    public function test_guest_redirected_to_login()
    {
        $response = $this->get('/admin/dashboard');
        
        $response->assertRedirect('/login');
    }
}
```

### 2. TEST OWNERSHIP VALIDATION

```php
// tests/Feature/OwnershipMiddlewareTest.php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Field;
use Tests\TestCase;

class OwnershipMiddlewareTest extends TestCase
{
    public function test_owner_can_update_own_field()
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $field = Field::factory()->create(['owner_id' => $owner->id]);
        
        $response = $this->actingAs($owner)
            ->put("/owner/lapangan/{$field->id}", ['name' => 'Updated Field']);
        
        $response->assertStatus(200);
    }

    public function test_owner_cannot_update_other_owner_field()
    {
        $owner1 = User::factory()->create(['role' => 'owner']);
        $owner2 = User::factory()->create(['role' => 'owner']);
        $field = Field::factory()->create(['owner_id' => $owner2->id]);
        
        $response = $this->actingAs($owner1)
            ->put("/owner/lapangan/{$field->id}", ['name' => 'Updated Field']);
        
        $response->assertStatus(403);
    }
}
```

---

## 📝 COMMAND REFERENCE

### Generate Middleware (sudah ada, untuk referensi)

```bash
# Admin middleware
php artisan make:middleware EnsureUserIsAdmin

# Owner middleware
php artisan make:middleware EnsureUserIsOwner

# Player middleware
php artisan make:middleware EnsureUserIsPlayer

# Ownership validation middleware
php artisan make:middleware EnsureOwnership
```

### Generate Policy (untuk authorization kompleks)

```bash
php artisan make:policy FieldPolicy --model=Field
```

### Generate Test Classes

```bash
php artisan make:test AdminMiddlewareTest --feature
php artisan make:test OwnershipMiddlewareTest --feature
```

---

## 🎯 IMPLEMENTATION CHECKLIST

- [x] Middleware sudah dibuat (4 files)
- [x] Middleware sudah diregistrasi di bootstrap/app.php
- [x] Admin routes dilindungi dengan middleware
- [x] Owner routes dilindungi dengan middleware
- [ ] Player routes - ditambahkan jika diperlukan
- [ ] Custom 403 error page (optional)
- [ ] Authorization policies (optional)
- [ ] Test cases (optional)

---

## 🔄 TROUBLESHOOTING

### Masalah: "Class not found" error
**Solusi**: Pastikan namespace di middleware benar dan file ada di `app/Http/Middleware/`

### Masalah: Middleware tidak bekerja
**Solusi**: Pastikan middleware sudah registered di `bootstrap/app.php` dengan alias yang benar

### Masalah: User redirect ke login padahal sudah authenticated
**Solusi**: Pastikan user memiliki `role` field yang benar di database

### Masalah: 403 error di semua route
**Solusi**: Cek database, pastikan user role sesuai (admin/owner/player)

---

## 📚 RESOURCES

- [Laravel Middleware Documentation](https://laravel.com/docs/11.x/middleware)
- [Laravel Authorization & Gates](https://laravel.com/docs/11.x/authorization)
- [Route Model Binding](https://laravel.com/docs/11.x/routing#route-model-binding)

---

**Last Updated**: 2026-05-25
**Maintained By**: Laravel Development Team
