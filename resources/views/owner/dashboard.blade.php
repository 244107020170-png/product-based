<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Owner</title>

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
                <button class="notif-btn" onclick="window.location.href='#'" type="button" style="display: inline-flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-headset"></i>
                </button>
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
                <p>Kelola lapangan dan booking Anda dengan mudah.</p>
            </div>

            <a href="{{ route('owner.tambahLapangan') }}" class="add-btn">
                <i class="fa-solid fa-plus"></i>
                Tambah Lapangan
            </a>
        </div>

        {{-- STATISTIC CARDS --}}
        <div class="stats-grid">

            <div class="stats-card">
                <div>
                    <p>Total Lapangan</p>
                    <h2 class="blue-text">{{ $fieldCount ?? 0 }}</h2>
                </div>

                <div class="stats-icon blue">
                    <i class="fa-regular fa-futbol"></i>
                </div>
            </div>

            <div class="stats-card">
                <div>
                    <p>Total Booking</p>
                    <h2 class="green-text">{{ $bookingCount ?? 0 }}</h2>
                </div>

                <div class="stats-icon green">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>

            <div class="stats-card">
                <div>
                    <p>Booking Hari Ini</p>
                    <h2 class="yellow-text">{{ $todayBooking ?? 0 }}</h2>
                </div>

                <div class="stats-icon yellow">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
            </div>

            <div class="stats-card">
                <div>
                    <p>Pendapatan Bulan Ini</p>
                    <h2 class="red-text">Rp {{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }}</h2>
                </div>

                <div class="stats-icon red">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
            </div>

        </div>

        {{-- QUICK ACTIONS --}}
        <div class="quick-actions">
            <h3>Akses Cepat</h3>
            <div class="actions-grid">
                <a href="{{ route('owner.kelolaLapangan') }}" class="action-card">
                    <i class="fa-solid fa-list"></i>
                    <span>Kelola Lapangan</span>
                </a>
                <a href="{{ route('owner.kelolaBooking') }}" class="action-card">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Kelola Booking</span>
                </a>
                <a href="{{ route('owner.jadwalDanSlot') }}" class="action-card">
                    <i class="fa-solid fa-clock"></i>
                    <span>Jadwal & Slot</span>
                </a>
                <a href="{{ route('owner.promosiDiskon') }}" class="action-card">
                    <i class="fa-solid fa-tag"></i>
                    <span>Promo & Diskon</span>
                </a>
            </div>
        </div>

    </main>

</div>

</body>
</html>