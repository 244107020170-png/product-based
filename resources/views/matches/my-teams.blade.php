@php
    use Carbon\Carbon;
    $user = auth()->user();
    $userName = $user?->name ?: 'Pecinta Olahraga';
    $currentDate = Carbon::now()->locale('id')->translatedFormat('j F Y');
    $profileAvatar = $user?->avatarUrl();

    $referer = request()->headers->get('referer');
    $previousUrl = url()->previous();
    $currentUrl = url()->current();
    $isInternalReferer = $referer && parse_url($referer, PHP_URL_HOST) === request()->getHost();
    $backUrl = $isInternalReferer && $previousUrl !== $currentUrl ? $previousUrl : route('dashboard');

    $sidebarItems = [
        ['label' => 'Beranda',  'icon' => asset('assets/images/icons/dashboard.png'),  'href' => route('dashboard'),      'active' => false],
        ['label' => 'Aktivitas',  'icon' => asset('assets/images/icons/aktivitas.png'),  'href' => url('/matches'),         'active' => false],
        ['label' => 'Favorit', 'icon' => asset('assets/images/icons/favoritmu.png'),  'href' => route('favorite.index'), 'active' => false],
        ['label' => 'Histori',   'icon' => asset('assets/images/icons/histori.png'),    'href' => route('history.index'),  'active' => false],
        ['label' => 'Cari tim',  'icon' => asset('assets/images/icons/caritim.png'),   'href' => route('matches.index'),  'active' => false],
        ['label' => 'Pemesanan',   'icon' => asset('assets/images/icons/booking.png'),   'href' => route('booking.index'),          'active' => false],
        ['label' => 'Keahlian','icon' => asset('assets/images/icons/keahlian.png'),  'href' => route('skill.index'),    'active' => false],
        ['label' => 'Profil',    'icon' => asset('assets/images/icons/profil.png'),    'href' => route('profile.show'),   'active' => false],
    ];
    $sidebarUtilities = [
        ['label' => 'Bantuan',    'icon' => asset('assets/images/icons/bantuan.png'),    'href' => route('preview.help')],
        ['label' => 'Pengaturan','icon' => asset('assets/images/icons/pengaturan.png'), 'href' => route('profile.edit')],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Tim Saya – {{ config('app.name','Spies Sport') }}</title>
@vite(['resources/css/app.css','resources/css/player-dashboard.css'])
<style>
.tm-main { max-width: 1020px; margin: 0 auto; padding: 24px 20px; }
.tm-title { font-size: clamp(1.4rem, 2.2vw, 1.9rem); font-weight: 800; color: #02025b; margin: 0 0 20px; }
.tm-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
.tm-card { background: #fff; border-radius: 16px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,77,.05); border: 1px solid rgba(0,0,77,.05); text-decoration: none; color: inherit; transition: all .3s ease; display: block; }
.tm-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,77,.12); }
.tm-empty { text-align: center; padding: 60px 20px; color: #9ca3af; }
.tm-empty p { margin: 0; font-size: 15px; }
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
                        <button type="button" class="{{ $cls }}" disabled><span class="player-sidebar__icon-wrap"><img src="{{ $item['icon'] }}" alt="" class="player-sidebar__icon"></span><span class="player-sidebar__label">{{ $item['label'] }}</span></button>
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
                <div style="position: relative;">
                    @include('partials.notification-bell')
                </div>
                <a href="{{ route('profile.show') }}" class="player-profile-pill">
                    <span class="player-profile-pill__avatar"><img src="{{ $profileAvatar }}" alt="Profil" class="player-avatar-image player-avatar-image--profile"></span>
                    <span class="player-profile-pill__name">{{ $userName }}</span>
                </a>
            </div>
        </header>

        <section class="tm-main">
            <a href="{{ $backUrl }}" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none; color: #02025b; font-weight: 600; font-size: 14px; margin-bottom: 12px;">&larr; Kembali</a>
            <h1 class="tm-title">Tim Saya</h1>

            @if($teams->isEmpty())
                <div class="tm-empty">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                    <p style="margin-top: 12px;">Belum ada tim yang dibuat.</p>
                    <a href="{{ route('matches.create') }}" style="display: inline-block; margin-top: 16px; background: #e11d48; color: #fff; padding: 10px 24px; border-radius: 8px; font-weight: 700; text-decoration: none;">Buat Tim Baru</a>
                </div>
            @else
                <div class="tm-grid">
                    @foreach($teams as $tm)
                    <a href="{{ route('matches.show', $tm->id) }}" class="tm-card">
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
                                <span style="color: #e11d48;">{{ \App\Models\MatchPlayer::where('match_id', $tm->id)->paid()->count() }}</span> / {{ $tm->max_player }} pemain
                            </span>
                            <span style="background: #02025b; color: #fff; padding: 4px 12px; border-radius: 8px; font-size: 11px; font-weight: 700;">Detail</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            @endif
        </section>
    </main>
</div>
</body>
</html>
