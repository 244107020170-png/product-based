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

    {{-- MOBILE SIDEBAR TOGGLE --}}
    <button id="sidebarToggle" class="sidebar-toggle-btn" onclick="toggleOwnerSidebar()" aria-label="Toggle sidebar">
        <i class="fa-solid fa-bars"></i>
    </button>

    {{-- SIDEBAR OVERLAY --}}
    <div id="sidebarOverlay" class="sidebar-overlay" onclick="toggleOwnerSidebar()"></div>

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

                <button class="notif-btn question" onclick="document.getElementById('ownerFaqModal').style.display='flex'">
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

<script>
function toggleOwnerSidebar() {
    document.querySelector('.sidebar').classList.toggle('is-open');
    document.getElementById('sidebarOverlay').classList.toggle('is-visible');
}
</script>

<div id="ownerFaqModal" style="display:none; position:fixed; inset:0; z-index:99999; background:rgba(0,0,0,.5); justify-content:center; align-items:center;" onclick="if(event.target===this)document.getElementById('ownerFaqModal').style.display='none'">
    <div style="background:white; border-radius:20px; padding:28px 24px; max-width:440px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,.25); max-height:80vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h3 style="margin:0; font-size:18px; font-weight:800; color:#02025b;">Pusat Bantuan</h3>
            <span onclick="document.getElementById('ownerFaqModal').style.display='none'" style="cursor:pointer; font-size:24px; color:#999; line-height:1;">&times;</span>
        </div>
        <div style="display:flex; flex-direction:column; gap:12px;">
            @php
                $faqs = [
                    ['q' => 'Bagaimana cara menambah lapangan?', 'a' => 'Klik tombol "Tambah Lapangan" di halaman ini atau buka menu Kelola Lapangan > Tambah Lapangan. Isi data lapangan lalu simpan.'],
                    ['q' => 'Bagaimana cara mengelola booking?', 'a' => 'Buka menu Kelola Booking. Anda bisa melihat, mengkonfirmasi, atau membatalkan booking dari sana.'],
                    ['q' => 'Bagaimana cara mengatur jadwal & slot?', 'a' => 'Buka menu Jadwal & Slot. Atur jadwal buka/tutup lapangan dan slot waktu yang tersedia.'],
                    ['q' => 'Bagaimana cara mengelola promo?', 'a' => 'Buka menu Promo & Diskon. Anda bisa membuat kode promo atau diskon khusus untuk lapangan Anda.'],
                    ['q' => 'Bagaimana cara menghubungi CS?', 'a' => 'Hubungi Customer Service kami di WhatsApp melalui tautan berikut.'],
                ];
            @endphp
            @foreach($faqs as $f)
            <div style="background:#f8f9ff; border-radius:12px; padding:14px 16px;">
                <p style="margin:0 0 6px; font-weight:700; color:#02025b; font-size:14px;">{{ $f['q'] }}</p>
                <p style="margin:0; color:#555; font-size:13px; line-height:1.5;">
                    {{ $f['a'] }}
                    @if(str_contains($f['a'], 'WhatsApp'))
                        <a href="https://wa.me/6281234567890?text=Halo%20CS%20Spies%20Sport" target="_blank" style="color:#EB5436; font-weight:700;">klik di sini</a>
                    @endif
                </p>
            </div>
            @endforeach
        </div>
    </div>
</div>

</body>
</html>