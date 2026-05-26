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

    // Status map for display — hanya 3 status
    $statusMap = [
        'selesai'   => ['label' => 'Selesai',      'class' => 'history-status--selesai', 'filter' => 'selesai'],
        'confirmed' => ['label' => 'Akan Datang',  'class' => 'history-status--akan',    'filter' => 'akan_datang'],
        'pending'   => ['label' => 'Akan Datang',  'class' => 'history-status--akan',    'filter' => 'akan_datang'],
        'cancelled' => ['label' => 'Dibatalkan',   'class' => 'history-status--dibatal', 'filter' => 'dibatalkan'],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Histori Booking - {{ config('app.name', 'Spies Sport') }}</title>

    @vite([
        'resources/css/app.css',
        'resources/css/player-dashboard.css',
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

                {{-- Hidden form for filter submission --}}
                <form method="GET" action="{{ route('history.index') }}" data-history-form id="history-filter-form">
                    <input type="hidden" name="status"     value="{{ $statusFilter }}">
                    <input type="hidden" name="sort_waktu" value="{{ $sortWaktu }}">
                    <input type="hidden" name="sort_harga" value="{{ $sortHarga }}">
                </form>

                <h1 class="history-page__title">Histori Booking</h1>

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
                <div class="history-toolbar">
                    <div class="history-filters">

                        {{-- Waktu dropdown --}}
                        <div class="hfilter" data-hfilter="sort_waktu">
                            <button type="button" class="hfilter__btn" data-hfilter-btn aria-expanded="false">
                                <span data-hfilter-label>Waktu</span>
                                <span class="hfilter__chevron"></span>
                            </button>
                            <div class="hfilter__tags">
                                <button type="button" class="hfilter__tag{{ $sortWaktu === 'terbaru' ? ' is-active' : '' }}"
                                    data-hfilter-tag="terbaru">Terbaru</button>
                                <button type="button" class="hfilter__tag{{ $sortWaktu === 'terlama' ? ' is-active' : '' }}"
                                    data-hfilter-tag="terlama">Terlama</button>
                            </div>
                            <div class="hfilter__menu" data-hfilter-menu hidden>
                                <button type="button" class="hfilter__option{{ $sortWaktu === 'terbaru' ? ' is-active' : '' }}"
                                    data-hfilter-tag="terbaru">Terbaru</button>
                                <button type="button" class="hfilter__option{{ $sortWaktu === 'terlama' ? ' is-active' : '' }}"
                                    data-hfilter-tag="terlama">Terlama</button>
                            </div>
                        </div>

                        {{-- Harga dropdown --}}
                        <div class="hfilter" data-hfilter="sort_harga">
                            <button type="button" class="hfilter__btn" data-hfilter-btn aria-expanded="false">
                                <span data-hfilter-label>Harga</span>
                                <span class="hfilter__chevron"></span>
                            </button>
                            <div class="hfilter__tags">
                                <button type="button" class="hfilter__tag{{ $sortHarga === 'teratas' ? ' is-active' : '' }}"
                                    data-hfilter-tag="teratas">Teratas</button>
                                <button type="button" class="hfilter__tag{{ $sortHarga === 'terbawah' ? ' is-active' : '' }}"
                                    data-hfilter-tag="terbawah">Terbawah</button>
                            </div>
                            <div class="hfilter__menu" data-hfilter-menu hidden>
                                <button type="button" class="hfilter__option{{ $sortHarga === 'teratas' ? ' is-active' : '' }}"
                                    data-hfilter-tag="teratas">Teratas</button>
                                <button type="button" class="hfilter__option{{ $sortHarga === 'terbawah' ? ' is-active' : '' }}"
                                    data-hfilter-tag="terbawah">Terbawah</button>
                            </div>
                        </div>

                    </div>

                    {{-- Total Pengeluaran --}}
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
                            <p class="history-total-card__amount">
                                Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}
                            </p>
                        </div>
                        <span class="history-total-card__arrow" aria-hidden="true"></span>
                    </div>
                </div>

                {{-- ── Booking List ── --}}
                <div class="history-list" id="history-booking-list">

                    @forelse ($bookings as $booking)
                        @php
                            $field       = $booking->field;
                            $statusKey   = $booking->status ?? 'pending';
                            $statusInfo  = $statusMap[$statusKey] ?? ['label' => ucfirst($statusKey), 'class' => 'history-status--pending', 'filter' => $statusKey];

                            $bookingDate = \Carbon\Carbon::parse($booking->date)->locale('id')->translatedFormat('j M Y');
                            $timeRange   = \Carbon\Carbon::parse($booking->start_time)->format('H:i').' - '.\Carbon\Carbon::parse($booking->end_time)->format('H:i');

                            $startH      = \Carbon\Carbon::parse($booking->start_time);
                            $endH        = \Carbon\Carbon::parse($booking->end_time);
                            $hours       = max(1, $startH->diffInHours($endH));
                            $price       = $field ? $field->price_per_hour * $hours : 0;
                        @endphp

                        <article class="history-card"
                            data-booking-status="{{ $statusInfo['filter'] ?? 'unknown' }}"
                            data-booking-id="{{ $booking->id }}"
                            id="booking-{{ $booking->id }}">

                            {{-- Image --}}
                            <div class="history-card__image">
                                @if ($field && isset($field->image) && $field->image)
                                    <img src="{{ asset('storage/'.$field->image) }}" alt="{{ $field->name ?? 'Lapangan' }}">
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

                            {{-- Body --}}
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

                                    <span class="history-status {{ $statusInfo['class'] }}">
                                        {{ $statusInfo['label'] }}
                                    </span>
                                </div>

                                <div class="history-card__meta">
                                    <span class="history-card__meta-item">
                                        <svg viewBox="0 0 24 24" fill="none">
                                            <rect x="3.5" y="5.5" width="17" height="15" rx="2.5" stroke="currentColor" stroke-width="1.6"/>
                                            <path d="M7 3.5V7M17 3.5V7M3.5 9.5H20.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                                        </svg>
                                        {{ $bookingDate }}
                                    </span>
                                    <span class="history-card__meta-item">
                                        <svg viewBox="0 0 24 24" fill="none">
                                            <circle cx="12" cy="12" r="8.5" stroke="currentColor" stroke-width="1.6"/>
                                            <path d="M12 7.5V12.5L15 14.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        {{ $timeRange }}
                                    </span>
                                </div>

                                <div class="history-card__footer">
                                    <span class="history-card__price">
                                        Rp{{ number_format($price, 0, ',', '.') }}
                                    </span>

                                    <div class="history-card__actions">
                                        <a href="{{ url('/fields') }}" class="hbtn hbtn--outline" id="detail-booking-{{ $booking->id }}">
                                            Detail
                                        </a>
                                        <a href="{{ url('/fields') }}" class="hbtn hbtn--primary" id="rebook-booking-{{ $booking->id }}">
                                            Pesan Lagi
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>

                    @empty
                        {{-- Tidak ada booking sama sekali di DB → tampil langsung --}}
                        <div class="history-empty">
                            <span class="history-empty__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <rect x="4" y="3" width="16" height="18" rx="2" stroke="currentColor" stroke-width="2"/>
                                    <path d="M8 7H16M8 11H16M8 15H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <p class="history-empty__text">Belum ada histori booking</p>
                            <p class="history-empty__sub">Mulai booking lapangan pertamamu sekarang!</p>
                            <a href="{{ url('/fields') }}" class="hbtn hbtn--primary">Booking Sekarang</a>
                        </div>
                    @endforelse

                    {{-- Empty state khusus JS: muncul hanya saat filter tab menghasilkan 0 kartu --}}
                    @if($bookings->count() > 0)
                    <div class="history-empty" data-history-empty hidden>
                        <span class="history-empty__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none">
                                <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
                                <path d="M16.5 16.5L20 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <p class="history-empty__text">Tidak ada booking di kategori ini</p>
                        <p class="history-empty__sub">Coba pilih kategori lain.</p>
                    </div>
                    @endif

                </div>{{-- /history-list --}}

            </div>{{-- /history-page --}}

        </main>
    </div>
</body>
</html>
