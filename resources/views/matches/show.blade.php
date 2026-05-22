@php
    use Carbon\Carbon;
    $user = auth()->user();
    $userName = $user?->name ?: 'Sport Enthusiast';
    $currentDate = Carbon::now()->locale('id')->translatedFormat('j F Y');
    $profileAvatar = $user?->avatarUrl();
    
    $sidebarItems = [
        ['label'=>'Beranda', 'icon'=>asset('assets/images/icons/dashboard.png'), 'href'=>route('dashboard'), 'active'=>false],
        ['label'=>'Aktivitas', 'icon'=>asset('assets/images/icons/aktivitas.png'), 'href'=>route('activity.index'), 'active'=>false],
        ['label'=>'Favorit', 'icon'=>asset('assets/images/icons/favoritmu.png'), 'href'=>route('favorite.index'), 'active'=>false],
        ['label'=>'Histori', 'icon'=>asset('assets/images/icons/histori.png'), 'href'=>route('history.index'), 'active'=>false],
        ['label'=>'Cari tim', 'icon'=>asset('assets/images/icons/caritim.png'), 'href'=>route('matches.index'), 'active'=>true],
        ['label'=>'Pemesanan', 'icon'=>asset('assets/images/icons/booking.png'), 'href'=>route('booking.index'), 'active'=>false],
        ['label'=>'Keahlian', 'icon'=>asset('assets/images/icons/keahlian.png'), 'href'=>route('skill.index'), 'active'=>false],
        ['label'=>'Profil', 'icon'=>asset('assets/images/icons/profil.png'), 'href'=>route('profile.show'), 'active'=>false],
    ];
    $sidebarUtilities = [
        ['label'=>'Bantuan', 'icon'=>asset('assets/images/icons/bantuan.png'), 'href'=>route('preview.help')],
        ['label'=>'Pengaturan', 'icon'=>asset('assets/images/icons/pengaturan.png'), 'href'=>route('profile.edit')],
    ];

    $playersJoined = $match->players->count();
    $maxPlayers = $match->max_player;
    $percentage = $maxPlayers > 0 ? min(100, ($playersJoined / $maxPlayers) * 100) : 0;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $match->title }} – {{ config('app.name', 'Spies Sport') }}</title>
    @vite(['resources/css/app.css', 'resources/css/player-dashboard.css', 'resources/js/player-dashboard.js'])
    <style>
        .match-detail-main { max-width: 800px; margin: 0 auto; padding: 24px 16px; }
        .btn-back { display: inline-flex; align-items: center; padding: 0 16px; height: 36px; background: rgba(255,255,255,.8); color: #11114b; font-size: .9rem; font-weight: 700; text-decoration: none; border-radius: 8px; transition: all .2s; border: 1.5px solid #14144a; margin-bottom: 20px; }
        .btn-back:hover { background: #fff; transform: translateY(-1px); }
        
        .detail-card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,77,.06); border: 1px solid rgba(0,0,77,.08); }
        .detail-cover { width: 100%; height: 280px; object-fit: cover; display: block; }
        .detail-body { padding: 32px; }
        
        .match-title { margin: 0 0 8px 0; font-size: 2rem; font-weight: 900; color: #02025b; line-height: 1.1; }
        .match-sport-badge { display: inline-block; padding: 6px 12px; background: #e6f0fa; color: #02025b; border-radius: 50px; font-size: .85rem; font-weight: 700; margin-bottom: 20px; border: 1px solid rgba(0,0,77,.1); }
        
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px; }
        .info-block { display: flex; gap: 12px; }
        .info-icon { width: 40px; height: 40px; border-radius: 10px; background: #f5f7fa; display: flex; align-items: center; justify-content: center; color: #02025b; flex-shrink: 0; }
        .info-icon svg { width: 20px; height: 20px; }
        .info-text-label { display: block; font-size: .85rem; color: #666; font-weight: 600; margin-bottom: 2px; }
        .info-text-val { display: block; font-size: 1rem; font-weight: 700; color: #111; line-height: 1.3; }

        .creator-block { display: flex; align-items: center; gap: 12px; padding: 16px; background: #f9fbfd; border-radius: 12px; border: 1px solid rgba(0,0,77,.05); margin-bottom: 32px; }
        .creator-avatar { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; }
        .creator-info h4 { margin: 0 0 2px 0; font-size: 1rem; color: #02025b; font-weight: 700; }
        .creator-info p { margin: 0; font-size: .85rem; color: #666; }

        .players-section { margin-bottom: 32px; }
        .players-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 12px; }
        .players-header h3 { margin: 0; font-size: 1.15rem; color: #02025b; font-weight: 800; }
        .players-header span { font-size: .95rem; font-weight: 700; color: #43a680; }
        
        .progress-bar { height: 10px; background: #e0e5ec; border-radius: 10px; overflow: hidden; margin-bottom: 16px; }
        .progress-fill { height: 100%; background: #43a680; border-radius: 10px; transition: width .5s ease; }

        .players-list { display: flex; flex-wrap: wrap; gap: 8px; }
        .player-avatar { width: 40px; height: 40px; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 2px 6px rgba(0,0,0,.1); object-fit: cover; }

        .action-container { border-top: 1px solid rgba(0,0,77,.08); padding-top: 24px; display: flex; justify-content: flex-end; }
        
        .btn-primary { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 14px 32px; background: #43a680; color: #fff; border-radius: 12px; font-weight: 800; font-size: 1.05rem; border: none; cursor: pointer; transition: all .2s; }
        .btn-primary:hover { background: #368d6a; transform: translateY(-1px); }
        .btn-primary:disabled { background: #b0c4b9; cursor: not-allowed; transform: none; }
        
        .btn-disabled { background: #e0e5ec; color: #666; cursor: not-allowed; }
        
        .btn-whatsapp { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 14px 24px; background: #25D366; color: #fff; border-radius: 12px; font-weight: 800; font-size: 1.05rem; border: none; cursor: pointer; transition: all .2s; text-decoration: none; }
        .btn-whatsapp:hover { background: #128C7E; transform: translateY(-1px); }

        @media (max-width: 768px) {
            .info-grid { grid-template-columns: 1fr; gap: 16px; }
            .detail-cover { height: 200px; }
            .detail-body { padding: 24px; }
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
                        <img src="{{ $profileAvatar }}" alt="Profil" class="player-avatar-image player-avatar-image--profile">
                    </span>
                    <span class="player-profile-pill__name">{{ $userName }}</span>
                </a>
            </div>
        </header>

        <section class="match-detail-main">
            <a href="{{ route('matches.index') }}" class="btn-back">&larr; Kembali</a>

            @if(session('error'))
                <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="detail-card">
                <img src="{{ $image }}" alt="{{ $sport }}" class="detail-cover">
                
                <div class="detail-body">
                    <div class="match-sport-badge">{{ $sport }}</div>
                    <h1 class="match-title">{{ $match->title }}</h1>
                    
                    <div class="creator-block">
                        <img src="{{ $match->creator->avatarUrl() }}" alt="Creator" class="creator-avatar">
                        <div class="creator-info">
                            <h4>{{ $match->creator->name }}</h4>
                            <p>Pembuat Match (Host)</p>
                        </div>
                    </div>

                    <div class="info-grid">
                        <div class="info-block">
                            <div class="info-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            </div>
                            <div>
                                <span class="info-text-label">Lokasi</span>
                                <span class="info-text-val">{{ $match->field->name ?? 'Belum ditentukan' }}<br><span style="font-size: .85rem; color: #666; font-weight:500;">{{ $match->field->location ?? '' }}</span></span>
                            </div>
                        </div>
                        <div class="info-block">
                            <div class="info-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            </div>
                            <div>
                                <span class="info-text-label">Waktu Bermain</span>
                                <span class="info-text-val">{{ $match->formattedDate() }}<br>{{ $match->timeRange() }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="players-section">
                        <div class="players-header">
                            <h3>Pemain Bergabung</h3>
                            <span>{{ $playersJoined }} / {{ $maxPlayers }} Pemain</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $percentage }}%;"></div>
                        </div>
                        
                        @if($playersJoined > 0)
                            <div class="players-list">
                                @foreach($match->players as $p)
                                    <img src="{{ $p->avatarUrl() }}" alt="{{ $p->name }}" class="player-avatar" title="{{ $p->name }}">
                                @endforeach
                            </div>
                        @else
                            <p style="margin: 0; color: #888; font-size: .9rem;">Belum ada pemain yang bergabung. Jadilah yang pertama!</p>
                        @endif
                    </div>

                    <div class="action-container">
                        @if($isCreator)
                            <button type="button" class="btn-primary btn-disabled" disabled>Ini Tim Buatanmu</button>
                        @elseif($hasJoined)
                            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                <button type="button" class="btn-primary btn-disabled" disabled>Sudah Bergabung</button>
                                @php
                                    $waNumber = $match->creator->phone ? preg_replace('/[^0-9]/', '', $match->creator->phone) : '6281234567890';
                                @endphp
                                <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="btn-whatsapp">
                                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.052 0C5.495 0 .16 5.333.158 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.332 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                                    Hubungi Host (WA)
                                </a>
                            </div>
                        @elseif($playersJoined >= $maxPlayers)
                            <button type="button" class="btn-primary btn-disabled" disabled>Tim Penuh</button>
                        @else
                            <form action="{{ route('matches.join', $match->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-primary">
                                    <svg viewBox="0 0 24 24" fill="none" width="20" height="20"><path d="M12.6 20.2C12.26 20.36 11.86 20.36 11.52 20.2C8.52 18.76 5 15.8 5 11.94C5 8.95 7.42 6.53 10.4 6.53C11.57 6.53 12.67 6.9 13.58 7.59C14.49 6.9 15.59 6.53 16.76 6.53C19.74 6.53 22.16 8.95 22.16 11.94C22.16 15.8 18.64 18.76 15.64 20.2" fill="currentColor"/></svg>
                                    Join Tim Sekarang
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>
