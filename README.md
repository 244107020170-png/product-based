# Persiapan Tech Stack

## Requirements
- PHP >= 8.2  
- Composer  
- MySQL  
- Filament  

---

## Detail

### 1. Progress Report Mingguan – Sistem Spies

#### Perubahan Teknologi Frontend
- Awalnya menggunakan **Bootstrap**  
- Diganti menjadi **Tailwind CSS**  

**Alasan:**
- Lebih fleksibel untuk custom UI  
- Desain modern lebih mudah diikuti  
- Cocok untuk pengembangan komponen berbasis Blade  

---

#### Penghapusan Filament (Admin Panel)
- Awalnya direncanakan menggunakan **Filament**  
- Setelah eksplorasi ditemukan kendala:  
  - Customisasi UI (login page) terbatas  
  - Struktur cukup kompleks untuk kebutuhan saat ini  

**Keputusan:**  
Filament dihapus, sistem auth & UI dibuat manual dengan **Laravel + Blade**  

---

#### Implementasi Authentication (Laravel Breeze)
Langkah:
```bash
composer require laravel/breeze --dev
php artisan breeze:install
npm install && npm run dev
php artisan migrate
```

**Hasil:**
- Login, register, logout tersedia  
- Auth berbasis session Laravel berjalan  

---

#### Setup Database
- Nama database: **spies**  
- Konfigurasi di `.env`  
- Migrasi berhasil dijalankan:  
  ```bash
  php artisan migrate
  ```

---

#### Penambahan Role User
- Ditambahkan kolom `role` pada tabel `users`  
- Update `User.php`:
  ```php
  protected $fillable = [
      'name',
      'email',
      'password',
      'role',
  ];
  ```

**Role yang digunakan:**
- `player`  
- `owner`  
- *(admin dipertimbangkan nanti)*  

---

#### Struktur Routing Berdasarkan Role

**Dashboard Logic:**
```php
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'owner') {
        return redirect('/owner/dashboard');
    } else {
        return view('dashboard'); // player
    }
})->middleware(['auth'])->name('dashboard');
```

**Owner Routes:**
```php
Route::prefix('owner')->group(function () {
    Route::get('/dashboard', fn() => view('owner.dashboard'));
    Route::get('/fields', fn() => view('owner.fields'));
    Route::get('/bookings', fn() => view('owner.bookings'));
});
```

**Player Routes:**
```php
Route::get('/fields', fn() => view('fields.index'));
Route::get('/matches', fn() => view('matches.index'));
```

---

#### Struktur View (Blade)
```
resources/views/
│
├── layouts/
│   ├── app.blade.php
│   └── navigation.blade.php
│
├── owner/
│   ├── dashboard.blade.php
│   ├── fields.blade.php
│   └── bookings.blade.php
│
├── fields/
│   └── index.blade.php
│
├── matches/
│   └── index.blade.php
│
└── dashboard.blade.php
```

---

#### Kendala & Solusi

- **Undefined $slot**  
  → Gunakan `@yield('content')` atau `<x-app-layout>` dengan benar  

- **Component layouts.app tidak ditemukan**  
  → Gunakan `@extends('layouts.app')` + `@section('content')`  

- **Error Auth (User null)**  
  → Gunakan `@auth {{ Auth::user()->name }} @endauth`  

- **Logout Error**  
  → Tambahkan conditional `@auth ... @endauth`  

- **Out of Memory Error**  
  → Perbaiki struktur layout agar tidak recursive  

---

#### Status Saat Ini
✅ Auth system berjalan (login, register, logout)  
✅ Role system sudah disiapkan  
✅ Routing dasar sesuai role  
✅ Struktur frontend mulai terbentuk  
✅ Tailwind sudah digunakan  

---

#### Rencana Selanjutnya
- Finalisasi desain UI/UX  
- Implementasi tampilan:
  - Dashboard (owner & player)  
  - Booking system UI  
  - Field listing  
- Tambahan:
  - Validasi role (middleware)  
  - Seeder (opsional, untuk testing)  
- Evaluasi kebutuhan admin panel (manual vs Filament)  

---

## Kesimpulan
Minggu ini fokus pada **fondasi sistem**: authentication, routing berbasis role, dan setup awal frontend dengan Tailwind.  
Memungkinkan untuk menggunakan filament ya.