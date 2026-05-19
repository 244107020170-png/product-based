<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Lapangan</title>

    @vite(['resources/css/owner-dashboard.css', 'resources/css/owner-bookings.css'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>
<body>

<div class="dashboard-layout">

    {{-- SIDEBAR --}}
    @include('owner.navbar')

    {{-- MAIN CONTENT --}}
    <main class="main-content">

        {{-- TOPBAR --}}
        <div class="topbar">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Search bookings, customers...">
            </div>

            <div class="topbar-right">
                <button class="notif-btn">
                    <i class="fa-solid fa-bell"></i>
                </button>

                <button class="notif-btn question">
                    <i class="fa-solid fa-circle-question"></i>
                </button>

                <div class="profile-box">
                    <div>
                        <h5>{{ auth()->user()->name }}</h5>
                        <p>Owner Profile</p>
                    </div>

                    <img src="https://i.pravatar.cc/100" alt="Profile">
                </div>
            </div>
        </div>


        {{-- WELCOME SECTION --}}
        <div class="welcome-section">
            <div>
                <h1>Selamat datang kembali, {{ auth()->user()->name }}!</h1>
            </div>

            <a href="{{ route('owner.tambahLapangan') }}" class="add-btn">
                <i class="fa-solid fa-plus"></i>
                Tambah Lapangan
            </a>
        </div>


        {{-- STATISTIC CARD --}}
        <div class="stats-grid">

            <div class="stats-card">
                <div>
                    <p>Total Lapangan</p>
                    <h2 class="blue-text">{{ count($fields) }}</h2>
                </div>

                <div class="stats-icon blue">
                    <i class="fa-regular fa-futbol"></i>
                </div>
            </div>

            <div class="stats-card">
                <div>
                    <p>Tersedia</p>
                    <h2 class="green-text">{{ count($fields) }}</h2>
                </div>

                <div class="stats-icon green">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>

            <div class="stats-card">
                <div>
                    <p>Perbaikan</p>
                    <h2 class="yellow-text">5</h2>
                </div>

                <div class="stats-icon yellow">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
            </div>

            <div class="stats-card">
                <div>
                    <p>Telah Dibooking</p>
                    <h2 class="red-text">5</h2>
                </div>

                <div class="stats-icon red">
                    <i class="fa-regular fa-calendar-check"></i>
                </div>
            </div>

        </div>


        {{-- FILTER --}}
        <div class="filter-section">
            <select>
                <option>Filter</option>
                <option>Futsal</option>
                <option>Basket</option>
                <option>Badminton</option>
            </select>

            <button>
                <i class="fa-solid fa-rotate-right"></i>
                Reset Filter
            </button>
        </div>


        {{-- FIELD CARD --}}
        <div class="field-grid">

            @forelse ($fields as $field)
            <div class="field-card">

                <div class="field-image">
                    <img src="{{ $field->cover_photo ?? 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=1200&auto=format&fit=crop' }}" alt="{{ $field->name }}">

                    <span class="badge">{{ $field->sport_type ?? 'Olahraga' }}</span>
                </div>

                <div class="field-content">
                    <div class="field-top">
                        <div>
                            <h3>{{ $field->name }}</h3>
                            <p>{{ $field->sport_type ?? 'Olahraga' }}</p>
                        </div>

                        <h4>Rp{{ number_format($field->price_per_hour ?? 0, 0, ',', '.') }}</h4>
                    </div>

                    <div class="facility-icons">
                        <span><i class="fa-solid fa-wifi"></i></span>
                        <span><i class="fa-solid fa-shower"></i></span>
                        <span><i class="fa-solid fa-car"></i></span>
                        <span><i class="fa-solid fa-fan"></i></span>
                    </div>

                    <div class="field-info">
                        <span>
                            <i class="fa-regular fa-clock"></i>
                            {{ $field->opening_time ?? '08:00' }} - {{ $field->closing_time ?? '22:00' }}
                        </span>

                        <span>
                            ⭐ {{ $field->rating ?? '4.8' }}
                        </span>
                    </div>

                    <div class="field-actions">
                        <button class="edit-btn">Edit</button>
                        <button class="schedule-btn">Jadwal</button>

                        <button class="more-btn">
                            <i class="fa-solid fa-ellipsis"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                <i class="fa-solid fa-inbox" style="font-size: 48px; color: #ccc; margin-bottom: 16px; display: block;"></i>
                <h3 style="color: #888; font-size: 18px; margin-bottom: 8px;">Belum ada lapangan</h3>
                <p style="color: #aaa; margin-bottom: 20px;">Mulai tambahkan lapangan untuk memulai bisnis Anda</p>
                <a href="{{ route('owner.tambahLapangan') }}" style="display: inline-block; background: linear-gradient(135deg, #ff4d4d, #ff2e63); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                    <i class="fa-solid fa-plus"></i> Tambah Lapangan
                </a>
            </div>
            @endforelse

        </div>

    </main>

</div>

</body>
</html>