@php
    use App\Models\Field;
    use Carbon\Carbon;
    $fields = Field::with('owner')->get();
    $userName = Auth::user()->name ?? 'Player';
    $userAvatar = Auth::user()->avatarUrl();
    $currentDate = Carbon::now()->locale('id')->translatedFormat('j F Y');
    
    // Sidebar
    $sidebarItems = [
        ['label'=>'Dashboard',  'icon'=>asset('assets/images/icons/dashboard.png'), 'href'=>route('dashboard'),    'active'=>true],
        ['label'=>'Aktivitas',  'icon'=>asset('assets/images/icons/aktivitas.png'), 'href'=>route('activity.index'),       'active'=>false],
        ['label'=>'Favoritmu',  'icon'=>asset('assets/images/icons/favoritmu.png'), 'href'=>route('favorite.index'),                  'active'=>false],
        ['label'=>'Histori',    'icon'=>asset('assets/images/icons/histori.png'),   'href'=>route('history.index'),                  'active'=>false],
        ['label'=>'Cari tim',   'icon'=>asset('assets/images/icons/caritim.png'),   'href'=>route('matches.index'),'active'=>false],
        ['label'=>'Booking',    'icon'=>asset('assets/images/icons/booking.png'),   'href'=>route('booking.index'),                  'active'=>false],
        ['label'=>'Keahlianmu', 'icon'=>asset('assets/images/icons/keahlian.png'),  'href'=>route('skill.index'),                  'active'=>false],
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
            <label class="player-search" for="fields-search">
                <span class="player-search__icon">
                    <svg viewBox="0 0 20 20" fill="none"><circle cx="9" cy="9" r="5.75" stroke="currentColor" stroke-width="1.8"/><path d="M13.5 13.5L17 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </span>
                <input id="fields-search" type="search" placeholder="Cari lapangan...">
            </label>
        </div>
        <div class="player-dashboard-topbar__right">
            <div class="player-dashboard-topbar__date">
                <span class="player-inline-icon">
                    <svg viewBox="0 0 24 24" fill="none"><rect x="3.5" y="5.5" width="17" height="15" rx="2.5" stroke="currentColor" stroke-width="1.8"/><path d="M7 3.5V7M17 3.5V7M3.5 9.5H20.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </span>
                <span>{{ $currentDate }}</span>
            </div>
            <button type="button" class="player-dashboard-topbar__icon">
                <span class="player-inline-icon">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M9 18H15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M6.5 17.5H17.5L16.3 15.6C15.9 15 15.7 14.3 15.7 13.6V10.8C15.7 8.49 14.04 6.54 11.8 6.16V5.5C11.8 4.67 11.13 4 10.3 4C9.47 4 8.8 4.67 8.8 5.5V6.16C6.56 6.54 4.9 8.49 4.9 10.8V13.6C4.9 14.3 4.7 15 4.3 15.6L3.1 17.5H6.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                </span>
            </button>
            <a href="{{ route('profile.show') }}" class="player-profile-pill">
                <span class="player-profile-pill__avatar">
                    <img src="{{ $userAvatar }}" alt="Profil" class="player-avatar-image player-avatar-image--profile">
                </span>
                <span class="player-profile-pill__name">{{ $userName }}</span>
            </a>
        </div>
    </header>

    <section style="padding: 20px; max-width: 1400px; margin: 0 auto;">

        <div style="margin-bottom: 30px;">
            <h2 style="font-size: 28px; font-weight: bold; color: #001a4d; margin-bottom: 10px;">🏐 Lapangan Tersedia</h2>
            <p style="color: #666;">Pilih lapangan yang ingin kamu booking</p>
        </div>

        @if($fields->isEmpty())
        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px;">
            <h3 style="font-size: 20px; color: #001a4d; margin-bottom: 10px;">Belum Ada Lapangan</h3>
            <p style="color: #666;">Tidak ada lapangan yang tersedia saat ini.</p>
        </div>
        @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px;">
            @foreach($fields as $field)
            <a href="{{ route('booking.show', $field->id) }}" style="text-decoration: none; color: inherit; transition: all 0.3s ease;"
               onmouseover="this.style.transform = 'translateY(-8px)'; this.style.boxShadow = '0 12px 24px rgba(0,0,0,0.15)';"
               onmouseout="this.style.transform = 'translateY(0)'; this.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)';">
                <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: 100%; display: flex; flex-direction: column;">
                    <div style="position: relative; height: 200px; overflow: hidden;">
                        <img src="{{ $field->image ?? asset('assets/images/bg/Explore.png') }}" 
                             alt="{{ $field->name }}"
                             style="width: 100%; height: 100%; object-fit: cover;">
                        <div style="position: absolute; top: 12px; right: 12px; background: rgba(0,0,0,0.8); color: white; padding: 8px 14px; border-radius: 50px; font-size: 12px; font-weight: 600;">
                            ⭐ {{ $field->rating ?? '4.8' }}
                        </div>
                    </div>
                    <div style="padding: 18px; flex: 1; display: flex; flex-direction: column;">
                        <h3 style="font-size: 18px; font-weight: 700; color: #001a4d; margin: 0 0 10px 0;">{{ $field->name }}</h3>
                        <p style="color: #666; font-size: 13px; margin: 0 0 12px 0; flex: 1;">{{ $field->location }}</p>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid #f0f0f0;">
                            <span style="font-size: 16px; font-weight: 700; color: #f59e0b;">{{ $field->formattedPrice() }}</span>
                            <button type="button" style="padding: 8px 16px; background: #f59e0b; color: #ffffff; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 12px;">
                                Booking →
                            </button>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif

    </section>
</main>
</div>

document.addEventListener('DOMContentLoaded', function() {
<script src="{{ asset('js/player-dashboard.js') }}"></script>
</body>
</html>
