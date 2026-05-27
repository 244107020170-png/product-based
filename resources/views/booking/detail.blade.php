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

    $statusStyles = [
        'pending' => ['bg' => '#fef3c7', 'color' => '#92400e', 'dot' => '#d97706'],
        'waiting_payment' => ['bg' => '#fef3c7', 'color' => '#92400e', 'dot' => '#d97706'],
        'waiting_confirmation' => ['bg' => '#fef3c7', 'color' => '#92400e', 'dot' => '#d97706'],
        'paid' => ['bg' => '#d1fae5', 'color' => '#065f46', 'dot' => '#10b981'],
        'confirmed' => ['bg' => '#bbf7d0', 'color' => '#166534', 'dot' => '#16a34a'],
        'completed' => ['bg' => '#d1fae5', 'color' => '#065f46', 'dot' => '#10b981'],
        'cancelled' => ['bg' => '#fee2e2', 'color' => '#991b1b', 'dot' => '#ef4444'],
        'expired' => ['bg' => '#fee2e2', 'color' => '#842029', 'dot' => '#dc2626'],
        'rejected' => ['bg' => '#fee2e2', 'color' => '#991b1b', 'dot' => '#dc2626'],
    ];

    $isExpired = $booking->status === 'expired' || ($booking->status === 'waiting_payment' && $booking->payment_deadline && now()->greaterThan($booking->payment_deadline));
    if ($isExpired) {
        $booking->status = 'expired';
    }
    $bookingStatusLabel = $statusLabels[$booking->status] ?? ucfirst(str_replace('_', ' ', $booking->status));
    $bookingStatusStyle = $statusStyles[$booking->status] ?? ['bg' => '#f4f6fb', 'color' => '#111', 'dot' => '#43a680'];

    $paymentCountdownLabel = '-';
    if (in_array($booking->status, ['waiting_confirmation', 'confirmed', 'paid', 'completed'])) {
        $paymentCountdownLabel = 'Selesai';
    } elseif ($booking->status === 'expired') {
        $paymentCountdownLabel = 'Kadaluarsa';
    } elseif ($booking->status === 'waiting_payment' && $booking->payment_deadline) {
        if (now()->lte($booking->payment_deadline)) {
            $remaining = $booking->payment_deadline->diff(now());
            $segments = [];
            if ($remaining->d) $segments[] = $remaining->d . ' hari';
            if ($remaining->h) $segments[] = $remaining->h . ' jam';
            if ($remaining->i) $segments[] = $remaining->i . ' menit';
            if (empty($segments)) $segments[] = 'kurang dari 1 menit';
            $paymentCountdownLabel = implode(' ', array_slice($segments, 0, 2)) . ' tersisa';
        } else {
            $paymentCountdownLabel = 'Kadaluarsa';
        }
    }
    
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

    $referer = request()->headers->get('referer');
    $previousUrl = url()->previous();
    $currentUrl = url()->current();
    $isInternalReferer = $referer && parse_url($referer, PHP_URL_HOST) === request()->getHost();
    $backUrl = $isInternalReferer && $previousUrl !== $currentUrl ? $previousUrl : route('booking.index');
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

    <section style="padding: 24px; max-width: 900px; margin: 0 auto;">
        
        <div style="margin-bottom: 20px;">
            <a href="{{ $backUrl }}" style="display: inline-flex; align-items: center; padding: 0 20px; height: 40px; background: rgba(255,255,255,.76); color: #11114b; font-size: .95rem; font-weight: 700; text-decoration: none; border-radius: 10px; transition: all .2s ease; border: 1.8px solid #14144a;">
                &larr; Kembali
            </a>
        </div>

        <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,77,.06); border: 1px solid rgba(0,0,77,.08);">
            
<div style="background: linear-gradient(135deg, #02025b 0%, #11114b 100%); color: white; padding: 24px; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                    <div>
                        <h1 style="margin: 0 0 8px 0; font-size: 24px; font-weight: 800;">Detail Booking</h1>
                        <p style="margin: 0; font-size: 14px; opacity: 0.9;">Booking ID: #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div style="margin-top: 8px;">
                        <span style="display: inline-flex; align-items: center; gap: 10px; padding: 10px 18px; border-radius: 50px; font-size: 14px; font-weight: 700; background: {{ $bookingStatusStyle['bg'] }}; color: {{ $bookingStatusStyle['color'] }};">
                            <span style="width: 10px; height: 10px; border-radius: 50%; background: {{ $bookingStatusStyle['dot'] }}; display: inline-block;"></span>
                            {{ $bookingStatusLabel }}
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

                <div style="margin-top: 28px; background: #f8fafc; border: 1px solid rgba(2,2,91,.08); padding: 24px; border-radius: 18px;">
                    <style>
                        .payment-details-layout {
                            display: grid;
                            grid-template-columns: 1fr 1fr;
                            gap: 24px;
                            align-items: start;
                        }
                        @media (max-width: 1024px) {
                            .payment-details-layout {
                                grid-template-columns: 1fr 320px;
                            }
                        }
                        @media (max-width: 768px) {
                            .payment-details-layout {
                                grid-template-columns: 1fr;
                            }
                            .qr-card {
                                max-width: 100% !important;
                            }
                        }
                    </style>
                    <div class="payment-details-layout">
                        <div>
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                                <span style="width: 10px; height: 10px; background: #43a680; border-radius: 50%; display: inline-block;"></span>
                                <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: #02025b;">Detail Pembayaran</h3>
                            </div>

                            <div style="margin-bottom: 12px;">
                                <div style="color: #666; font-size: 14px; margin-bottom: 4px;">Status Pembayaran</div>
                                <div style="font-weight: 700; color: #111;">{{ $bookingStatusLabel }}</div>
                            </div>

                            <div style="margin-bottom: 12px;">
                                <div style="color: #666; font-size: 14px; margin-bottom: 4px;">Batas Pembayaran</div>
                                <div style="font-weight: 700; color: #111;">
                                    {{ $booking->payment_deadline ? $booking->payment_deadline->locale('id')->translatedFormat('H:i, d F Y') : '-' }}
                                </div>
                            </div>

                            <div style="margin-bottom: 18px;">
                                <div style="color: #666; font-size: 14px; margin-bottom: 4px;">Countdown</div>
                                <div id="payment-countdown" data-deadline="{{ $booking->status === 'waiting_payment' && $booking->payment_deadline ? $booking->payment_deadline->toIso8601String() : '' }}" style="font-weight: 700; color: {{ $paymentCountdownLabel === 'Selesai' ? '#16a34a' : ($paymentCountdownLabel === 'Kadaluarsa' ? '#dc2626' : '#111') }};">
                                    {{ $paymentCountdownLabel }}
                                </div>
                            </div>

                            <div style="display: grid; gap: 12px;">
                                @if($booking->status === 'waiting_payment' && $booking->payment_deadline && now()->lte($booking->payment_deadline))
                                    <form action="{{ route('booking.pay', $booking->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" style="width: 100%; border: none; border-radius: 14px; background: #43a680; color: white; font-weight: 800; padding: 14px 18px; cursor: pointer;">Saya Sudah Bayar</button>
                                    </form>
                                @elseif($booking->status === 'waiting_confirmation')
                                    <div style="padding: 14px 16px; border-radius: 14px; background: #eafaf1; color: #155724; font-weight: 700;">Pembayaran diterima. Tunggu konfirmasi owner.</div>
                                @elseif($booking->status === 'confirmed')
                                    <div style="padding: 14px 16px; border-radius: 14px; background: #e7f5ff; color: #0d3c61; font-weight: 700;">Booking sudah dikonfirmasi.</div>
                                @elseif($booking->status === 'expired')
                                    <div style="padding: 16px; border-radius: 14px; background: #fff4f4; color: #842029; font-weight: 700; text-align: center;">
                                        <div style="margin-bottom: 12px;">Pembayaran sudah kadaluarsa.</div>
                                        <a href="{{ route('booking.show', $booking->field_id) }}" style="display: inline-block; padding: 10px 24px; background: #842029; color: white; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 700;">Pesan Lapangan Lagi</a>
                                    </div>
                                @else
                                    <div style="padding: 14px 16px; border-radius: 14px; background: #f4f6fb; color: #333;">Aksi pembayaran tidak tersedia untuk status ini.</div>
                                @endif
                            </div>
                        </div>

                        <div class="qr-card" style="width: 100%; background: white; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,77,.08); padding: 24px; text-align: center;">
                            <div style="font-size: 13px; color: #666; margin-bottom: 12px;">Scan QR dan transfer</div>
                            <div style="width: 200px; height: 200px; margin: 0 auto 12px auto; background: white; border: 1px solid rgba(0,0,77,.08); border-radius: 20px; display: grid; place-items: center;">
                                <svg width="150" height="150" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="160" height="160" rx="28" fill="#02025b"/>
                                    <rect x="24" y="24" width="42" height="42" rx="8" fill="#fff"/>
                                    <rect x="24" y="94" width="42" height="42" rx="8" fill="#fff"/>
                                    <rect x="94" y="24" width="42" height="42" rx="8" fill="#fff"/>
                                    <rect x="70" y="70" width="20" height="20" fill="#fff"/>
                                    <rect x="70" y="94" width="42" height="20" fill="#fff"/>
                                    <rect x="94" y="70" width="20" height="42" fill="#fff"/>
                                    <rect x="24" y="70" width="20" height="20" fill="#fff"/>
                                    <rect x="46" y="46" width="14" height="14" fill="#02025b"/>
                                    <rect x="46" y="100" width="14" height="14" fill="#02025b"/>
                                    <rect x="100" y="46" width="14" height="14" fill="#02025b"/>
                                </svg>
                            </div>
                            <div style="font-size: 18px; font-weight: 800; color: #02025b;">Rp{{ number_format($booking->total_price ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</main>
</div>

<div id="toast" style="position: fixed; top: 24px; right: 24px; z-index: 99999; padding: 16px 24px; border-radius: 12px; font-weight: 700; font-size: 14px; color: white; display: none; align-items: center; gap: 12px; box-shadow: 0 8px 32px rgba(0,0,0,.15); max-width: 400px; transform: translateX(120%); transition: transform .3s ease;">
    <span id="toast-icon" style="font-size: 20px; flex-shrink: 0;"></span>
    <span id="toast-msg" style="flex: 1;"></span>
    <button onclick="closeToast()" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer; padding: 0; line-height: 1; opacity: .8;">&times;</button>
</div>
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

    @if($booking->status === 'waiting_payment')
    (function() {
        var countdownEl = document.getElementById('payment-countdown');
        if (!countdownEl) return;

        var deadlineValue = countdownEl.dataset.deadline;
        if (!deadlineValue) return;

        var deadline = new Date(deadlineValue);
        if (isNaN(deadline.getTime())) return;

        function formatCountdown(diffMs) {
            var totalSeconds = Math.max(0, Math.floor(diffMs / 1000));
            var minutes = Math.floor(totalSeconds / 60);
            var seconds = totalSeconds % 60;
            return minutes + ' menit ' + String(seconds).padStart(2, '0') + ' detik';
        }

        function updateCountdown() {
            var now = new Date();
            var diff = deadline.getTime() - now.getTime();
            if (diff <= 0) {
                countdownEl.textContent = 'Kadaluarsa';
                clearInterval(timer);
                return;
            }
            countdownEl.textContent = formatCountdown(diff);
        }

        if (countdownEl.textContent.includes('menit') || countdownEl.textContent.includes('hari') || countdownEl.textContent.includes('jam') || countdownEl.textContent.includes('detik')) {
            updateCountdown();
            var timer = setInterval(updateCountdown, 1000);
        }
    })();
    @endif
</script>
</body>
</html>
