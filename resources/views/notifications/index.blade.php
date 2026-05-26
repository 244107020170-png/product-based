@php
    use Carbon\Carbon;
    $user = auth()->user();
    $userName = $user?->name ?: 'Pecinta Olahraga';
    $currentDate = Carbon::now()->locale('id')->translatedFormat('j F Y');
    $profileAvatar = $user?->avatarUrl();
    $sidebarItems = [
        ['label' => 'Beranda', 'icon' => asset('assets/images/icons/dashboard.png'), 'href' => route('dashboard'), 'active' => false],
        ['label' => 'Aktivitas', 'icon' => asset('assets/images/icons/aktivitas.png'), 'href' => route('activity.index'), 'active' => false],
        ['label' => 'Favorit', 'icon' => asset('assets/images/icons/favoritmu.png'), 'href' => route('favorite.index'), 'active' => false],
        ['label' => 'Histori', 'icon' => asset('assets/images/icons/histori.png'), 'href' => route('history.index'), 'active' => false],
        ['label' => 'Cari tim', 'icon' => asset('assets/images/icons/caritim.png'), 'href' => route('matches.index'), 'active' => false],
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
    <title>Notifikasi – {{ config('app.name', 'Spies Sport') }}</title>
    @vite(['resources/css/app.css', 'resources/css/player-dashboard.css', 'resources/js/player-dashboard.js'])
    <style>
        .notif-main { max-width: 800px; margin: 0 auto; padding: 24px 16px; }
        .notif-title { margin: 0 0 20px 0; font-size: 1.5rem; font-weight: 900; color: #02025b; }
        .notif-card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,77,.06); border: 1px solid rgba(0,0,77,.08); }
        .notif-item { display: flex; align-items: flex-start; gap: 14px; padding: 16px 20px; border-bottom: 1px solid rgba(0,0,77,.06); }
        .notif-item:last-child { border-bottom: none; }
        .notif-item.unread { background: #f0f7ff; }
        .notif-dot { width: 10px; height: 10px; border-radius: 50%; background: #3b82f6; flex-shrink: 0; margin-top: 6px; }
        .notif-content { flex: 1; }
        .notif-text { font-size: 14px; color: #1f2937; margin: 0 0 4px 0; }
        .notif-text strong { color: #02025b; }
        .notif-time { font-size: 12px; color: #9ca3af; }
        .notif-action { flex-shrink: 0; }
        .btn-sm { padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; border: none; cursor: pointer; }
        .btn-confirm { background: #43a680; color: #fff; }
        .btn-reject { background: #f8d7da; color: #842029; }
        .pagination { padding: 16px 20px; display: flex; justify-content: center; gap: 8px; }
        .pagination a, .pagination span { padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; color: #02025b; background: #f3f4f6; }
        .pagination .active { background: #02025b; color: #fff; }
        .empty-state { text-align: center; padding: 48px 20px; color: #9ca3af; }
        .empty-state svg { width: 64px; height: 64px; margin-bottom: 16px; opacity: .4; }
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
                <a href="{{ route('profile.show') }}" class="player-profile-pill">
                    <span class="player-profile-pill__avatar"><img src="{{ $profileAvatar }}" alt="Profil" class="player-avatar-image player-avatar-image--profile"></span>
                    <span class="player-profile-pill__name">{{ $userName }}</span>
                </a>
            </div>
        </header>

        <section class="notif-main">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1 class="notif-title">Notifikasi</h1>
                @if(auth()->user()->unreadNotifications->isNotEmpty())
                    <form action="{{ route('notifications.markAllRead') }}" method="POST">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: #3b82f6; font-weight: 600; font-size: 13px; cursor: pointer;">Tandai semua sudah dibaca</button>
                    </form>
                @endif
            </div>

            <div class="notif-card">
                @if($notifications->isEmpty())
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 18H15"/><path d="M6.5 17.5H17.5L16.3 15.6C15.9 15 15.7 14.3 15.7 13.6V10.8C15.7 8.49 14.04 6.54 11.8 6.16V5.5C11.8 4.67 11.13 4 10.3 4C9.47 4 8.8 4.67 8.8 5.5V6.16C6.56 6.54 4.9 8.49 4.9 10.8V13.6C4.9 14.3 4.7 15 4.3 15.6L3.1 17.5H6.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                        <p style="margin: 0;">Belum ada notifikasi</p>
                    </div>
                @else
                    @foreach($notifications as $notif)
                        @php
                            $data = $notif->data;
                            $match = \App\Models\Matchs::find($data['match_id'] ?? null);
                        @endphp
                        <div class="notif-item {{ $notif->unread() ? 'unread' : '' }}">
                            @if($notif->unread())
                                <div class="notif-dot"></div>
                            @else
                                <div style="width: 10px; flex-shrink: 0;"></div>
                            @endif
                            <div class="notif-content">
                                <p class="notif-text">
                                    <strong>{{ $data['user_name'] ?? 'Pemain' }}</strong>
                                    @if(($data['type'] ?? '') === 'payment_claimed')
                                        mengklaim sudah bayar untuk pertandingan <strong>{{ $data['match_title'] ?? '#' }}</strong>
                                        — Rp{{ number_format($data['amount'] ?? 0, 0, ',', '.') }}
                                    @else
                                        {{ $data['message'] ?? '' }}
                                    @endif
                                </p>
                                <div class="notif-time">{{ Carbon::parse($notif->created_at)->locale('id')->diffForHumans() }}</div>
                            </div>
                            @if(($data['type'] ?? '') === 'payment_claimed' && $match && $match->created_by === auth()->id())
                                @php $entry = $match->participantEntries->firstWhere('user_id', $data['user_id']); @endphp
                                @if($entry && $entry->isWaiting())
                                    <div class="notif-action" style="display: flex; gap: 6px;">
                                        <form action="{{ route('matches.participant.confirm', [$match->id, $entry->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-sm btn-confirm">Confirm</button>
                                        </form>
                                        <form action="{{ route('matches.participant.reject', [$match->id, $entry->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-sm btn-reject">Reject</button>
                                        </form>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                    <div class="pagination">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </section>
    </main>
</div>
</body>
</html>
