@php
    use Carbon\Carbon;
    $userName = Auth::user()->name ?? 'Pemain';
    $userAvatar = Auth::user()->avatarUrl();
    $currentDate = Carbon::now()->locale('id')->translatedFormat('j F Y');

    $statusLabels = [
        'pending' => 'Menunggu',
        'waiting_payment' => 'Menunggu Pembayaran',
        'waiting_confirmation' => 'Menunggu Konfirmasi',
        'paid' => 'Dibayar',
        'confirmed' => 'Terkonfirmasi',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
        'expired' => 'Kadaluarsa',
        'rejected' => 'Ditolak',
    ];

    $bookings->each(function ($b) {
        if ($b->status === 'waiting_payment' && $b->payment_deadline && now()->greaterThan($b->payment_deadline)) {
            $b->status = 'expired';
        }
    });
    
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
    <title>Pemesanan Saya – {{ config('app.name', 'Spies Sport') }}</title>
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
            <label class="player-search" for="booking-list-search">
                <span class="player-search__icon">
                    <svg viewBox="0 0 20 20" fill="none"><circle cx="9" cy="9" r="5.75" stroke="currentColor" stroke-width="1.8"/><path d="M13.5 13.5L17 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </span>
                <input id="booking-list-search" type="search" placeholder="Cari lapangan...">
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

    <section style="padding: 20px; max-width: 1200px; margin: 0 auto;">

        <div id="toast" style="position: fixed; top: 24px; right: 24px; z-index: 99999; padding: 16px 24px; border-radius: 12px; font-weight: 700; font-size: 14px; color: white; display: none; align-items: center; gap: 12px; box-shadow: 0 8px 32px rgba(0,0,0,.15); max-width: 400px; transform: translateX(120%); transition: transform .3s ease;">
            <span id="toast-icon" style="font-size: 20px; flex-shrink: 0;"></span>
            <span id="toast-msg" style="flex: 1;"></span>
            <button onclick="closeToast()" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer; padding: 0; line-height: 1; opacity: .8;">&times;</button>
        </div>

        @if($bookings->isEmpty())
        <div style="text-align: center; padding: 60px 20px;">
            <h2 style="font-size: 24px; font-weight: bold; color: #02025b; margin-bottom: 10px; display:flex; align-items:center; justify-content:center; gap:8px;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                    <rect x="4" y="3" width="16" height="18" rx="2" stroke="currentColor" stroke-width="2"/>
                    <path d="M8 7H16M8 11H16M8 15H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Belum Ada Pemesanan
            </h2>
            <p style="color: #666; margin-bottom: 30px;">Mulai booking lapangan favorit kamu sekarang juga!</p>
            <a href="{{ route('dashboard') }}" style="display: inline-block; padding: 12px 30px; background: #43a680; color: white; text-decoration: none; border-radius: 8px; font-weight: 600;">
                Cari Lapangan
            </a>
        </div>
        @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            @foreach($bookings as $booking)
            <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,77,.08); border: 1px solid rgba(0,0,77,.06); display: flex; flex-direction: column; height: 100%;">
                <div style="background: linear-gradient(135deg, #02025b 0%, #11114b 100%); color: white; padding: 16px;">
                    <h3 style="margin: 0 0 8px 0; font-size: 18px; font-weight: 800; letter-spacing: .02em;">{{ $booking->field->name }}</h3>
                    <p style="margin: 0; font-size: 14px; opacity: 0.9; display:flex; align-items:center; gap:6px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                            <path d="M12 20.5C12 20.5 18 14.73 18 10.5C18 7.19 15.31 4.5 12 4.5C8.69 4.5 6 7.19 6 10.5C6 14.73 12 20.5 12 20.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            <circle cx="12" cy="10.5" r="2.4" fill="currentColor"/>
                        </svg>
                        <span>{{ $booking->field->location }}</span>
                    </p>
                </div>
                <div style="padding: 16px; display: flex; flex-direction: column; flex: 1;">
                    <div style="margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #f0f0f0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                            <span style="color: #666; display:flex; align-items:center; gap:6px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                    <rect x="3.5" y="5.5" width="17" height="15" rx="2.5" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M7 3.5V7M17 3.5V7M3.5 9.5H20.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                                <span>Tanggal:</span>
                            </span>
                            <span style="font-weight: 700; color: #02025b;">{{ $booking->date->format('d M Y') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 14px;">
                            <span style="color: #666; display:flex; align-items:center; gap:6px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="12" r="8.5" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M12 7.5V12.5L15 14.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>Jam:</span>
                            </span>
                            <span style="font-weight: 700; color: #02025b;">{{ $booking->start_time }} - {{ $booking->end_time }}</span>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 14px; color: #666;">Total: <span style="font-weight: 800; color: #02025b;">Rp{{ number_format($booking->total_price ?? 0, 0, ',', '.') }}</span></span>
                        <span style="display: inline-block; padding: 6px 12px; border-radius: 50px; font-size: 12px; font-weight: 600;
                            {{ $booking->status === 'confirmed' ? 'background: #d4edda; color: #155724;' : ($booking->status === 'expired' ? 'background: #fee2e2; color: #991b1b;' : 'background: #fff3cd; color: #856404;') }}">
                            {{ $statusLabels[$booking->status] ?? ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </div>
                    
                    <div style="margin-top: auto; border-top: 1px dashed rgba(0,0,77,.1); padding-top: 16px; display: flex; flex-direction: column; gap: 8px;">
                        @if($booking->status === 'expired')
                        <a href="{{ route('booking.show', $booking->field_id) }}" class="booking-detail-btn" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 12px 0; background: #842029; color: white; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: .95rem; border: none; transition: all .2s ease;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                            Pesan Lapangan Lagi
                        </a>
                        @else
                        <a href="{{ route('booking.detail', $booking->id) }}" class="booking-detail-btn" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 12px 0; background: #f5f7fa; color: #02025b; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: .95rem; border: 1px solid rgba(0,0,77,.1); transition: all .2s ease;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            Lihat Detail Pemesanan
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </section>
</main>
</div>

<style>
    .booking-detail-btn:hover {
        background: #ebeef4 !important;
        transform: translateY(-1px);
    }
</style>
<script src="{{ asset('js/player-dashboard.js') }}"></script>
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
