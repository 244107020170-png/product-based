@php
    $user = auth()->user();
    $userName = $user?->name ?: 'Sport Enthusiast';
    $currentDate = \Carbon\Carbon::now()->locale('id')->translatedFormat('j F Y');
    $profileAvatar = asset('assets/images/characters/image%2024.png');
    $reviewAvatar = asset('assets/images/characters/image%2024.png');
    $heroCharacter = asset('assets/images/characters/image%2022.png');

    $sidebarItems = [
        ['label' => 'Dashboard', 'icon' => asset('assets/images/icons/dashboard.png'), 'href' => route('dashboard'), 'active' => true],
        ['label' => 'Aktivitas', 'icon' => asset('assets/images/icons/aktivitas.png'), 'href' => url('/matches'), 'active' => false],
        ['label' => 'Favoritmu', 'icon' => asset('assets/images/icons/favoritmu.png'), 'href' => null, 'active' => false],
        ['label' => 'Histori', 'icon' => asset('assets/images/icons/histori.png'), 'href' => null, 'active' => false],
        ['label' => 'Cari tim', 'icon' => asset('assets/images/icons/caritim.png'), 'href' => url('/matches'), 'active' => false],
        ['label' => 'Booking', 'icon' => asset('assets/images/icons/booking.png'), 'href' => url('/fields'), 'active' => false],
        ['label' => 'Keahlianmu', 'icon' => asset('assets/images/icons/keahlian.png'), 'href' => null, 'active' => false],
        ['label' => 'Profil', 'icon' => asset('assets/images/icons/profil.png'), 'href' => route('profile.edit'), 'active' => false],
    ];

    $sidebarUtilities = [
        ['label' => 'Bantuan', 'icon' => asset('assets/images/icons/bantuan.png'), 'href' => route('preview.help')],
        ['label' => 'Pengaturan', 'icon' => asset('assets/images/icons/pengaturan.png'), 'href' => route('profile.edit')],
    ];

    $notifications = [
        ['icon' => asset('assets/images/icons/gor.png'), 'title' => 'GOR Bimasakti Malang', 'meta' => 'Hari ini, 15.00', 'status' => null, 'status_tone' => null, 'filter' => 'today'],
        ['icon' => asset('assets/images/icons/bultang.png'), 'title' => 'GOR Bulu Tangkis Tidar', 'meta' => 'Besok, 15.00', 'status' => null, 'status_tone' => null, 'filter' => 'tomorrow'],
        ['icon' => asset('assets/images/icons/futsal.png'), 'title' => 'Champion Futsal Malang', 'meta' => 'Perlu dicek ulang', 'status' => 'Konfirmasi gagal', 'status_tone' => 'danger', 'filter' => 'status'],
    ];

    $recommendedMatches = [
        ['icon' => asset('assets/images/icons/gor.png'), 'title' => 'Basket', 'location' => 'GOR Bimasakti Malang', 'schedule' => 'Hari ini, 20:00', 'badge' => 'For You'],
        ['icon' => asset('assets/images/icons/bultang.png'), 'title' => 'Badminton', 'location' => 'GOR Bulu Tangkis Tidar', 'schedule' => 'Besok, 09:00', 'badge' => 'For You'],
        ['icon' => asset('assets/images/icons/futsal.png'), 'title' => 'Futsal', 'location' => 'Champion Futsal Malang', 'schedule' => '20 April 2026, 20:00', 'badge' => 'For You'],
    ];

    $favoriteFields = [
        ['icon' => asset('assets/images/icons/gor.png'), 'title' => 'Basket', 'location' => 'GOR Bimasakti Malang'],
        ['icon' => asset('assets/images/icons/bultang.png'), 'title' => 'Badminton', 'location' => 'GOR Bulu Tangkis Tidar'],
        ['icon' => asset('assets/images/icons/futsal.png'), 'title' => 'Futsal', 'location' => 'Champion Futsal Malang'],
    ];

    $badgeSteps = [
        ['slot' => 'BG1', 'title' => 'Beginner', 'meta' => '1-5 Match'],
        ['slot' => 'BG2', 'title' => 'Active', 'meta' => '6-20 Match'],
        ['slot' => 'BG3', 'title' => 'Pro', 'meta' => '>20 Match'],
    ];

    $rebookItems = [
        ['title' => 'Lapangan Voli - Veteran Muda', 'distance' => '3.5 KM'],
        ['title' => 'Lapangan Futsal - Soekarno Hatta', 'distance' => '3.5 KM'],
        ['title' => 'Kolam Renang - Blimbing', 'distance' => '3.5 KM'],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Dashboard Player - {{ config('app.name', 'Spies Sport') }}</title>

        @vite([
            'resources/css/app.css',
            'resources/css/player-dashboard.css',
            'resources/js/player-dashboard.js',
        ])
    </head>
    <body class="player-dashboard-page">
        <div class="player-dashboard-shell">
            <aside class="player-sidebar" data-sidebar>
                <div class="player-sidebar__inner">
                    <div class="player-sidebar__header">
                        <a href="{{ route('dashboard') }}" class="player-sidebar__brand" aria-label="Dashboard player">
                            <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Spies Sport" class="player-sidebar__logo">
                        </a>

                        <button type="button" class="player-sidebar__close" data-sidebar-close aria-label="Tutup sidebar">
                            <span></span>
                            <span></span>
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

            <main class="player-dashboard-main">
                <header class="player-dashboard-topbar">
                    <div class="player-dashboard-topbar__left">
                        <button type="button" class="player-dashboard-topbar__menu" data-sidebar-open aria-label="Buka sidebar">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>

                        <label class="player-search" for="dashboard-search">
                            <span class="player-search__icon" aria-hidden="true">
                                <svg viewBox="0 0 20 20" fill="none">
                                    <circle cx="9" cy="9" r="5.75" stroke="currentColor" stroke-width="1.8"></circle>
                                    <path d="M13.5 13.5L17 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                                </svg>
                            </span>
                            <input
                                id="dashboard-search"
                                type="search"
                                placeholder="Cari lapangan, match, notifikasi, atau kategori..."
                                data-dashboard-search
                            >
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

                        <button type="button" class="player-dashboard-topbar__icon" aria-label="Notifikasi">
                            <span class="player-inline-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M9 18H15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                                    <path d="M6.5 17.5H17.5L16.3 15.6C15.9 15 15.7 14.3 15.7 13.6V10.8C15.7 8.49 14.04 6.54 11.8 6.16V5.5C11.8 4.67 11.13 4 10.3 4C9.47 4 8.8 4.67 8.8 5.5V6.16C6.56 6.54 4.9 8.49 4.9 10.8V13.6C4.9 14.3 4.7 15 4.3 15.6L3.1 17.5H6.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                                </svg>
                            </span>
                        </button>

                        <button type="button" class="player-profile-pill" aria-label="Profil pengguna">
                            <span class="player-profile-pill__avatar" data-asset-slot="profile-avatar">
                                <img src="{{ $profileAvatar }}" alt="" class="player-avatar-image">
                            </span>
                            <span class="player-profile-pill__name">{{ $userName }}</span>
                        </button>
                    </div>
                </header>

                <section class="player-dashboard-title">
                    <div>
                        <h1>Dashboard</h1>
                    </div>

                    <div class="player-dashboard-title__actions">
                        <div class="player-filter" data-filter-root>
                            <button type="button" class="player-filter__button" data-filter-button aria-expanded="false">
                                <span data-filter-label>By Day</span>
                                <span class="player-filter__chevron"></span>
                            </button>

                            <div class="player-filter__menu" data-filter-menu hidden>
                                <button type="button" class="player-filter__option is-active" data-filter-option="all">By Day</button>
                                <button type="button" class="player-filter__option" data-filter-option="today">Hari ini</button>
                                <button type="button" class="player-filter__option" data-filter-option="tomorrow">Besok</button>
                                <button type="button" class="player-filter__option" data-filter-option="status">Status</button>
                            </div>
                        </div>

                        <a href="{{ url('/matches') }}" class="player-primary-button">Buat Pertandingan Baru</a>
                    </div>
                </section>

                <section class="player-dashboard-hero">
                    <div class="player-dashboard-card player-dashboard-card--hero">
                        <div class="player-dashboard-card__heading">
                            <h2 class="player-hero__title">Hi, Sport Enthusiast!</h2>
                        </div>

                        <div class="player-hero">
                            <div class="player-review-card" data-dashboard-searchable="review rating lapangan futsal veteran kota malang permainan sport enthusiast">
                                <div class="player-review-card__header">
                                    <div class="player-review-card__meta">
                                        <div class="player-review-card__avatar" data-asset-slot="review-avatar">
                                            <img src="{{ $reviewAvatar }}" alt="" class="player-avatar-image">
                                        </div>
                                        <div class="player-review-card__copy">
                                            <h3>Gimana permainan nya?</h3>
                                            <p>Lapangan Futsal, Veteran</p>
                                            <span>(Kota Malang)</span>
                                        </div>
                                    </div>

                                    <button
                                        type="button"
                                        class="player-review-card__bookmark"
                                        aria-label="Simpan review"
                                        aria-pressed="false"
                                        data-review-bookmark
                                    >
                                        <img src="{{ asset('assets/images/icons/bookmark.png') }}" alt="" class="player-bookmark-icon">
                                    </button>
                                </div>

                                <div class="player-review-card__time">10 Apr - 14:00 WIB</div>

                                <div class="player-rating" data-rating-root>
                                    @for ($star = 1; $star <= 5; $star++)
                                        <button
                                            type="button"
                                            class="player-rating__star"
                                            data-rating-star
                                            data-value="{{ $star }}"
                                            aria-label="{{ $star }} bintang"
                                        >
                                            &#9733;
                                        </button>
                                    @endfor
                                </div>

                                <p class="player-rating__hint" data-rating-label>Pilih rating untuk placeholder ini</p>
                            </div>

                            <div class="player-hero__art">
                                <div class="player-hero__character-frame" data-asset-slot="character-combo">
                                    <img src="{{ $heroCharacter }}" alt="Karakter dashboard player" class="player-hero__character-image">
                                </div>
                            </div>
                        </div>
                    </div>

                    <aside class="player-dashboard-card player-dashboard-card--notifications">
                        <div class="player-dashboard-card__heading player-dashboard-card__heading--split">
                            <h2>Notifikasi</h2>
                            <a href="#" class="player-link-button">Lihat semua</a>
                        </div>

                        <div class="player-list player-list--notifications" data-filter-container>
                            @foreach ($notifications as $notification)
                                <article
                                    class="player-list-card"
                                    data-notification-item
                                    data-filter-value="{{ $notification['filter'] }}"
                                    data-dashboard-searchable="{{ strtolower('notifikasi '.$notification['title'].' '.$notification['meta'].' '.$notification['status']) }}"
                                >
                                    <div class="player-list-card__side">
                                        <img src="{{ $notification['icon'] }}" alt="" class="player-sport-icon">
                                    </div>

                                    <div class="player-list-card__content">
                                        <h3>{{ $notification['title'] }}</h3>
                                        @if ($notification['status'])
                                            <p class="player-list-card__status player-list-card__status--{{ $notification['status_tone'] }}">{{ $notification['status'] }}</p>
                                        @else
                                            <p>{{ $notification['meta'] }}</p>
                                        @endif
                                    </div>

                                    <div class="player-list-card__side player-list-card__side--end">
                                        <span class="player-inline-icon player-inline-icon--pin" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none">
                                                <path d="M12 20.5C12 20.5 18 14.73 18 10.5C18 7.19 15.31 4.5 12 4.5C8.69 4.5 6 7.19 6 10.5C6 14.73 12 20.5 12 20.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                                                <circle cx="12" cy="10.5" r="2.2" stroke="currentColor" stroke-width="1.8"></circle>
                                            </svg>
                                        </span>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="player-empty-state" data-filter-empty hidden>
                            Tidak ada notifikasi untuk filter ini.
                        </div>
                    </aside>
                </section>

                <section class="player-dashboard-grid">
                    <article class="player-dashboard-card" data-dashboard-searchable="upcoming match futsal competition zona sm futsal pertandingan jadwal detail">
                        <div class="player-dashboard-card__heading">
                            <img src="{{ asset('assets/images/icons/upcoming.png') }}" alt="" class="player-section-icon">
                            <h2>Upcoming Match</h2>
                            <span class="player-chip">2h</span>
                        </div>

                        <div class="player-upcoming-card">
                            <div class="player-upcoming-card__meta">
                                <img src="{{ asset('assets/images/icons/futsal.png') }}" alt="" class="player-sport-icon">
                                <div>
                                    <h3>Futsal Competition</h3>
                                    <p>Zona SM Futsal</p>
                                </div>
                            </div>

                            <div class="player-upcoming-card__participants">
                                <div class="player-upcoming-card__avatars">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <small>+2</small>
                                </div>
                                <div class="player-upcoming-card__countdown">
                                    Starts In <strong>2h 1m</strong>
                                </div>
                            </div>

                            <a href="{{ url('/matches') }}" class="player-primary-button player-primary-button--block">View Details</a>
                        </div>
                    </article>

                    <article class="player-dashboard-card" data-dashboard-searchable="rekomendasi match basket badminton futsal gor bimasakti tidar champion for you">
                        <div class="player-dashboard-card__heading player-dashboard-card__heading--split">
                            <div class="player-dashboard-card__title-with-icon">
                                <img src="{{ asset('assets/images/icons/rekomendasi.png') }}" alt="" class="player-section-icon">
                                <h2>Rekomendasi Match</h2>
                            </div>
                            <a href="{{ url('/matches') }}" class="player-link-button">See all</a>
                        </div>

                        <div class="player-stack-list">
                            @foreach ($recommendedMatches as $match)
                                <article
                                    class="player-stack-item"
                                    data-dashboard-searchable="{{ strtolower($match['title'].' '.$match['location'].' '.$match['schedule']) }}"
                                >
                                    <div class="player-stack-item__top">
                                        <span class="player-badge">{{ $match['badge'] }}</span>
                                        <h3>{{ $match['title'] }}</h3>
                                        <img src="{{ $match['icon'] }}" alt="" class="player-sport-icon">
                                    </div>
                                    <p>{{ $match['location'] }}</p>
                                    <span>{{ $match['schedule'] }}</span>
                                </article>
                            @endforeach
                        </div>
                    </article>

                    <article class="player-dashboard-card" data-dashboard-searchable="lapangan favorit basket badminton futsal gor bimasakti tidar champion quick book">
                        <div class="player-dashboard-card__heading">
                            <img src="{{ asset('assets/images/icons/lapfav.png') }}" alt="" class="player-section-icon">
                            <h2>Lapangan Favorit</h2>
                        </div>

                        <div class="player-stack-list">
                            @foreach ($favoriteFields as $field)
                                <article
                                    class="player-stack-item player-stack-item--favorite"
                                    data-dashboard-searchable="{{ strtolower($field['title'].' '.$field['location']) }}"
                                >
                                    <div class="player-stack-item__top">
                                        <img src="{{ $field['icon'] }}" alt="" class="player-sport-icon">
                                        <h3>{{ $field['title'] }}</h3>
                                        <span class="player-quick-button">Quick Book</span>
                                    </div>
                                    <p>{{ $field['location'] }}</p>
                                </article>
                            @endforeach
                        </div>

                        <a href="{{ url('/fields') }}" class="player-dashboard-card__footer-link">See all</a>
                    </article>

                    <article class="player-dashboard-card" data-dashboard-searchable="badge pemain active player beginner pro level match player">
                        <div class="player-dashboard-card__heading">
                            <img src="{{ asset('assets/images/icons/badge.png') }}" alt="" class="player-section-icon">
                            <h2>Badge Pemain</h2>
                        </div>

                        <div class="player-badge-card">
                            <div class="player-badge-card__hero">
                                <div class="player-badge-card__main-icon" data-asset-slot="badge-main">
                                    <img src="{{ asset('assets/images/icons/badge.png') }}" alt="" class="player-badge-image">
                                </div>
                                <div class="player-badge-card__copy">
                                    <p>Level: <strong>Active Player</strong></p>
                                    <span>12 Match Player</span>
                                </div>
                                <span class="player-inline-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none">
                                        <path d="M12 4.5L13.98 8.51L18.4 9.15L15.2 12.27L15.96 16.67L12 14.59L8.04 16.67L8.8 12.27L5.6 9.15L10.02 8.51L12 4.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </div>

                            <div class="player-badge-card__progress">
                                <span class="is-active"></span>
                                <span class="is-active"></span>
                                <span class="is-active"></span>
                                <span class="is-active"></span>
                                <span></span>
                            </div>

                            <div class="player-badge-card__steps">
                                @foreach ($badgeSteps as $step)
                                    <div class="player-badge-step">
                                        <div class="player-slot player-slot--mini-badge">{{ $step['slot'] }}</div>
                                        <strong>{{ $step['title'] }}</strong>
                                        <span>{{ $step['meta'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </article>
                </section>

                <section class="player-dashboard-rebook">
                    <div class="player-dashboard-rebook__heading">
                        <div>
                            <h2>Pesan lagi</h2>
                        </div>
                    </div>

                    <div class="player-dashboard-card player-dashboard-card--rebook" data-dashboard-searchable="pesan lagi lapangan voli futsal kolam renang veteran muda soekarno hatta blimbing">
                        <div class="player-rebook-grid">
                            @foreach ($rebookItems as $item)
                                <article
                                    class="player-rebook-card"
                                    data-dashboard-searchable="{{ strtolower($item['title'].' '.$item['distance']) }}"
                                >
                                    <div class="player-slot player-slot--image" data-asset-slot="rebook-image-{{ $loop->iteration }}">
                                        Gambar Lapangan
                                    </div>

                                    <div class="player-rebook-card__body">
                                        <div class="player-rebook-card__location">
                                            <span class="player-inline-icon player-inline-icon--pin" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none">
                                                    <path d="M12 20.5C12 20.5 18 14.73 18 10.5C18 7.19 15.31 4.5 12 4.5C8.69 4.5 6 7.19 6 10.5C6 14.73 12 20.5 12 20.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
                                                    <circle cx="12" cy="10.5" r="2.2" stroke="currentColor" stroke-width="1.8"></circle>
                                                </svg>
                                            </span>
                                            <h3>{{ $item['title'] }}</h3>
                                        </div>
                                        <p>{{ $item['distance'] }}</p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
