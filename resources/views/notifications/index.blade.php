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

    $typeConfig = [
        'booking_confirmed'        => ['icon' => 'confirmation_number', 'category' => 'PEMESANAN', 'bg' => 'bg-primary/10', 'text' => 'text-primary'],
        'booking_payment_received' => ['icon' => 'payments',            'category' => 'PEMESANAN', 'bg' => 'bg-primary/10', 'text' => 'text-primary'],
        'payment_claimed'          => ['icon' => 'groups',              'category' => 'KOMUNITAS', 'bg' => 'bg-tertiary-fixed-dim/20', 'text' => 'text-tertiary'],
        'payment_confirmed'        => ['icon' => 'check_circle',        'category' => 'KOMUNITAS', 'bg' => 'bg-tertiary-fixed-dim/20', 'text' => 'text-tertiary'],
        'community_joined'         => ['icon' => 'group_add',           'category' => 'KOMUNITAS', 'bg' => 'bg-tertiary-fixed-dim/20', 'text' => 'text-tertiary'],
    ];
    $defaultTypeCfg = ['icon' => 'notifications', 'category' => 'SISTEM', 'bg' => 'bg-secondary-container/30', 'text' => 'text-secondary'];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Notifikasi – {{ config('app.name', 'Spies Sport') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=Poppins:wght@400;500;600&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/player-dashboard.css', 'resources/js/player-dashboard.js'])
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'on-tertiary-container': '#fff7f1',
                        'on-tertiary-fixed-variant': '#653e00',
                        'primary-fixed': '#ffdad6',
                        'on-surface': '#1b1c1b',
                        'on-secondary-container': '#00734e',
                        'on-primary-container': '#fff6f5',
                        'on-primary': '#ffffff',
                        'error-container': '#ffdad6',
                        'secondary-fixed': '#89f8c2',
                        'outline': '#916f6b',
                        'on-secondary-fixed': '#002113',
                        'inverse-primary': '#ffb4ab',
                        'primary-fixed-dim': '#ffb4ab',
                        'error': '#ba1a1a',
                        'on-primary-fixed': '#410002',
                        'background': '#fcf9f8',
                        'on-tertiary': '#ffffff',
                        'surface-container-highest': '#e4e2e1',
                        'surface-bright': '#fcf9f8',
                        'on-primary-fixed-variant': '#93000b',
                        'surface-container-low': '#f6f3f2',
                        'tertiary-fixed-dim': '#ffb95f',
                        'secondary-container': '#89f8c2',
                        'secondary': '#006c49',
                        'secondary-fixed-dim': '#6cdba7',
                        'inverse-on-surface': '#f3f0ef',
                        'tertiary-container': '#a06500',
                        'on-secondary': '#ffffff',
                        'primary-container': '#dc2626',
                        'on-tertiary-fixed': '#2a1700',
                        'on-surface-variant': '#5c403c',
                        'inverse-surface': '#303030',
                        'surface-dim': '#dcd9d8',
                        'surface-container-lowest': '#ffffff',
                        'primary': '#b70011',
                        'outline-variant': '#e6bdb8',
                        'tertiary-fixed': '#ffddb8',
                        'on-error-container': '#93000a',
                        'surface-variant': '#e4e2e1',
                        'tertiary': '#7f4f00',
                        'surface-container-high': '#eae7e7',
                        'on-secondary-fixed-variant': '#005236',
                        'surface-tint': '#bf0715',
                        'surface': '#fcf9f8',
                        'on-error': '#ffffff',
                        'surface-container': '#f0edec',
                        'on-background': '#1b1c1b',
                        'brand-accent': '#EB5436',
                        'portal-bg': '#FDF9ED',
                    },
                    fontFamily: {
                        'display-lg': ['Poppins'],
                        'stat-sm': ['Poppins'],
                        'headline-md': ['Poppins'],
                        'headline-sm': ['Poppins'],
                        'stat-lg': ['Poppins'],
                        'label-caps': ['Poppins'],
                        'body-md': ['Poppins'],
                        'body-lg': ['Poppins'],
                    },
                    fontSize: {
                        'display-lg': ['36px', { lineHeight: '44px', letterSpacing: '-0.02em', fontWeight: '700' }],
                        'stat-sm': ['14px', { lineHeight: '20px', letterSpacing: '0.01em', fontWeight: '500' }],
                        'headline-md': ['24px', { lineHeight: '32px', fontWeight: '600' }],
                        'headline-sm': ['20px', { lineHeight: '28px', fontWeight: '600' }],
                        'stat-lg': ['28px', { lineHeight: '32px', fontWeight: '600' }],
                        'label-caps': ['12px', { lineHeight: '16px', letterSpacing: '0.05em', fontWeight: '600' }],
                        'body-md': ['14px', { lineHeight: '20px', fontWeight: '400' }],
                        'body-lg': ['16px', { lineHeight: '24px', fontWeight: '400' }],
                    },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .custom-shadow { box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.08); }
        body { background-color: #FDF9ED; font-family: 'Poppins', sans-serif; }
        .pagination { display: flex; justify-content: center; gap: 8px; padding: 16px 0; }
        .pagination a, .pagination span { padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; color: #02025b; background: #f3f4f6; }
        .pagination .active { background: #02025b; color: #fff; }
    </style>
</head>
<body class="player-dashboard-page antialiased overflow-x-hidden" style="--player-dashboard-bg:url('{{ asset('assets/images/bg/bg-login.png') }}');">
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

        {{-- Main Content --}}
        <div class="max-w-[1440px] mx-auto px-4 md:px-8 py-6">
            {{-- Page Header --}}
            <div class="flex items-end justify-between mb-8">
                <div>
                    <h2 class="font-display-lg text-display-lg text-on-surface">Notifikasi</h2>
                    <p class="font-body-lg text-on-surface-variant mt-1">Pantau aktivitas olahraga dan penawaran spesial Anda.</p>
                </div>
                @if(auth()->user()->unreadNotifications->isNotEmpty())
                <form action="{{ route('notifications.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center font-semibold text-body-md decoration-2 underline-offset-4 bg-white text-[#00004d] border-2 border-[#00004d] hover:bg-blue-50 px-4 py-2 rounded-lg transition-colors mr-6">
                        Tandai semua telah dibaca
                    </button>
                </form>
                @endif
            </div>

            {{-- Notifications Feed --}}
            @if($notifications->isEmpty())
                <div class="text-center py-16 text-on-surface-variant">
                    <span class="material-symbols-outlined text-5xl opacity-40 mb-4">notifications_off</span>
                    <p class="font-body-lg">Belum ada notifikasi</p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-4">
                    @foreach($notifications as $notif)
                        @php
                            $data = $notif->data;
                            $type = $data['type'] ?? '';
                            $cfg = $typeConfig[$type] ?? $defaultTypeCfg;
                            $isUnread = $notif->unread();
                            $match = \App\Models\Matchs::find($data['match_id'] ?? null);
                            $_mapsLink = $data['maps_link'] ?? null;
                            if (!$_mapsLink && ($data['booking_id'] ?? null) && in_array($type, ['booking_confirmed', 'booking_payment_received'])) {
                                $_mapsLink = optional(optional(\App\Models\Booking::find($data['booking_id']))->field)->maps_link;
                            }

                            if ($type === 'booking_confirmed') {
                                $_title = 'Booking Dikonfirmasi';
                                $_message = 'Booking ' . ($data['field_name'] ?? 'Lapangan') . ' telah dikonfirmasi oleh owner.';
                            } elseif ($type === 'booking_payment_received') {
                                $_title = 'Pembayaran Diterima';
                                $_message = 'Pembayaran untuk ' . ($data['field_name'] ?? 'Lapangan') . ' diterima, menunggu konfirmasi owner.';
                            } elseif ($type === 'payment_claimed') {
                                $_title = 'Klaim Pembayaran';
                                $_message = ($data['user_name'] ?? 'Pemain') . ' mengklaim sudah bayar untuk pertandingan ' . ($data['match_title'] ?? '#') . ' — Rp' . number_format($data['amount'] ?? 0, 0, ',', '.');
                            } elseif ($type === 'payment_confirmed') {
                                $_title = 'Pembayaran Dikonfirmasi';
                                $_message = 'Pembayaran untuk ' . ($data['match_title'] ?? 'pertandingan') . ' telah dikonfirmasi.';
                            } elseif ($type === 'community_joined') {
                                $_title = 'Anggota Komunitas Baru';
                                $_message = ($data['user_name'] ?? 'Seseorang') . ' bergabung ke komunitas ' . ($data['community_name'] ?? '');
                            } else {
                                $_title = 'Notifikasi';
                                $_message = $data['message'] ?? '';
                            }
                        @endphp
                        <div class="group relative flex items-start gap-5 bg-white p-6 rounded-xl custom-shadow {{ $isUnread ? 'border-l-[6px] border-brand-accent' : 'border-l-[6px] border-transparent' }} hover:translate-x-1 transition-all cursor-pointer">
                            <div class="w-12 h-12 rounded-full {{ $cfg['bg'] }} flex items-center justify-center {{ $cfg['text'] }} shrink-0">
                                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">{{ $cfg['icon'] }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-1 gap-2">
                                    <h3 class="font-headline-sm text-on-surface">{{ $_title }}</h3>
                                    <span class="text-xs font-label-caps text-on-surface-variant bg-surface-container px-2 py-1 rounded shrink-0">{{ $cfg['category'] }}</span>
                                </div>
                                <p class="font-body-lg text-on-surface-variant mb-2">{{ $_message }}</p>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm text-on-surface-variant">schedule</span>
                                    <span class="font-body-md text-on-surface-variant text-sm">{{ Carbon::parse($notif->created_at)->locale('id')->diffForHumans() }}</span>
                                </div>

                                {{-- Maps link for booking_confirmed --}}
                                @if($type === 'booking_confirmed' && !empty($_mapsLink))
                                <div class="mt-2">
                                    <a href="{{ $_mapsLink }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-sm font-semibold text-brand-accent hover:underline">
                                        <span class="material-symbols-outlined text-base">map</span>
                                        Buka Google Maps
                                    </a>
                                </div>
                                @endif

                                {{-- Detail link --}}
                                @if(in_array($type, ['booking_confirmed', 'booking_payment_received']) && ($data['booking_id'] ?? null))
                                <div class="mt-1">
                                    <a href="{{ route('booking.detail', $data['booking_id']) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-brand-accent hover:underline">
                                        <span class="material-symbols-outlined text-base">visibility</span>
                                        Lihat Detail
                                    </a>
                                </div>
                                @endif

                                {{-- Confirm/Reject for match host --}}
                                @if($type === 'payment_claimed' && $match && $match->created_by === auth()->id())
                                    @php $entry = $match->participantEntries->firstWhere('user_id', $data['user_id']); @endphp
                                    @if($entry && $entry->isWaiting())
                                    <div class="flex gap-2 mt-3">
                                        <form action="{{ route('matches.participant.confirm', [$match->id, $entry->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-semibold hover:bg-green-700 transition-colors">Konfirmasi</button>
                                        </form>
                                        <form action="{{ route('matches.participant.reject', [$match->id, $entry->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-red-100 text-red-800 rounded-lg text-sm font-semibold hover:bg-red-200 transition-colors">Tolak</button>
                                        </form>
                                    </div>
                                    @endif
                                @endif
                            </div>
                            @if($isUnread)
                            <div class="w-2.5 h-2.5 bg-brand-accent rounded-full absolute top-6 right-6 shadow-[0_0_8px_rgba(235,84,54,0.6)]"></div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="pagination mt-8">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </main>
</div>

<script>
    document.querySelectorAll('.group').forEach(function(item) {
        item.addEventListener('click', function() {
            var dot = item.querySelector('.bg-brand-accent.rounded-full.absolute');
            if (dot) {
                dot.style.opacity = '0';
                item.classList.remove('border-brand-accent');
                item.classList.add('border-transparent');
            }
        });
    });
</script>
</body>
</html>