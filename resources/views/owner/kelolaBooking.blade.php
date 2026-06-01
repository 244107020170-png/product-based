<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Kelola Pesanan</title>

    @vite(['resources/css/app.css', 'resources/css/owner-kelola-booking.css', 'resources/js/owner-kelola-booking.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
                       placeholder="Cari pemesanan, pelanggan...">

            </div>

            <div class="topbar-right">

                <a href="{{ route('owner.notifikasi') }}" class="notif-btn" style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;position:relative;">
                    <i class="fa-solid fa-bell"></i>
                    @if(auth()->user()->unreadNotifications()->count() > 0)
                        <span style="position:absolute;top:2px;right:2px;width:10px;height:10px;background:#ef4444;border:2px solid #fff;border-radius:50%;"></span>
                    @endif
                </a>

                <button class="notif-btn">
                    <i class="fa-solid fa-headset"></i>
                </button>
                <a href="{{ route('owner.bantuan') }}" class="notif-btn question" style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                    <i class="fa-solid fa-circle-question"></i>
                </a>

                <div class="profile-box">

                    <div>
                        <h5>{{ auth()->user()->name }}</h5>
                        <p>Profil Pemilik</p>
                    </div>

                    <img src="https://i.pravatar.cc/100"
                         alt="Profil">

                </div>

            </div>

        </div>

        {{-- CONTENT --}}
        <div class="booking-layout">

            {{-- LEFT --}}
            <div class="booking-main">

                {{-- HEADER --}}
                <div class="welcome-section">

                    <div>
                        <h1>Lihat Pesanan Lapangan Anda</h1>
                        <p>Kelola semua pemesanan lapangan olahraga Anda.</p>
                    </div>
                    <a href="{{ route('owner.tambahLapangan') }}" class="add-btn">
                        <i class="fa-solid fa-plus"></i> Tambah Lapangan
                    </a>

                </div>

                {{-- STATS --}}
                @php
                    $fieldIds = auth()->user()->fields->pluck('id');
                    $totalFields = $fieldIds->count();
                    $availableFields = \App\Models\Field::whereIn('id', $fieldIds)->where('is_available', true)->count();
                    $inMaintenance = \App\Models\Maintenance::whereHas('field', fn($q) => $q->whereIn('id', $fieldIds))
                        ->where('status', '!=', 'Selesai')->count();
                    $bookedCount = \App\Models\Booking::whereHas('field', fn($q) => $q->whereIn('id', $fieldIds))
                        ->whereIn('status', ['pending', 'waiting_payment', 'paid', 'confirmed', 'completed'])->count();
                @endphp
                <div class="stats-grid">

                    <div class="stats-card">

                        <div>
                            <p>Total Lapangan</p>
                            <h2>{{ $totalFields }}</h2>
                        </div>

                        <div class="stats-icon blue">
                            <i class="fa-solid fa-futbol"></i>
                        </div>

                    </div>

                    <div class="stats-card">

                        <div>
                            <p>Tersedia</p>
                            <h2>{{ $availableFields }}</h2>
                        </div>

                        <div class="stats-icon green">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>

                    </div>

                    <div class="stats-card">

                        <div>
                            <p>Perbaikan</p>
                            <h2>{{ $inMaintenance }}</h2>
                        </div>

                        <div class="stats-icon yellow">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                        </div>

                    </div>

                    <div class="stats-card">

                        <div>
                            <p>Telah Dipesan</p>
                            <h2>{{ $bookedCount }}</h2>
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

                                <div class="filter-dropdown">
                                    <button class="filter-btn">

                                        <i class="fa-solid fa-sliders"></i>

                                        Filter

                                        <i class="fa-solid fa-chevron-down"></i>

                                    </button>

                                    <div class="filter-menu">
                                        <span data-status="all" class="filter-option active">Semua</span>
                                        <span data-status="Menunggu Pembayaran" class="filter-option">Menunggu Pembayaran</span>
                                        <span data-status="Dibayar" class="filter-option">Dibayar</span>
                                        <span data-status="Dikonfirmasi" class="filter-option">Dikonfirmasi</span>
                                        <span data-status="Selesai" class="filter-option">Selesai</span>
                                        <span data-status="Dibatalkan" class="filter-option">Dibatalkan</span>
                                        <span data-status="Ditolak" class="filter-option">Ditolak</span>
                                    </div>
                                </div>

                                <div class="sort-group">
                                    <button class="sort-btn active" data-sort="terbaru">Terbaru</button>
                                    <button class="sort-btn" data-sort="terlama">Terlama</button>
                                </div>

                            </div>

                            <button class="reset-btn">

                                <i class="fa-solid fa-rotate-right"></i>

                                Atur Ulang Filter

                            </button>

                        </div>

                        {{-- TABLE --}}
                        <div class="table-card">

                            <table class="booking-table">

                                <thead>

                                <tr>

                                    <th>Pelanggan</th>
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

                                @forelse ($bookings as $booking)
                                @php
                                    $statusLabel = match($booking->status) {
                                        'pending', 'waiting_payment' => 'Menunggu Pembayaran',
                                        'waiting_confirmation' => 'Menunggu Konfirmasi',
                                        'paid'      => 'Dibayar',
                                        'confirmed' => 'Dikonfirmasi',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                        'rejected'  => 'Ditolak',
                                        'expired'   => 'Kadaluarsa',
                                        default     => ucfirst($booking->status),
                                    };
                                    $badgeClass = match($booking->status) {
                                        'paid' => 'success',
                                        'confirmed' => 'success',
                                        'completed' => 'info',
                                        'pending', 'waiting_payment' => 'secondary',
                                        'waiting_confirmation' => 'warning',
                                        'cancelled' => 'danger',
                                        'rejected' => 'danger-dark',
                                        'expired' => 'danger',
                                        default => 'info',
                                    };
                                    $start = \Carbon\Carbon::parse($booking->start_time);
                                    $end   = \Carbon\Carbon::parse($booking->end_time);
                                    $hours = max(1, $start->diffInHours($end));
                                    $duration = $hours . ' Jam';
                                    if ($hours != floor($hours)) $duration = number_format($hours, 1) . ' Jam';
                                @endphp
                                <tr data-booking-id="{{ $booking->id }}"
                                    data-customer-name="{{ $booking->user?->name ?? 'N/A' }}"
                                    data-customer-phone="{{ $booking->user?->phone ?? '' }}"
                                    data-customer-email="{{ $booking->user?->email ?? '' }}"
                                    data-field-name="{{ $booking->field?->name ?? 'N/A' }}"
                                    data-field-type="{{ $booking->field?->type ?? '' }}"
                                    data-date="{{ $booking->date?->format('d M Y') }}"
                                    data-date-sort="{{ $booking->date?->format('Y-m-d') }}"
                                    data-time="{{ \Carbon\Carbon::parse($booking->start_time)->format('H.i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H.i') }}"
                                    data-time-sort="{{ $booking->start_time }}"
                                    data-duration="{{ $duration }}"
                                    data-status="{{ $statusLabel }}"
                                    data-price="Rp{{ number_format($booking->total_price ?? 0, 0, ',', '.') }}"
                                    data-raw-status="{{ $booking->status }}"
                                    data-court-number="{{ $booking->court_number ?? '' }}">

                                    <td>

                                        <div class="customer-cell">

                                            <img src="https://i.pravatar.cc/100?u={{ $booking->user_id }}"
                                                 alt="">

                                            <div>
                                                <h5>{{ $booking->user?->name ?? 'N/A' }}</h5>
                                                <p>{{ $booking->user?->phone ?? '-' }}</p>
                                            </div>

                                        </div>

                                    </td>

                                    <td>

                                        <div class="field-cell">

                                            <i class="fa-regular fa-futbol"></i>

                                            <div>
                                                <h5>{{ $booking->field?->name ?? 'N/A' }} @if($booking->court_number)(Lapangan {{ $booking->court_number }})@endif</h5>
                                                <p>{{ $booking->field?->type ?? 'Olahraga' }}</p>
                                            </div>

                                        </div>

                                    </td>

                                    <td>
                                        {{ $booking->date?->format('d M Y') }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H.i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H.i') }}
                                    </td>

                                    <td>
                                        {{ $duration }}
                                    </td>

                                    <td>

                                        <span class="status-badge {{ $badgeClass }}">
                                            {{ $statusLabel }}
                                        </span>

                                    </td>

                                    <td>
                                        Rp{{ number_format($booking->total_price ?? 0, 0, ',', '.') }}
                                    </td>

                                    <td>
                                        <button class="action-btn" style="cursor: pointer;">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 40px; color: #888;">
                                        <i class="fa-solid fa-inbox" style="font-size: 48px; display: block; margin-bottom: 16px; color: #ccc;"></i>
                                        Belum ada pesanan
                                    </td>
                                </tr>
                                @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                    {{-- RIGHT DETAIL --}}
                    <div class="booking-detail">

                        <div class="detail-card">

                            <div class="detail-header">

                                <h3>Detail Pesanan</h3>

                                <button>
                                    <i class="fa-solid fa-xmark"></i>
                                </button>

                            </div>

                            <div class="status-badge success" data-field="status">
                                Telah Dikonfirmasi
                            </div>

                            <div class="booking-id">
                                ID Pesanan #B3422131
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

                                <h4>Informasi Pesanan</h4>

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
                                        <span>Total Harga</span>
                                        <strong>Rp120.000</strong>
                                    </div>

                                </div>

                            </div>

                            {{-- HISTORY --}}
                            <div class="detail-section">

                                <h4>Riwayat Pesanan</h4>

                                <ul class="history-list">

                                    <li style="color: black">Pesanan dibuat</li>
                                    <li style="color: #F29E10">Menunggu konfirmasi</li>
                                    <li style="color: #1b9d59">Pesanan selesai</li>

                                </ul>

                            </div>

                            {{-- BUTTONS --}}
                            <div class="edit-actions">
                                <button class="edit-btn">

                                    <i class="fa-solid fa-pen"></i>

                                    Ubah

                                </button>

                                <button class="save-btn" style="display:none;">

                                    <i class="fa-solid fa-check"></i>

                                    Simpan

                                </button>

                                <button class="cancel-btn" style="display:none;">

                                    <i class="fa-solid fa-xmark"></i>

                                    Batal

                                </button>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>

</div>

@include('owner.faq-popup')
</body>
</html>