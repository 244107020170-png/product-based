@php
    use Carbon\Carbon;
    $userName = Auth::user()->name ?? 'Pemain';
    $userAvatar = Auth::user()->avatarUrl();
    $currentDate = Carbon::now()->locale('id')->translatedFormat('j F Y');
    
    // Sidebar
    $sidebarItems = [
        ['label'=>'Beranda',  'icon'=>asset('assets/images/icons/dashboard.png'), 'href'=>route('dashboard'),    'active'=>false],
        ['label'=>'Aktivitas',  'icon'=>asset('assets/images/icons/aktivitas.png'), 'href'=>route('activity.index'),       'active'=>false],
        ['label'=>'Favorit',  'icon'=>asset('assets/images/icons/favoritmu.png'), 'href'=>route('favorite.index'),                  'active'=>false],
        ['label'=>'Histori',    'icon'=>asset('assets/images/icons/histori.png'),   'href'=>route('history.index'),                  'active'=>false],
        ['label'=>'Cari tim',   'icon'=>asset('assets/images/icons/caritim.png'),   'href'=>route('matches.index'),'active'=>false],
        ['label'=>'Pemesanan',    'icon'=>asset('assets/images/icons/booking.png'),   'href'=>route('booking.index'),       'active'=>true],
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
    <title>Detail Booking – {{ config('app.name', 'Spies Sport') }}</title>
    @vite([
        'resources/css/app.css',
        'resources/css/player-dashboard.css',
    ])
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
            <button type="button" class="player-sidebar__close" data-sidebar-close><span></span><span></span></button>
        </div>

        <nav class="player-sidebar__nav">
            @foreach($sidebarItems as $item)
            @php $cls='player-sidebar__item'.($item['active']?' is-active':'').($item['href']?'':' is-disabled'); @endphp
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
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="player-sidebar__item player-sidebar__item--logout">
                    <span class="player-sidebar__icon-wrap"><img src="{{ asset('assets/images/icons/keluar.png') }}" alt="" class="player-sidebar__icon"></span>
                    <span class="player-sidebar__label">Keluar</span>
                </button>
            </form>
        </div>
    </div>
</aside>
<button type="button" class="player-sidebar__backdrop" data-sidebar-backdrop></button>

{{-- ============ MAIN ============ --}}
<main class="player-dashboard-main">
    <header class="player-dashboard-topbar">
        <div class="player-dashboard-topbar__left">
            <button type="button" class="player-dashboard-topbar__menu" data-sidebar-open><span></span><span></span><span></span></button>
        </div>
        <div class="player-dashboard-topbar__right">
            <div class="player-dashboard-topbar__date">
                <span class="player-inline-icon">
                    <svg viewBox="0 0 24 24" fill="none"><rect x="3.5" y="5.5" width="17" height="15" rx="2.5" stroke="currentColor" stroke-width="1.8"/><path d="M7 3.5V7M17 3.5V7M3.5 9.5H20.5" stroke="currentColor" stroke-width="1.8"/></svg>
                </span>
                <span>{{ $currentDate }}</span>
            </div>
            <a href="{{ route('profile.show') }}" class="player-profile-pill">
                <span class="player-profile-pill__avatar">
                    <img src="{{ $userAvatar }}" alt="Profil" class="player-avatar-image player-avatar-image--profile">
                </span>
                <span class="player-profile-pill__name">{{ $userName }}</span>
            </a>
        </div>
    </header>

    <section style="padding: 24px; max-width: 600px; margin: 0 auto;">
        
        <div style="margin-bottom: 20px;">
            <a href="{{ route('booking.index') }}" style="display: inline-flex; align-items: center; padding: 0 20px; height: 40px; background: rgba(255,255,255,.76); color: #11114b; font-size: .95rem; font-weight: 700; text-decoration: none; border-radius: 10px; transition: all .2s ease; border: 1.8px solid #14144a;">
                &larr; Kembali ke Daftar Booking
            </a>
        </div>

        <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,77,.06); border: 1px solid rgba(0,0,77,.08);">
            
            <div style="background: linear-gradient(135deg, #02025b 0%, #11114b 100%); color: white; padding: 24px; text-align: center;">
                <h1 style="margin: 0 0 8px 0; font-size: 24px; font-weight: 800;">Detail Booking</h1>
                <p style="margin: 0; font-size: 14px; opacity: 0.9;">Booking ID: #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</p>
                <div style="margin-top: 16px;">
                    <span style="display: inline-block; padding: 6px 16px; border-radius: 50px; font-size: 14px; font-weight: 700;
                        {{ $booking->status === 'confirmed' ? 'background: #B5FF2B; color: #02025b;' : 'background: #fff3cd; color: #856404;' }}">
                        Status: {{ ucfirst($booking->status) }}
                    </span>
                </div>
            </div>

            <div style="padding: 32px 24px;">
                <div style="margin-bottom: 24px;">
                    <h3 style="margin: 0 0 4px 0; font-size: 20px; font-weight: 800; color: #02025b;">{{ $booking->field->name }}</h3>
                    <p style="margin: 0; color: #666; display:flex; align-items:center; gap:6px; font-size: 15px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M12 20.5C12 20.5 18 14.73 18 10.5C18 7.19 15.31 4.5 12 4.5C8.69 4.5 6 7.19 6 10.5C6 14.73 12 20.5 12 20.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            <circle cx="12" cy="10.5" r="2.4" fill="currentColor"/>
                        </svg>
                        <span>{{ $booking->field->location }}</span>
                    </p>
                </div>

                <div style="background: #f5f7fa; border-radius: 12px; padding: 20px; margin-bottom: 24px; border: 1px solid rgba(0,0,77,.05);">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid rgba(0,0,77,.1);">
                        <span style="color: #666; font-size: 15px;">Tanggal Bermain</span>
                        <span style="font-weight: 700; color: #02025b; font-size: 15px;">{{ \Carbon\Carbon::parse($booking->date)->locale('id')->translatedFormat('l, d F Y') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #666; font-size: 15px;">Waktu</span>
                        <span style="font-weight: 700; color: #02025b; font-size: 15px;">{{ $booking->start_time }} - {{ $booking->end_time }}</span>
                    </div>
                </div>

                <div style="border-top: 2px dashed rgba(0,0,77,.1); padding-top: 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 16px; color: #666; font-weight: 600;">Total Pembayaran</span>
                        <span style="font-size: 24px; font-weight: 800; color: #02025b;">Rp{{ number_format($booking->total_price ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>

            </div>
        </div>

    </section>
</main>
</div>

<script src="{{ asset('js/player-dashboard.js') }}"></script>
</body>
</html>
