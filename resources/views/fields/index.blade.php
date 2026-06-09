@php
    use App\Models\Field;
    use Carbon\Carbon;

    $selectedSport = request('sport');

    // All fields (unfiltered) — for sport selector modal + sport list
    $allFields = Field::with('owner')->get();

    // Filter displayed fields by sport (preserves controller's nearby filter on $fields)
    $fields = $selectedSport ? $fields->where('type', $selectedSport) : $fields;

    $promoFieldIds = \App\Models\Discount::active()
        ->with('fields')->get()
        ->flatMap(fn($d) => $d->fields->pluck('id'))
        ->unique()->toArray();
    $fields = $fields->sortByDesc(function ($f) use ($promoFieldIds) {
        $hp = in_array($f->id, $promoFieldIds);
        $ft = $f->featured ?? false;
        if ($ft && $hp) return 3;
        if ($ft)        return 2;
        if ($hp)        return 1;
        return 0;
    })->values();

    $sportList = ['Futsal','Badminton','Basket','Voli','Tennis','Golf','Renang','Panahan','Lari','Sepeda','Tinju','Bela Diri','Yoga','Fitness','Hiking','Padel','Baseball','Rugby','Senam'];
    $sportEmoji = [
        'Futsal'=>'⚽','Badminton'=>'🏸','Basket'=>'🏀','Voli'=>'🏐','Tennis'=>'🎾',
        'Golf'=>'🏌️','Renang'=>'🏊','Panahan'=>'🏹','Lari'=>'🏃','Sepeda'=>'🚴',
        'Tinju'=>'🥊','Bela Diri'=>'🥋','Yoga'=>'🧘','Fitness'=>'🏋️','Hiking'=>'🥾',
        'Padel'=>'🎾','Baseball'=>'⚾','Rugby'=>'🏉','Senam'=>'🤸',
    ];
    $uniqueSports = $allFields->pluck('type')->unique()->sort()->values()->toArray();
    $availableSports = array_values(array_unique(array_merge($sportList, $uniqueSports)));
    sort($availableSports);

    $userName = Auth::user()->name ?? 'Pemain';
    $userAvatar = Auth::user()->avatarUrl();
    $currentDate = Carbon::now()->locale('id')->translatedFormat('j F Y');
    $upcomingJoin = isset($upcomingJoin) ? $upcomingJoin : null;
    $myTeams = \App\Models\Matchs::with('field')->where('created_by', Auth::id())->latest()->take(4)->get();
    
    // Sidebar
    $sidebarItems = [
        ['label'=>'Beranda',  'icon'=>asset('assets/images/icons/dashboard.png'), 'href'=>route('dashboard'),    'active'=>true],
        ['label'=>'Aktivitas',  'icon'=>asset('assets/images/icons/aktivitas.png'), 'href'=>route('activity.index'),       'active'=>false],
        ['label'=>'Favorit',  'icon'=>asset('assets/images/icons/favoritmu.png'), 'href'=>route('favorite.index'),                  'active'=>false],
        ['label'=>'Histori',    'icon'=>asset('assets/images/icons/histori.png'),   'href'=>route('history.index'),                  'active'=>false],
        ['label'=>'Cari tim',   'icon'=>asset('assets/images/icons/caritim.png'),   'href'=>route('matches.index'),'active'=>false],
        ['label'=>'Pemesanan',    'icon'=>asset('assets/images/icons/booking.png'),   'href'=>route('booking.index'),                  'active'=>false],
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
    <title>Cari Lapangan – {{ config('app.name', 'Spies Sport') }}</title>
    @vite([
        'resources/css/app.css',
        'resources/css/player-dashboard.css',
        'resources/js/player-dashboard.js',
    ])
</head>
<body class="player-dashboard-page"
      style="--player-dashboard-bg:url('{{ asset('assets/images/bg/bg-login.png') }}');">
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

    {{-- Topbar (same as Skill page) --}}
    <header class="player-dashboard-topbar">
        <div class="player-dashboard-topbar__left">
            <button type="button" class="player-dashboard-topbar__menu" data-sidebar-open><span></span><span></span><span></span></button>
            <div class="player-search-wrapper">
                <label class="player-search" for="fields-search">
                    <span class="player-search__icon">
                        <svg viewBox="0 0 20 20" fill="none"><circle cx="9" cy="9" r="5.75" stroke="currentColor" stroke-width="1.8"/><path d="M13.5 13.5L17 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </span>
                    <input id="fields-search" type="search" placeholder="Cari lapangan...">
                </label>
                <div class="search-suggestions" id="search-suggestions"></div>
            </div>
            @if(!empty($isNearby))
                <a href="{{ route('dashboard') }}" style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; background:white; color:#02025b; border:1.5px solid rgba(0,0,77,.12); border-radius:20px; font-size:13px; font-weight:700; cursor:pointer; white-space:nowrap; text-decoration:none; transition:all .2s;" onmouseover="this.style.borderColor='#EB5436'" onmouseout="this.style.borderColor='rgba(0,0,77,.12)'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span class="hidden sm:inline">Kembali</span>
                    <span class="sm:hidden">Back</span>
                </a>
            @else
            <button onclick="findNearestFields()" style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; background:#EB5436; color:white; border:none; border-radius:20px; font-size:13px; font-weight:700; cursor:pointer; white-space:nowrap; box-shadow:0 4px 12px rgba(235,84,54,.25); transition:all .2s;" onmouseover="this.style.boxShadow='0 6px 20px rgba(235,84,54,.35)'" onmouseout="this.style.boxShadow='0 4px 12px rgba(235,84,54,.25)'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span class="hidden sm:inline">Lapangan Terdekat</span>
                <span class="sm:hidden">Terdekat</span>
            </button>
            @endif
        </div>
        <div class="player-dashboard-topbar__right">
            <div class="player-dashboard-topbar__date">
                <span class="player-inline-icon">
                    <svg viewBox="0 0 24 24" fill="none"><rect x="3.5" y="5.5" width="17" height="15" rx="2.5" stroke="currentColor" stroke-width="1.8"/><path d="M7 3.5V7M17 3.5V7M3.5 9.5H20.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </span>
                <span>{{ $currentDate }}</span>
            </div>
            <a href="javascript:void(0)" onclick="toggleFaqPopup()" class="player-dashboard-topbar__icon" title="Hubungi CS" style="text-decoration: none; color: inherit;">
                <span class="player-inline-icon">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M21 15C21 15.53 20.79 16.04 20.41 16.41C20.04 16.79 19.53 17 19 17H7L3 21V5C3 4.47 3.21 3.96 3.59 3.59C3.96 3.21 4.47 3 5 3H19C19.53 3 20.04 3.21 20.41 3.59C20.79 3.96 21 4.47 21 5V15Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
            </a>
            <div style="position: relative;">
                @include('partials.notification-bell')
            </div>
            <div class="profile-dropdown-wrap">
                <button class="player-profile-pill" id="profile-dropdown-trigger" style="border:none;background:none;cursor:pointer;display:flex;align-items:center;gap:10px;padding:6px 12px 6px 6px;font-family:inherit;border-radius:16px;">
                    <span class="player-profile-pill__avatar" style="width:44px;height:44px;">
                        <img src="{{ $userAvatar }}" alt="Profil" class="player-avatar-image player-avatar-image--profile" onerror="this.src='{{ asset('assets/images/characters/' . (Auth::user()->gender === 'perempuan' ? 'profil2.png' : 'profil1.png')) }}'">
                    </span>
                    <span class="player-profile-pill__name" style="font-size:0.95rem;">{{ $userName }}</span>
                </button>
                <div class="profile-dropdown-menu" id="profile-dropdown-menu">
                    <a href="{{ route('profile.show') }}" class="profile-dropdown-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Profil Saya
                    </a>
                    <div class="profile-dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="profile-dropdown-item" style="color:#dc2626;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <section style="padding: 20px; max-width: 1400px; margin: 0 auto;">

        <style>
            .hero-section {
                display: grid;
                grid-template-columns: 2fr 1fr;
                gap: 24px;
                margin-bottom: 24px;
            }
            .dashboard-header-flex {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 24px;
                flex-wrap: wrap;
                gap: 16px;
            }
            @media (max-width: 1024px) {
                .hero-section {
                    grid-template-columns: 1fr;
                }
            }
            .lvl-card {
                background: white;
                border-radius: 20px;
                padding: 20px;
                box-shadow: 0 4px 20px rgba(0,0,77,.05);
                border: 1px solid rgba(0,0,77,.05);
                display: flex;
                flex-direction: column;
            }
            .lvl-card__head {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 16px;
            }
            .lvl-card__title {
                margin: 0;
                font-size: 15px;
                font-weight: 800;
                color: #02025b;
            }
            .lvl-card__link {
                font-size: 12px;
                color: #666;
                font-weight: 600;
                text-decoration: none;
            }
            .lvl-card__body {
                background: #f8fafc;
                padding: 16px;
                border-radius: 12px;
                margin-bottom: 16px;
            }
            .lvl-card__level-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 8px;
            }
            .lvl-card__level-label {
                font-size: 13px;
                color: #666;
            }
            .lvl-card__level-name {
                font-size: 14px;
                font-weight: 800;
            }
            .lvl-card__track {
                height: 6px;
                background: #e2e8f0;
                border-radius: 6px;
                overflow: hidden;
                margin-bottom: 6px;
            }
            .lvl-card__bar {
                height: 100%;
                border-radius: 6px;
                transition: width .6s ease;
            }
            .lvl-card__poin {
                margin: 0;
                font-size: 10px;
                color: #888;
                text-align: center;
            }
            .lvl-card__tiers {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 12px;
                text-align: center;
            }
            .lvl-card__tier {
                transition: all .2s;
            }
            .lvl-card__tier.is-current {
                transform: scale(1.04);
            }
            .lvl-card__tier:not(.is-earned) {
                opacity: .45;
            }
            .lvl-card__tier-icon {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 6px;
            }
            .lvl-card__tier-icon svg {
                width: 20px;
                height: 20px;
            }
            .lvl-card__tier-name {
                margin: 4px 0 0 0;
                font-size: 11px;
            }
            .lvl-card__tier-range {
                font-size: 9px;
                color: #666;
            }
            @media (max-width: 768px) {
                .dashboard-header-flex {
                    flex-direction: column;
                    align-items: flex-start;
                }
                .hero-content-inner {
                    max-width: 100% !important;
                }
                .lvl-card__tiers {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 10px;
                }
                .lvl-card__tier-icon {
                    width: 36px;
                    height: 36px;
                }
                .lvl-card__tier-icon svg {
                    width: 18px;
                    height: 18px;
                }
                .lvl-card__tier-name {
                    font-size: 10px;
                }
                .lvl-card__tier-range {
                    font-size: 8px;
                }
            }
            @media (max-width: 480px) {
                .lvl-card__tiers {
                    grid-template-columns: 1fr 1fr;
                    gap: 8px;
                }
                .lvl-card {
                    padding: 14px;
                }
                .lvl-card__body {
                    padding: 12px;
                }
                .lvl-card__tier-icon {
                    width: 32px;
                    height: 32px;
                }
                .lvl-card__tier-icon svg {
                    width: 16px;
                    height: 16px;
                }
                .lvl-card__tier-name {
                    font-size: 9px;
                }
                .lvl-card__tier-range {
                    font-size: 8px;
                }
            }
            @media (min-width: 1181px) { .player-sidebar { position: sticky; top: 0; height: 100vh; overflow-y: auto; align-self: flex-start; } }
            .player-sidebar__inner { height: 100%; }
            .player-search-wrapper { position: relative; flex:1; max-width:360px; }
            .player-search { width:100%; margin-left:0; }
            @media (min-width:1024px) { .player-search-wrapper { max-width:480px; } }
            @media (max-width:640px) { .player-search-wrapper { max-width:200px; } }
            .search-suggestions { position:absolute; top:calc(100% + 6px); left:0; right:0; background:#fff; border-radius:14px; box-shadow:0 12px 40px rgba(0,0,77,.15); z-index:100; display:none; max-height:280px; overflow-y:auto; padding:8px 0; }
            .search-suggestions.is-visible { display:block; }
            .search-suggestion-item { display:flex; align-items:center; gap:10px; padding:10px 16px; cursor:pointer; transition:background .15s; font-size:13px; color:#02025b; }
            .search-suggestion-item:hover { background:#f5f5ff; }
            .search-suggestion-item .ss-icon { width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:16px; }
            .search-suggestion-item .ss-text { flex:1; min-width:0; }
            .search-suggestion-item .ss-text strong { font-weight:700; }
            .search-suggestion-item .ss-text small { display:block; font-size:11px; color:#94a3b8; }
            .search-result-overlay { display:none; }
            .search-result-overlay.is-active { display:block; }
            .dashboard-section { transition:opacity .2s; }
            .dashboard-section.is-hidden { display:none !important; }
            .profile-dropdown-wrap { position:relative; }
            .profile-dropdown-menu { position:absolute; top:calc(100% + 8px); right:0; background:#fff; border-radius:14px; box-shadow:0 12px 40px rgba(0,0,77,.15); z-index:200; min-width:180px; padding:8px; display:none; opacity:0; transform:translateY(-6px); transition:opacity .2s ease,transform .2s ease; }
            .profile-dropdown-menu.is-visible { display:block; opacity:1; transform:translateY(0); }
            .profile-dropdown-item { display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:10px; text-decoration:none; color:#02025b; font-size:13px; font-weight:600; transition:background .15s; cursor:pointer; border:none; background:none; width:100%; text-align:left; font-family:inherit; }
            .profile-dropdown-item:hover { background:#f5f5ff; }
            .profile-dropdown-divider { height:1px; background:#e2e8f0; margin:4px 8px; }

            /* ── RESPONSIVE MOBILE (320px–1180px) ── */
            /* Sidebar + main content full-width on mobile */
            @media (max-width: 1180px) {
                .player-dashboard-shell { display: block; }
                .player-dashboard-main { width: 100%; max-width: 100%; padding: 20px 16px 34px; }
                .player-dashboard-main section[style*="max-width: 1400px"] { max-width: 100% !important; padding: 20px 0 !important; margin: 0 !important; }
                #dashboard-above-sections > div:not(.dashboard-header-flex):not([style*="margin-bottom"]) { padding-left: 0 !important; padding-right: 0 !important; }
            }
            @media (max-width: 768px) {
                .hero-section .hero-content-inner { max-width: 100% !important; }
                .hero-bg-card h2 { font-size: 20px !important; }
                .dashboard-header-flex h1 { font-size: 22px !important; }
                .dashboard-header-flex > div { width: 100%; }
                .dashboard-header-flex > div a { width: 100%; text-align: center; }
                .player-dashboard-main section[style*="max-width: 1400px"] { padding: 16px 0 !important; }
            }
            @media (max-width: 640px) {
                .player-dashboard-main section[style*="max-width: 1400px"] { padding: 12px 0 !important; }
                .hero-section > div:first-child { overflow: visible !important; }
                .hero-bg-card { padding: 20px !important; height: auto !important; min-height: 260px; background-size: auto 200px !important; background-position: right -10px bottom !important; }
                .hero-bg-card h2 { font-size: 18px !important; max-width: 60%; }
                #field-card-grid { grid-template-columns: 1fr !important; gap: 16px !important; }
                #field-card-grid .field-card > div[style*="height: 200px"] { height: 160px !important; }
                #field-card-grid .field-card > div[style*="padding: 16px 18px 18px"] { padding: 12px 14px 14px !important; }
                #field-card-grid .field-card h3 { font-size: 16px !important; }
                #field-card-grid .field-card button { font-size: 11px !important; padding: 6px 12px !important; }
                .dashboard-header-flex { margin-bottom: 16px !important; }
                .dashboard-header-flex h1 { font-size: 20px !important; }
                .dashboard-section h2[style*="font-size: 24px"] { font-size: 20px !important; }
                .dashboard-section .lvl-card { padding: 14px !important; }
                .player-dashboard-topbar__left .player-search-wrapper { max-width: 100% !important; }
                .player-dashboard-topbar__left .player-search-wrapper .player-search { margin: 4px 0 !important; }
                .player-dashboard-topbar__left button[onclick*="findNearestFields"],
                .player-dashboard-topbar__left a[href*="nearby"] { font-size: 11px !important; padding: 6px 12px !important; }
                .player-dashboard-topbar__right { flex-wrap: wrap; justify-content: space-between; }
                .player-dashboard-topbar__date { font-size: 0.75rem; padding: 4px 8px; }
                .player-dashboard-topbar__icon { width: 36px; height: 36px; }
                .player-profile-pill { padding: 4px 6px 4px 4px !important; }
                .player-profile-pill__avatar { width: 32px !important; height: 32px !important; }
                .player-profile-pill__name { font-size: 0.8rem !important; }
                .player-dashboard-topbar__menu { width: 36px; height: 36px; }
                .player-dashboard-main { padding: 12px 12px 24px !important; }
                .hero-section { gap: 16px !important; }
                .profile-dropdown-menu { right: 0; left: auto; min-width: 160px; }
                /* Collapse all grids to single column */
                #dashboard-above-sections > div[style*="grid"],
                .dashboard-section > div[style*="grid"],
                .dashboard-section > div > div[style*="grid"],
                div[style*="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr))"],
                div[style*="grid-template-columns: repeat(auto-fit, minmax(260px, 1fr))"],
                div[style*="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr))"] {
                    grid-template-columns: 1fr !important; gap: 16px !important;
                }
            }
            @media (max-width: 480px) {
                .player-dashboard-main { padding: 8px 8px 20px !important; }
                .player-dashboard-main section[style*="max-width: 1400px"] { padding: 8px 0 !important; }
                .hero-bg-card { background-size: auto 160px !important; min-height: 220px; padding: 16px !important; }
                .hero-bg-card h2 { font-size: 16px !important; max-width: 55%; }
                #field-card-grid .field-card > div[style*="height: 200px"] { height: 140px !important; }
                #field-card-grid .field-card > div[style*="padding: 16px 18px 18px"] { padding: 10px 12px 12px !important; }
                #field-card-grid .field-card h3 { font-size: 15px !important; }
                #field-card-grid .field-card p { font-size: 12px !important; }
                .dashboard-header-flex h1 { font-size: 18px !important; }
                .dashboard-section h2[style*="font-size: 24px"] { font-size: 18px !important; }
                .dashboard-section > div[style*="padding: 20px;"] { padding: 14px !important; }
                .dashboard-section .lvl-card__body { padding: 10px !important; }
                .player-dashboard-topbar__right .player-dashboard-topbar__date span:last-child { display: none; }
                .player-dashboard-topbar__right .profile-dropdown-wrap .player-profile-pill .player-profile-pill__name { display: none; }
                .dashboard-section h4 { font-size: 14px !important; }
                #dashboard-above-sections > div[style*="grid"] { gap: 12px !important; }
            }
            @media (max-width: 380px) {
                .player-dashboard-main { padding: 8px 6px 16px !important; }
                .player-dashboard-main section[style*="max-width: 1400px"] { padding: 6px 0 !important; }
                .hero-bg-card { background-size: auto 130px !important; min-height: 180px; padding: 12px !important; }
                .hero-bg-card h2 { font-size: 14px !important; max-width: 55%; }
                .hero-section { gap: 12px !important; }
                #field-card-grid { gap: 12px !important; }
                #field-card-grid .field-card > div[style*="height: 200px"] { height: 120px !important; }
                .dashboard-section > div[style*="padding: 20px;"] { padding: 10px !important; }
            }
        </style>

        <div id="dashboard-above-sections" class="dashboard-section">
        <!-- NEW DASHBOARD HEADER -->
        <div class="dashboard-header-flex">
            <h1 style="font-size: 28px; font-weight: 800; color: #02025b; margin: 0;"></h1>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="javascript:void(0)" onclick="showCreateModal()" style="background: #e11d48; color: white; padding: 10px 20px; border-radius: 8px; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: background .2s; display: inline-block;">
                    Buat Pertandingan Baru
                </a>
            </div>
        </div>

        @if(empty($isNearby))
        <!-- HERO SECTION -->
        <div class="hero-section">
            <!-- Hero Banner -->
            <div style="position: relative; overflow: hidden;">
                <style>
                    .hero-bg-card {
                        background: white;
                        background-image: url('{{ asset('assets/images/characters/hero.png') }}');
                        background-repeat: no-repeat;
                        background-position: right 10px bottom;
                        background-size: auto 340px;
                        border-radius: 20px;
                        padding: 32px;
                        box-shadow: 0 4px 20px rgba(0,0,77,.05);
                        border: 1px solid rgba(0,0,77,.05);
                        height: 340px;
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                    }
                    @media (max-width: 768px) {
                        .hero-bg-card {
                            height: 300px;
                            background-size: auto 240px;
                            background-position: right -15px bottom;
                        }
                    }
                    @media (max-width: 480px) {
                        .hero-bg-card {
                            height: 240px;
                            background-size: auto 180px;
                            background-position: right -20px bottom;
                            padding: 20px;
                        }
                        .hero-bg-card h2 {
                            font-size: 18px !important;
                        }
                    }
                </style>
                <div class="hero-bg-card">
                    <div class="hero-content-inner" style="max-width: 60%;">
                        <h2 style="font-size: 24px; font-weight: 600; color: #02025b; margin: 0 0 20px 0;">Hai {{ Auth::user()->name ?? 'Pecinta Olahraga' }}! Siap bikin keringat jadi teman?</h2>
                        
                        @if($pendingReviewBooking)
                        <div onclick="openDashReviewWithData({{ $pendingReviewBooking->field_id }}, {{ $pendingReviewBooking->id }}, '{{ $pendingReviewBooking->field->name }}', '{{ \Carbon\Carbon::parse($pendingReviewBooking->date)->locale('id')->translatedFormat('j M') }} - {{ \Carbon\Carbon::parse($pendingReviewBooking->start_time)->format('H:i') }} WIB')" style="cursor:pointer; background: #FDF9ED; padding: 16px 20px; border-radius: 14px; display: flex; gap: 14px; align-items: center; box-shadow: 0 4px 12px rgba(0,0,0,.05); width: 100%; max-width: 360px; transition:all .2s;" onmouseover="this.style.boxShadow='0 6px 20px rgba(0,0,0,.1)'" onmouseout="this.style.boxShadow='0 4px 12px rgba(0,0,0,.05)'">
                            <div style="width:64px; height:64px; overflow:hidden; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
                                <img src="{{ asset('assets/images/characters/review.png') }}" alt="Review" style="width:72px; height:72px; object-fit:contain;">
                            </div>
                            <div>
                                <p style="margin: 0; font-weight: 800; color: #02025b; font-size: 14px;">Gimana permainannya?</p>
                                <p style="margin: 2px 0 0; font-size: 12px; font-weight: 600; color: #02025b;">{{ $pendingReviewBooking->field->name }}</p>
                                <p style="margin: 1px 0 0; font-size: 11px; color: #666;">{{ $pendingReviewBooking->field->location ?? 'Kota Malang' }}</p>
                                <p style="margin: 1px 0 0; font-size: 10px; color: #999;">{{ \Carbon\Carbon::parse($pendingReviewBooking->date)->locale('id')->translatedFormat('j M') }} - {{ \Carbon\Carbon::parse($pendingReviewBooking->start_time)->format('H:i') }} WIB</p>
                                <div style="display: flex; gap: 2px; margin-top: 3px;">
                                    <span style="font-size:12px; color:#f59e0b;">★</span>
                                    <span style="font-size:12px; color:#f59e0b;">★</span>
                                    <span style="font-size:12px; color:#f59e0b;">★</span>
                                    <span style="font-size:12px; color:#f59e0b;">★</span>
                                    <span style="font-size:12px; color:#f59e0b;">★</span>
                                </div>
                            </div>
                        </div>
                        @elseif($recommendationField)
                        <div onclick="window.location.href='{{ route('booking.show', $recommendationField->id) }}'" style="cursor:pointer; background: #FDF9ED; padding: 16px 20px; border-radius: 14px; display: flex; gap: 14px; align-items: center; box-shadow: 0 4px 12px rgba(0,0,0,.05); width: 100%; max-width: 360px; transition:all .2s;" onmouseover="this.style.boxShadow='0 6px 20px rgba(0,0,0,.1)'" onmouseout="this.style.boxShadow='0 4px 12px rgba(0,0,0,.05)'">
                            <div style="width:64px; height:64px; overflow:hidden; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
                                <img src="{{ asset('assets/images/characters/review.png') }}" alt="Rekomendasi" style="width:54px; height:54px; object-fit:contain;">
                            </div>
                            <div>
                                <p style="margin: 0; font-weight: 800; color: #02025b; font-size: 14px;">Ayo main disini!</p>
                                <p style="margin: 2px 0 0; font-size: 12px; font-weight: 600; color: #02025b;">{{ $recommendationField->name }}</p>
                                <p style="margin: 1px 0 0; font-size: 11px; color: #666;">{{ $recommendationField->location ?? 'Kota Malang' }}</p>
                                <p style="margin: 2px 0 0; font-size: 12px; color: #f59e0b; font-weight: 600;">⭐ {{ number_format($recommendationField->rating, 1) }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Notifikasi -->
            @php $notifItems = auth()->user()->notifications()->orderBy('created_at', 'desc')->take(5)->get(); @endphp
            <div style="background: white; border-radius: 20px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,77,.05); border: 1px solid rgba(0,0,77,.05); display: flex; flex-direction: column;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: #02025b;">Notifikasi</h3>
                    <a href="{{ route('notifications.index') }}" style="font-size: 13px; color: #666; text-decoration: none; font-weight: 600;">Lihat semua</a>
                </div>
                <div style="display: flex; flex-direction: column; gap: 8px; overflow-y: auto; max-height: 280px;">
                    @forelse($notifItems as $notif)
                    @php
                        $d = $notif->data;
                        $isUnread = is_null($notif->read_at);
                        $notifType = $d['type'] ?? '';
                        $matchId = $d['match_id'] ?? null;
                        $bookingId = $d['booking_id'] ?? null;
                        $matchTitle = $d['match_title'] ?? '';
                        $userName = $d['user_name'] ?? '';
                        $amount = $d['amount'] ?? 0;
                        $fieldName = $d['field_name'] ?? '';
                        $notifLink = match($notifType) {
                            'booking_payment_received', 'booking_confirmed' => $bookingId ? route('booking.detail', $bookingId) : null,
                            default => $matchId ? route('matches.show', $matchId) : null,
                        };
                        $mapsLink = $d['maps_link'] ?? null;
                        if (!$mapsLink && $bookingId && in_array($notifType, ['booking_confirmed', 'booking_payment_received'])) {
                            $mapsLink = optional(optional(\App\Models\Booking::find($bookingId))->field)->maps_link;
                        }
                    @endphp
                    <div style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; background: {{ $isUnread ? '#f0f7ff' : 'transparent' }}; border-radius: 10px; {{ $isUnread ? 'border: 1px solid #dbeafe;' : '' }}">
                        @if($notifType === 'payment_claimed')
                            <div style="width: 32px; height: 32px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;">💰</div>
                        @elseif($notifType === 'booking_confirmed')
                            <div style="width: 32px; height: 32px; background: #dbeafe; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">📍</div>
                        @elseif($notifType === 'community_joined')
                            <div style="width: 32px; height: 32px; background: #d1fae5; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;">👥</div>
                        @else
                            <div style="width: 32px; height: 32px; background: #bbf7d0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;">✅</div>
                        @endif
                        <div style="flex: 1; min-width: 0;">
                            <p style="margin: 0; font-size: 13px; color: #1f2937; line-height: 1.3;">
                                @if($notifType === 'payment_claimed')
                                    <strong>{{ $userName }}</strong> mengklaim sudah bayar <strong>{{ $matchTitle }}</strong>
                                @elseif($notifType === 'payment_confirmed')
                                    Pembayaran untuk <strong>{{ $matchTitle }}</strong> dikonfirmasi
                                @elseif($notifType === 'booking_payment_received')
                                    Pembayaran untuk <strong>{{ $fieldName }}</strong> diterima, menunggu konfirmasi owner
                                @elseif($notifType === 'booking_confirmed')
                                    Booking <strong>{{ $fieldName }}</strong> telah dikonfirmasi
                                    @if(!empty($mapsLink))
                                        &nbsp;<a href="{{ $mapsLink }}" target="_blank" rel="noopener noreferrer" style="color:#3b82f6;font-weight:600;text-decoration:none;white-space:nowrap;" title="Buka Google Maps"><svg viewBox="0 0 24 24" fill="none" width="15" height="15" style="vertical-align:middle;"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="#3b82f6"/><circle cx="12" cy="9" r="2.5" fill="#fff"/></svg></a>
                                    @endif
                                @elseif($notifType === 'community_joined')
                                    <strong>{{ $userName }}</strong> bergabung ke komunitas <strong>{{ $d['community_name'] ?? '' }}</strong>
                                @else
                                    {{ $d['message'] ?? '' }}
                                @endif
                            </p>
                            <p style="margin: 2px 0 0 0; font-size: 11px; color: #9ca3af;{{ $isUnread ? ' font-weight: 600;' : '' }}">{{ \Carbon\Carbon::parse($notif->created_at)->locale('id')->diffForHumans() }}</p>
                        </div>
                        @if($notifLink)
                            <a href="{{ $notifLink }}" style="flex-shrink: 0; color: #02025b;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 16 16 12 12 8"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                            </a>
                        @endif
                    </div>
                    @empty
                    <p style="text-align: center; color: #999; font-size: 13px; padding: 20px 0;">Belum ada notifikasi.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 4 WIDGETS -->
        @php
            $nowTs = \Carbon\Carbon::now()->timestamp;
            $nearestUpcoming = null;
            $nearestTs = null;
            if (isset($upcomingBooking) && $upcomingBooking) {
                $d = \Carbon\Carbon::parse($upcomingBooking->date->format('Y-m-d') . ' ' . $upcomingBooking->start_time);
                if ($d->timestamp > $nowTs) {
                    $nearestUpcoming = (object)[
                        'type' => 'booking',
                        'label' => $upcomingBooking->field->name ?? 'Booking',
                        'desc' => $upcomingBooking->field->location ?? '',
                        'date' => $upcomingBooking->date,
                        'time' => $upcomingBooking->start_time,
                        'endTime' => $upcomingBooking->end_time,
                        'detailUrl' => route('booking.detail', $upcomingBooking->id),
                        'ts' => $d->timestamp,
                    ];
                    $nearestTs = $d->timestamp;
                }
            }
            if (isset($upcomingJoin) && $upcomingJoin && $upcomingJoin->match) {
                $m = $upcomingJoin->match;
                $dt = \Carbon\Carbon::parse($m->date . ' ' . $m->time);
                $mt = $dt->timestamp;
                if ($mt > $nowTs && (!$nearestUpcoming || $mt < $nearestTs)) {
                    $nearestUpcoming = (object)[
                        'type' => 'match',
                        'label' => $m->title,
                        'desc' => ($m->field->name ?? 'Lapangan') . ($m->field->location ? ' - ' . $m->field->location : ''),
                        'date' => $m->date,
                        'time' => $m->time,
                        'endTime' => null,
                        'detailUrl' => route('matches.show', $m->id),
                        'ts' => $mt,
                    ];
                    $nearestTs = $mt;
                }
            }
        @endphp
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 32px;">
            <!-- Upcoming Match -->
            <div style="background: white; border-radius: 20px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,77,.05); border: 1px solid rgba(0,0,77,.05); display: flex; flex-direction: column;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#02025b" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <h4 style="margin: 0; font-size: 15px; font-weight: 800; color: #02025b;">Mendatang</h4>
                    </div>
                    @if($nearestUpcoming)
                        <span style="background: #bbf7d0; color: #166534; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700;">{{ \Carbon\Carbon::parse($nearestUpcoming->date)->format('d M') }}</span>
                    @else
                        <span style="background: #bbf7d0; color: #166534; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700;">-</span>
                    @endif
                </div>
                @if($nearestUpcoming)
                    <h3 style="margin: 0 0 4px 0; font-size: 18px; font-weight: 800; color: #02025b;">{{ $nearestUpcoming->label }}</h3>
                    <p style="margin: 0 0 4px 0; font-size: 13px; color: #666;">{{ $nearestUpcoming->desc }}</p>
                    <p style="margin: 0 0 12px 0; font-size: 12px; color: #888;">
                        {{ \Carbon\Carbon::parse($nearestUpcoming->date)->format('d M Y') }},
                        {{ substr($nearestUpcoming->time, 0, 5) }}
                        @if($nearestUpcoming->endTime)
                            - {{ substr($nearestUpcoming->endTime, 0, 5) }}
                        @else
                            WIB
                        @endif
                    </p>
                    <div id="upcoming-countdown" style="display: flex; gap: 12px; margin-bottom: 16px;" data-target="{{ $nearestUpcoming->ts }}">
                        <div style="text-align: center;"><span style="display: block; font-size: 24px; font-weight: 900; color: #02025b;" id="cd-days">00</span><span style="font-size: 11px; color: #888;">Hari</span></div>
                        <div style="text-align: center;"><span style="display: block; font-size: 24px; font-weight: 900; color: #02025b;" id="cd-hours">00</span><span style="font-size: 11px; color: #888;">Jam</span></div>
                        <div style="text-align: center;"><span style="display: block; font-size: 24px; font-weight: 900; color: #02025b;" id="cd-minutes">00</span><span style="font-size: 11px; color: #888;">Menit</span></div>
                        <div style="text-align: center;"><span style="display: block; font-size: 24px; font-weight: 900; color: #02025b;" id="cd-seconds">00</span><span style="font-size: 11px; color: #888;">Detik</span></div>
                    </div>
                    <a href="{{ $nearestUpcoming->detailUrl }}" style="margin-top: auto; background: #e11d48; color: white; border: none; padding: 10px; border-radius: 8px; font-weight: 700; cursor: pointer; text-align: center; text-decoration: none;">Lihat Detail</a>
                @else
                    <div style="flex:1; display:flex; flex-direction:column; justify-content:center; align-items:center; gap:12px; text-align:center;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#02025b" stroke-width="1.5" opacity=".3"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <p style="margin:0; font-size:14px; color:#888;">Belum ada jadwal mendatang.</p>
                        <a href="{{ route('dashboard') }}" style="background:#02025b; color:white; padding:10px 20px; border-radius:10px; font-weight:700; font-size:13px; text-decoration:none;">Booking Sekarang</a>
                    </div>
                @endif
            </div>

            <!-- Rekomendasi -->
            @php
                $widgetRecItems = collect();
                $seenIds = [];

                // 1. Promo items — fields with active discounts
                $promoFields = \App\Models\Field::where('is_available', true)
                    ->whereHas('discounts', fn($q) => $q->active())
                    ->with(['discounts' => fn($q) => $q->active()])
                    ->inRandomOrder()
                    ->take(3)
                    ->get();

                foreach ($promoFields as $f) {
                    $discount = $f->discounts->first();
                    $badgeText = 'Promo';
                    if ($discount) {
                        $badgeText = $discount->type === 'percentage'
                            ? "Promo {$discount->value}%"
                            : 'Diskon Rp' . number_format($discount->value, 0, ',', '.');
                    }
                    $widgetRecItems->push((object)[
                        'badgeType' => 'promo',
                        'badgeText' => $badgeText,
                        'name' => $f->name,
                        'location' => $f->location,
                        'url' => route('booking.show', $f->id),
                    ]);
                    $seenIds[] = $f->id;
                }

                // 2. Recommended based on user activity
                if ($widgetRecItems->count() < 3) {
                    $uSports = \App\Models\Booking::where('user_id', Auth::id())
                        ->whereIn('status', [\App\Enums\BookingStatus::CONFIRMED, \App\Enums\BookingStatus::COMPLETED])
                        ->with('field')
                        ->get()
                        ->pluck('field.type')
                        ->filter()
                        ->unique()
                        ->values()
                        ->toArray();

                    if (empty($uSports)) {
                        $uSports = Auth::user()->sport_preference
                            ? array_map('trim', explode(',', Auth::user()->sport_preference))
                            : [];
                    }

                    if (!empty($uSports)) {
                        $recFields = \App\Models\Field::where('is_available', true)
                            ->whereNotIn('id', $seenIds)
                            ->whereIn('type', $uSports)
                            ->orderBy('rating', 'desc')
                            ->take(3 - $widgetRecItems->count())
                            ->get();

                        foreach ($recFields as $f) {
                            $widgetRecItems->push((object)[
                                'badgeType' => 'recommended',
                                'badgeText' => 'Recommended For You',
                                'name' => $f->name,
                                'location' => $f->location,
                                'url' => route('booking.show', $f->id),
                            ]);
                            $seenIds[] = $f->id;
                        }
                    }
                }

                // 3. Popular items (most booked)
                if ($widgetRecItems->count() < 3) {
                    $popFields = \App\Models\Field::withCount('bookings')
                        ->where('is_available', true)
                        ->whereNotIn('id', $seenIds)
                        ->orderBy('bookings_count', 'desc')
                        ->take(3 - $widgetRecItems->count())
                        ->get();

                    foreach ($popFields as $f) {
                        $widgetRecItems->push((object)[
                            'badgeType' => 'popular',
                            'badgeText' => 'Popular Choice',
                            'name' => $f->name,
                            'location' => $f->location,
                            'url' => route('booking.show', $f->id),
                        ]);
                        $seenIds[] = $f->id;
                    }
                }
            @endphp
            <div style="background: white; border-radius: 20px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,77,.05); border: 1px solid rgba(0,0,77,.05); display: flex; flex-direction: column;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="#e11d48" stroke="none"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <h4 style="margin: 0; font-size: 15px; font-weight: 800; color: #02025b;">Rekomendasi</h4>
                    </div>
                    <a href="{{ route('recommendation.index') }}" style="font-size: 12px; color: #666; font-weight: 600; text-decoration: none;">Lihat semua</a>
                </div>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @forelse($widgetRecItems as $item)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 8px; border-bottom: 1px solid rgba(0,0,77,.05);">
                            <div>
                                @if($item->badgeType === 'promo')
                                    <span style="background: #bbf7d0; color: #166534; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 800;">🟢 {{ $item->badgeText }}</span>
                                @elseif($item->badgeType === 'recommended')
                                    <span style="background: #dbeafe; color: #1e40af; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 800;">⭐ {{ $item->badgeText }}</span>
                                @else
                                    <span style="background: #fee2e2; color: #e11d48; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 800;">🔥 {{ $item->badgeText }}</span>
                                @endif
                                <span style="font-size: 14px; font-weight: 800; color: #02025b; margin-left: 6px;">{{ $item->name }}</span>
                                <p style="margin: 4px 0 0 0; font-size: 11px; color: #666;">{{ $item->location ?: '' }}</p>
                            </div>
                            <a href="{{ $item->url }}" style="color: #02025b;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 16 16 12 12 8"/><line x1="8" y1="12" x2="16" y2="12"/></svg></a>
                        </div>
                    @empty
                        <p style="margin: 0; font-size: 13px; color: #888;">Tidak ada rekomendasi.</p>
                    @endforelse
                </div>
            </div>

            @if($favoriteFields->isNotEmpty())
            <!-- Lapangan Favorit -->
            <div style="background: white; border-radius: 20px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,77,.05); border: 1px solid rgba(0,0,77,.05); display: flex; flex-direction: column;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="#e11d48"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        <h4 style="margin: 0; font-size: 15px; font-weight: 800; color: #02025b;">Lapangan Favorit</h4>
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach($favoriteFields->take(3) as $ff)
                        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; padding-bottom: 8px; border-bottom: 1px solid rgba(0,0,77,.05);">
                            <div style="min-width:0; overflow:hidden;">
                                <span style="font-size: 14px; font-weight: 800; color: #02025b; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $ff->field->name ?? '' }}</span>
                                <p style="margin: 4px 0 0 0; font-size: 11px; color: #666; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $ff->field->location ?? '' }}</p>
                            </div>
                            <a href="{{ route('booking.show', $ff->field_id) }}" style="flex-shrink:0; background: #bbf7d0; color: #166534; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; text-decoration: none;">Pesan Cepat</a>
                        </div>
                    @endforeach
                </div>
                @if($favoriteFields->count() >= 4)
                <a href="{{ route('favorite.index') }}" style="margin-top: auto; font-size: 13px; color: #666; font-weight: 600; text-decoration: none; text-align: right;">Lihat semua ></a>
                @endif
            </div>
            @endif

            <!-- Level Pemain -->
            @php
                $_bookings = \App\Models\Booking::where('user_id', Auth::id())
                    ->whereIn('status', ['selesai', 'confirmed', 'pending'])
                    ->count();
                $_matches = \Illuminate\Support\Facades\DB::table('match_players')
                    ->where('user_id', Auth::id())
                    ->count();
                $_reviews = \App\Models\Review::where('user_id', Auth::id())->count();
                $_pts = ($_bookings * 1) + ($_matches * 2) + ($_reviews * 3);
                $_tiers = [
                    ['name' => 'Pemula', 'min' => 0, 'max' => 5,  'earn' => 1,  'color' => '#6b7280', 'bg' => '#f3f4f6'],
                    ['name' => 'Aktif',  'min' => 6, 'max' => 20, 'earn' => 6,  'color' => '#1d6fcf', 'bg' => '#eff6ff'],
                    ['name' => 'Pro',    'min' => 21,'max' => 999,'earn' => 21, 'color' => '#7c3aed', 'bg' => '#f5f3ff'],
                ];
                $_cur = $_tiers[0];
                $_nxt = $_tiers[1];
                foreach ($_tiers as $i => $t) {
                    if ($_pts >= $t['earn']) { $_cur = $t; $_nxt = $_tiers[$i + 1] ?? null; }
                }
                $_pct = 100;
                if ($_nxt) {
                    $_tierRange = $_cur['max'] - $_cur['min'];
                    $_tierProgress = max(0, $_pts - $_cur['min']);
                    $_pct = $_tierRange > 0 ? min(100, round(($_tierProgress / $_tierRange) * 100)) : 100;
                }
                $_ptsToNext = $_nxt ? max(0, $_nxt['earn'] - $_pts) : 0;
            @endphp
            <div class="lvl-card">
                <div class="lvl-card__head">
                    <h4 class="lvl-card__title">Level Pemain</h4>
                    <a href="{{ route('skill.index') }}" class="lvl-card__link">Detail</a>
                </div>

                <div class="lvl-card__body">
                    <div class="lvl-card__level-row">
                        <span class="lvl-card__level-label">Level</span>
                        <span class="lvl-card__level-name" style="color:{{ $_cur['color'] }}">{{ $_cur['name'] }}</span>
                    </div>
                    <div class="lvl-card__track">
                        <div class="lvl-card__bar" style="width:{{ $_pct }}%;background:{{ $_nxt ? $_nxt['color'] : $_cur['color'] }}"></div>
                    </div>
                    <p class="lvl-card__poin">
                        {{ $_pts }} Poin
                        @if($_nxt)· Butuh {{ $_ptsToNext }} poin lagi ke {{ $_nxt['name'] }}@endif
                    </p>
                </div>

                <div class="lvl-card__tiers">
                    @foreach($_tiers as $t)
                    @php $earned = $_pts >= $t['earn']; $isCur = $t['name'] === $_cur['name']; @endphp
                    <div class="lvl-card__tier {{ $earned ? 'is-earned' : '' }} {{ $isCur ? 'is-current' : '' }}" style="{{ $isCur ? '--tier-clr:' . $t['color'] : '' }}">
                        <div class="lvl-card__tier-icon" style="background:{{ $earned ? $t['color'] : '#e2e8f0' }}">
                            @if($loop->first)
                            <svg viewBox="0 0 24 24" fill="{{ $earned ? '#fff' : '#94a3b8' }}"><path d="M12 22V12M12 12C12 12 7 11 5 7C3 3 7 2 9 3C11 4 12 7 12 7M12 12C12 12 17 10 18 6C19 2 15 2 13 3C11 4 12 7 12 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @elseif($loop->iteration === 2)
                            <svg viewBox="0 0 24 24" fill="{{ $earned ? '#fff' : '#94a3b8' }}"><path d="M13 2L3 14H12L11 22L21 10H12L13 2Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"/></svg>
                            @else
                            <svg viewBox="0 0 24 24" fill="{{ $earned ? '#fff' : '#94a3b8' }}"><path d="M8 21H16M12 17V21M7 4H17V11C17 14.314 14.761 17 12 17C9.239 17 7 14.314 7 11V4Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 6H4C4 6 3 10 6 11M17 6H20C20 6 21 10 18 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @endif
                        </div>
                        <p class="lvl-card__tier-name" style="color:{{ $earned ? $t['color'] : '#02025b' }};font-weight:{{ $isCur ? 800 : 600 }}">{{ $t['name'] }}</p>
                        <span class="lvl-card__tier-range">{{ $t['min'] === 0 ? '0' : $t['min'] }}-{{ $t['max'] >= 999 ? '∞' : $t['max'] }} Poin</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        @if(isset($recommendedCommunities) && $recommendedCommunities->isNotEmpty())
        <!-- Komunitas Rekomendasi -->
        <div style="margin-bottom: 36px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 style="font-size: 24px; font-weight: 800; color: #02025b; margin: 0;">Komunitas</h2>
                <a href="{{ route('matches.index') }}" style="font-size: 13px; color: #666; font-weight: 600; text-decoration: none;">Cari Komunitas</a>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px;">
                @foreach($recommendedCommunities as $rc)
                <div style="background: white; border-radius: 16px; padding: 18px; box-shadow: 0 4px 20px rgba(0,0,77,.05); border: 1px solid rgba(0,0,77,.06); display: flex; gap: 14px; transition: all .3s ease;">
                    <div style="flex-shrink: 0; width: 56px; height: 56px; border-radius: 12px; overflow: hidden; background: #f1f5f9;">
                        @if($rc->photo)
                            <img src="{{ $rc->photo }}" alt="{{ $rc->name }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#059669,#10b981);color:white;font-weight:800;font-size:18px;">{{ mb_substr($rc->name, 0, 1) }}</div>
                        @endif
                    </div>
                    <div style="flex:1;min-width:0;">
                        <h4 style="margin:0 0 2px;font-size:15px;font-weight:800;color:#02025b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $rc->name }}</h4>
                        <div style="display:flex;gap:8px;align-items:center;margin-bottom:4px;">
                            <span style="font-size:11px;color:#059669;font-weight:700;background:#ecfdf5;padding:1px 6px;border-radius:4px;">{{ $rc->sport_category }}</span>
                            <span style="font-size:11px;color:#94a3b8;">{{ $rc->city ?: '' }}</span>
                        </div>
                        <p style="margin:0 0 6px;font-size:12px;color:#64748b;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $rc->description ?: '' }}</p>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="font-size:11px;color:#94a3b8;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom;margin-right:2px;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                {{ $rc->members_count ?? $rc->members->count() ?? 0 }} Anggota
                            </span>
                            @php
                                $rcIsCreator = $rc->created_by === (Auth::id() ?? 0);
                                $rcIsMember = in_array($rc->id, $myCommunityIds ?? []);
                            @endphp
                            @if($rcIsCreator)
                                <a href="{{ route('matches.index') }}" style="margin-left:auto;font-size:11px;font-weight:700;color:#059669;">Kelola</a>
                            @elseif($rcIsMember)
                                <a href="{{ $rc->whatsapp_link }}" target="_blank" rel="noopener noreferrer" style="margin-left:auto;font-size:11px;font-weight:700;color:#059669;">WhatsApp</a>
                            @else
                                <button onclick="joinDashCommunity({{ $rc->id }}, this)" style="margin-left:auto;background:none;border:none;padding:0;font-size:11px;font-weight:700;color:#059669;cursor:pointer;">Gabung</button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Tim Saya -->
        <div style="margin-bottom: 40px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 style="font-size: 24px; font-weight: 800; color: #02025b; margin: 0;">Tim Saya</h2>
                <a href="{{ route('matches.myTeams') }}" style="font-size: 13px; color: #666; font-weight: 600; text-decoration: none;">Lihat semua</a>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
                @forelse($myTeams as $tm)
                <a href="{{ route('matches.show', $tm->id) }}" style="text-decoration: none; color: inherit; display: block; background: white; border-radius: 16px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,77,.05); border: 1px solid rgba(0,0,77,.05); transition: all .3s ease;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                        <h4 style="margin: 0; font-size: 16px; font-weight: 800; color: #02025b;">{{ $tm->title }}</h4>
                        @if($tm->isPublic())
                            <span style="background: #dbeafe; color: #1e40af; padding: 2px 8px; border-radius: 6px; font-size: 10px; font-weight: 700;">Publik</span>
                        @else
                            <span style="background: #f3e8ff; color: #6b21a8; padding: 2px 8px; border-radius: 6px; font-size: 10px; font-weight: 700;">Pribadi</span>
                        @endif
                    </div>
                    <p style="margin: 0 0 6px 0; font-size: 13px; color: #666;">
                        <span style="display: inline-flex; align-items: center; gap: 4px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3.5" y="5.5" width="17" height="15" rx="2.5"/><path d="M7 3.5V7M17 3.5V7M3.5 9.5H20.5"/></svg>
                            {{ \Carbon\Carbon::parse($tm->date)->locale('id')->translatedFormat('j F Y') }}
                        </span>
                        <span style="margin-left: 12px; display: inline-flex; align-items: center; gap: 4px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $tm->time)->format('H:i') }} WIB
                        </span>
                    </p>
                    <p style="margin: 0; font-size: 13px; color: #666;">
                        <span style="display: inline-flex; align-items: center; gap: 4px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            {{ $tm->field->name ?? 'Lapangan' }}
                        </span>
                    </p>
                    <div style="margin-top: 14px; padding-top: 14px; border-top: 1px solid rgba(0,0,77,.06); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 13px; color: #02025b; font-weight: 700;">
                            <span style="color: #e11d48;">{{ $tm->participantEntries->count() }}</span> / {{ $tm->max_player }} pemain
                        </span>
                        <span style="background: #02025b; color: #fff; padding: 4px 12px; border-radius: 8px; font-size: 11px; font-weight: 700;">Detail</span>
                    </div>
                </a>
                @empty
                <div style="grid-column:1/-1; text-align:center; padding:32px 20px; background:#f8fafc; border-radius:16px;">
                    <p style="margin:0 0 12px; font-size:14px; color:#888;">Belum punya tim. Buat tim pertama kamu!</p>
                    <a href="{{ route('matches.create') }}" style="display:inline-block; background:#e11d48; color:white; padding:10px 24px; border-radius:10px; font-weight:700; font-size:13px; text-decoration:none;">Buat Pertandingan Baru</a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pesan Lagi -->
        <div style="margin-bottom: 40px;">
            <h2 style="font-size: 24px; font-weight: 800; color: #02025b; margin-bottom: 16px;">Pesan lagi</h2>
            <div style="background: white; border-radius: 20px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,77,.05); border: 1px solid rgba(0,0,77,.05);">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px;">
                    @forelse($pesanLagiFields as $plf)
                    <a href="{{ route('booking.show', $plf->id) }}" style="text-decoration: none; color: inherit; display: block;">
                        <div style="border-radius: 12px; overflow: hidden; height: 160px; margin-bottom: 12px; position: relative;">
                            <span style="position: absolute; top: 10px; left: 10px; background: #02025b; color: white; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 700; z-index: 2;">Sebelumnya</span>
                            <img src="{{ $plf->image_url }}" alt="{{ $plf->name }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'">
                        </div>
                        <h4 style="margin: 0 0 4px 0; font-size: 16px; font-weight: 800; color: #02025b;">{{ $plf->name }}</h4>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span style="font-size: 12px; font-weight: 600; color: #666;">{{ $plf->location ?: 'Lokasi tidak tersedia' }}</span>
                        </div>
                    </a>
                    @empty
                    <div style="grid-column:1/-1; text-align:center; padding:32px 20px;">
                        <p style="margin:0 0 12px; font-size:14px; color:#888;">Belum ada riwayat booking. Yuk booking lapangan!</p>
                        <a href="{{ route('dashboard') }}#lapangan" style="display:inline-block; background:#02025b; color:white; padding:10px 24px; border-radius:10px; font-weight:700; font-size:13px; text-decoration:none;">Cari Lapangan</a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif
        </div>{{-- /dashboard-above-sections --}}

        <!-- LAPANGAN TERSEDIA (Original Section) -->
        <div id="lapangan-section-header" class="dashboard-section" style="margin-bottom: 30px;">
            <h2 style="font-size: 24px; font-weight: 800; color: #02025b; margin-bottom: 10px; display:flex; align-items:center; gap:8px;">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none">
                    <rect x="3" y="8" width="18" height="11" rx="2" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M7 8V5.5M12 8V5.5M17 8V5.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M3 13H21" stroke="currentColor" stroke-width="1.8"/>
                </svg>
                <span>{{ !empty($isNearby) ? 'Lapangan Terdekat' : 'Lapangan Tersedia' }}</span>
            </h2>
            @if(!empty($isNearby))
            <div style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; margin-bottom: 12px;">
                <span style="font-size: 14px;">📍</span>
                <span style="font-size: 12px; font-weight: 700; color: #92400e;">Menampilkan lapangan terdekat berdasarkan {{ request('lat') && request('lng') ? 'lokasi Anda' : 'kota ' . e(Auth::user()->city ?? '') }}</span>
            </div>
            @endif
            @if($selectedSport)
            <div style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; background: #eef2ff; border: 1px solid #c7d2fe; border-radius: 10px; margin-bottom: 8px;">
                <span style="font-size: 16px;">
                    @php
                        $filterEmoji = match($selectedSport) {
                            'Futsal' => '⚽', 'Badminton' => '🏸', 'Basket' => '🏀',
                            'Voli' => '🏐', 'Tennis' => '🎾', 'Golf' => '🏌️',
                            'Renang' => '🏊', 'Panahan' => '🏹', 'Lari' => '🏃',
                            'Sepeda' => '🚴', 'Tinju' => '🥊', 'Bela Diri' => '🥋',
                            'Yoga' => '🧘', 'Fitness' => '🏋️', 'Hiking' => '🥾',
                            'Padel' => '🎾', 'Baseball' => '⚾', 'Rugby' => '🏉',
                            'Senam' => '🤸', default => '🏆',
                        };
                    @endphp
                    {{ $filterEmoji }}
                </span>
                <span style="font-size: 13px; font-weight: 700; color: #4338ca;">{{ $selectedSport }}</span>
                <a href="{{ route('dashboard') }}" style="font-size: 11px; font-weight: 600; color: #6366f1; text-decoration: none; margin-left: 4px; hover:text-decoration: underline;">&times; Hapus filter</a>
            </div>
            @endif
            <p style="color: #666;">Pilih lapangan yang ingin kamu booking</p>
        </div>

        @if(!empty($nearbyMessage))
        <div style="display:flex; align-items:center; gap:12px; padding:16px 20px; background:#fffbeb; border:1px solid #fde68a; border-radius:14px; margin-bottom:20px;">
            <span style="font-size:20px;">📍</span>
            <div style="flex:1;">
                <p style="margin:0; font-size:13px; font-weight:700; color:#92400e;">{{ $nearbyMessage }}</p>
                @if(str_contains($nearbyMessage, 'isi kota'))
                    <a href="{{ route('profile.edit') }}" style="font-size:12px; font-weight:600; color:#EB5436; text-decoration:underline; margin-top:4px; display:inline-block;">Isi kota di Edit Profil &rarr;</a>
                @endif
            </div>
            <button onclick="this.parentElement.remove()" style="background:none; border:none; font-size:18px; cursor:pointer; color:#92400e; padding:0; line-height:1;">&times;</button>
        </div>
        @endif

        @if($fields->isEmpty())
        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px;">
            <h3 style="font-size: 20px; color: #001a4d; margin-bottom: 10px;">{{ !empty($isNearby) ? 'Tidak Ada Lapangan Terdekat' : 'Belum Ada Lapangan' }}</h3>
            <p style="color: #666;">{{ !empty($isNearby) ? 'Tidak ditemukan lapangan di sekitar kotamu. Coba ubah kota di Edit Profil.' : 'Tidak ada lapangan yang tersedia saat ini.' }}</p>
            @if(!empty($isNearby))
            <a href="{{ route('dashboard') }}" style="display:inline-block; margin-top:12px; padding:10px 24px; background:#02025b; color:white; border-radius:10px; font-size:13px; font-weight:700; text-decoration:none;">&larr; Tampilkan Semua Lapangan</a>
            @endif
        </div>
        @else
        <div id="field-card-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px;">
            @foreach($fields as $field)
            @php $isFav = in_array($field->id, $favoriteIds ?? []); @endphp
            @php
                $hasPromo = $field->hasActivePromo();
                $hasReviews = ($field->review_count ?? 0) > 0;
                $_searchText = strtolower(($field->name ?? '') . ' ' . ($field->location ?? '') . ' ' . ($field->type ?? ''));
            @endphp
            <div class="field-card" data-search="{{ $_searchText }}" style="text-decoration: none; color: inherit; transition: all 0.3s ease; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: 100%; display: flex; flex-direction: column; cursor:pointer;" onclick="window.location.href='{{ route('booking.show', array_filter(['field' => $field->id, 'sport' => $selectedSport])) }}'">
                {{-- GAMBAR --}}
                <div style="position: relative; height: 200px; overflow: hidden;">
                    <img src="{{ $field->image_url }}"
                         alt="{{ $field->name }}"
                         style="width: 100%; height: 100%; object-fit: cover;"
                         onerror="this.style.display='none'">
                    {{-- Favorit (kiri atas) --}}
                    <span onclick="event.stopPropagation();toggleFavorite({{ $field->id }}, this)" style="position:absolute;top:12px;left:12px;background:rgba(0,0,0,0.6);color:{{ $isFav ? '#EB5436' : 'white' }};width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .2s;font-size:18px;z-index:2;" data-fav="{{ $isFav ? '1' : '0' }}">
                        {{ $isFav ? '❤️' : '🤍' }}
                    </span>
                    {{-- Rating (kanan atas) --}}
                    <div style="position:absolute;top:12px;right:12px;background:rgba(0,0,0,0.8);color:white;padding:6px 14px;border-radius:50px;font-size:12px;font-weight:600;display:flex;align-items:center;gap:5px;z-index:2;">
                        <span>⭐</span>
                        @if($hasReviews)
                            <span>{{ number_format($field->rating ?? 0, 1) }}</span>
                        @else
                            <span style="font-weight:400;">Baru</span>
                        @endif
                    </div>
                    {{-- Featured label --}}
                    @if($field->featured)
                    <div style="position:absolute;bottom:12px;left:12px;background:#02025b;color:white;padding:4px 10px;border-radius:20px;font-size:10px;font-weight:700;display:flex;align-items:center;gap:3px;z-index:2;">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="#fbbf24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        Featured
                    </div>
                    @endif
                </div>
                {{-- KONTEN BAWAH GAMBAR --}}
                <div style="padding: 16px 18px 18px; flex: 1; display: flex; flex-direction: column;">
                    {{-- Promo badge --}}
                    @if($hasPromo)
                    <div style="margin-bottom:10px;">
                        <span style="background:#dc2626;color:white;padding:5px 12px;border-radius:20px;font-size:11px;font-weight:800;display:inline-flex;align-items:center;gap:4px;">
                            🔥 {{ $field->promo_badge }}
                        </span>
                        <div style="font-size:10px;color:#94a3b8;margin-top:4px;">
                            {{ $field->promo_start }} — {{ $field->promo_end }}
                        </div>
                    </div>
                    @endif
                    {{-- Nama --}}
                    <h3 style="font-size: 18px; font-weight: 700; color: #001a4d; margin: 0 0 4px 0; line-height:1.3;">{{ $field->name }}</h3>
                    {{-- Lokasi --}}
                    <p style="color: #666; font-size: 13px; margin: 0 0 14px 0; display:flex;align-items:center;gap:4px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $field->location }}
                    </p>
                    {{-- Harga & Tombol --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid #f0f0f0; margin-top:auto;">
                        @if($hasPromo)
                        <div style="display:flex; flex-direction:column; gap:1px;">
                            <span style="font-size: 16px; font-weight: 700; color: #dc2626;">{{ $field->promo_price }}</span>
                            <span style="font-size: 12px; color: #999; text-decoration: line-through;">{{ $field->formattedPrice() }}</span>
                        </div>
                        @else
                        <span style="font-size: 16px; font-weight: 700; color: #001a4d;">{{ $field->formattedPrice() }}</span>
                        @endif
                        <button type="button" onclick="event.stopPropagation();window.location.href='{{ route('booking.show', array_filter(['field' => $field->id, 'sport' => $selectedSport])) }}'" style="padding: 8px 16px; background: #f59e0b; color: #ffffff; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 12px; white-space:nowrap;">
                            Pesan →
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </section>
</main>
</div>


<script>
(function(){
    var cdEl = document.getElementById('upcoming-countdown');
    if (!cdEl) return;
    var target = parseInt(cdEl.dataset.target) * 1000;
    if (isNaN(target)) { cdEl.style.display = 'none'; return; }
    function pad(n) { return n < 10 ? '0' + n : '' + n; }
    function update() {
        var diff = target - Date.now();
        if (diff <= 0) {
            document.getElementById('cd-days').textContent = '--';
            document.getElementById('cd-hours').textContent = '--';
            document.getElementById('cd-minutes').textContent = '--';
            document.getElementById('cd-seconds').textContent = '--';
            return;
        }
        var days = Math.floor(diff / 86400000);
        var hours = Math.floor((diff % 86400000) / 3600000);
        var minutes = Math.floor((diff % 3600000) / 60000);
        var seconds = Math.floor((diff % 60000) / 1000);
        document.getElementById('cd-days').textContent = pad(days);
        document.getElementById('cd-hours').textContent = pad(hours);
        document.getElementById('cd-minutes').textContent = pad(minutes);
        document.getElementById('cd-seconds').textContent = pad(seconds);
    }
    update();
    setInterval(update, 1000);
})();
</script>

<div id="createMatchModal" style="display:none; position:fixed; inset:0; z-index:10000; background:rgba(0,0,0,.5); justify-content:center; align-items:center;" onclick="if(event.target===this)hideCreateModal()">
    <div style="background:#fff; border-radius:16px; padding:36px 32px; max-width:420px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,.25); text-align:center;">
        <h2 style="margin:0 0 6px; font-size:20px; font-weight:800; color:#02025b;">Buat Pertandingan</h2>
        <p style="margin:0 0 24px; font-size:14px; color:#666;">Pilih tipe pertandingan yang ingin kamu buat</p>
        <div style="display:flex; gap:16px; flex-wrap:wrap;">
            <a href="{{ route('matches.create') }}" style="flex:1; min-width:140px; padding:20px 16px; border-radius:12px; border:2px solid #e11d48; text-decoration:none; transition:all .2s; background:#fff; display:flex; flex-direction:column; align-items:center; gap:8px;" onmouseover="this.style.background='#fff5f5'" onmouseout="this.style.background='#fff'">
                <span style="font-size:36px;">🌍</span>
                <span style="font-weight:800; font-size:16px; color:#02025b;">Publik</span>
                <span style="font-size:12px; color:#666;">Cari pemain lain</span>
            </a>
            <a href="javascript:void(0)" onclick="hideCreateModal();showSportModal()" style="flex:1; min-width:140px; padding:20px 16px; border-radius:12px; border:2px solid #02025b; text-decoration:none; transition:all .2s; background:#fff; display:flex; flex-direction:column; align-items:center; gap:8px;" onmouseover="this.style.background='#f5f5ff'" onmouseout="this.style.background='#fff'">
                <span style="font-size:36px;">🔒</span>
                <span style="font-weight:800; font-size:16px; color:#02025b;">Pribadi</span>
                <span style="font-size:12px; color:#666;">Booking lapangan langsung</span>
            </a>
        </div>
        <button onclick="hideCreateModal()" style="margin-top:20px; background:none; border:none; color:#999; font-size:14px; cursor:pointer; font-weight:600;">Batal</button>
    </div>
</div>
<div id="sportSelectModal" style="display:none; position:fixed; inset:0; z-index:10001; background:rgba(0,0,0,.5); justify-content:center; align-items:center;" onclick="if(event.target===this)hideSportModal()">
    <div style="background:#fff; border-radius:24px; max-width:420px; width:92%; max-height:85vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,.25); padding:28px 24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <div>
                <h2 style="margin:0; font-size:18px; font-weight:800; color:#02025b;">Mau olahraga apa hari ini?</h2>
                <p style="margin:4px 0 0; font-size:13px; color:#666;">Pilih olahraga untuk mencari lapangan yang cocok</p>
            </div>
            <button onclick="hideSportModal()" style="background:none; border:none; font-size:24px; cursor:pointer; color:#999; padding:4px;">&times;</button>
        </div>
        <input id="sportSearchInput" type="text" placeholder="Cari olahraga..." oninput="filterSports()" style="width:100%; padding:10px 14px; border-radius:12px; border:1px solid rgba(0,0,77,.15); background:#f5f5f5; font-size:14px; outline:none; box-sizing:border-box; margin-bottom:16px;">
        <div id="sportGrid" style="display:grid; grid-template-columns:repeat(3, 1fr); gap:10px;">
            @foreach($availableSports as $sport)
            <button type="button" data-sport="{{ $sport }}" onclick="selectSport('{{ addslashes($sport) }}')" style="padding:14px 8px; border-radius:14px; border:2px solid #e2e8f0; background:#fff; cursor:pointer; transition:all .2s; display:flex; flex-direction:column; align-items:center; gap:6px; font-family:inherit;" onmouseover="this.style.borderColor='#6366f1';this.style.background='#eef2ff'" onmouseout="this.style.borderColor='#e2e8f0';this.style.background='#fff'">
                <span style="font-size:24px;">{{ $sportEmoji[$sport] ?? '🏆' }}</span>
                <span style="font-size:10px; font-weight:800; color:#02025b; text-transform:uppercase; letter-spacing:0.5px; text-align:center; line-height:1.2;">{{ $sport }}</span>
            </button>
            @endforeach
        </div>
        <div id="sportNoResult" style="display:none; text-align:center; padding:24px 0; color:#94a3b8; font-size:14px;">Olahraga tidak ditemukan</div>
        <div style="display:flex; justify-content:center; margin-top:16px; padding-top:16px; border-top:1px solid #f1f5f9;">
            <button onclick="hideSportModal()" style="padding:8px 24px; background:#f1f5f9; border:none; border-radius:10px; color:#64748b; font-size:13px; font-weight:700; cursor:pointer;">Nanti dulu</button>
        </div>
    </div>
</div>
<div id="fieldListModal" style="display:none; position:fixed; inset:0; z-index:10002; background:rgba(0,0,0,.5); justify-content:center; align-items:center;" onclick="if(event.target===this)hideFieldModal()">
    <div style="background:#fff; border-radius:16px; max-width:600px; width:92%; max-height:85vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,.25);">
        <div style="position:sticky; top:0; background:#fff; border-radius:16px 16px 0 0; padding:24px 24px 16px; border-bottom:1px solid rgba(0,0,77,.08); z-index:1;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <div>
                    <h2 style="margin:0; font-size:18px; font-weight:800; color:#02025b;">Pilih Lapangan</h2>
                    <p style="margin:4px 0 0; font-size:13px; color:#666;">Pilih lapangan untuk booking langsung</p>
                </div>
                <button onclick="hideFieldModal()" style="background:none; border:none; font-size:24px; cursor:pointer; color:#999; padding:4px;">&times;</button>
            </div>
            <input id="fieldSearchInput" type="text" placeholder="Cari lapangan atau lokasi..." oninput="filterFields()" style="width:100%; padding:10px 14px; border-radius:10px; border:1px solid rgba(0,0,77,.15); background:#f5f5f5; font-size:14px; outline:none; box-sizing:border-box;">
        </div>
        <div style="padding:16px 24px 24px;">
            <div id="fieldListGrid" style="display:grid; grid-template-columns:repeat(auto-fill, minmax(240px, 1fr)); gap:16px;">
                @foreach($allFields as $f)
                <a href="{{ route('booking.show', $f->id) }}" data-field-name="{{ strtolower($f->name) }}" data-field-location="{{ strtolower($f->location ?? '') }}" data-field-sport="{{ $f->type }}" style="text-decoration:none; color:inherit; display:flex; align-items:center; gap:14px; padding:14px; border-radius:12px; border:1px solid rgba(0,0,77,.08); transition:all .2s; background:#fafafa;" onmouseover="this.style.borderColor='#02025b';this.style.background='#fff'" onmouseout="this.style.borderColor='rgba(0,0,77,.08)';this.style.background='#fafafa'">
                    <div style="width:56px; height:56px; border-radius:10px; overflow:hidden; flex-shrink:0; background:#e2e8f0;">
                        <img src="{{ $f->image_url }}" alt="" style="width:100%; height:100%; object-fit:cover;" onerror="this.style.display='none'">
                    </div>
                    <div style="min-width:0; flex:1;">
                        <h4 style="margin:0 0 2px; font-size:14px; font-weight:800; color:#02025b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $f->name }}</h4>
                        <p style="margin:0; font-size:12px; color:#666; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:-1px;margin-right:2px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            {{ $f->location ?: 'Lokasi tidak tersedia' }}
                        </p>
                        <p style="margin:2px 0 0; font-size:11px; color:#888;">
                            <span style="display:inline-flex;align-items:center;gap:2px;">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="#f59e0b"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                @if(($f->review_count ?? 0) > 0){{ number_format($f->rating ?? 0, 1) }}@else Baru @endif
                            </span>
                            &middot;
                            <span>{{ $f->type ?? 'Olahraga' }}</span>
                        </p>
                    </div>
                    <span style="font-size:18px; color:#ccc;">&rarr;</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- FAQ POPUP -->
<div id="faqPopup" style="display:none; position:fixed; bottom:90px; right:24px; width:340px; max-width:calc(100vw - 48px); background:white; border-radius:20px; box-shadow:0 20px 60px rgba(0,0,0,.2); z-index:1000; overflow:hidden;">
    <div style="background:#EB5436; color:white; padding:16px 20px; display:flex; align-items:center; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:10px;">
            <span class="material-symbols-outlined" style="font-size:22px;color:white;font-variation-settings:'FILL' 1;">support_agent</span>
            <span style="font-weight:700; font-size:15px;">Pusat Bantuan</span>
        </div>
        <span onclick="toggleFaqPopup()" style="cursor:pointer; font-size:20px; line-height:1; color:white;">&times;</span>
    </div>
    <div style="padding:16px 20px;">
        <p style="font-size:13px; color:#666; margin-bottom:12px;">Ada yang bisa kami bantu?</p>
        <div onclick="faqAnswer('booking')" style="padding:12px 14px; border-radius:12px; border:1px solid rgba(0,0,77,.08); margin-bottom:8px; cursor:pointer; transition:all .2s; display:flex; align-items:center; gap:10px;" onmouseover="this.style.borderColor='#EB5436';this.style.background='#fff5f2'" onmouseout="this.style.borderColor='rgba(0,0,77,.08)';this.style.background='transparent'">
            <span style="color:#EB5436; font-size:20px;">📅</span>
            <div><div style="font-weight:700; font-size:13px; color:#02025b;">Cara Booking</div><div style="font-size:11px; color:#888;">Panduan memesan lapangan</div></div>
        </div>
        <div onclick="faqAnswer('join_match')" style="padding:12px 14px; border-radius:12px; border:1px solid rgba(0,0,77,.08); margin-bottom:8px; cursor:pointer; transition:all .2s; display:flex; align-items:center; gap:10px;" onmouseover="this.style.borderColor='#EB5436';this.style.background='#fff5f2'" onmouseout="this.style.borderColor='rgba(0,0,77,.08)';this.style.background='transparent'">
            <span style="color:#EB5436; font-size:20px;">👥</span>
            <div><div style="font-weight:700; font-size:13px; color:#02025b;">Cara Join Pertandingan Umum</div><div style="font-size:11px; color:#888;">Bergabung pertandingan publik</div></div>
        </div>
        <div onclick="faqAnswer('payment')" style="padding:12px 14px; border-radius:12px; border:1px solid rgba(0,0,77,.08); margin-bottom:8px; cursor:pointer; transition:all .2s; display:flex; align-items:center; gap:10px;" onmouseover="this.style.borderColor='#EB5436';this.style.background='#fff5f2'" onmouseout="this.style.borderColor='rgba(0,0,77,.08)';this.style.background='transparent'">
            <span style="color:#EB5436; font-size:20px;">💳</span>
            <div><div style="font-weight:700; font-size:13px; color:#02025b;">Cara Pembayaran</div><div style="font-size:11px; color:#888;">Informasi metode pembayaran</div></div>
        </div>
        <div onclick="faqAnswer('cs')" style="padding:12px 14px; border-radius:12px; border:1px solid rgba(0,0,77,.08); cursor:pointer; transition:all .2s; display:flex; align-items:center; gap:10px;" onmouseover="this.style.borderColor='#EB5436';this.style.background='#fff5f2'" onmouseout="this.style.borderColor='rgba(0,0,77,.08)';this.style.background='transparent'">
            <span style="color:#EB5436; font-size:20px;">🎧</span>
            <div><div style="font-weight:700; font-size:13px; color:#02025b;">Hubungi Customer Service</div><div style="font-size:11px; color:#888;">Chat dengan admin via WhatsApp</div></div>
        </div>
    </div>
    <div id="faqAnswerBox" style="display:none; padding:16px 20px; border-top:1px solid rgba(0,0,77,.06); background:#f8fafc;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
            <span style="font-weight:700; font-size:13px; color:#02025b;" id="faqAnswerTitle"></span>
            <span onclick="closeFaqAnswer()" style="cursor:pointer; font-size:16px; color:#999;">&times;</span>
        </div>
        <p style="font-size:13px; color:#555; line-height:1.6; white-space:pre-line;" id="faqAnswerText"></p>
    </div>
</div>

<script>
function toggleFaqPopup() {
    var popup = document.getElementById('faqPopup');
    popup.style.display = popup.style.display === 'none' ? 'block' : 'none';
    document.getElementById('faqAnswerBox').style.display = 'none';
}
function faqAnswer(type) {
    var titleEl = document.getElementById('faqAnswerTitle');
    var textEl = document.getElementById('faqAnswerText');
    var boxEl = document.getElementById('faqAnswerBox');
    var answers = {
        booking: { title: 'Cara Booking', text: '1. Pilih lapangan yang kamu inginkan.\n2. Pilih tanggal dan jam yang tersedia.\n3. Klik "Pesan" dan ikuti instruksi pembayaran.\n4. Laporkan pembayaran ke owner untuk konfirmasi.\n5. Setelah dikonfirmasi, booking kamu aktif!' },
        join_match: { title: 'Cara Join Pertandingan Umum', text: '1. Buka halaman "Cari Tim".\n2. Geser kartu pertandingan yang tersedia.\n3. Klik "Bergabung" pada pertandingan yang diinginkan.\n4. Lanjutkan pembayaran kontribusi jika ada.\n5. Tunggu konfirmasi dari host pertandingan.' },
        payment: { title: 'Cara Pembayaran', text: 'Pembayaran dilakukan dengan transfer ke rekening owner lapangan. Setelah transfer, laporkan pembayaran melalui halaman detail booking. Owner akan mengkonfirmasi pembayaran kamu.' },
        cs: { title: 'Hubungi Customer Service', text: '' }
    };
    var answer = answers[type];
    if (!answer) return;
    titleEl.textContent = answer.title;
    textEl.textContent = answer.text;
    boxEl.style.display = 'block';
    if (type === 'cs') {
        textEl.style.display = 'none';
        boxEl.innerHTML = '<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;"><span style="font-weight:700; font-size:13px; color:#02025b;">Hubungi Customer Service</span><span onclick="closeFaqAnswer()" style="cursor:pointer; font-size:16px; color:#999;">&times;</span></div><p style="font-size:13px; color:#555; margin-bottom:12px;">Kamu akan dihubungkan dengan admin kami melalui WhatsApp.</p><a href="https://wa.me/6281234567890?text=Halo%20Spies%20Sport%2C%20saya%20butuh%20bantuan" target="_blank" style="display:block; text-align:center; background:#25D366; color:white; padding:12px; border-radius:12px; font-weight:700; text-decoration:none;">&#x1F4AC; Chat WhatsApp</a>';
    } else {
        textEl.style.display = 'block';
    }
}
function closeFaqAnswer() { document.getElementById('faqAnswerBox').style.display = 'none'; }

function toggleFavorite(fieldId, el) {
    event.stopPropagation();
    fetch('{{ route("favorite.toggle") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ field_id: fieldId })
    }).then(function(r) { return r.json(); }).then(function(data) {
        if (data.favorited) {
            el.setAttribute('data-fav', '1');
            el.innerHTML = '❤️';
            el.style.color = '#EB5436';
        } else {
            el.setAttribute('data-fav', '0');
            el.innerHTML = '🤍';
            el.style.color = 'white';
        }
    });
}

function openDashboardReview() {
    fetch('{{ route("review.check-any") }}')
    .then(function(r) { return r.json(); }).then(function(data) {
        if (data.eligible && data.booking) {
            document.getElementById('dashReviewFieldId').value = data.booking.field_id;
            document.getElementById('dashReviewBookingId').value = data.booking.id;
            document.getElementById('dashReviewModal').style.display = 'flex';
            document.getElementById('dashReviewHint').innerHTML = '<strong>' + data.booking.field_name + '</strong> &middot; ' + data.booking.date;
            dashResetHalfRating();
        } else {
            var msg = document.createElement('div');
            msg.style.cssText = 'position:fixed;top:20px;left:50%;transform:translateX(-50%);background:#02025b;color:white;padding:12px 24px;border-radius:12px;font-weight:600;z-index:9999;font-size:14px;box-shadow:0 8px 24px rgba(0,0,0,.2);text-align:center;';
            msg.textContent = data.message || 'Belum ada booking selesai untuk direview.';
            document.body.appendChild(msg);
            setTimeout(function() { msg.remove(); }, 4000);
        }
    }).catch(function() {
        var msg = document.createElement('div');
        msg.style.cssText = 'position:fixed;top:20px;left:50%;transform:translateX(-50%);background:#dc2626;color:white;padding:12px 24px;border-radius:12px;font-weight:600;z-index:9999;font-size:14px;';
        msg.textContent = 'Gagal memuat data review.';
        document.body.appendChild(msg);
        setTimeout(function() { msg.remove(); }, 3000);
    });
}
function openDashReviewWithData(fieldId, bookingId, fieldName, hint) {
    document.getElementById('dashReviewFieldId').value = fieldId;
    document.getElementById('dashReviewBookingId').value = bookingId;
    document.getElementById('dashReviewHint').innerHTML = '<strong>' + fieldName + '</strong> &middot; ' + hint;
    document.getElementById('dashReviewModal').style.display = 'flex';
    dashResetHalfRating();
    document.getElementById('dashReviewText').value = '';
    document.getElementById('dashReviewError').style.display = 'none';
    document.getElementById('dashReviewPhotoLabel').textContent = 'Tambahkan foto';
    document.getElementById('dashReviewPhotos').value = '';
}
function dashSetHalfRating(val) {
    document.getElementById('dashRatingValue').value = val;
    document.getElementById('dashRatingDisplay').textContent = val + ' dari 5 bintang';
    for (var i = 1; i <= 5; i++) {
        var fill = document.getElementById('dash-hsf-' + i);
        if (val >= i) fill.style.width = '100%';
        else if (val >= i - 0.5) fill.style.width = '50%';
        else fill.style.width = '0%';
    }
}
function dashResetHalfRating() {
    dashSetHalfRating(0);
    document.getElementById('dashRatingDisplay').textContent = 'Klik bintang untuk memberi rating';
}
function dashCloseReview() {
    document.getElementById('dashReviewModal').style.display = 'none';
}
function dashUpdatePhotoLabel(input) {
    var label = document.getElementById('dashReviewPhotoLabel');
    if (input.files.length > 0) {
        label.textContent = input.files.length + ' foto dipilih';
    } else {
        label.textContent = 'Tambahkan foto';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('dashReviewForm').addEventListener('submit', function(e) {
        var ratingEl = document.getElementById('dashRatingValue');
        var review = document.getElementById('dashReviewText').value.trim();
        var errorEl = document.getElementById('dashReviewError');
        if (parseFloat(ratingEl.value) === 0) { e.preventDefault(); errorEl.textContent = 'Pilih rating terlebih dahulu.'; errorEl.style.display = 'block'; return; }
        if (review.length < 10) { e.preventDefault(); errorEl.textContent = 'Review minimal 10 karakter.'; errorEl.style.display = 'block'; return; }
        errorEl.style.display = 'none';
    });
});

function findNearestFields() {
    @if(empty(Auth::user()->city))
    var msg = document.createElement('div');
    msg.style.cssText = 'position:fixed;top:20px;left:50%;transform:translateX(-50%);background:#02025b;color:white;padding:16px 24px;border-radius:12px;font-weight:600;z-index:9999;font-size:14px;box-shadow:0 8px 24px rgba(0,0,0,.2);text-align:center;';
    msg.innerHTML = '📍 Isi kota kamu dulu ya<br><a href="{{ route("profile.edit") }}" style="color:#fde68a;font-size:12px;margin-top:6px;display:inline-block;">Edit Profil &rarr;</a>';
    document.body.appendChild(msg);
    setTimeout(function() { msg.remove(); }, 5000);
    @else
    var msg = document.createElement('div');
    msg.style.cssText = 'position:fixed;top:20px;left:50%;transform:translateX(-50%);background:#02025b;color:white;padding:12px 24px;border-radius:12px;font-weight:600;z-index:9999;font-size:14px;box-shadow:0 8px 24px rgba(0,0,0,.2);';
    if (navigator.geolocation) {
        msg.textContent = 'Mendeteksi lokasi Anda...';
        document.body.appendChild(msg);
        navigator.geolocation.getCurrentPosition(function(pos) {
            msg.textContent = 'Menampilkan lapangan terdekat...';
            window.location.href = '{{ route("dashboard") }}?nearby=1&lat=' + pos.coords.latitude + '&lng=' + pos.coords.longitude;
        }, function() {
            msg.textContent = 'Menggunakan kota terdaftar untuk mencari lapangan terdekat.';
            setTimeout(function() { msg.remove(); }, 3000);
            window.location.href = '{{ route("dashboard") }}?nearby=1';
        });
    } else {
        msg.textContent = 'Browser tidak mendukung geolokasi. Menggunakan kota terdaftar.';
        document.body.appendChild(msg);
        setTimeout(function() { msg.remove(); window.location.href = '{{ route("dashboard") }}?nearby=1'; }, 2000);
    }
    @endif
}
</script>

<script>
var _selectedSport = '';
function showCreateModal(){document.getElementById('createMatchModal').style.display='flex';}
function hideCreateModal(){document.getElementById('createMatchModal').style.display='none';}
function showSportModal(){document.getElementById('sportSelectModal').style.display='flex';setTimeout(function(){document.getElementById('sportSearchInput').focus();},100);document.getElementById('sportSearchInput').value='';filterSports();}
function hideSportModal(){document.getElementById('sportSelectModal').style.display='none';}
function showFieldModal(){var m=document.getElementById('fieldListModal');m.style.display='flex';applyFieldSportFilter();setTimeout(function(){document.getElementById('fieldSearchInput').focus();},100);}
function hideFieldModal(){document.getElementById('fieldListModal').style.display='none';}
function filterFields(){var q=document.getElementById('fieldSearchInput').value.toLowerCase(),cards=document.querySelectorAll('#fieldListGrid > a');for(var i=0;i<cards.length;i++){var card=cards[i];card.style.display=(card.getAttribute('data-field-name').includes(q)||card.getAttribute('data-field-location').includes(q))?'flex':'none';}}
function filterSports(){var q=document.getElementById('sportSearchInput').value.toLowerCase(),btns=document.querySelectorAll('#sportGrid > button'),found=0;for(var i=0;i<btns.length;i++){var s=btns[i].getAttribute('data-sport').toLowerCase();if(!q||s.indexOf(q)!==-1){btns[i].style.display='flex';found++;}else{btns[i].style.display='none';}}document.getElementById('sportNoResult').style.display=found?'none':'block';}
function selectSport(s){_selectedSport=s;hideSportModal();showFieldModal();}
function applyFieldSportFilter(){var cards=document.querySelectorAll('#fieldListGrid > a');for(var i=0;i<cards.length;i++){var card=cards[i];if(!_selectedSport||card.getAttribute('data-field-sport')===_selectedSport){card.style.display='flex';card.href=card.href.split('?')[0]+'?sport='+encodeURIComponent(_selectedSport);}else{card.style.display='none';}}}
</script>

<!-- Review Modal -->
<div id="dashReviewModal" style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,.5);justify-content:center;align-items:center;padding:20px;" onclick="if(event.target===this)dashCloseReview()">
    <div style="background:white;border-radius:20px;padding:28px;max-width:440px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.25);position:relative;max-height:90vh;overflow-y:auto;">
        <button type="button" onclick="dashCloseReview()" style="position:absolute;top:12px;right:12px;background:none;border:none;font-size:22px;cursor:pointer;color:#94a3b8;line-height:1;">&times;</button>
        <h3 style="margin:0 0 4px;font-size:18px;font-weight:800;color:#02025b;">Beri Review</h3>
        <p id="dashReviewHint" style="margin:0 0 16px;font-size:13px;color:#64748b;"></p>

        <form id="dashReviewForm" method="POST" action="{{ route('review.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="field_id" id="dashReviewFieldId">
            <input type="hidden" name="booking_id" id="dashReviewBookingId">

            {{-- Rating --}}
            <div style="margin-bottom:16px;">
                <p style="margin:0 0 8px;font-size:13px;font-weight:600;color:#02025b;">Rating <span style="color:#dc2626;">*</span></p>
                <div id="dashHalfStarContainer" style="display:flex;gap:2px;flex-direction:row-reverse;justify-content:flex-end;">
                    @for($i = 5; $i >= 1; $i--)
                    <div class="dash-hstar" data-star="{{ $i }}" style="position:relative;width:32px;height:32px;cursor:pointer;">
                        <span class="dash-hstar-bg" style="position:absolute;inset:0;font-size:32px;line-height:1;color:#e2e8f0;pointer-events:none;">★</span>
                        <span class="dash-hstar-fill" id="dash-hsf-{{ $i }}" style="position:absolute;inset:0;font-size:32px;line-height:1;color:#f59e0b;overflow:hidden;width:0%;pointer-events:none;">★</span>
                        <span class="dash-hstar-left" onclick="dashSetHalfRating({{ $i - 0.5 }})" style="position:absolute;top:0;left:0;bottom:0;width:50%;z-index:2;cursor:pointer;"></span>
                        <span class="dash-hstar-right" onclick="dashSetHalfRating({{ $i }})" style="position:absolute;top:0;right:0;bottom:0;width:50%;z-index:2;cursor:pointer;"></span>
                    </div>
                    @endfor
                </div>
                <p id="dashRatingDisplay" style="margin:6px 0 0;font-size:12px;color:#94a3b8;">Klik bintang untuk memberi rating</p>
                <input type="hidden" name="rating" id="dashRatingValue" value="0">
            </div>

            {{-- Review text --}}
            <div style="margin-bottom:16px;">
                <p style="margin:0 0 8px;font-size:13px;font-weight:600;color:#02025b;">Ulasan <span style="color:#dc2626;">*</span></p>
                <textarea name="review" id="dashReviewText" rows="4" placeholder="Tulis ulasan kamu di sini (minimal 10 karakter)..." style="width:100%;padding:12px 14px;border-radius:12px;border:1px solid rgba(0,0,77,.15);font-size:14px;outline:none;resize:none;box-sizing:border-box;font-family:inherit;"></textarea>
            </div>

            {{-- Photo upload --}}
            <div style="margin-bottom:16px;">
                <p style="margin:0 0 8px;font-size:13px;font-weight:600;color:#02025b;">Foto <span style="color:#94a3b8;font-weight:400;">(opsional)</span></p>
                <label style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:12px;border:1px dashed rgba(0,0,77,.2);background:#f8fafc;cursor:pointer;transition:all .2s;font-size:13px;color:#64748b;" onmouseover="this.style.borderColor='#EB5436'" onmouseout="this.style.borderColor='rgba(0,0,77,.2)'">
                    <svg viewBox="0 0 24 24" fill="none" width="20" height="20"><rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.6"/><circle cx="7.5" cy="9.5" r="1.5" fill="currentColor"/><path d="M3 16L8 11L12 15L16 10L21 16" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span id="dashReviewPhotoLabel">Tambahkan foto</span>
                    <input type="file" name="photos[]" id="dashReviewPhotos" accept="image/jpeg,image/png,image/webp" multiple style="display:none;" onchange="dashUpdatePhotoLabel(this)">
                </label>
                <p style="margin:6px 0 0;font-size:11px;color:#94a3b8;">Maksimal 5 foto (JPEG, PNG, WebP). Maks 5MB per foto.</p>
            </div>

            <p id="dashReviewError" style="display:none;color:#dc2626;font-size:12px;margin:6px 0 0;"></p>
            <button type="submit" id="dashReviewSubmitBtn" style="width:100%;margin-top:8px;padding:14px;background:#EB5436;color:white;border:none;border-radius:12px;font-weight:700;font-size:15px;cursor:pointer;">Kirim Review</button>
        </form>
    </div>
</div>

<script>
function joinDashCommunity(communityId, btn) {
    if (btn.disabled) return;
    btn.disabled = true;
    btn.textContent = 'Memproses...';
    var csrf = document.querySelector('meta[name="csrf-token"]');
    if (!csrf) return;
    var fd = new FormData();
    fd.append('_token', csrf.getAttribute('content'));
    fetch('/komunitas/' + communityId + '/join', {
        method: 'POST',
        body: fd,
    }).then(function(r) { return r.json(); }).then(function(data) {
        if (data.joined) {
            var card = btn.closest('[style*="border-radius: 16px"]');
            if (card) {
                var countEl = card.querySelector('[style*="color:#94a3b8"]');
                if (countEl) countEl.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom;margin-right:2px;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> ' + data.count + ' Anggota';
            }
            btn.outerHTML = '<a href="' + data.whatsapp + '" target="_blank" rel="noopener noreferrer" style="margin-left:auto;font-size:11px;font-weight:700;color:#059669;text-decoration:none;">WhatsApp</a>';
        } else {
            btn.disabled = false;
            btn.textContent = 'Gabung';
        }
    }).catch(function() {
        btn.disabled = false;
        btn.textContent = 'Gabung';
    });
}
</script>

{{-- Search + Profile Dropdown JS --}}
<script>
(function(){
    // ── Profile Dropdown ──
    var trigger = document.getElementById('profile-dropdown-trigger');
    var menu = document.getElementById('profile-dropdown-menu');
    if (trigger && menu) {
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            menu.classList.toggle('is-visible');
        });
        document.addEventListener('click', function(e) {
            if (!menu.contains(e.target) && e.target !== trigger) {
                menu.classList.remove('is-visible');
            }
        });
    }

    // ── Search ──
    var searchInput = document.getElementById('fields-search');
    var suggestions = document.getElementById('search-suggestions');
    var aboveSections = document.getElementById('dashboard-above-sections');
    var lapanganHeader = document.getElementById('lapangan-section-header');
    var fieldGrid = document.getElementById('field-card-grid');
    var fieldCards = fieldGrid ? fieldGrid.querySelectorAll('.field-card') : [];

    // Build suggestion pool
    var suggestionPool = [];
    var fieldData = [];
    fieldCards.forEach(function(card) {
        var searchText = card.getAttribute('data-search') || '';
        var nameEl = card.querySelector('h3');
        var name = nameEl ? nameEl.textContent.trim() : '';
        if (name && fieldData.indexOf(name) === -1) {
            suggestionPool.push({ label: name, type: 'field', icon: '🏟️' });
            fieldData.push(name);
        }
        // Extract type from search text (last word after location)
        var parts = searchText.split(' ');
        for (var i = 0; i < parts.length; i++) {
            var word = parts[i].trim();
            if (word && ['futsal','badminton','basket','voli','tennis','golf','renang','panahan','lari','sepeda','tinju','bela diri','yoga','fitness','hiking','padel','baseball','rugby','senam'].indexOf(word) !== -1) {
                var sportLabel = word.charAt(0).toUpperCase() + word.slice(1);
                if (fieldData.indexOf(sportLabel) === -1) {
                    suggestionPool.push({ label: sportLabel, type: 'sport', icon: '🏅' });
                    fieldData.push(sportLabel);
                }
            }
        }
    });

    function doSearch(query) {
        query = query.toLowerCase().trim();

        if (query.length === 0) {
            // Reset — show everything
            if (aboveSections) aboveSections.classList.remove('is-hidden');
            if (lapanganHeader) lapanganHeader.classList.remove('is-hidden');
            fieldCards.forEach(function(c) { c.style.display = ''; });
            suggestions.classList.remove('is-visible');
            suggestions.innerHTML = '';
            return;
        }

        // Hide sections above
        if (aboveSections) aboveSections.classList.add('is-hidden');
        if (lapanganHeader) lapanganHeader.classList.add('is-hidden');

        // Filter cards
        var matchCount = 0;
        fieldCards.forEach(function(card) {
            var haystack = (card.getAttribute('data-search') || '').toLowerCase();
            if (haystack.indexOf(query) !== -1) {
                card.style.display = '';
                matchCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Show suggestions
        var filtered = [];
        for (var i = 0; i < suggestionPool.length; i++) {
            if (filtered.length >= 5) break;
            if (suggestionPool[i].label.toLowerCase().indexOf(query) !== -1) {
                filtered.push(suggestionPool[i]);
            }
        }
        if (filtered.length > 0) {
            suggestions.innerHTML = filtered.map(function(s) {
                return '<div class="search-suggestion-item" data-suggest="' + s.label.replace(/"/g, '&quot;') + '">' +
                    '<span class="ss-icon">' + s.icon + '</span>' +
                    '<span class="ss-text"><strong>' + s.label + '</strong><small>' + (s.type === 'sport' ? 'Kategori Olahraga' : 'Nama Lapangan') + '</small></span>' +
                    '</div>';
            }).join('');
            suggestions.classList.add('is-visible');
            // Click suggestion fills input
            suggestions.querySelectorAll('.search-suggestion-item').forEach(function(item) {
                item.addEventListener('click', function() {
                    searchInput.value = this.getAttribute('data-suggest');
                    doSearch(searchInput.value);
                    suggestions.classList.remove('is-visible');
                });
            });
        } else {
            suggestions.classList.remove('is-visible');
        }

        // Show "no results" if needed
        var existingNoResult = document.getElementById('search-no-result');
        if (matchCount === 0) {
            if (!existingNoResult) {
                var noResult = document.createElement('div');
                noResult.id = 'search-no-result';
                noResult.style.cssText = 'text-align:center;padding:60px 20px;color:#94a3b8;';
                noResult.innerHTML = '<span style="font-size:48px;display:block;margin-bottom:16px;">🔍</span><p style="font-weight:700;margin:0 0 4px;">Lapangan tidak ditemukan</p><p style="font-size:13px;margin:0;">Coba kata kunci lain</p>';
                if (fieldGrid) fieldGrid.parentElement.appendChild(noResult);
            }
        } else {
            if (existingNoResult) existingNoResult.remove();
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            doSearch(this.value);
        });
    }

    // Close suggestions on click outside
    document.addEventListener('click', function(e) {
        var wrapper = document.querySelector('.player-search-wrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            suggestions.classList.remove('is-visible');
        }
    });
})();
</script>
</body>
</html>
