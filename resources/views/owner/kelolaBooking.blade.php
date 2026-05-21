<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Kelola Booking</title>

    @vite(['resources/css/owner-kelola-booking.css', 'resources/js/owner-kelola-booking.js'])

    {{-- GOOGLE FONT --}}
    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    {{-- FONT AWESOME --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>

<body>

<div class="dashboard-layout">

    {{-- SIDEBAR --}}
    @include('owner.navbar')

    {{-- MAIN --}}
    <main class="main-content">

        {{-- TOPBAR --}}
        <div class="topbar">

            <div class="search-box">

                <i class="fa-solid fa-magnifying-glass"></i>

                <input type="text"
                       placeholder="Search bookings, customers...">

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

                    <img src="https://i.pravatar.cc/100"
                         alt="Profile">

                </div>

            </div>

        </div>

        {{-- CONTENT --}}
        <div class="booking-layout">

            {{-- LEFT --}}
            <div class="booking-main">

                {{-- HEADER --}}
                <div class="booking-header">

                    <div>
                        <h1>Selamat datang kembali, Owner Arena Sport!</h1>
                    </div>

                    <button class="add-btn">

                        <i class="fa-solid fa-plus"></i>

                        Tambah Lapangan

                    </button>

                </div>

                {{-- STATS --}}
                <div class="stats-grid">

                    <div class="stats-card">

                        <div>
                            <p>Total Lapangan</p>
                            <h2>12</h2>
                        </div>

                        <div class="stats-icon blue">
                            <i class="fa-solid fa-futbol"></i>
                        </div>

                    </div>

                    <div class="stats-card">

                        <div>
                            <p>Tersedia</p>
                            <h2>2</h2>
                        </div>

                        <div class="stats-icon green">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>

                    </div>

                    <div class="stats-card">

                        <div>
                            <p>Perbaikan</p>
                            <h2>5</h2>
                        </div>

                        <div class="stats-icon yellow">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                        </div>

                    </div>

                    <div class="stats-card">

                        <div>
                            <p>Telah Dibooking</p>
                            <h2>5</h2>
                        </div>

                        <div class="stats-icon red">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>

                    </div>

                </div>

                <div class="content-detail-row">

                    <div class="content-main">

                        {{-- FILTER --}}
                        <div class="filter-card">

                            <div class="filter-left">

                                <button class="filter-btn">

                                    <i class="fa-solid fa-sliders"></i>

                                    Filter

                                    <i class="fa-solid fa-chevron-down"></i>

                                </button>

                            </div>

                            <button class="reset-btn">

                                <i class="fa-solid fa-rotate-right"></i>

                                Reset Filter

                            </button>

                        </div>

                        {{-- TABLE --}}
                        <div class="table-card">

                            <table class="booking-table">

                                <thead>

                                <tr>

                                    <th>
                                        <input type="checkbox">
                                    </th>

                                    <th>Customer</th>
                                    <th>Lapangan</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Durasi</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Aksi</th>

                                </tr>

                                </thead>

                                <tbody>

                                <tr>

                                    <td>
                                        <input type="checkbox">
                                    </td>

                                    <td>

                                        <div class="customer-cell">

                                            <img src="https://i.pravatar.cc/100"
                                                 alt="">

                                            <div>
                                                <h5>Namtan T.</h5>
                                                <p>089123456789</p>
                                            </div>

                                        </div>

                                    </td>

                                    <td>

                                        <div class="field-cell">

                                            <i class="fa-regular fa-futbol"></i>

                                            <div>
                                                <h5>Lapangan A</h5>
                                                <p>Futsal Indoor</p>
                                            </div>

                                        </div>

                                    </td>

                                    <td>
                                        22 Mei 2026
                                    </td>

                                    <td>
                                        08.00 - 09.00
                                    </td>

                                    <td>
                                        1 Jam
                                    </td>

                                    <td>

                                        <span class="status-badge success">
                                            Telah Dikonfirmasi
                                        </span>

                                    </td>

                                    <td>
                                        Rp120.000
                                    </td>

                                    <td>

                                        <button class="action-btn">
                                            <i class="fa-solid fa-ellipsis"></i>
                                        </button>

                                    </td>

                                </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                    {{-- RIGHT DETAIL --}}
                    <div class="booking-detail">

                        <div class="detail-card">

                            <div class="detail-header">

                                <h3>Detail Booking</h3>

                                <button>
                                    <i class="fa-solid fa-xmark"></i>
                                </button>

                            </div>

                            <div class="status-badge success">
                                Telah Dikonfirmasi
                            </div>

                            <div class="booking-id">
                                Booking ID #B3422131
                            </div>

                            {{-- PROFILE --}}
                            <div class="detail-profile">

                                <img src="https://i.pravatar.cc/100"
                                     alt="">

                                <div>

                                    <h4>Namtan Tipnaree</h4>

                                    <p>089123456789</p>

                                    <p>namtanowner@gmail.com</p>

                                </div>

                            </div>

                            {{-- INFO --}}
                            <div class="detail-section">

                                <h4>Informasi Booking</h4>

                                <div class="detail-info">

                                    <div>
                                        <span>Lapangan</span>
                                        <strong>Lapangan A</strong>
                                    </div>

                                    <div>
                                        <span>Tanggal</span>
                                        <strong>22 Mei 2026</strong>
                                    </div>

                                    <div>
                                        <span>Waktu</span>
                                        <strong>08.00 - 09.00</strong>
                                    </div>

                                    <div>
                                        <span>Harga / jam</span>
                                        <strong>Rp120.000</strong>
                                    </div>

                                </div>

                            </div>

                            {{-- HISTORY --}}
                            <div class="detail-section">

                                <h4>Riwayat Booking</h4>

                                <ul class="history-list">

                                    <li>Booking dibuat</li>
                                    <li>Menunggu konfirmasi</li>
                                    <li>Booking selesai</li>

                                </ul>

                            </div>

                            {{-- BUTTON --}}
                            <button class="edit-btn">

                                <i class="fa-solid fa-pen"></i>

                                Edit

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>

</div>

</body>
</html>