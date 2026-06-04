@php
    use Carbon\Carbon;
    $unreadCount = auth()->user()->unreadNotifications()->count();

    $typeConfig = [
        'owner_new_booking'      => ['icon' => 'fa-calendar-plus',      'category' => 'PEMESANAN', 'color' => '#dc2626', 'bg' => '#fef2f2'],
        'owner_payment_received' => ['icon' => 'fa-circle-check',       'category' => 'PEMESANAN', 'color' => '#006c49', 'bg' => '#f0fdf4'],
        'owner_booking_cancelled'=> ['icon' => 'fa-ban',                'category' => 'PEMESANAN', 'color' => '#991b1b', 'bg' => '#fee2e2'],
        'owner_promo_claimed'   => ['icon' => 'fa-tag',                'category' => 'PROMO',     'color' => '#7f4f00', 'bg' => '#fffbeb'],
        'owner_new_review'      => ['icon' => 'fa-star',               'category' => 'SISTEM',    'color' => '#f59e0b', 'bg' => '#fff7ed'],
    ];
    $defaultTypeCfg = ['icon' => 'fa-bell', 'category' => 'SISTEM', 'color' => '#5c403c', 'bg' => '#f6f3f2'];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Notifikasi - Spiessport Portal Pemilik</title>
    @vite(['resources/css/app.css', 'resources/css/owner-dashboard.css'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <style>
        body { background-color: #f7f2f2; }
        .notif-unread-bar { position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: #dc2626; border-radius: 4px 0 0 4px; }
        .notif-item { transition: all 0.2s ease; }
        .notif-item:hover { border-color: #dc2626 !important; }
        .notif-dot { width: 8px; height: 8px; border-radius: 50%; background: #dc2626; flex-shrink: 0; margin-top: 6px; }
        .notif-tab { padding: 10px 20px; border: none; background: transparent; font-weight: 600; font-size: 13px; color: #94a3b8; cursor: pointer; transition: all 0.2s; border-bottom: 3px solid transparent; white-space: nowrap; font-family: inherit; }
        .notif-tab.is-active { color: #dc2626; border-bottom-color: #dc2626; }
        .notif-tab:hover:not(.is-active) { color: #64748b; }
        .notif-stat { background: white; border-radius: 16px; padding: 18px; box-shadow: 0 4px 16px rgba(0,0,0,0.04); }
        .pagination { display: flex; justify-content: center; gap: 8px; padding: 16px 0; }
        .pagination a, .pagination span { padding: 8px 14px; border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none; color: #64748b; background: white; border: 1px solid #e2e8f0; transition: all 0.2s; }
        .pagination a:hover { border-color: #dc2626; color: #dc2626; }
        .pagination .active { background: #dc2626; color: #fff; border-color: #dc2626; }
        @media (max-width: 768px) {
            .notif-tabs-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .notif-stat-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 480px) {
            .notif-stat-grid { grid-template-columns: 1fr; }
            .notif-item-wrap { flex-direction: column; gap: 12px !important; }
        }
    </style>
</head>
<body>
<div class="dashboard-layout">
    @include('owner.navbar')

    <main class="main-content">
        <div class="topbar">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Cari pemesanan, pelanggan...">
            </div>
            <div class="topbar-right">
                <a href="{{ route('owner.notifikasi') }}" class="notif-btn" style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;position:relative;">
                    <i class="fa-solid fa-bell"></i>
                    @if($unreadCount > 0)
                        <span style="position:absolute;top:2px;right:2px;width:10px;height:10px;background:#ef4444;border:2px solid #fff;border-radius:50%;"></span>
                    @endif
                </a>
                <button class="notif-btn" onclick="toggleFaqPopup()"><i class="fa-solid fa-headset"></i></button>
                <a href="{{ route('owner.bantuan') }}" class="notif-btn question" style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;"><i class="fa-solid fa-circle-question"></i></a>
                <a href="#" class="profile-box" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="text-decoration:none;color:inherit;">
                    <div>
                        <h5>{{ auth()->user()->name }}</h5>
                        <p>Profil Pemilik</p>
                    </div>
                    <img src="https://i.pravatar.cc/100" alt="Profil">
                </a>
            </div>
        </div>

        <div class="welcome-section">
            <div>
                <h1>Notifikasi</h1>
                <p>Pantau aktivitas terbaru fasilitas Anda.</p>
            </div>
            @if($unreadCount > 0)
                <form action="{{ route('owner.notifications.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="add-btn" style="padding:10px 18px;font-size:13px;">
                        <i class="fa-solid fa-check-double"></i> Tandai semua telah dibaca
                    </button>
                </form>
            @endif
        </div>

        @php
            $allNotifs = $notifications;
            $bookingCount = $allNotifs->filter(fn($n) => in_array($n->data['type'] ?? '', ['owner_new_booking', 'owner_payment_received', 'owner_booking_cancelled']))->count();
            $promoCount = $allNotifs->filter(fn($n) => ($n->data['type'] ?? '') === 'owner_promo_claimed')->count();
            $systemCount = $allNotifs->filter(fn($n) => ($n->data['type'] ?? '') === 'owner_new_review')->count();
        @endphp

        {{-- Stats --}}
        <div class="stats-grid notif-stat-grid" style="margin-bottom:24px;">
            <div class="stats-card">
                <div>
                    <p>Total Notifikasi</p>
                    <h2 style="color:#dc2626;">{{ $allNotifs->total() }}</h2>
                </div>
                <div class="stats-icon red"><i class="fa-solid fa-bell"></i></div>
            </div>
            <div class="stats-card">
                <div>
                    <p>Belum Dibaca</p>
                    <h2 style="color:#3b82f6;">{{ $unreadCount }}</h2>
                </div>
                <div class="stats-icon blue"><i class="fa-regular fa-bell"></i></div>
            </div>
            <div class="stats-card">
                <div>
                    <p>Pesanan & Pembayaran</p>
                    <h2 style="color:#1db954;">{{ $bookingCount }}</h2>
                </div>
                <div class="stats-icon green"><i class="fa-solid fa-calendar-check"></i></div>
            </div>
            <div class="stats-card">
                <div>
                    <p>Promo Diklaim</p>
                    <h2 style="color:#f59e0b;">{{ $promoCount }}</h2>
                </div>
                <div class="stats-icon yellow"><i class="fa-solid fa-tag"></i></div>
            </div>
        </div>

        <div style="display:flex;flex-wrap:wrap;gap:24px;align-items:start;">
            {{-- Notification List --}}
            <div style="flex:1;min-width:0;max-width:100%;">
                {{-- Tabs --}}
                <div class="notif-tabs-wrap" style="display:flex;gap:0;border-bottom:2px solid #e2e8f0;margin-bottom:20px;">
                    <button class="notif-tab is-active" data-ntab="semua">Semua</button>
                    <button class="notif-tab" data-ntab="booking">Pesanan</button>
                    <button class="notif-tab" data-ntab="promo">Promo</button>
                    <button class="notif-tab" data-ntab="sistem">Sistem</button>
                </div>

                {{-- Notifications Feed --}}
                @if($allNotifs->isEmpty())
                    <div style="text-align:center;padding:60px 20px;color:#94a3b8;">
                        <i class="fa-regular fa-bell-slash" style="font-size:48px;opacity:0.4;margin-bottom:16px;display:block;"></i>
                        <p style="font-size:15px;">Belum ada notifikasi</p>
                    </div>
                @else
                    <div style="display:flex;flex-direction:column;gap:12px;">
                        @foreach($allNotifs as $notif)
                            @php
                                $data = $notif->data;
                                $type = $data['type'] ?? '';
                                $cfg = $typeConfig[$type] ?? $defaultTypeCfg;
                                $isUnread = $notif->unread();

                                $ntabCat = match($type) {
                                    'owner_new_booking', 'owner_payment_received', 'owner_booking_cancelled' => 'booking',
                                    'owner_promo_claimed' => 'promo',
                                    'owner_new_review' => 'sistem',
                                    default => 'sistem',
                                };

                                if ($type === 'owner_new_booking') {
                                    $_title = 'Pesanan Baru';
                                    $_message = $data['user_name'] . ' memesan ' . ($data['field_name'] ?? 'Lapangan') . ' pada ' . Carbon::parse($data['date'] ?? '')->locale('id')->translatedFormat('d M Y') . ' (' . substr($data['start_time'] ?? '', 0, 5) . ' - ' . substr($data['end_time'] ?? '', 0, 5) . ').';
                                } elseif ($type === 'owner_payment_received') {
                                    $_title = 'Pembayaran Diterima';
                                    $_message = 'Pembayaran dari ' . $data['user_name'] . ' untuk ' . ($data['field_name'] ?? 'Lapangan') . ' telah diterima dan pesanan otomatis dikonfirmasi.';
                                } elseif ($type === 'owner_booking_cancelled') {
                                    $_title = 'Pesanan Dibatalkan';
                                    $_message = $data['user_name'] . ' membatalkan ' . ($data['field_name'] ?? 'Lapangan') . ' pada ' . Carbon::parse($data['date'] ?? '')->locale('id')->translatedFormat('d M Y') . ' (' . substr($data['start_time'] ?? '', 0, 5) . ' - ' . substr($data['end_time'] ?? '', 0, 5) . ').';
                                } elseif ($type === 'owner_promo_claimed') {
                                    $_title = 'Promo Diklaim';
                                    $_message = $data['user_name'] . ' mengklaim promo "' . ($data['promo_name'] ?? 'Promo') . '" kode ' . ($data['promo_code'] ?? '') . '.';
                                } elseif ($type === 'owner_new_review') {
                                    $_title = 'Ulasan Baru';
                                    $_message = $data['user_name'] . ' memberikan rating ' . str_repeat('<i class="fa-solid fa-star" style="color:#f59e0b;font-size:12px;"></i>', (int)($data['rating'] ?? 0)) . ' untuk ' . ($data['field_name'] ?? 'Lapangan') . '.';
                                } else {
                                    $_title = 'Notifikasi';
                                    $_message = $data['message'] ?? '';
                                }
                            @endphp
                            <div class="notif-item" data-ntab-item="{{ $ntabCat }}" style="position:relative;background:white;border-radius:16px;padding:18px 20px;border:1px solid {{ $isUnread ? '#fecaca' : '#e2e8f0' }};display:flex;align-items:flex-start;gap:14px;cursor:pointer;overflow:hidden;">
                                @if($isUnread)
                                    <div class="notif-unread-bar"></div>
                                @endif
                                <div style="width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:{{ $cfg['bg'] }};color:{{ $cfg['color'] }};font-size:18px;">
                                    <i class="fa-solid {{ $cfg['icon'] }}"></i>
                                </div>
                                <div style="flex:1;min-width:0;">
                                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;margin-bottom:4px;">
                                        <h3 style="font-size:15px;font-weight:700;color:#1e293b;margin:0;">
                                            {{ $_title }}
                                            <span style="font-size:10px;font-weight:600;color:{{ $cfg['color'] }};background:{{ $cfg['bg'] }};padding:2px 8px;border-radius:999px;margin-left:8px;vertical-align:middle;">{{ $cfg['category'] }}</span>
                                        </h3>
                                        <span style="font-size:11px;color:#94a3b8;white-space:nowrap;flex-shrink:0;">
                                            <i class="fa-regular fa-clock" style="margin-right:3px;"></i>
                                            {{ Carbon::parse($notif->created_at)->locale('id')->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p style="font-size:13px;color:#64748b;margin:0;line-height:1.5;">{!! $_message !!}</p>
                                    @if(in_array($type, ['owner_new_booking', 'owner_booking_cancelled']) && ($data['booking_id'] ?? null))
                                        <div style="margin-top:8px;">
                                            <a href="{{ route('owner.kelolaBooking') }}" style="font-size:12px;font-weight:600;color:#dc2626;text-decoration:none;">
                                                <i class="fa-solid fa-arrow-right" style="margin-right:4px;"></i>Lihat Pesanan
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                @if($isUnread)
                                    <div style="width:8px;height:8px;border-radius:50%;background:#dc2626;flex-shrink:0;margin-top:6px;"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="pagination" style="margin-top:20px;">
                        {{ $allNotifs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>

@include('owner.faq-popup')

<script>
    // Tab switching
    document.querySelectorAll('.notif-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.notif-tab').forEach(function(t) {
                t.classList.remove('is-active');
            });
            tab.classList.add('is-active');
            var activeTab = tab.getAttribute('data-ntab');
            document.querySelectorAll('[data-ntab-item]').forEach(function(item) {
                if (activeTab === 'semua' || item.getAttribute('data-ntab-item') === activeTab) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Mark as read on click
    document.querySelectorAll('.notif-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
            if (e.target.tagName === 'A' || e.target.closest('a')) return;
            var bar = item.querySelector('.notif-unread-bar');
            var dot = item.querySelector('[style*="border-radius:50%;background:#dc2626"]');
            if (bar) bar.style.opacity = '0';
            if (dot) dot.style.opacity = '0';
            item.style.borderColor = '#e2e8f0';
        });
    });
</script>
</body>
</html>
