@php
    use Carbon\Carbon;

    $user = auth()->user();
    $userName = $user?->name ?: 'Pecinta Olahraga';
    $currentDate = Carbon::now()->locale('id')->translatedFormat('j F Y');
    $profileAvatar = $user?->avatarUrl();
    $sportOptions = $cards->pluck('sport')->unique()->values();
    
    // Helper function for sport color badges
    $sportColor = function($sport) {
        return match($sport) {
            'Futsal' => 'bg-blue-50 text-blue-700 border-blue-200',
            'Badminton' => 'bg-green-50 text-green-700 border-green-200',
            'Basket' => 'bg-orange-50 text-orange-700 border-orange-200',
            'Voli' => 'bg-purple-50 text-purple-700 border-purple-200',
            'Tennis' => 'bg-red-50 text-red-700 border-red-200',
            default => 'bg-gray-50 text-gray-700 border-gray-200',
        };
    };

    $sidebarItems = [
        ['label' => 'Beranda', 'icon' => asset('assets/images/icons/dashboard.png'), 'href' => route('dashboard'), 'active' => false],
        ['label' => 'Aktivitas', 'icon' => asset('assets/images/icons/aktivitas.png'), 'href' => route('activity.index'), 'active' => false],
        ['label' => 'Favorit', 'icon' => asset('assets/images/icons/favoritmu.png'), 'href' => route('favorite.index'), 'active' => false],
        ['label' => 'Histori', 'icon' => asset('assets/images/icons/histori.png'), 'href' => route('history.index'), 'active' => false],
        ['label' => 'Cari tim', 'icon' => asset('assets/images/icons/caritim.png'), 'href' => route('matches.index'), 'active' => true],
        ['label' => 'Pemesanan', 'icon' => asset('assets/images/icons/booking.png'), 'href' => route('booking.index'), 'active' => false],
        ['label' => 'Keahlian', 'icon' => asset('assets/images/icons/keahlian.png'), 'href' => route('skill.index'), 'active' => false],
        ['label' => 'Profil', 'icon' => asset('assets/images/icons/profil.png'), 'href' => route('profile.show'), 'active' => false],
    ];
    $sidebarUtilities = [
        ['label' => 'Bantuan', 'icon' => asset('assets/images/icons/bantuan.png'), 'href' => route('preview.help')],
        ['label' => 'Pengaturan', 'icon' => asset('assets/images/icons/pengaturan.png'), 'href' => route('profile.edit')],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cari Tim – {{ config('app.name', 'Spies Sport') }}</title>
    @vite(['resources/css/app.css', 'resources/css/player-dashboard.css', 'resources/js/player-dashboard.js'])
    <style>
        .redesign-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 12px 40px;
        }

        .redesign-grid {
            display: grid;
            grid-template-columns: 280px 1fr 300px;
            gap: 20px;
            min-height: calc(100vh - 220px);
        }

        .redesign-col-left, .redesign-col-right {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .card-section {
            background: white;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid rgba(0, 0, 77, 0.08);
            box-shadow: 0 4px 12px rgba(0, 0, 77, 0.06);
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            background: #11114b;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background: #0b0b36;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(17, 17, 75, 0.2);
        }

        /* Filter Section */
        .filter-section-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #02025b;
            margin-bottom: 12px;
        }

        .filter-checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .filter-checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .filter-checkbox-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #11114b;
        }

        .filter-checkbox-item label {
            flex: 1;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 500;
            color: #333;
        }

        /* Booking List */
        .booking-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .booking-card {
            padding: 12px;
            border: 1px solid rgba(0, 0, 77, 0.1);
            border-radius: 10px;
            background: rgba(17, 17, 75, 0.03);
            transition: all 0.2s ease;
        }

        .booking-card:hover {
            border-color: rgba(17, 17, 75, 0.2);
            background: rgba(17, 17, 75, 0.06);
        }

        .booking-card-sport {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 6px;
            margin-bottom: 6px;
        }

        .booking-card-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #02025b;
            margin-bottom: 4px;
        }

        .booking-card-meta {
            font-size: 0.8rem;
            color: #666;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        /* Center Swipe Section */
        .redesign-col-center {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .swipe-container {
            width: 100%;
            max-width: 450px;
            position: relative;
        }

        .swipe-card-modern {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 12px 32px rgba(0, 0, 77, 0.15);
            border: 1px solid rgba(0, 0, 77, 0.08);
            position: relative;
            z-index: 2;
            transform: translateX(0) rotate(0deg);
            transition: transform 0.3s cubic-bezier(0.22, 0.61, 0.36, 1), opacity 0.3s ease;
            touch-action: pan-y;
        }

        .swipe-card-modern.is-swiping-left {
            transform: translateX(calc(-100% - 160px)) rotate(-22deg) scale(0.95);
            opacity: 0;
        }

        .swipe-card-modern.is-swiping-right {
            transform: translateX(calc(100% + 160px)) rotate(22deg) scale(0.95);
            opacity: 0;
        }

        .swipe-card-modern--back {
            position: absolute;
            inset: 20px 0 auto 0;
            z-index: 1;
            transform: scale(0.97);
            opacity: 0.65;
            filter: saturate(0.85);
            pointer-events: none;
        }

        .swipe-card-modern--back .swipe-actions { display: none; }

        .swipe-image-container {
            width: 100%;
            height: 340px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            overflow: hidden;
            position: relative;
        }

        .swipe-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .swipe-content {
            padding: 20px;
        }

        .swipe-title {
            font-size: 1.5rem;
            font-weight: 900;
            color: #02025b;
            margin: 0 0 12px 0;
            line-height: 1.2;
        }

        .swipe-meta-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .swipe-meta-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.95rem;
            font-weight: 500;
            color: #333;
        }

        .swipe-meta-item svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .swipe-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 16px;
        }

        .swipe-action-btn {
            padding: 12px 16px;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .swipe-action-btn svg {
            width: 16px;
            height: 16px;
        }

        .btn-skip {
            background: #ef4444;
            color: white;
        }

        .btn-skip:hover:not(:disabled) {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3);
        }

        .btn-join {
            background: #10b981;
            color: white;
        }

        .btn-join:hover:not(:disabled) {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
        }

        .swipe-action-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .swipe-empty-state {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .swipe-empty-state.is-visible {
            display: flex;
        }

        .swipe-empty-state-icon {
            font-size: 3rem;
            margin-bottom: 12px;
        }

        .swipe-empty-state-text {
            font-size: 1rem;
            font-weight: 600;
            color: #666;
        }

        /* Right Column - Badge & Teams */
        .badge-card {
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 16px;
            text-align: center;
        }

        .badge-level {
            font-size: 2rem;
            font-weight: 900;
            margin-bottom: 8px;
        }

        .badge-text {
            font-size: 0.9rem;
            opacity: 0.95;
            margin-bottom: 12px;
        }

        .badge-progress {
            width: 100%;
            height: 6px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .badge-progress-bar {
            height: 100%;
            background: white;
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        .badge-stats {
            font-size: 0.8rem;
            opacity: 0.9;
        }

        /* Teams List */
        .teams-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .team-item {
            padding: 12px;
            border: 1px solid rgba(0, 0, 77, 0.1);
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .team-item:hover {
            border-color: rgba(0, 0, 77, 0.2);
            background: rgba(17, 17, 75, 0.02);
        }

        .team-item-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #02025b;
            margin-bottom: 6px;
        }

        .team-item-meta {
            font-size: 0.8rem;
            color: #666;
            display: flex;
            justify-content: space-between;
        }

        .view-all-btn {
            width: 100%;
            padding: 10px;
            background: rgba(17, 17, 75, 0.05);
            color: #11114b;
            border: 1px solid rgba(17, 17, 75, 0.2);
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .view-all-btn:hover {
            background: rgba(17, 17, 75, 0.1);
            border-color: rgba(17, 17, 75, 0.3);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .redesign-grid {
                grid-template-columns: 240px 1fr 260px;
                gap: 16px;
            }

            .swipe-card-modern {
                max-width: 400px;
            }
        }

        @media (max-width: 1024px) {
            .redesign-grid {
                grid-template-columns: 1fr;
            }

            .redesign-col-left, .redesign-col-right {
                order: 2;
            }

            .redesign-col-center {
                order: 1;
            }

            .redesign-container {
                padding: 16px 12px 30px;
            }
        }

        @media (max-width: 768px) {
            .redesign-grid {
                grid-template-columns: 1fr;
                gap: 16px;
                min-height: auto;
            }

            .card-section {
                padding: 16px;
            }

            .swipe-image-container {
                height: 300px;
            }

            .swipe-title {
                font-size: 1.3rem;
            }

            .redesign-container {
                padding: 12px 8px 24px;
            }
        }

        @media (max-width: 480px) {
            .redesign-grid {
                grid-template-columns: 1fr;
            }

            .redesign-col-left {
                order: 2;
            }

            .redesign-col-center {
                order: 1;
            }

            .redesign-col-right {
                order: 3;
            }

            .card-section {
                padding: 14px;
            }

            .swipe-container {
                max-width: 100%;
            }

            .swipe-image-container {
                height: 260px;
            }

            .swipe-title {
                font-size: 1.2rem;
            }

            .swipe-content {
                padding: 16px;
            }

            .swipe-meta-item {
                font-size: 0.9rem;
            }

            .swipe-actions {
                gap: 10px;
            }

            .swipe-action-btn {
                padding: 10px 12px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body class="player-dashboard-page" style="--player-dashboard-bg:url('{{ asset('assets/images/bg/bg-login.png') }}');">
<div class="player-dashboard-shell">
    <aside class="player-sidebar" data-sidebar>
        <div class="player-sidebar__inner">
            <div class="player-sidebar__header">
                <a href="{{ route('dashboard') }}" class="player-sidebar__brand">
                    <img src="{{ asset('assets/images/logo/logodb.png') }}" alt="Spies Sport" class="player-sidebar__logo">
                </a>
                <button type="button" class="player-sidebar__close" data-sidebar-close><span></span><span></span></button>
            </div>
            <nav class="player-sidebar__nav">
                @foreach($sidebarItems as $item)
                    @php $cls = 'player-sidebar__item'.($item['active']?' is-active':'').($item['href']?'':' is-disabled'); @endphp
                    @if($item['href'])
                        <a href="{{ $item['href'] }}" class="{{ $cls }}">
                            <span class="player-sidebar__icon-wrap"><img src="{{ $item['icon'] }}" alt="" class="player-sidebar__icon"></span>
                            <span class="player-sidebar__label">{{ $item['label'] }}</span>
                        </a>
                    @else
                        <button type="button" class="{{ $cls }}" disabled>
                            <span class="player-sidebar__icon-wrap"><img src="{{ $item['icon'] }}" alt="" class="player-sidebar__icon"></span>
                            <span class="player-sidebar__label">{{ $item['label'] }}</span>
                        </button>
                    @endif
                @endforeach
            </nav>
            <div class="player-sidebar__footer">
                @foreach($sidebarUtilities as $item)
                    <a href="{{ $item['href'] }}" class="player-sidebar__item">
                        <span class="player-sidebar__icon-wrap"><img src="{{ $item['icon'] }}" alt="" class="player-sidebar__icon"></span>
                        <span class="player-sidebar__label">{{ $item['label'] }}</span>
                    </a>
                @endforeach
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="player-sidebar__item player-sidebar__item--logout">
                        <span class="player-sidebar__icon-wrap"><img src="{{ asset('assets/images/icons/keluar.png') }}" alt="" class="player-sidebar__icon"></span>
                        <span class="player-sidebar__label">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>
    <button type="button" class="player-sidebar__backdrop" data-sidebar-backdrop></button>

    <main class="player-dashboard-main">
        <header class="player-dashboard-topbar">
            <div class="player-dashboard-topbar__left">
                <button type="button" class="player-dashboard-topbar__menu" data-sidebar-open><span></span><span></span><span></span></button>
                <label class="player-search" for="team-search">
                    <span class="player-search__icon">
                        <svg viewBox="0 0 20 20" fill="none"><circle cx="9" cy="9" r="5.75" stroke="currentColor" stroke-width="1.8"/><path d="M13.5 13.5L17 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </span>
                    <input id="team-search" type="search" placeholder="Cari lapangan">
                </label>
            </div>
            <div class="player-dashboard-topbar__right">
                <div class="player-dashboard-topbar__date">
                    <span class="player-inline-icon">
                        <svg viewBox="0 0 24 24" fill="none"><rect x="3.5" y="5.5" width="17" height="15" rx="2.5" stroke="currentColor" stroke-width="1.8"/><path d="M7 3.5V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M17 3.5V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M3.5 9.5H20.5" stroke="currentColor" stroke-width="1.8"/></svg>
                    </span>
                    <span>{{ $currentDate }}</span>
                </div>
                <div style="position: relative;">
                    @include('partials.notification-bell')
                </div>
                <a href="{{ route('profile.show') }}" class="player-profile-pill">
                    <span class="player-profile-pill__avatar">
                        <img src="{{ $profileAvatar }}" alt="Profil" class="player-avatar-image player-avatar-image--profile">
                    </span>
                    <span class="player-profile-pill__name">{{ $userName }}</span>
                </a>
            </div>
        </header>

        {{-- ALERT MESSAGES --}}
        @if(session('success'))
        <div style="margin: 0 20px 20px; background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 12px 16px; border-radius: 10px; font-size: 14px; font-weight: 600;">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div style="margin: 0 20px 20px; background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 12px 16px; border-radius: 10px; font-size: 14px; font-weight: 600;">
            {{ session('error') }}
        </div>
        @endif

        {{-- 3-COLUMN LAYOUT --}}
        <section class="redesign-container">
            <div class="redesign-grid">

                {{-- LEFT COLUMN --}}
                <div class="redesign-col-left">
                    {{-- Create Match Button --}}
                    <a href="{{ route('matches.create') }}" class="btn-primary">
                        <span>+</span> Buat Pertandingan
                    </a>

                    {{-- Filter Section --}}
                    <div class="card-section">
                        <div class="filter-section-title">Pilih Olahraga</div>
                        <div class="filter-checkbox-group" id="sport-filter-group">
                            @foreach($sportOptions as $sport)
                            <div class="filter-checkbox-item">
                                <input type="checkbox" id="sport-{{ Str::slug($sport) }}" value="{{ $sport }}" class="sport-checkbox">
                                <label for="sport-{{ Str::slug($sport) }}">{{ $sport }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Upcoming Bookings --}}
                    @if($upcomingBookings->isNotEmpty())
                    <div class="card-section">
                        <div class="filter-section-title">Pertandingan Mendatang</div>
                        <div class="booking-list">
                            @foreach($upcomingBookings as $booking)
                            @php
                                $sport = match(true) {
                                    str_contains(strtolower($booking->field->name), 'futsal') => 'Futsal',
                                    str_contains(strtolower($booking->field->name), 'badminton') || str_contains(strtolower($booking->field->name), 'bulu') => 'Badminton',
                                    str_contains(strtolower($booking->field->name), 'basket') => 'Basket',
                                    str_contains(strtolower($booking->field->name), 'voli') || str_contains(strtolower($booking->field->name), 'volley') => 'Voli',
                                    str_contains(strtolower($booking->field->name), 'tenis') || str_contains(strtolower($booking->field->name), 'tennis') => 'Tennis',
                                    default => 'Olahraga'
                                };
                            @endphp
                            <div class="booking-card">
                                <div class="booking-card-sport {{ $sportColor($sport) }}">{{ $sport }}</div>
                                <div class="booking-card-title">{{ $booking->field->name }}</div>
                                <div class="booking-card-meta">
                                    <span>{{ \Carbon\Carbon::parse($booking->date)->locale('id')->format('d M Y') }}</span>
                                    <span>{{ \Carbon\Carbon::createFromFormat('H:i:s', $booking->start_time)->format('H:i') }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- CENTER COLUMN - SWIPE CARD --}}
                <div class="redesign-col-center">
                    <div class="swipe-container">
                        {{-- Back Card (Preview) --}}
                        <div class="swipe-card-modern swipe-card-modern--back" data-swipe-card-back hidden>
                            <div class="swipe-image-container">
                                <img data-card-back-image src="" alt="Tim berikutnya">
                            </div>
                            <div class="swipe-content">
                                <h3 class="swipe-title" data-card-back-title></h3>
                            </div>
                        </div>

                        {{-- Front Card --}}
                        <div class="swipe-card-modern" data-swipe-card>
                            <div class="swipe-image-container">
                                <img data-card-image src="" alt="Tim olahraga">
                            </div>
                            <div class="swipe-content">
                                <h3 class="swipe-title" data-card-title></h3>
                                <div class="swipe-meta-list">
                                    <div class="swipe-meta-item">
                                        <svg viewBox="0 0 24 24" fill="none"><path d="M12 21C12 21 18.5 14.5 18.5 9.75C18.5 6.3 15.7 3.5 12.25 3.5C8.8 3.5 6 6.3 6 9.75C6 14.5 12 21 12 21Z" fill="#EA1E1E"/><circle cx="12.2" cy="9.7" r="2.1" fill="white"/></svg>
                                        <span data-card-venue></span>
                                    </div>
                                    <div class="swipe-meta-item">
                                        <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8.2" fill="#F28B1D"/><path d="M3.8 12H20.2" stroke="#111" stroke-width="1.5"/></svg>
                                        <span data-card-sport></span>
                                    </div>
                                    <div class="swipe-meta-item">
                                        <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8.5" stroke="#111" stroke-width="1.7"/><path d="M12 7.6V12.4L15.2 14.2" stroke="#111" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        <span data-card-schedule></span>
                                    </div>
                                    <div class="swipe-meta-item">
                                        <svg viewBox="0 0 24 24" fill="none"><path d="M7 10.5C7 8.57 8.57 7 10.5 7C11.96 7 13.22 7.9 13.75 9.17C14.2 8.88 14.74 8.7 15.33 8.7C16.95 8.7 18.27 10.02 18.27 11.64C18.27 13.26 16.95 14.58 15.33 14.58H8.7C7.21 14.58 6 13.37 6 11.88C6 11.27 6.2 10.72 6.53 10.27" stroke="#111" stroke-width="1.7" stroke-linecap="round"/></svg>
                                        <span data-card-needs></span>
                                    </div>
                                </div>
                                <div class="swipe-actions">
                                    <button type="button" class="swipe-action-btn btn-skip" data-swipe-skip>
                                        <svg viewBox="0 0 24 24" fill="none"><path d="M6.5 6.5L17.5 17.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/><path d="M17.5 6.5L6.5 17.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
                                        Lewati
                                    </button>
                                    <button type="button" class="swipe-action-btn btn-join" data-swipe-join>
                                        <svg viewBox="0 0 24 24" fill="none"><path d="M12.6 20.2C12.26 20.36 11.86 20.36 11.52 20.2C8.52 18.76 5 15.8 5 11.94C5 8.95 7.42 6.53 10.4 6.53C11.57 6.53 12.67 6.9 13.58 7.59C14.49 6.9 15.59 6.53 16.76 6.53C19.74 6.53 22.16 8.95 22.16 11.94C22.16 15.8 18.64 18.76 15.64 20.2" fill="currentColor"/></svg>
                                        Gabung
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Empty State --}}
                        <div class="swipe-empty-state" data-swipe-empty>
                            <div class="swipe-empty-state-icon">🔍</div>
                            <div class="swipe-empty-state-text">Tidak ada pertandingan untuk filter ini</div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN --}}
                <div class="redesign-col-right">
                    {{-- Badge / Level Card --}}
                    <div class="badge-card">
                        <div class="badge-level">{{ $userSkill['level'] }}</div>
                        <div class="badge-text">Lencana Pemain</div>
                        <div class="badge-progress">
                            <div class="badge-progress-bar" style="width: {{ (($userSkill['progress'] + 1) / 5) * 100 }}%"></div>
                        </div>
                        <div class="badge-stats">
                            {{ $userSkill['totalBookings'] + $userSkill['totalMatches'] }} aktivitas
                        </div>
                    </div>

                    {{-- My Teams --}}
                    @if($myTeams->isNotEmpty())
                    <div class="card-section">
                        <div class="filter-section-title">Tim Anda</div>
                        <div class="teams-list">
                            @foreach($myTeams->take(3) as $team)
                            <div class="team-item">
                                <div class="team-item-title">{{ $team->title }}</div>
                                <div class="team-item-meta">
                                    <span>{{ \Carbon\Carbon::parse($team->date)->locale('id')->format('d M') }}</span>
                                    <span class="font-semibold">{{ $team->players->count() }}/{{ $team->max_player }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if($myTeams->count() > 3)
                        <button class="view-all-btn" onclick="alert('Feature: View all teams')">Lihat Semua</button>
                        @endif
                    </div>
                    @else
                    <div class="card-section" style="text-align: center; color: #999; padding: 24px;">
                        <div style="font-size: 2rem; margin-bottom: 8px;">⚽</div>
                        <div style="font-size: 0.95rem; font-weight: 600;">Belum ada tim</div>
                    </div>
                    @endif
                </div>

            </div>
        </section>
    </main>
</div>

<script>
(() => {
    const allCardsRaw = @json($cards);
    const cardEl = document.querySelector('[data-swipe-card]');
    const backCardEl = document.querySelector('[data-swipe-card-back]');
    const emptyEl = document.querySelector('[data-swipe-empty]');
    const skipBtn = document.querySelector('[data-swipe-skip]');
    const joinBtn = document.querySelector('[data-swipe-join]');
    const titleEl = document.querySelector('[data-card-title]');
    const backTitleEl = document.querySelector('[data-card-back-title]');
    const imageEl = document.querySelector('[data-card-image]');
    const backImageEl = document.querySelector('[data-card-back-image]');
    const venueEl = document.querySelector('[data-card-venue]');
    const sportEl = document.querySelector('[data-card-sport]');
    const needsEl = document.querySelector('[data-card-needs]');
    const scheduleEl = document.querySelector('[data-card-schedule]');
    const sportCheckboxes = document.querySelectorAll('.sport-checkbox');

    if (!cardEl || !emptyEl || !skipBtn || !joinBtn) {
        return;
    }

    const allCards = (Array.isArray(allCardsRaw) ? allCardsRaw : Object.values(allCardsRaw || {}))
        .filter((item) => item && typeof item === 'object')
        .map((item, index) => ({
            ...item,
            _swipeKey: String(item.id ?? `idx-${index}`),
        }));

    let deck = [];
    const swipedKeys = new Set();
    let pointerStartX = null;
    let dragShiftX = 0;
    let isAnimating = false;

    // Build deck based on selected sports
    const buildDeck = () => {
        const selectedSports = Array.from(sportCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        let available = allCards.filter(item => {
            const matchesSport = selectedSports.length === 0 || selectedSports.includes(item.sport);
            const notSwiped = !swipedKeys.has(item._swipeKey);
            return matchesSport && notSwiped;
        });

        // Auto-recycle if all swiped
        if (!available.length && allCards.length > 0) {
            const validCards = allCards.filter(item => 
                selectedSports.length === 0 || selectedSports.includes(item.sport)
            );
            swipedKeys.forEach(key => {
                if (validCards.some(c => c._swipeKey === key)) {
                    swipedKeys.delete(key);
                }
            });
            available = validCards.filter(item => !swipedKeys.has(item._swipeKey));
        }

        deck = available;
        renderCard();
    };

    const resetCardTransform = () => {
        cardEl.classList.remove('is-swiping-left', 'is-swiping-right');
        cardEl.style.transition = '';
        cardEl.style.transform = '';
    };

    const renderCard = () => {
        if (!deck.length) {
            cardEl.style.display = 'none';
            if (backCardEl) backCardEl.hidden = true;
            emptyEl.classList.add('is-visible');
            skipBtn.disabled = true;
            joinBtn.disabled = true;
            isAnimating = false;
            return;
        }

        const current = deck[0];
        cardEl.style.display = 'block';
        resetCardTransform();
        emptyEl.classList.remove('is-visible');
        skipBtn.disabled = false;
        joinBtn.disabled = false;

        imageEl.src = current.image;
        imageEl.alt = `Tim ${current.sport}`;
        titleEl.textContent = current.title;
        venueEl.textContent = current.venue;
        sportEl.textContent = current.sport;
        needsEl.textContent = `Butuh ${current.neededPlayers} pemain`;
        scheduleEl.textContent = current.schedule;

        // Show back card preview
        if (backCardEl && backImageEl && backTitleEl) {
            const next = deck[1];
            if (next) {
                backCardEl.hidden = false;
                backImageEl.src = next.image;
                backImageEl.alt = `Tim ${next.sport}`;
                backTitleEl.textContent = next.title;
            } else {
                backCardEl.hidden = true;
            }
        }
    };

    const swipe = (direction) => {
        if (!deck.length || isAnimating) return;
        isAnimating = true;
        resetCardTransform();
        cardEl.classList.add(direction === 'left' ? 'is-swiping-left' : 'is-swiping-right');

        const current = deck[0];
        if (current) {
            swipedKeys.add(current._swipeKey);
        }

        setTimeout(() => {
            isAnimating = false;
            if (direction === 'right' && current) {
                window.location.href = `/cari-tim/${current.id}`;
            } else {
                buildDeck();
            }
        }, 300);
    };

    // Event listeners
    skipBtn?.addEventListener('click', () => swipe('left'));
    joinBtn?.addEventListener('click', () => swipe('right'));

    // Sport filter - rebuild deck on change
    sportCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', buildDeck);
    });

    // Touch/pointer swipe gesture
    cardEl?.addEventListener('pointerdown', (event) => {
        if (!deck.length || isAnimating) return;
        pointerStartX = event.clientX;
        dragShiftX = 0;
        cardEl.style.transition = 'none';
    });

    cardEl?.addEventListener('pointermove', (event) => {
        if (pointerStartX === null) return;
        dragShiftX = event.clientX - pointerStartX;
        const rotate = dragShiftX * 0.05;
        cardEl.style.transform = `translateX(${dragShiftX}px) rotate(${rotate}deg)`;
    });

    cardEl?.addEventListener('pointerup', () => {
        if (pointerStartX === null) return;
        cardEl.style.transition = '';
        pointerStartX = null;
        if (dragShiftX < -90) swipe('left');
        else if (dragShiftX > 90) swipe('right');
        else cardEl.style.transform = '';
    });

    cardEl?.addEventListener('pointercancel', () => {
        pointerStartX = null;
        cardEl.style.transition = '';
        cardEl.style.transform = '';
    });

    // Initial render
    buildDeck();
})();
</script>
</body>
</html>
