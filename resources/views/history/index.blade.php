@php
    use Carbon\Carbon;

    $user            = auth()->user();
$userName = $user?->name ?: 'Pecinta Olahraga';
    $currentDate     = Carbon::now()->locale('id')->translatedFormat('j F Y');
    $profileAvatar = $user?->avatarUrl();

    $sidebarItems = [
        ['label' => 'Beranda',  'icon' => asset('assets/images/icons/dashboard.png'),  'href' => route('dashboard'),      'active' => false],
        ['label' => 'Aktivitas',  'icon' => asset('assets/images/icons/aktivitas.png'),  'href' => url('/matches'),         'active' => false],
        ['label' => 'Favorit', 'icon' => asset('assets/images/icons/favoritmu.png'),  'href' => route('favorite.index'),                    'active' => false],
        ['label' => 'Histori',   'icon' => asset('assets/images/icons/histori.png'),    'href' => route('history.index'),  'active' => true],
        ['label' => 'Cari tim',  'icon' => asset('assets/images/icons/caritim.png'),   'href' => route('matches.index'),  'active' => false],
        ['label' => 'Pemesanan',   'icon' => asset('assets/images/icons/booking.png'),   'href' => route('booking.index'),          'active' => false],
        ['label' => 'Keahlian','icon' => asset('assets/images/icons/keahlian.png'),  'href' => route('skill.index'),                    'active' => false],
        ['label' => 'Profil',    'icon' => asset('assets/images/icons/profil.png'),    'href' => route('profile.show'),   'active' => false],
    ];

    $sidebarUtilities = [
        ['label' => 'Bantuan',    'icon' => asset('assets/images/icons/bantuan.png'),    'href' => route('preview.help')],
        ['label' => 'Pengaturan','icon' => asset('assets/images/icons/pengaturan.png'), 'href' => route('profile.edit')],
    ];

    // Status map for display
    $statusMap = [
        'selesai'             => ['label' => 'Selesai',              'class' => 'history-status--selesai', 'filter' => 'selesai'],
        'completed'           => ['label' => 'Selesai',              'class' => 'history-status--selesai', 'filter' => 'selesai'],
        'confirmed'           => ['label' => 'Akan Datang',          'class' => 'history-status--akan',    'filter' => 'akan_datang'],
        'pending'             => ['label' => 'Menunggu Konfirmasi',  'class' => 'history-status--pending',  'filter' => 'akan_datang'],
        'waiting_payment'     => ['label' => 'Menunggu Pembayaran',  'class' => 'history-status--pending',  'filter' => 'akan_datang'],
        'waiting_confirmation'=> ['label' => 'Menunggu Konfirmasi',  'class' => 'history-status--pending',  'filter' => 'akan_datang'],
        'paid'                => ['label' => 'Dibayar',              'class' => 'history-status--akan',    'filter' => 'akan_datang'],
        'cancelled'           => ['label' => 'Dibatalkan',           'class' => 'history-status--dibatal', 'filter' => 'dibatalkan'],
        'expired'             => ['label' => 'Kadaluarsa',           'class' => 'history-status--dibatal', 'filter' => 'dibatalkan'],
        'rejected'            => ['label' => 'Ditolak',              'class' => 'history-status--dibatal', 'filter' => 'dibatalkan'],
    ];

    $nonSelesaiStatuses = ['cancelled', 'expired', 'rejected'];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Histori Pemesanan - {{ config('app.name', 'Spies Sport') }}</title>

    @vite([
        'resources/css/app.css',
        'resources/css/player-dashboard.css',
        'resources/css/player-profile-view.css',
        'resources/css/player-history.css',
        'resources/js/player-history.js',
    ])
</head>
<body class="player-dashboard-page" style="--player-dashboard-bg: url('{{ asset('assets/images/bg/bg-login.png') }}');">
    <div class="player-dashboard-shell">

        {{-- ======== SIDEBAR ======== --}}
        <aside class="player-sidebar" data-sidebar>
            <div class="player-sidebar__inner">
                <div class="player-sidebar__header">
                    <a href="{{ route('dashboard') }}" class="player-sidebar__brand" aria-label="Dashboard">
                        <img src="{{ asset('assets/images/logo/logodb.png') }}" alt="Spies Sport" class="player-sidebar__logo">
                    </a>
                    <button type="button" class="player-sidebar__close" data-sidebar-close aria-label="Tutup sidebar">
                        <span></span><span></span>
                    </button>
                </div>

                <nav class="player-sidebar__nav" aria-label="Menu utama player">
                    @foreach ($sidebarItems as $item)
                        @php
                            $itemClasses = 'player-sidebar__item'.($item['active'] ? ' is-active' : '').($item['href'] ? '' : ' is-disabled');
                        @endphp
                        @if ($item['href'])
                            <a href="{{ $item['href'] }}" class="{{ $itemClasses }}">
                                <span class="player-sidebar__icon-wrap">
                                    <img src="{{ $item['icon'] }}" alt="" class="player-sidebar__icon">
                                </span>
                                <span class="player-sidebar__label">{{ $item['label'] }}</span>
                            </a>
                        @else
                            <button type="button" class="{{ $itemClasses }}" disabled aria-disabled="true">
                                <span class="player-sidebar__icon-wrap">
                                    <img src="{{ $item['icon'] }}" alt="" class="player-sidebar__icon">
                                </span>
                                <span class="player-sidebar__label">{{ $item['label'] }}</span>
                            </button>
                        @endif
                    @endforeach
                </nav>

                <div class="player-sidebar__footer">
                    @foreach ($sidebarUtilities as $item)
                        <a href="{{ $item['href'] }}" class="player-sidebar__item">
                            <span class="player-sidebar__icon-wrap">
                                <img src="{{ $item['icon'] }}" alt="" class="player-sidebar__icon">
                            </span>
                            <span class="player-sidebar__label">{{ $item['label'] }}</span>
                        </a>
                    @endforeach

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="player-sidebar__item player-sidebar__item--logout">
                            <span class="player-sidebar__icon-wrap">
                                <img src="{{ asset('assets/images/icons/keluar.png') }}" alt="" class="player-sidebar__icon">
                            </span>
                            <span class="player-sidebar__label">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <button type="button" class="player-sidebar__backdrop" data-sidebar-backdrop aria-label="Tutup sidebar"></button>

        {{-- ======== MAIN CONTENT ======== --}}
        <main class="player-dashboard-main">

            {{-- Topbar --}}
            <header class="player-dashboard-topbar">
                <div class="player-dashboard-topbar__left">
                    <button type="button" class="player-dashboard-topbar__menu" data-sidebar-open aria-label="Buka sidebar">
                        <span></span><span></span><span></span>
                    </button>

                    <label class="player-search" for="history-search">
                        <span class="player-search__icon" aria-hidden="true">
                            <svg viewBox="0 0 20 20" fill="none">
                                <circle cx="9" cy="9" r="5.75" stroke="currentColor" stroke-width="1.8"></circle>
                                <path d="M13.5 13.5L17 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                            </svg>
                        </span>
                        <input id="history-search" type="search" placeholder="Cari lapangan...">
                    </label>
                </div>

                <div class="player-dashboard-topbar__right">
                    <div class="player-dashboard-topbar__date">
                        <span class="player-inline-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none">
                                <rect x="3.5" y="5.5" width="17" height="15" rx="2.5" stroke="currentColor" stroke-width="1.8"></rect>
                                <path d="M7 3.5V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                                <path d="M17 3.5V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                                <path d="M3.5 9.5H20.5" stroke="currentColor" stroke-width="1.8"></path>
                            </svg>
                        </span>
                        <span>{{ $currentDate }}</span>
                    </div>

                    <div style="position: relative;">
                        @include('partials.notification-bell')
                    </div>

                    <a href="{{ route('profile.show') }}" class="player-profile-pill" aria-label="Profil pengguna">
                        <span class="player-profile-pill__avatar">
                            <img src="{{ $profileAvatar }}" alt="Foto profil {{ $userName }}" class="player-avatar-image player-avatar-image--profile">
                        </span>
                        <span class="player-profile-pill__name">{{ $userName }}</span>
                    </a>
                </div>
            </header>

            {{-- ======= HISTORY PAGE ======= --}}
            <div class="history-page">

                <h1 class="history-page__title">Histori</h1>

                {{-- ── Stats ── --}}
                <div class="history-stats">
                    <button type="button" class="history-stat-card{{ $statusFilter === 'semua' ? ' is-active' : '' }}"
                        data-stat-filter="semua">
                        <span class="history-stat-card__number">{{ $totalSemua }}</span>
                        <span class="history-stat-card__label">
                            <span class="history-stat-dot history-stat-dot--semua"></span>
                            Semua
                        </span>
                    </button>
                    <button type="button" class="history-stat-card{{ $statusFilter === 'selesai' ? ' is-active' : '' }}"
                        data-stat-filter="selesai">
                        <span class="history-stat-card__number">{{ $totalSelesai }}</span>
                        <span class="history-stat-card__label">
                            <span class="history-stat-dot history-stat-dot--selesai"></span>
                            Selesai
                        </span>
                    </button>
                    <button type="button" class="history-stat-card{{ $statusFilter === 'akan_datang' ? ' is-active' : '' }}"
                        data-stat-filter="akan_datang">
                        <span class="history-stat-card__number">{{ $totalAkan }}</span>
                        <span class="history-stat-card__label">
                            <span class="history-stat-dot history-stat-dot--akan"></span>
                            Akan Datang
                        </span>
                    </button>
                    <button type="button" class="history-stat-card{{ $statusFilter === 'dibatalkan' ? ' is-active' : '' }}"
                        data-stat-filter="dibatalkan">
                        <span class="history-stat-card__number">{{ $totalDibatal }}</span>
                        <span class="history-stat-card__label">
                            <span class="history-stat-dot history-stat-dot--dibatal"></span>
                            Dibatalkan
                        </span>
                    </button>
                </div>

                {{-- ── Toolbar ── --}}
                <form method="GET" action="{{ route('history.index') }}" data-history-form id="history-filter-form">
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                    <div class="history-toolbar">
                        <div class="history-filters">
                            <select name="sort_waktu" class="hfilter-select" onchange="this.form.submit()">
                                <option value="terbaru" {{ $sortWaktu === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="terlama" {{ $sortWaktu === 'terlama' ? 'selected' : '' }}>Terlama</option>
                            </select>
                            <select name="sort_harga" class="hfilter-select" onchange="this.form.submit()">
                                <option value="teratas" {{ $sortHarga === 'teratas' ? 'selected' : '' }}>Teratas</option>
                                <option value="terbawah" {{ $sortHarga === 'terbawah' ? 'selected' : '' }}>Terbawah</option>
                            </select>
                        </div>
                    <div class="history-total-card" aria-label="Total pengeluaran">
                        <span class="history-total-card__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                                <path d="M9 10.5C9 9.1 10.2 8 11.8 8H14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M9 13.5H14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M9.5 16C10 16.7 10.9 17 12 17C13.5 17 14.7 16.1 14.9 14.8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <div class="history-total-card__body">
                            <p class="history-total-card__label">Total Pengeluaran</p>
                            <p class="history-total-card__amount">Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                        </div>
                        <span class="history-total-card__arrow" aria-hidden="true"></span>
                    </div>
                </div>
                </form>

                {{-- ── Tabs ── --}}
                <div class="hist-tabs-wrap">
                    <div class="hist-tabs" role="tablist">
                        <button class="hist-tab is-active" data-histab="pesanan" role="tab" aria-selected="true" aria-controls="histab-pesanan">Pesanan</button>
                        <button class="hist-tab" data-histab="ulasan" role="tab" aria-selected="false" aria-controls="histab-ulasan">Ulasan</button>
                    </div>
                </div>

                {{-- ── TAB PESANAN ── --}}
                <div class="profview-panel is-active" id="histab-pesanan" role="tabpanel">

                {{-- ── Unified Booking + Match List ── --}}
                <div class="history-list" id="history-booking-list">

                    @forelse ($allItems as $item)
                        @if ($item['type'] === 'booking')
                            @php
                                $booking = $item['original'];
                                $field   = $booking->field;
                                $statusKey = $item['status_key'];
                                $statusInfo = $statusMap[$statusKey] ?? ['label' => ucfirst($statusKey), 'class' => 'history-status--pending', 'filter' => $statusKey];
                                $bookingDate = \Carbon\Carbon::parse($booking->date)->locale('id')->translatedFormat('j M Y');
                                $timeRange   = \Carbon\Carbon::parse($booking->start_time)->format('H:i').' - '.\Carbon\Carbon::parse($booking->end_time)->format('H:i');
                                $price       = $booking->total_price;
                            @endphp
                            <article class="history-card"
                                data-booking-status="{{ $statusInfo['filter'] ?? 'unknown' }}"
                                data-booking-id="{{ $booking->id }}"
                                id="booking-{{ $booking->id }}">
                                <div class="history-card__image">
                                    @if ($field)
                                        <img src="{{ $field->image_url }}" alt="{{ $field->name ?? 'Lapangan' }}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                        <div class="history-card__image-placeholder" aria-hidden="true" style="display:none">
                                            <svg viewBox="0 0 24 24" fill="none" width="32" height="32">
                                                <rect x="3" y="8" width="18" height="11" rx="2" stroke="currentColor" stroke-width="1.8"/>
                                                <path d="M7 8V5.5M12 8V5.5M17 8V5.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                <path d="M3 13H21" stroke="currentColor" stroke-width="1.8"/>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="history-card__image-placeholder" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" width="32" height="32">
                                                <rect x="3" y="8" width="18" height="11" rx="2" stroke="currentColor" stroke-width="1.8"/>
                                                <path d="M7 8V5.5M12 8V5.5M17 8V5.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                <path d="M3 13H21" stroke="currentColor" stroke-width="1.8"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="history-card__body">
                                    <div class="history-card__header">
                                        <div>
                                            <div class="history-card__title">
                                                <span class="history-card__pin" aria-hidden="true">
                                                    <svg viewBox="0 0 24 24" fill="none" width="15" height="15">
                                                        <path d="M12 20.5C12 20.5 18 14.73 18 10.5C18 7.19 15.31 4.5 12 4.5C8.69 4.5 6 7.19 6 10.5C6 14.73 12 20.5 12 20.5Z" fill="#ef4444" stroke="#ef4444" stroke-width="1.2"/>
                                                        <circle cx="12" cy="10.5" r="2" fill="#fff"/>
                                                    </svg>
                                                </span>
                                                <h2 class="history-card__name">{{ $field->name ?? 'Lapangan' }}</h2>
                                            </div>
                                            <p class="history-card__location">{{ $field->location ?? '-' }}</p>
                                        </div>
                                        <div style="text-align:right;flex-shrink:0;">
                                            <span class="history-status {{ $statusInfo['class'] }}">{{ $statusInfo['label'] }}</span>
                                            @if($statusKey === 'selesai')
                                                @if(in_array($field->id, $reviewedFieldIds))
                                                <div style="margin-top:6px;"><span class="hist-rv-badge hist-rv-badge--done"><svg width="7" height="7" viewBox="0 0 7 7" fill="none"><circle cx="3.5" cy="3.5" r="3.5" fill="#1e8f67"/></svg> Sudah Direview</span></div>
                                                @else
                                                <div style="margin-top:6px;"><span class="hist-rv-badge hist-rv-badge--pending"><svg width="7" height="7" viewBox="0 0 7 7" fill="none"><circle cx="3.5" cy="3.5" r="3.5" fill="#f59e0b"/></svg> Menunggu Ulasan</span></div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div class="history-card__meta">
                                        <span class="history-card__meta-item">
                                            <svg viewBox="0 0 24 24" fill="none"><rect x="3.5" y="5.5" width="17" height="15" rx="2.5" stroke="currentColor" stroke-width="1.6"/><path d="M7 3.5V7M17 3.5V7M3.5 9.5H20.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                                            {{ $bookingDate }}
                                        </span>
                                        <span class="history-card__meta-item">
                                            <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8.5" stroke="currentColor" stroke-width="1.6"/><path d="M12 7.5V12.5L15 14.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            {{ $timeRange }}
                                        </span>
                                    </div>
                                    <div class="history-card__footer">
                                        <span class="history-card__price">Rp{{ number_format($price, 0, ',', '.') }}</span>
                                        <div class="history-card__actions">
                                            @if($statusKey === 'selesai' && !in_array($field->id, $reviewedFieldIds))
                                                <button type="button" class="hbtn hbtn--review" onclick="openReviewModal({{ $booking->id }}, '{{ addslashes($field->name ?? 'Lapangan') }}', {{ $field->id ?? 'null' }})">⭐ Beri Ulasan</button>
                                            @endif
                                            <a href="{{ route('booking.detail', $booking->id) }}" class="hbtn hbtn--outline">Rincian</a>
                                            <a href="{{ url('/fields') }}" class="hbtn hbtn--primary" id="rebook-booking-{{ $booking->id }}">Pesan Lagi</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @else
                            @php
                                $mj = $item['original'];
                                $cm = $mj->match;
                                $mStatusKey = $item['status_key'];
                                $mStatusInfo = $statusMap[$mStatusKey] ?? ['label' => 'Selesai', 'class' => 'history-status--selesai', 'filter' => 'selesai'];
                                $mDate = \Carbon\Carbon::parse($cm->date)->locale('id')->translatedFormat('j M Y');
                                $mTime = $cm->time ? substr($cm->time, 0, 5) . ' WIB' : '-';
                            @endphp
                            <article class="history-card"
                                data-booking-status="{{ $mStatusInfo['filter'] ?? 'selesai' }}"
                                data-booking-id="match-{{ $mj->id }}">
                                <div class="history-card__image">
                                    <div class="history-card__image-placeholder" aria-hidden="true" style="background: #f0fdf4;">
                                        <svg viewBox="0 0 24 24" fill="none" width="32" height="32">
                                            <path d="M17 10H7M17 14H7M12 2L15 8H9L12 2Z" stroke="#166534" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                            <rect x="4" y="8" width="16" height="13" rx="2" stroke="#166534" stroke-width="1.8"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="history-card__body">
                                    <div class="history-card__header">
                                        <div>
                                            <div class="history-card__title">
                                                <span class="history-card__pin" aria-hidden="true">
                                                    <svg viewBox="0 0 24 24" fill="none" width="15" height="15">
                                                        <circle cx="12" cy="12" r="10" fill="#166534" stroke="#166534" stroke-width="1.2"/>
                                                        <path d="M12 6V12L16 14" stroke="#fff" stroke-width="1.8" stroke-linecap="round"/>
                                                    </svg>
                                                </span>
                                                <h2 class="history-card__name">{{ $cm->title }}</h2>
                                            </div>
                                            <p class="history-card__location">{{ $cm->field?->name ?? 'Lapangan' }}{{ $cm->field?->location ? ' - ' . $cm->field->location : '' }}</p>
                                        </div>
                                        <span class="history-status {{ $mStatusInfo['class'] }}">{{ $mStatusInfo['label'] }}</span>
                                    </div>
                                    <div class="history-card__meta">
                                        <span class="history-card__meta-item">
                                            <svg viewBox="0 0 24 24" fill="none"><rect x="3.5" y="5.5" width="17" height="15" rx="2.5" stroke="currentColor" stroke-width="1.6"/><path d="M7 3.5V7M17 3.5V7M3.5 9.5H20.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                                            {{ $mDate }}
                                        </span>
                                        <span class="history-card__meta-item">
                                            <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8.5" stroke="currentColor" stroke-width="1.6"/><path d="M12 7.5V12.5L15 14.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            {{ $mTime }}
                                        </span>
                                    </div>
                                    <div class="history-card__footer">
                                        <span class="history-card__price"><span style="color: #166534; font-weight: 700;">Pertandingan Umum</span></span>
                                        <div class="history-card__actions">
                                            <a href="{{ route('matches.show', $cm->id) }}" class="hbtn hbtn--outline">Rincian</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endif

                    @empty
                        {{-- No data at all → main empty state --}}
                        <div class="history-empty">
                            <span class="history-empty__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <rect x="4" y="3" width="16" height="18" rx="2" stroke="currentColor" stroke-width="2"/>
                                    <path d="M8 7H16M8 11H16M8 15H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <p class="history-empty__text">Belum ada histori pemesanan</p>
                            <p class="history-empty__sub">Mulai pesan lapangan atau gabung pertandingan sekarang!</p>
                            <a href="{{ url('/fields') }}" class="hbtn hbtn--primary">Pesan Sekarang</a>
                        </div>
                    @endforelse

                    {{-- Empty state untuk JS filter: muncul saat filter tab menghasilkan 0 kartu --}}
                    @if($allItems->count() > 0)
                    <div class="history-empty" data-history-empty hidden>
                        <span class="history-empty__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none">
                                <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
                                <path d="M16.5 16.5L20 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <p class="history-empty__text">Tidak ada pemesanan di kategori ini</p>
                        <p class="history-empty__sub">Coba pilih kategori lain.</p>
                    </div>
                    @endif

                </div>{{-- /history-list --}}

                </div>{{-- /TAB PESANAN --}}

                {{-- ── TAB ULASAN ── --}}
                <div class="profview-panel" id="histab-ulasan" role="tabpanel">
                    @if($userReviews->isNotEmpty())
                    <div class="hist-reviews-list">
                        @foreach($userReviews as $rv)
                        @php
                            $rvField = $rv->field;
                            $photos = $rv->photos ?? [];
                        @endphp
                        <div class="hist-review-card">
                            <div class="hist-review-card__avatar">
                                <img src="{{ $profileAvatar }}" alt="">
                            </div>
                            <div class="hist-review-card__body">
                                <div class="hist-review-card__top">
                                    <div>
                                        <p class="hist-review-card__field">{{ $rvField?->name ?? 'Lapangan' }}</p>
                                        <div class="hist-review-card__stars">
                                            @for($i = 1; $i <= 5; $i++)
                                            <span style="color:{{ $i <= $rv->rating ? '#f59e0b' : '#e2e8f0' }};">★</span>
                                            @endfor
                                        </div>
                                    </div>
                                    <span class="hist-review-card__date">{{ \Carbon\Carbon::parse($rv->created_at)->locale('id')->translatedFormat('j F Y') }}</span>
                                </div>
                                @if($rv->review)
                                <p class="hist-review-card__text">{{ $rv->review }}</p>
                                @endif
                                @if(count($photos) > 0)
                                <div class="hist-review-card__photos">
                                    @foreach($photos as $photo)
                                    <a href="{{ asset('storage/'.$photo) }}" target="_blank" class="hist-review-card__photo">
                                        <img src="{{ asset('storage/'.$photo) }}" alt="Foto review">
                                    </a>
                                    @endforeach
                                    @if(count($photos) > 3)
                                    <div class="hist-review-card__photo-more">+{{ count($photos) - 3 }}</div>
                                    @endif
                                </div>
                                @endif
                                <a href="{{ route('booking.detail', $rv->booking_id) }}" class="hist-review-card__detail">Lihat Detail</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="history-empty">
                        <span class="history-empty__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M12 2L15 8L22 9L17 14L18 21L12 17.5L6 21L7 14L2 9L9 8L12 2Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <p class="history-empty__text">Belum ada review</p>
                        <p class="history-empty__sub">Selesaikan booking-mu dan beri rating untuk lapangan yang kamu pakai!</p>
                    </div>
                    @endif
                </div>{{-- /TAB ULASAN --}}

            </div>{{-- /history-page --}}

        </main>
    </div>

    {{-- Review Modal --}}
    <div id="reviewModal" style="display:none;position:fixed;inset:0;z-index:10000;background:rgba(0,0,0,.5);justify-content:center;align-items:center;padding:20px;" onclick="if(event.target===this)closeReviewModal()">
        <div style="background:white;border-radius:20px;padding:28px;max-width:440px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.25);position:relative;max-height:90vh;overflow-y:auto;">
            <button type="button" onclick="closeReviewModal()" style="position:absolute;top:12px;right:12px;background:none;border:none;font-size:22px;cursor:pointer;color:#94a3b8;line-height:1;">&times;</button>
            <h3 style="margin:0 0 4px;font-size:18px;font-weight:800;color:#02025b;">Beri Ulasan</h3>
            <p id="reviewModalFieldName" style="margin:0 0 16px;font-size:13px;color:#64748b;"></p>

            <form id="reviewForm" method="POST" action="{{ route('review.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="field_id" id="reviewFieldId">
                <input type="hidden" name="booking_id" id="reviewBookingId">

                {{-- Rating --}}
                <div style="margin-bottom:16px;">
                    <p style="margin:0 0 8px;font-size:13px;font-weight:600;color:#02025b;">Rating <span style="color:#dc2626;">*</span></p>
                    <div id="halfStarContainer" style="display:flex;gap:2px;flex-direction:row-reverse;justify-content:flex-end;">
                        @for($i = 5; $i >= 1; $i--)
                        <div class="hstar" data-star="{{ $i }}" style="position:relative;width:32px;height:32px;cursor:pointer;">
                            <span class="hstar-bg" style="position:absolute;inset:0;font-size:32px;line-height:1;color:#e2e8f0;pointer-events:none;">★</span>
                            <span class="hstar-fill" id="hsf-{{ $i }}" style="position:absolute;inset:0;font-size:32px;line-height:1;color:#f59e0b;overflow:hidden;width:0%;pointer-events:none;">★</span>
                            <span class="hstar-left" onclick="setHalfRating({{ $i - 0.5 }})" style="position:absolute;top:0;left:0;bottom:0;width:50%;z-index:2;cursor:pointer;"></span>
                            <span class="hstar-right" onclick="setHalfRating({{ $i }})" style="position:absolute;top:0;right:0;bottom:0;width:50%;z-index:2;cursor:pointer;"></span>
                        </div>
                        @endfor
                    </div>
                    <p id="ratingDisplay" style="margin:6px 0 0;font-size:12px;color:#94a3b8;">Klik bintang untuk memberi rating</p>
                    <input type="hidden" name="rating" id="reviewRating" value="0">
                </div>

                {{-- Review text --}}
                <div style="margin-bottom:16px;">
                    <p style="margin:0 0 8px;font-size:13px;font-weight:600;color:#02025b;">Ulasan <span style="color:#dc2626;">*</span></p>
                    <textarea name="review" id="reviewText" rows="4" placeholder="Tulis ulasan kamu di sini (minimal 10 karakter)..." style="width:100%;padding:12px 14px;border-radius:12px;border:1px solid rgba(0,0,77,.15);font-size:14px;outline:none;resize:none;box-sizing:border-box;font-family:inherit;"></textarea>
                </div>

                {{-- Photo upload --}}
                <div style="margin-bottom:16px;">
                    <p style="margin:0 0 8px;font-size:13px;font-weight:600;color:#02025b;">Foto <span style="color:#94a3b8;font-weight:400;">(opsional)</span></p>
                    <label style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:12px;border:1px dashed rgba(0,0,77,.2);background:#f8fafc;cursor:pointer;transition:all .2s;font-size:13px;color:#64748b;" onmouseover="this.style.borderColor='#EB5436'" onmouseout="this.style.borderColor='rgba(0,0,77,.2)'">
                        <svg viewBox="0 0 24 24" fill="none" width="20" height="20"><rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.6"/><circle cx="7.5" cy="9.5" r="1.5" fill="currentColor"/><path d="M3 16L8 11L12 15L16 10L21 16" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span id="reviewPhotoLabel">Tambahkan foto</span>
                        <input type="file" name="photos[]" id="reviewPhotos" accept="image/jpeg,image/png,image/webp" multiple style="display:none;" onchange="updatePhotoLabel(this)">
                    </label>
                    <p style="margin:6px 0 0;font-size:11px;color:#94a3b8;">Maksimal 5 foto (JPEG, PNG, WebP). Maks 5MB per foto.</p>
                </div>

                <p id="reviewError" style="display:none;color:#dc2626;font-size:12px;margin:6px 0 0;"></p>
                <button type="submit" id="reviewSubmitBtn" style="width:100%;margin-top:8px;padding:14px;background:#EB5436;color:white;border:none;border-radius:12px;font-weight:700;font-size:15px;cursor:pointer;">Kirim Ulasan</button>
            </form>
        </div>
    </div>

    <script>
    function setHalfRating(val) {
        document.getElementById('reviewRating').value = val;
        document.getElementById('ratingDisplay').textContent = val + ' dari 5 bintang';
        for (var i = 1; i <= 5; i++) {
            var fill = document.getElementById('hsf-' + i);
            if (val >= i) fill.style.width = '100%';
            else if (val >= i - 0.5) fill.style.width = '50%';
            else fill.style.width = '0%';
        }
    }
    function resetHalfRating() {
        setHalfRating(0);
        document.getElementById('ratingDisplay').textContent = 'Klik bintang untuk memberi rating';
    }
    function openReviewModal(bookingId, fieldName, fieldId) {
        document.getElementById('reviewBookingId').value = bookingId;
        document.getElementById('reviewFieldId').value = fieldId;
        document.getElementById('reviewModalFieldName').textContent = fieldName;
        resetHalfRating();
        document.getElementById('reviewText').value = '';
        document.getElementById('reviewError').style.display = 'none';
        document.getElementById('reviewPhotoLabel').textContent = 'Tambahkan foto';
        document.getElementById('reviewPhotos').value = '';
        document.getElementById('reviewModal').style.display = 'flex';
    }
    function closeReviewModal() { document.getElementById('reviewModal').style.display = 'none'; }
    function updatePhotoLabel(input) {
        var label = document.getElementById('reviewPhotoLabel');
        if (input.files.length > 0) {
            label.textContent = input.files.length + ' foto dipilih';
        } else {
            label.textContent = 'Tambahkan foto';
        }
    }
    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        var ratingEl = document.getElementById('reviewRating');
        var review = document.getElementById('reviewText').value.trim();
        var errorEl = document.getElementById('reviewError');
        if (parseFloat(ratingEl.value) === 0) { e.preventDefault(); errorEl.textContent = 'Pilih rating terlebih dahulu.'; errorEl.style.display = 'block'; return; }
        if (review.length < 10) { e.preventDefault(); errorEl.textContent = 'Ulasan minimal 10 karakter.'; errorEl.style.display = 'block'; return; }
        errorEl.style.display = 'none';
    });
    </script>

    {{-- Tab switching JS --}}
    <script>
    (function(){
        var tabs = document.querySelectorAll('[data-histab]');
        var panels = {
            pesanan: document.getElementById('histab-pesanan'),
            ulasan: document.getElementById('histab-ulasan'),
        };
        tabs.forEach(function(t) {
            t.addEventListener('click', function() {
                tabs.forEach(function(x) {
                    x.classList.remove('is-active');
                    x.setAttribute('aria-selected', 'false');
                });
                Object.values(panels).forEach(function(p) { if (p) p.classList.remove('is-active'); });
                t.classList.add('is-active');
                t.setAttribute('aria-selected', 'true');
                var target = panels[t.getAttribute('data-histab')];
                if (target) target.classList.add('is-active');
            });
        });
    })();
    </script>
</body>
</html>
