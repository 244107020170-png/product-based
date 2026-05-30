@php
    use Carbon\Carbon;
    $userName = Auth::check() ? Auth::user()->name : 'Pemain';
    $userAvatar = Auth::check() ? Auth::user()->avatarUrl() : asset('assets/images/characters/profil1.png');
    $currentDate = Carbon::now()->locale('id')->translatedFormat('j F Y');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rekomendasi – {{ config('app.name', 'Spies Sport') }}</title>
    @vite(['resources/css/app.css', 'resources/css/player-dashboard.css'])
</head>
<body class="player-dashboard-page" style="--player-dashboard-bg:url('{{ asset('assets/images/bg/bg-login.png') }}');">
<div class="player-dashboard-shell">
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
        <section style="padding: 40px 24px; max-width: 800px; margin: 0 auto; text-align: center;">
            <div style="background: white; border-radius: 24px; padding: 60px 40px; box-shadow: 0 4px 20px rgba(0,0,77,.05); border: 1px solid rgba(0,0,77,.08);">
                <div style="font-size: 64px; margin-bottom: 24px;">🚀</div>
                <h1 style="font-size: 28px; font-weight: 800; color: #02025b; margin: 0 0 12px 0;">Halaman Rekomendasi</h1>
                <p style="font-size: 16px; color: #666; margin: 0 0 24px 0;">Halaman ini akan segera hadir. Pantau terus update dari kami!</p>
                <p style="font-size: 14px; color: #999;">Recommendation Page Coming Soon</p>
                <a href="{{ route('dashboard') }}" style="display: inline-block; margin-top: 24px; padding: 12px 32px; background: #02025b; color: white; border-radius: 12px; font-weight: 700; text-decoration: none;">Kembali ke Beranda</a>
            </div>
        </section>
    </main>
</div>
</body>
</html>
