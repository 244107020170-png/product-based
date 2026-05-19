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
                        <h5>Namtan</h5>
                        <p>Owner Profile</p>
                    </div>

                    <img src="https://i.pravatar.cc/100" alt="Profile">
                </div>
            </div>
        </div>


        {{-- WELCOME SECTION --}}
        <div class="welcome-section">
            <div>
                <h1>Selamat datang kembali, Owner Arena Sport!</h1>
            </div>

            <a href="/owner/tambahLapangan" class="add-btn">
                <i class="fa-solid fa-plus"></i>
                Tambah Lapangan
            </a>
        </div>


        {{-- STATISTIC CARD --}}
        <div class="stats-grid">

            <div class="stats-card">
                <div>
                    <p>Total Lapangan</p>
                    <h2 class="blue-text">12</h2>
                </div>

                <div class="stats-icon blue">
                    <i class="fa-regular fa-futbol"></i>
                </div>
            </div>

            <div class="stats-card">
                <div>
                    <p>Tersedia</p>
                    <h2 class="green-text">2</h2>
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

            @for ($i = 0; $i < 10; $i++)
            <div class="field-card">

                <div class="field-image">
                    <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=1200&auto=format&fit=crop" alt="Lapangan">

                    <span class="badge">Futsal</span>
                </div>

                <div class="field-content">
                    <div class="field-top">
                        <div>
                            <h3>Lapangan A</h3>
                            <p>Futsal</p>
                        </div>

                        <h4>Rp120.000</h4>
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
                            08.00 - 22.00
                        </span>

                        <span>
                            ⭐ 4.8
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
            @endfor

        </div>

    </main>

</div>

</body>
</html>