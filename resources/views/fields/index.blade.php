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
            @media (max-width: 768px) {
                .dashboard-header-flex {
                    flex-direction: column;
                    align-items: flex-start;
                }
                .hero-content-inner {
                    max-width: 100% !important;
                }
            }
        </style>

        <!-- NEW DASHBOARD HEADER -->
        <div class="dashboard-header-flex">
            <h1 style="font-size: 28px; font-weight: 800; color: #02025b; margin: 0;">Dashboard</h1>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <select style="padding: 10px 16px; border-radius: 8px; border: 1px solid rgba(0,0,77,.1); outline: none; font-weight: 600; color: #02025b; background: white;">
                    <option>By Day</option>
                </select>
                <a href="{{ route('matches.create') }}" style="background: #e11d48; color: white; padding: 10px 20px; border-radius: 8px; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: background .2s; text-align: center;">
                    Buat Pertandingan Baru
                </a>
            </div>
        </div>

        <!-- HERO SECTION -->
        <div class="hero-section">
            <!-- Hero Banner -->
            <div style="background: white; border-radius: 20px; padding: 32px; box-shadow: 0 4px 20px rgba(0,0,77,.05); border: 1px solid rgba(0,0,77,.05); position: relative; overflow: hidden; min-height: 220px; display: flex; flex-direction: column; justify-content: center;">
                <div class="hero-content-inner" style="position: relative; z-index: 2; max-width: 60%;">
                    <h2 style="font-size: 32px; font-weight: 900; color: #02025b; margin: 0 0 20px 0;">Hi, {{ Auth::user()->name ?? 'Sport Enthusiast' }}!</h2>
                    
                    <div style="background: rgba(255,255,255,.9); padding: 16px; border-radius: 12px; display: flex; gap: 16px; align-items: center; box-shadow: 0 4px 12px rgba(0,0,0,.05); max-width: 320px;">
                        <img src="{{ Auth::user()->avatarUrl() }}" alt="Profil" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                        <div>
                            <p style="margin: 0; font-weight: 800; color: #02025b; font-size: 14px;">Gimana permainan nya?</p>
                            <p style="margin: 2px 0; font-size: 12px; color: #666;">Silakan review permainan terakhir kamu.</p>
                            <div style="display: flex; gap: 4px; margin-top: 4px;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="#cbd5e1"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="#cbd5e1"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="#cbd5e1"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="#cbd5e1"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="#cbd5e1"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
                <img src="{{ asset('assets/images/characters/hero.png') }}" class="hero-char-img" style="position: absolute; right: 0; bottom: 0; height: 130%; z-index: 1; object-fit: contain;">
                <style>
                    @media (max-width: 768px) {
                        .hero-char-img {
                            opacity: 0.2;
                            height: 100% !important;
                            right: -20px !important;
                        }
                    }
                </style>
            </div>

            <!-- Notifikasi -->
            <div style="background: white; border-radius: 20px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,77,.05); border: 1px solid rgba(0,0,77,.05); display: flex; flex-direction: column;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: #02025b;">Notifikasi</h3>
                    <a href="#" style="font-size: 13px; color: #666; text-decoration: none; font-weight: 600;">Lihat semua</a>
                </div>
                <div style="display: flex; flex-direction: column; gap: 12px; overflow-y: auto; max-height: 200px;">
                    @foreach(range(1, 3) as $i)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f8fafc; border-radius: 12px; border: 1px solid rgba(0,0,77,.03);">
                        <div style="display: flex; gap: 12px; align-items: center;">
                            <div style="width: 32px; height: 32px; background: #02025b; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20.5a8.5 8.5 0 100-17 8.5 8.5 0 000 17z"/></svg>
                            </div>
                            <div>
                                <p style="margin: 0; font-size: 14px; font-weight: 700; color: #02025b;">Sistem Info</p>
                                <p style="margin: 2px 0 0 0; font-size: 12px; color: #666;">Silakan lengkapi profil</p>
                            </div>
                        </div>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#02025b" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 4 WIDGETS -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 32px;">
            <!-- Upcoming Match -->
            <div style="background: white; border-radius: 20px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,77,.05); border: 1px solid rgba(0,0,77,.05); display: flex; flex-direction: column;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#02025b" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <h4 style="margin: 0; font-size: 15px; font-weight: 800; color: #02025b;">Upcoming Match</h4>
                    </div>
                    <span style="background: #bbf7d0; color: #166534; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700;">{{ $upcomingMatch ? 'Akan datang' : '-' }}</span>
                </div>
                @if($upcomingMatch)
                    <h3 style="margin: 0 0 4px 0; font-size: 18px; font-weight: 800; color: #02025b;">{{ $upcomingMatch->title }}</h3>
                    <p style="margin: 0 0 16px 0; font-size: 13px; color: #666;">{{ $upcomingMatch->field->name ?? 'Venue' }}</p>
                    <a href="{{ route('matches.show', $upcomingMatch->id) }}" style="margin-top: auto; background: #e11d48; color: white; border: none; padding: 10px; border-radius: 8px; font-weight: 700; cursor: pointer; text-align: center; text-decoration: none;">View Details</a>
                @else
                    <p style="margin: 0; color: #888; font-size: 14px;">Belum ada jadwal.</p>
                @endif
            </div>

            <!-- Rekomendasi Match -->
            <div style="background: white; border-radius: 20px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,77,.05); border: 1px solid rgba(0,0,77,.05); display: flex; flex-direction: column;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#e11d48" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                        <h4 style="margin: 0; font-size: 15px; font-weight: 800; color: #02025b;">Rekomendasi Match</h4>
                    </div>
                    <a href="{{ route('matches.index') }}" style="font-size: 12px; color: #666; font-weight: 600; text-decoration: none;">See all</a>
                </div>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @forelse($recommendedMatches as $rm)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 8px; border-bottom: 1px solid rgba(0,0,77,.05);">
                            <div>
                                <span style="background: #fee2e2; color: #e11d48; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 800;">For You</span>
                                <span style="font-size: 14px; font-weight: 800; color: #02025b; margin-left: 6px;">{{ $rm->title }}</span>
                                <p style="margin: 4px 0 0 0; font-size: 11px; color: #666;">{{ $rm->field->name ?? '' }}</p>
                            </div>
                            <a href="{{ route('matches.show', $rm->id) }}" style="color: #02025b;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 16 16 12 12 8"/><line x1="8" y1="12" x2="16" y2="12"/></svg></a>
                        </div>
                    @empty
                        <p style="margin: 0; font-size: 13px; color: #888;">Tidak ada rekomendasi.</p>
                    @endforelse
                </div>
            </div>

            <!-- Lapangan Favorit -->
            <div style="background: white; border-radius: 20px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,77,.05); border: 1px solid rgba(0,0,77,.05); display: flex; flex-direction: column;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="#e11d48"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        <h4 style="margin: 0; font-size: 15px; font-weight: 800; color: #02025b;">Lapangan Favorit</h4>
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @forelse($favoriteFields as $ff)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 8px; border-bottom: 1px solid rgba(0,0,77,.05);">
                            <div>
                                <span style="font-size: 14px; font-weight: 800; color: #02025b;">{{ $ff->field->name ?? '' }}</span>
                                <p style="margin: 4px 0 0 0; font-size: 11px; color: #666;">{{ $ff->field->location ?? '' }}</p>
                            </div>
                            <a href="{{ route('booking.show', $ff->field_id) }}" style="background: #bbf7d0; color: #166534; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; text-decoration: none;">Quick Book</a>
                        </div>
                    @empty
                        <p style="margin: 0; font-size: 13px; color: #888;">Belum ada favorit.</p>
                    @endforelse
                </div>
                <a href="{{ route('favorite.index') }}" style="margin-top: auto; font-size: 13px; color: #666; font-weight: 600; text-decoration: none; text-align: right;">See all ></a>
            </div>

            <!-- Badge Pemain -->
            <div style="background: white; border-radius: 20px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,77,.05); border: 1px solid rgba(0,0,77,.05); display: flex; flex-direction: column;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="#f59e0b"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <h4 style="margin: 0; font-size: 15px; font-weight: 800; color: #02025b;">Badge Pemain</h4>
                    </div>
                </div>
                
                <div style="background: #f8fafc; padding: 16px; border-radius: 12px; margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 13px; color: #666;">Level:</span>
                        <span style="font-size: 13px; font-weight: 800; color: {{ Auth::user()->tierColor() }};">{{ Auth::user()->tierName() }}</span>
                    </div>
                    <div style="height: 6px; background: #e2e8f0; border-radius: 6px; overflow: hidden; margin-bottom: 8px;">
                        <div style="height: 100%; width: {{ Auth::user()->progressPercentage() }}%; background: #3b82f6; border-radius: 6px;"></div>
                    </div>
                    <p style="margin: 0; font-size: 11px; color: #888; text-align: center;">{{ Auth::user()->points ?? 0 }} Points / {{ Auth::user()->nextTierTarget() }}</p>
                </div>

                <div style="display: flex; justify-content: space-between; text-align: center;">
                    <div>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#fbbf24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <p style="margin: 4px 0 0 0; font-size: 11px; font-weight: 700; color: #02025b;">Beginner</p>
                        <span style="font-size: 9px; color: #666;">0-20 Pts</span>
                    </div>
                    <div>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#3b82f6"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <p style="margin: 4px 0 0 0; font-size: 11px; font-weight: 700; color: #02025b;">Pro</p>
                        <span style="font-size: 9px; color: #666;">20-50 Pts</span>
                    </div>
                    <div>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#e11d48"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <p style="margin: 4px 0 0 0; font-size: 11px; font-weight: 700; color: #02025b;">Master</p>
                        <span style="font-size: 9px; color: #666;">>50 Pts</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pesan Lagi -->
        <div style="margin-bottom: 40px;">
            <h2 style="font-size: 24px; font-weight: 800; color: #02025b; margin-bottom: 16px;">Pesan lagi</h2>
            <div style="background: white; border-radius: 20px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,77,.05); border: 1px solid rgba(0,0,77,.05);">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                    @foreach($pesanLagiFields as $plf)
                    <div>
                        <div style="border-radius: 12px; overflow: hidden; height: 160px; margin-bottom: 12px; position: relative;">
                            <img src="{{ $plf->image ?? asset('assets/images/bg/Explore.png') }}" alt="{{ $plf->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h4 style="margin: 0 0 4px 0; font-size: 16px; font-weight: 800; color: #02025b;">{{ $plf->name }}</h4>
                        <div style="display: flex; gap: 8px; align-items: center; color: #e11d48;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span style="font-size: 12px; font-weight: 600; color: #666;">{{ rand(2, 8) }}.{{ rand(1,9) }} KM</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- LAPANGAN TERSEDIA (Original Section) -->
        <div style="margin-bottom: 30px;">
            <h2 style="font-size: 24px; font-weight: 800; color: #02025b; margin-bottom: 10px; display:flex; align-items:center; gap:8px;">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none">
                    <rect x="3" y="8" width="18" height="11" rx="2" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M7 8V5.5M12 8V5.5M17 8V5.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M3 13H21" stroke="currentColor" stroke-width="1.8"/>
                </svg>
                <span>Lapangan Tersedia</span>
            </h2>
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
                            <span style="display:inline-flex;vertical-align:-2px;margin-right:4px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 3L14.9 8.7L21 9.6L16.5 14L17.6 20L12 17.1L6.4 20L7.5 14L3 9.6L9.1 8.7L12 3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                </svg>
                            </span>{{ $field->rating ?? '4.8' }}
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
