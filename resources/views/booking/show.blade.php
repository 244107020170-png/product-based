@php
    use Carbon\Carbon;
    $userName = Auth::user()->name ?? 'Pemain';
    $userAvatar = Auth::user()->avatarUrl();
    $currentDate = Carbon::now()->locale('id')->translatedFormat('j F Y');
    $field = $field;
    $allFields = $allFields;
    $availableTimes = $availableTimes;

    $prefillDate = request('date', '');
    $prefillStart = request('start_time', '');
    $prefillEnd = request('end_time', '');

    $field->load('discounts');

    $visibleFields = ['id', 'name', 'type', 'location', 'price_per_hour', 'image', 'image_url', 'fallback_image', 'facilities', 'rating',
        'promo_price', 'promo_badge', 'promo_price_raw', 'promo_start', 'promo_end', 'has_active_promo',
        'open_time', 'close_time'];
    $selectedFieldJson = $field->makeVisible($visibleFields);
    $allFieldsJson = $allFields->map(fn($f) => $f->makeVisible($visibleFields))->toArray();
    
    $numCourts = $field->number_of_courts ?? 1;

    // Sidebar
    $sidebarItems = [
        ['label'=>'Beranda',  'icon'=>asset('assets/images/icons/dashboard.png'), 'href'=>route('dashboard'),    'active'=>true],
        ['label'=>'Aktivitas',  'icon'=>asset('assets/images/icons/aktivitas.png'), 'href'=>route('activity.index'),       'active'=>false],
        ['label'=>'Favorit',  'icon'=>asset('assets/images/icons/favoritmu.png'), 'href'=>route('favorite.index'),                  'active'=>false],
        ['label'=>'Histori',    'icon'=>asset('assets/images/icons/histori.png'),   'href'=>route('history.index'),                  'active'=>false],
        ['label'=>'Cari tim',   'icon'=>asset('assets/images/icons/caritim.png'),   'href'=>route('matches.index'),'active'=>false],
        ['label'=>'Pemesanan',    'icon'=>asset('assets/images/icons/booking.png'),   'href'=>route('booking.index'),       'active'=>false],
        ['label'=>'Keahlian', 'icon'=>asset('assets/images/icons/keahlian.png'),  'href'=>route('skill.index'),                  'active'=>false],
        ['label'=>'Profil',     'icon'=>asset('assets/images/icons/profil.png'),    'href'=>route('profile.show'), 'active'=>false],
    ];
    $sidebarUtilities = [
        ['label'=>'Bantuan',    'icon'=>asset('assets/images/icons/bantuan.png'),    'href'=>route('preview.help')],
        ['label'=>'Pengaturan', 'icon'=>asset('assets/images/icons/pengaturan.png'), 'href'=>route('profile.edit')],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pemesanan – {{ config('app.name', 'Spies Sport') }}</title>
    @vite([
        'resources/css/app.css',
        'resources/css/player-dashboard.css',
    ])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        /* Custom styles for booking page */
        .bk-container { max-width: 900px; margin: 0 auto; padding: 20px; font-family: 'Poppins', sans-serif; }
        .bk-card { background: white; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); position: relative; margin-bottom: 24px; }
        
        /* Top Card */
        .bk-top-wrap { display: flex; padding: 20px; gap: 24px; align-items: center; }
        .bk-carousel { width: 320px; height: 200px; border-radius: 12px; overflow: hidden; position: relative; flex-shrink: 0; }
        .bk-carousel img { width: 100%; height: 100%; object-fit: cover; }
        .bk-carousel-dots { position: absolute; bottom: 12px; left: 0; right: 0; display: flex; justify-content: center; gap: 6px; }
        .bk-dot { width: 6px; height: 6px; border-radius: 50%; background: rgba(255,255,255,0.5); }
        .bk-dot.active { background: #ff4d4d; }
        .bk-carousel-btn { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.8); border: none; border-radius: 50%; width: 28px; height: 28px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #333; }
        .bk-carousel-btn.right { right: 10px; }
        
        .bk-info h1 { font-size: 24px; font-weight: 800; color: #000; margin: 0 0 12px 0; }
        .bk-meta { display: flex; align-items: center; gap: 16px; font-size: 14px; color: #333; font-weight: 600; }
        .bk-meta-item { display: flex; align-items: center; gap: 6px; }
        .bk-icon { display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; }
        .bk-icon svg { width: 18px; height: 18px; }
        
        /* Fasilitas Toggle */
        .bk-fasilitas-wrapper { background: #fffdf5; border-radius: 0 0 20px 20px; margin-top: -10px; padding: 24px 20px 20px; border-top: 1px dashed #eee; display: none; }
        .bk-fasilitas-wrapper.open { display: block; }
        .bk-toggle-btn { position: absolute; bottom: -16px; left: 50%; transform: translateX(-50%); background: white; border: 1px solid #eee; border-radius: 6px; width: 50px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.05); z-index: 10; }
        
        .bk-fasilitas-title { display: flex; align-items: center; gap: 8px; font-size: 18px; font-weight: 700; color: #000; margin-bottom: 16px; }
        .bk-fasilitas-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px; }
        .bk-f-item { display: flex; align-items: center; gap: 10px; background: white; border-radius: 8px; padding: 10px 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.03); font-weight: 600; color: #555; font-size: 13px; border: 1px solid #f1f5f9; }
        
        /* Bottom Form Card */
        .bk-form-wrap { display: flex; gap: 30px; padding: 24px; }
        .bk-form-left { flex: 1; }
        .bk-form-right { width: 300px; flex-shrink: 0; }
        
        /* Dropdowns & Pickers Container */
        .bk-input-group { margin-bottom: 24px; }
        .bk-label { display: block; font-size: 16px; font-weight: 700; color: #000; margin-bottom: 10px; }
        .bk-input-box { border: 1px solid #d4cbb8; border-radius: 10px; padding: 14px 16px; width: 100%; display: flex; align-items: center; justify-content: space-between; background: #FAF8F1; font-weight: 600; color: #333; cursor: pointer; position: relative; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02); }
        .bk-input-box input { border: none; background: transparent; width: 100%; outline: none; font-weight: 600; font-size: 15px; color: #333; cursor: pointer; }
        
        .bk-dropdown { position: absolute; top: calc(100% + 8px); left: 0; width: 100%; background: #FAF8F1; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); z-index: 50; max-height: 280px; overflow-y: auto; display: none; padding: 16px; border: 1px solid #d4cbb8; }
        .bk-dropdown.open { display: block; }
        
        /* Time Grid */
        .bk-time-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .bk-time-pill { padding: 8px 4px; text-align: center; border: 1px solid #666; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s; background: transparent; color: #333; }
        .bk-time-pill:hover:not(.disabled) { background: #e8e4d9; }
        .bk-time-pill.selected-start { background: #00004d; color: white; border-color: #00004d; }
        .bk-time-pill.selected-end { background: #1e40af; color: white; border-color: #1e40af; }
        .bk-time-pill.in-range { background: #dbeafe; color: #1e3a5f; border-color: #93c5fd; }
        .bk-time-pill.clickable { cursor: pointer; }
        .bk-time-pill.not-clickable { opacity: 0.4; cursor: not-allowed; }
        .bk-time-pill.full { background: #d32f2f; color: white; border-color: #d32f2f; cursor: not-allowed; opacity: 0.9; }
        .bk-time-pill.selected-start.full, .bk-time-pill.selected-end.full { display: none; }
        
        /* Sub-field Grid */
        .bk-subfield-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .bk-subfield-pill { padding: 8px 4px; text-align: center; border: 1px solid #666; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; transition: all 0.2s; background: transparent; color: #333; }
        .bk-subfield-pill:hover:not(.disabled) { background: #e8e4d9; }
        .bk-subfield-pill.selected { background: #00004d; color: white; border-color: #00004d; }
        .bk-subfield-pill.full { background: #d32f2f; color: white; border-color: #d32f2f; cursor: not-allowed; opacity: 0.9; }
        
        /* Summary Box */
        .bk-summary { border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
        .bk-summary-placeholder { border: 1px dashed #d4cbb8; border-radius: 12px; padding: 24px; background: #FAF8F1; }
        .bk-selanjutnya-btn { width: 100%; background: #00004d; color: white; border: none; border-radius: 8px; padding: 14px; font-size: 16px; font-weight: 700; cursor: pointer; transition: background 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .bk-selanjutnya-btn:hover { background: #000033; }
        .bk-selanjutnya-btn:disabled { background: #94a3b8; cursor: not-allowed; }
        .bk-summary-section { overflow: hidden; transition: max-height 0.5s ease, opacity 0.4s ease; max-height: 0; opacity: 0; }
        .bk-summary-section.open { max-height: 800px; opacity: 1; }
        .bk-summary-title { font-size: 18px; font-weight: 700; color: #000; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        
        .bk-divider { height: 1px; background: #e2e8f0; margin: 16px 0; }
        
        .bk-sum-row { display: flex; justify-content: space-between; font-size: 14px; color: #555; margin-bottom: 12px; font-weight: 500; }
        .bk-sum-val { color: #000; font-weight: 600; }
        
        .bk-total-row { display: flex; justify-content: space-between; font-size: 20px; font-weight: 800; color: #000; margin: 20px 0; align-items: center;}
        
        .bk-btn-submit { width: 100%; background: #00004d; color: white; border: none; border-radius: 8px; padding: 14px; font-size: 16px; font-weight: 700; cursor: pointer; transition: background 0.2s; }
        .bk-btn-submit:hover { background: #000033; }
        
        @media (max-width: 768px) {
            .bk-top-wrap { flex-direction: column; }
            .bk-carousel { width: 100%; height: 180px; }
            .bk-form-wrap { flex-direction: column; }
            .bk-form-right { width: 100%; }
            .bk-container { padding: 12px; }
            .bk-info h1 { font-size: 20px; }
            .bk-time-grid { grid-template-columns: repeat(3, 1fr); gap: 6px; }
        }
        @media (max-width: 480px) {
            .bk-container { padding: 8px; }
            .bk-carousel { height: 160px; }
            .bk-info h1 { font-size: 17px; }
            .bk-meta { flex-wrap: wrap; gap: 8px; font-size: 12px; }
            .bk-time-grid { grid-template-columns: repeat(2, 1fr); }
            .bk-time-pill { font-size: 11px; padding: 6px 2px; }
            .bk-form-wrap { padding: 14px; gap: 16px; }
            .bk-summary { padding: 16px; }
            .bk-total-row { font-size: 16px; }
            .bk-btn-submit { font-size: 14px; padding: 12px; }
            .bk-fasilitas-grid { grid-template-columns: repeat(2, 1fr); }
            .bk-f-item { font-size: 11px; padding: 8px 10px; }
        }
        @media (max-width: 360px) {
            .bk-carousel { height: 140px; }
            .bk-time-grid { grid-template-columns: repeat(2, 1fr); gap: 4px; }
            .bk-time-pill { font-size: 10px; padding: 5px 2px; }
            .bk-subfield-grid { grid-template-columns: repeat(2, 1fr); }
        }

        /* Ketentuan Pemesanan Card */
        .bk-ketentuan-card {
            background: #fff8e7;
            border: 1px solid #f0d78c;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 14px;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }
        .bk-ketentuan-icon {
            font-size: 20px;
            flex-shrink: 0;
            margin-top: 1px;
            line-height: 1;
        }
        .bk-ketentuan-title {
            font-size: 13px;
            font-weight: 800;
            color: #92400e;
            margin-bottom: 6px;
        }
        .bk-ketentuan-list {
            margin: 0;
            padding: 0;
            list-style: none;
            font-size: 12px;
            color: #a16207;
            line-height: 1.7;
        }
        .bk-ketentuan-list li {
            position: relative;
            padding-left: 14px;
        }
        .bk-ketentuan-list li::before {
            content: "•";
            position: absolute;
            left: 2px;
            color: #d97706;
            font-weight: 700;
        }
        @media (max-width: 480px) {
            .bk-ketentuan-card { padding: 10px 12px; }
            .bk-ketentuan-title { font-size: 12px; }
            .bk-ketentuan-list { font-size: 11px; }
        }
    </style>
</head>
<body class="player-dashboard-page" style="--player-dashboard-bg:url('{{ asset('assets/images/bg/bg-login.png') }}');">
<div class="player-dashboard-shell">

{{-- ============ SIDEBAR ============ --}}
<aside class="player-sidebar" data-sidebar>
    <div class="player-sidebar__inner">
        <div class="player-sidebar__header">
            <a href="{{ route('dashboard') }}" class="player-sidebar__brand">
                <img src="{{ asset('assets/images/logo/logodb.png') }}" alt="Spies Sport" class="player-sidebar__logo">
            </a>
            <button type="button" class="player-sidebar__close" data-sidebar-close aria-label="Tutup"><span></span><span></span></button>
        </div>

        <nav class="player-sidebar__nav" aria-label="Menu utama">
            @foreach($sidebarItems as $item)
            @php $cls='player-sidebar__item'.($item['active']?' is-active':'').($item['href']?'':' is-disabled'); @endphp
            @if($item['href'])
            <a href="{{ $item['href'] }}" class="{{ $cls }}">
                <span class="player-sidebar__icon-wrap"><img src="{{ $item['icon'] }}" alt="" class="player-sidebar__icon"></span>
                <span class="player-sidebar__label">{{ $item['label'] }}</span>
            </a>
            @else
            <button type="button" class="{{ $cls }}" disabled aria-disabled="true">
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
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="player-sidebar__item player-sidebar__item--logout">
                    <span class="player-sidebar__icon-wrap"><img src="{{ asset('assets/images/icons/keluar.png') }}" alt="" class="player-sidebar__icon"></span>
                    <span class="player-sidebar__label">Keluar</span>
                </button>
            </form>
        </div>
    </div>
</aside>
<button type="button" class="player-sidebar__backdrop" data-sidebar-backdrop aria-label="Tutup sidebar"></button>

{{-- ============ MAIN ============ --}}
<main class="player-dashboard-main">

    {{-- Topbar --}}
    <header class="player-dashboard-topbar">
        <div class="player-dashboard-topbar__left">
            <button type="button" class="player-dashboard-topbar__menu" data-sidebar-open><span></span><span></span><span></span></button>
            <label class="player-search" for="booking-search">
                <span class="player-search__icon">
                    <svg viewBox="0 0 20 20" fill="none"><circle cx="9" cy="9" r="5.75" stroke="currentColor" stroke-width="1.8"/><path d="M13.5 13.5L17 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </span>
                <input id="booking-search" type="search" placeholder="Cari lapangan...">
            </label>
        </div>
        <div class="player-dashboard-topbar__right">
            <div class="player-dashboard-topbar__date">
                <span class="player-inline-icon">
                    <svg viewBox="0 0 24 24" fill="none"><rect x="3.5" y="5.5" width="17" height="15" rx="2.5" stroke="currentColor" stroke-width="1.8"/><path d="M7 3.5V7M17 3.5V7M3.5 9.5H20.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </span>
                <span>{{ $currentDate }}</span>
            </div>
            <div style="position: relative;">
                @include('partials.notification-bell')
            </div>
            <a href="{{ route('profile.show') }}" class="player-profile-pill">
                <span class="player-profile-pill__avatar">
                    <img src="{{ $userAvatar }}" alt="Profil" class="player-avatar-image player-avatar-image--profile">
                </span>
                <span class="player-profile-pill__name">{{ $userName }}</span>
            </a>
        </div>
    </header>

    <div class="bk-container" x-data="bookingApp()">
        
        {{-- ==== TOP CARD: Info Lapangan ==== --}}
        <div class="bk-card" style="z-index: 10;">
            <div class="bk-top-wrap">
                {{-- Carousel --}}
                <div class="bk-carousel">
                    <img :src="selectedField.image_url" alt="Lapangan" onerror="if(selectedField.fallback_image)this.src=selectedField.fallback_image;this.onerror=null">
                    <button class="bk-carousel-btn right">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                    <div class="bk-carousel-dots">
                        <div class="bk-dot"></div>
                        <div class="bk-dot"></div>
                        <div class="bk-dot active"></div>
                        <div class="bk-dot"></div>
                    </div>
                </div>
                
                {{-- Info --}}
                <div class="bk-info">
                    {{-- Ketentuan Pemesanan --}}
                    <div class="bk-ketentuan-card">
                        <div class="bk-ketentuan-icon">⚠️</div>
                        <div style="flex:1;">
                            <div class="bk-ketentuan-title">Ketentuan Pemesanan</div>
                            <ul class="bk-ketentuan-list">
                                <li>Pemesanan minimal 3 jam sebelum jadwal bermain.</li>
                                <li>Pembatalan maksimal 8 jam sebelum jadwal bermain.</li>
                                <li>Pesanan lewat batas tidak dapat dibatalkan via sistem.</li>
                            </ul>
                        </div>
                    </div>
                    <h1 x-text="selectedField.name"></h1>
                    <template x-if="selectedField.has_active_promo">
                        <div style="display:inline-flex;align-items:center;gap:6px;background:#dc2626;color:white;padding:6px 14px;border-radius:20px;font-size:13px;font-weight:800;margin-bottom:10px;">
                            <span>🔥</span>
                            <span x-text="selectedField.promo_badge"></span>
                        </div>
                    </template>
                    <div class="bk-meta">
                        <div class="bk-meta-item">
                            <span class="bk-icon" style="color: #ff4d4d;">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M12 20.5C12 20.5 18 14.73 18 10.5C18 7.19 15.31 4.5 12 4.5C8.69 4.5 6 7.19 6 10.5C6 14.73 12 20.5 12 20.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                    <circle cx="12" cy="10.5" r="2.4" fill="currentColor"/>
                                </svg>
                            </span>
                            <span x-text="selectedField.location"></span>
                        </div>
                        <div class="bk-meta-item">
                            <span class="bk-icon" style="color: #fbbf24;">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M12 3L14.9 8.7L21 9.6L16.5 14L17.6 20L12 17.1L6.4 20L7.5 14L3 9.6L9.1 8.7L12 3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span x-text="selectedField.rating ?? '—'"></span>
                        </div>
                        <div class="bk-meta-item">
                            <span class="bk-icon" style="color: #b45309;">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                                    <path d="M9 10.5C9 9.1 10.2 8 11.8 8H14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M9 13.5H14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M9.5 16C10 16.7 10.9 17 12 17C13.5 17 14.7 16.1 14.9 14.8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <template x-if="selectedField.has_active_promo">
                                <span>
                                    <span style="font-weight:800;color:#dc2626;" x-text="formatPrice(selectedField.promo_price_raw) + ' / jam'"></span>
                                    <span style="text-decoration:line-through;color:#999;font-size:12px;margin-left:6px;" x-text="formatPrice(selectedField.price_per_hour) + ' / jam'"></span>
                                </span>
                            </template>
                            <template x-if="!selectedField.has_active_promo">
                                <span x-text="formatPrice(selectedField.price_per_hour) + ' / jam'"></span>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Toggle Button --}}
            <button class="bk-toggle-btn" @click="showFasilitas = !showFasilitas">
                <svg x-show="!showFasilitas" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                <svg x-show="showFasilitas" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 15l-6-6-6 6"/></svg>
            </button>
            
            {{-- Fasilitas (Collapsible) --}}
            <div class="bk-fasilitas-wrapper" :class="{ 'open': showFasilitas }">
                <div class="bk-fasilitas-title">
                    <span class="bk-icon" style="color: #fbbf24;">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M12 3L14.9 8.7L21 9.6L16.5 14L17.6 20L12 17.1L6.4 20L7.5 14L3 9.6L9.1 8.7L12 3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    Fasilitas
                </div>
                <div class="bk-fasilitas-grid">
                    <template x-for="f in selectedFieldFacilities" :key="f.name">
                        <div class="bk-f-item">
                            <span x-html="f.icon" class="bk-icon" style="color:#0f766e;"></span>
                            <span x-text="f.name"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        
        {{-- ==== BOTTOM CARD: Form Booking ==== --}}
        <div class="bk-card bk-form-wrap">
            <div class="bk-form-left">
                
                {{-- Tanggal --}}
                <div class="bk-input-group">
                    <label class="bk-label">Pilih Tanggal</label>
                    <div class="bk-input-box">
                        <span style="color: #666; display: flex; align-items: center;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </span>
                        <input type="text" x-ref="dateInput" placeholder="Pilih tanggal" readonly style="margin-left: 12px;">
                        <span style="color: #666;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                        </span>
                    </div>
                </div>
                
                {{-- Jam Mulai → Jam Selesai --}}
                <div class="bk-input-group" style="position: relative;">
                    <label class="bk-label">Jam Main</label>
                    <div class="bk-input-box" @click="showTimeDropdown = !showTimeDropdown; showFieldDropdown = false">
                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            <span style="color: #666;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            </span>
                            <template x-if="!selectedStartTime">
                                <span style="color:#94a3b8;">Pilih Jam Mulai</span>
                            </template>
                            <template x-if="selectedStartTime && !selectedEndTime">
                                <span><span style="font-weight:800;color:#00004d;" x-text="selectedStartTime"></span><span style="margin:0 4px;color:#94a3b8;"> → </span><span style="color:#94a3b8;">Pilih Jam Selesai</span></span>
                            </template>
                            <template x-if="selectedStartTime && selectedEndTime">
                                <span><span style="font-weight:800;color:#00004d;" x-text="selectedStartTime"></span><span style="margin:0 4px;"> → </span><span style="font-weight:800;color:#00004d;" x-text="selectedEndTime"></span></span>
                            </template>
                        </div>
                        <span style="color: #666;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                        </span>
                    </div>
                    
                    <div class="bk-dropdown" :class="{ 'open': showTimeDropdown }">
                        <template x-if="timeSelectionPhase === 'start'">
                            <div>
                                <div style="font-size:12px;font-weight:600;color:#666;margin-bottom:10px;">Pilih Jam Mulai</div>
                                <div class="bk-time-grid">
                                    <template x-for="time in availableTimes" :key="time.start">
                                        <div class="bk-time-pill" 
                                             :class="{ 
                                                'selected-start': selectedStartTime === time.start,
                                                'full': time.isFull,
                                                'disabled': time.isFull 
                                             }"
                                             @click="if(!time.isFull) { selectedStartTime = time.start; timeSelectionPhase = 'end'; }">
                                            <span x-text="time.start"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template x-if="timeSelectionPhase === 'end'">
                            <div>
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;font-size:12px;font-weight:600;color:#666;">
                                    <span>Jam Mulai: <strong style="color:#00004d;" x-text="selectedStartTime"></strong></span>
                                    <span style="color:#94a3b8;">→</span>
                                    <span>Pilih Jam Selesai</span>
                                    <button type="button" @click="selectedStartTime='';selectedEndTime='';timeSelectionPhase='start'" style="margin-left:auto;background:none;border:none;color:#dc2626;font-size:12px;cursor:pointer;text-decoration:underline;">Ubah</button>
                                </div>
                                <div class="bk-time-grid">
                                    <template x-for="time in endTimeOptions" :key="time.start">
                                        <div class="bk-time-pill" 
                                             :class="{ 
                                                'selected-end': selectedEndTime === time.start,
                                                'in-range': selectedEndTime && time.start > selectedStartTime && time.start < selectedEndTime,
                                                'full': time.isFull,
                                                'clickable': !time.isFull
                                             }"
                                             @click="if(!time.isFull) { selectedEndTime = time.start; timeSelectionPhase = 'complete'; showTimeDropdown = false; calculateTotal(); computeCourtAvailability(); }">
                                            <span x-text="time.start"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template x-if="timeSelectionPhase === 'complete'">
                            <div>
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;font-size:12px;font-weight:600;color:#666;">
                                    <span style="font-weight:800;color:#00004d;" x-text="selectedStartTime"></span>
                                    <span>→</span>
                                    <span style="font-weight:800;color:#00004d;" x-text="selectedEndTime"></span>
                                    <span style="margin-left:auto;color:#16a34a;">✓ Dipilih</span>
                                    <button type="button" @click="selectedStartTime='';selectedEndTime='';timeSelectionPhase='start'" style="margin-left:8px;background:none;border:none;color:#dc2626;font-size:12px;cursor:pointer;text-decoration:underline;">Ubah</button>
                                </div>
                                <div class="bk-time-grid">
                                    <template x-for="time in endTimeOptions" :key="time.start">
                                        <div class="bk-time-pill" 
                                             :class="{ 
                                                'selected-end': selectedEndTime === time.start,
                                                'in-range': time.start > selectedStartTime && time.start < selectedEndTime,
                                                'full': time.isFull
                                             }"
                                             @click="selectedStartTime='';selectedEndTime='';timeSelectionPhase='start'; showTimeDropdown = false;">
                                            <span x-text="time.start"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                
                {{-- Lapangan (Sub-fields) --}}
                <div class="bk-input-group" style="position: relative;">
                    <label class="bk-label">Pilih Lapangan</label>
                    <div class="bk-input-box" @click="showFieldDropdown = !showFieldDropdown; showTimeDropdown = false">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span style="color: #666;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="3" x2="12" y2="21"></line><line x1="3" y1="12" x2="12" y2="12"></line></svg>
                            </span>
                            <span x-text="selectedSubfield ? selectedSubfield.name : 'Pilih Lapangan'"></span>
                        </div>
                        <span style="color: #666;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                        </span>
                    </div>
                    
                    <div class="bk-dropdown" :class="{ 'open': showFieldDropdown }">
                        <div class="bk-subfield-grid">
                            <template x-for="sf in subFields" :key="sf.id">
                                <div class="bk-subfield-pill"
                                     :class="{ 
                                        'selected': selectedSubfield && selectedSubfield.id === sf.id,
                                        'full': sf.isFull,
                                        'disabled': sf.isFull
                                     }"
                                     @click="if(!sf.isFull) { selectedSubfield = sf; showFieldDropdown = false; calculateTotal(); }">
                                    <span x-text="sf.name"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                
            </div>
            
            {{-- Ringkasan --}}
            <div class="bk-form-right">
                {{-- Placeholder / Selanjutnya --}}
                <template x-if="!showSummary">
                    <div class="bk-summary-placeholder">
                        <div style="display: flex; gap: 10px; margin-bottom: 16px;">
                            <span class="bk-icon" style="color: #ff4d4d; margin-top: 2px;">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M12 20.5C12 20.5 18 14.73 18 10.5C18 7.19 15.31 4.5 12 4.5C8.69 4.5 6 7.19 6 10.5C6 14.73 12 20.5 12 20.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                    <circle cx="12" cy="10.5" r="2.4" fill="currentColor"/>
                                </svg>
                            </span>
                            <div>
                                <div style="font-weight: 700; color: #000; font-size: 14px;" x-text="selectedField.name"></div>
                                <div style="font-size: 12px; color: #666; margin-top: 2px;" x-text="selectedField.location"></div>
                            </div>
                        </div>
                        <template x-if="selectedField.has_active_promo">
                            <div>
                                <div style="display:inline-flex;align-items:center;gap:6px;background:#dc2626;color:white;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:800;">
                                    <span>🔥</span>
                                    <span x-text="selectedField.promo_badge"></span>
                                </div>
                                <div style="font-size:10px;color:#94a3b8;margin-top:4px;">
                                    <span x-text="selectedField.promo_start"></span>
                                    <span>&nbsp;—&nbsp;</span>
                                    <span x-text="selectedField.promo_end"></span>
                                </div>
                            </div>
                        </template>
                        <div style="margin: 16px 0; text-align: center; color: #94a3b8; font-size: 14px;">
                            <template x-if="!isFormComplete">
                                <span>Lengkapi data pemesanan untuk melihat ringkasan</span>
                            </template>
                            <template x-if="isFormComplete">
                                <span style="color:#16a34a;font-weight:600;">✓ Semua data lengkap</span>
                            </template>
                        </div>
                        <button class="bk-selanjutnya-btn" :disabled="!isFormComplete" @click="revealSummary()">
                            <span>⬇</span>
                            <span>Selanjutnya</span>
                        </button>
                    </div>
                </template>
                
                {{-- Full Summary --}}
                <template x-if="showSummary">
                    <div class="bk-summary-section" :class="{ 'open': showSummary }">
                        <div class="bk-summary">
                            <div class="bk-summary-title">Ringkasan Pesanan</div>
                            
                            <div style="display: flex; gap: 10px; margin-bottom: 16px;">
                                <span class="bk-icon" style="color: #ff4d4d; margin-top: 2px;">
                                    <svg viewBox="0 0 24 24" fill="none">
                                        <path d="M12 20.5C12 20.5 18 14.73 18 10.5C18 7.19 15.31 4.5 12 4.5C8.69 4.5 6 7.19 6 10.5C6 14.73 12 20.5 12 20.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                        <circle cx="12" cy="10.5" r="2.4" fill="currentColor"/>
                                    </svg>
                                </span>
                                <div>
                                    <div style="font-weight: 700; color: #000; font-size: 14px;" x-text="selectedField.name"></div>
                                    <div style="font-size: 12px; color: #666; margin-top: 2px;" x-text="selectedField.location"></div>
                                </div>
                            </div>
                            
                            <div class="bk-divider"></div>
                            
                            <template x-if="selectedField.has_active_promo">
                                <div>
                                    <div class="bk-sum-row" style="color:#999;">
                                        <span>Harga Asli</span>
                                        <span class="bk-sum-val" style="text-decoration:line-through;color:#999;" x-text="formatPrice(selectedField.price_per_hour * duration)"></span>
                                    </div>
                                    <div class="bk-sum-row" style="color:#dc2626;">
                                        <span x-text="selectedField.promo_badge"></span>
                                        <span class="bk-sum-val" style="color:#dc2626;font-weight:800;" x-text="'-' + formatPrice((selectedField.price_per_hour - selectedField.promo_price_raw) * duration)"></span>
                                    </div>
                                    <div class="bk-sum-row" style="border-bottom:1px dashed #e2e8f0;padding-bottom:12px;margin-bottom:12px;">
                                        <span style="font-weight:700;color:#000;font-size:15px;">Harga Setelah Diskon</span>
                                        <span style="font-weight:800;color:#16a34a;font-size:17px;" x-text="formatPrice(selectedField.promo_price_raw * duration)"></span>
                                    </div>
                                </div>
                            </template>
                            <template x-if="!selectedField.has_active_promo">
                                <div class="bk-sum-row">
                                    <span>Harga</span>
                                    <span class="bk-sum-val" x-text="formatPrice(selectedField.price_per_hour) + '/jam'"></span>
                                </div>
                            </template>
                            <div class="bk-sum-row">
                                <span>Durasi</span>
                                <span class="bk-sum-val" x-text="duration + ' Jam'"></span>
                            </div>
                            <div class="bk-sum-row" style="font-weight:700;color:#000;font-size:15px;border-bottom:1px dashed #e2e8f0;padding-bottom:12px;margin-bottom:12px;">
                                <span>Subtotal</span>
                                <span class="bk-sum-val" style="font-weight:800;color:#000;font-size:17px;" x-text="formatPrice(effectivePrice * duration)"></span>
                            </div>
                            <div class="bk-sum-row">
                                <span>Biaya Admin</span>
                                <span class="bk-sum-val" x-text="formatPrice(adminFee)"></span>
                            </div>
                            
                            <div class="bk-divider"></div>
                            
                            <div class="bk-total-row">
                                <span>Total</span>
                                <span x-text="formatPrice(totalPrice)"></span>
                            </div>
                            
                            <button class="bk-btn-submit" @click.prevent="submitBooking()">Lanjut Pembayaran</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
        
    </div>

</main>
</div>

<script>
function bookingApp() {
    return {
        privateSport: '{{ $privateSport ?? '' }}',
        selectedField: @json($selectedFieldJson),
        allFields: @json($allFieldsJson),
        
        availableTimes: @json($availableTimes).map(t => ({
            ...t,
            isFull: !t.available
        })),
        
        slots: @json($slots),
        
        subFields: [],
        selectedSubfield: null,
        
        selectedDate: '',
        selectedStartTime: '',
        selectedEndTime: '',
        timeSelectionPhase: 'start',
        showSummary: false,
        
        showFasilitas: true, // Based on screenshot it's open by default
        showTimeDropdown: false,
        showFieldDropdown: false,
        
        adminFee: 2000,
        totalPrice: 0,
        promoTotalPrice: 0,
        
        initSubFields() {
            const numCourts = this.selectedField.number_of_courts || 1;
            this.subFields = [];
            for (let i = 0; i < numCourts; i++) {
                this.subFields.push({ id: i + 1, name: 'Lapangan ' + (i + 1), isFull: false });
            }
            this.computeCourtAvailability();
        },

        computeCourtAvailability() {
            if (!this.selectedStartTime || !this.selectedEndTime) {
                this.subFields = this.subFields.map(sf => ({ ...sf, isFull: false }));
                return;
            }
            const startHour = parseInt(this.selectedStartTime.split(':')[0]);
            const endHour = parseInt(this.selectedEndTime.split(':')[0]);
            this.subFields = this.subFields.map(sf => {
                let full = false;
                for (let h = startHour; h < endHour; h++) {
                    const slot = this.slots.find(s => s.court_number === sf.id && s.hour === h);
                    if (slot && slot.status !== 'tersedia') {
                        full = true;
                        break;
                    }
                }
                return { ...sf, isFull: full };
            });
            if (this.selectedSubfield && this.selectedSubfield.isFull) {
                this.selectedSubfield = null;
            }
        },
        
        init() {
            const prefillDate = '{{ $prefillDate }}';
            const prefillStart = '{{ $prefillStart }}';
            const prefillEnd = '{{ $prefillEnd }}';

            this.$nextTick(() => {
                const picker = flatpickr(this.$refs.dateInput, {
                    minDate: 'today',
                    dateFormat: "d M Y",
                    onChange: (selectedDates, dateStr) => {
                        const date = selectedDates[0];
                        const y = date.getFullYear();
                        const m = String(date.getMonth()+1).padStart(2, '0');
                        const d = String(date.getDate()).padStart(2, '0');
                        const newDate = `${y}-${m}-${d}`;
                        const params = new URLSearchParams(window.location.search);
                        params.set('date', newDate);
                        window.location.search = params.toString();
                    }
                });

                if (prefillDate) {
                    const d = new Date(prefillDate + 'T00:00:00');
                    picker.setDate(d);
                    this.selectedDate = prefillDate;
                }

                this.initSubFields();
                if (prefillStart && prefillEnd) {
                    this.selectedStartTime = prefillStart;
                    this.selectedEndTime = prefillEnd;
                    this.timeSelectionPhase = 'complete';
                    this.calculateTotal();
                    this.initSubFields();
                }
                this.calculateTotal();
            });
            
            // Close dropdowns on outside click
            window.addEventListener('click', (e) => {
                if(!e.target.closest('.bk-input-group')) {
                    this.showTimeDropdown = false;
                    this.showFieldDropdown = false;
                }
            });
        },
        
        get selectedFieldFacilities() {
            const facilities = this.selectedField.facilities || [];
            const checkIcon = '<svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"></circle><path d="M8 12.5L10.8 15.2L16 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>';
            const facilityIcons = {
                'Rumput Premium': checkIcon,
                'Mushala': checkIcon,
                'Toilet Bersih': checkIcon,
                'Kursi': checkIcon,
                'Parkir Luas': checkIcon,
                'LED Tuning': checkIcon,
                'Kantin': checkIcon,
                'Ruang Ganti': checkIcon,
                'AC': checkIcon,
                'WiFi': checkIcon,
            };
            return facilities.map(f => ({ name: f, icon: facilityIcons[f] || checkIcon }));
        },
        
        get duration() {
            if (!this.selectedStartTime || !this.selectedEndTime) return 0;
            var start = parseInt(this.selectedStartTime.split(':')[0]);
            var end = parseInt(this.selectedEndTime.split(':')[0]);
            return Math.max(0, end - start);
        },
        
        get effectivePrice() {
            var base = parseInt(this.selectedField.price_per_hour);
            if (this.selectedField.has_active_promo && this.selectedField.promo_price_raw) {
                return parseInt(this.selectedField.promo_price_raw);
            }
            return base;
        },
        
        formatPrice(price) {
            return 'Rp' + parseInt(price).toLocaleString('id-ID');
        },
        
        calculateTotal() {
            var price = this.effectivePrice;
            var hrs = this.duration;
            this.totalPrice = (price * hrs) + this.adminFee;
            this.promoTotalPrice = (price * hrs) + this.adminFee;
        },
        
        get endTimeOptions() {
            if (!this.selectedStartTime) return [];
            const startHour = parseInt(this.selectedStartTime.split(':')[0]);
            const closeTime = this.selectedField.close_time || '22:00';
            const closeHour = parseInt(closeTime.split(':')[0]);
            const options = [];
            for (let h = startHour + 1; h <= closeHour; h++) {
                const timeStr = String(h).padStart(2, '0') + ':00';
                let isFull = false;
                for (let check = startHour; check < h; check++) {
                    const checkStr = String(check).padStart(2, '0') + ':00';
                    const slot = this.availableTimes.find(t => t.start === checkStr);
                    if (slot && slot.isFull) {
                        isFull = true;
                        break;
                    }
                }
                options.push({ start: timeStr, isFull });
            }
            return options;
        },

        get isTimeComplete() {
            return this.selectedStartTime && this.selectedEndTime;
        },
        get isFormComplete() {
            return this.selectedDate && this.isTimeComplete && this.selectedSubfield;
        },
        revealSummary() {
            this.showSummary = true;
            this.$nextTick(() => {
                const el = this.$el.querySelector('.bk-form-right');
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        },
        
        submitBooking() {
            if (!this.selectedDate || !this.selectedStartTime || !this.selectedEndTime) {
                showToast('Pilih tanggal dan jam terlebih dahulu', 'error');
                return;
            }

            // Validasi minimal 3 jam sebelum jadwal
            const now = new Date();
            const playDate = new Date(this.selectedDate + 'T' + this.selectedStartTime + ':00');
            const diffHours = (playDate.getTime() - now.getTime()) / (1000 * 60 * 60);
            if (diffHours < 3) {
                showToast('Pemesanan tidak dapat dilakukan karena jadwal bermain kurang dari 3 jam dari waktu saat ini.', 'error');
                return;
            }

            fetch('{{ route("booking.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    field_id: this.selectedField.id,
                    date: this.selectedDate,
                    start_time: this.selectedStartTime,
                    end_time: this.selectedEndTime,
                    sport: this.privateSport || undefined,
                })
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) {
                    const errors = data.errors ? Object.values(data.errors).flat().join('\n') : null;
                    showToast(data.message || errors || 'Gagal membuat booking.', 'error');
                    return;
                }

                window.location.href = '{{ url("bookings") }}/' + data.booking.id;
            })
            .catch(e => showToast(e.message || e, 'error'));
        }
    }
}
</script>

<div id="toast" style="position: fixed; top: 24px; right: 24px; z-index: 99999; padding: 16px 24px; border-radius: 12px; font-weight: 700; font-size: 14px; color: white; display: none; align-items: center; gap: 12px; box-shadow: 0 8px 32px rgba(0,0,0,.15); max-width: 400px; transform: translateX(120%); transition: transform .3s ease;">
    <span id="toast-icon" style="font-size: 20px; flex-shrink: 0;"></span>
    <span id="toast-msg" style="flex: 1;"></span>
    <button onclick="closeToast()" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer; padding: 0; line-height: 1; opacity: .8;">&times;</button>
</div>
<script>
    var toastEl = document.getElementById('toast');
    var toastMsg = document.getElementById('toast-msg');
    var toastIcon = document.getElementById('toast-icon');
    var toastTimer;

    function showToast(msg, type) {
        if (toastTimer) clearTimeout(toastTimer);
        toastMsg.textContent = msg;
        toastEl.style.background = type === 'error' ? '#dc2626' : '#16a34a';
        toastIcon.textContent = type === 'error' ? '\u26A0' : '\u2714';
        toastEl.style.display = 'flex';
        setTimeout(function() { toastEl.style.transform = 'translateX(0)'; }, 10);
        toastTimer = setTimeout(closeToast, 4000);
    }

    function closeToast() {
        toastEl.style.transform = 'translateX(120%)';
        setTimeout(function() { toastEl.style.display = 'none'; }, 300);
    }

    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @elseif(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif
</script>
</body>
</html>
