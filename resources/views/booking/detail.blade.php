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
        'confirmed' => 'Dikonfirmasi',
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

    $isUpcoming = $booking->status === 'confirmed' && (
        $booking->date > now()->toDateString() ||
        ($booking->date == now()->toDateString() && $booking->start_time > now()->toTimeString())
    );

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
    <title>Detail Pemesanan – {{ config('app.name', 'Spies Sport') }}</title>
    @vite([
        'resources/css/app.css',
        'resources/css/player-dashboard.css',
        'resources/js/app.js',
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
                        <h1 style="margin: 0 0 8px 0; font-size: 24px; font-weight: 800;">Detail Pemesanan</h1>
                        <p style="margin: 0; font-size: 14px; opacity: 0.9;">ID Pemesanan: #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</p>
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
                    @if($booking->court_number)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid rgba(0,0,77,.1);">
                        <span style="color: #666; font-size: 15px;">Lapangan</span>
                        <span style="font-weight: 700; color: #02025b; font-size: 15px;">Lapangan {{ $booking->court_number }}</span>
                    </div>
                    @endif
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #666; font-size: 15px;">Waktu</span>
                        <span style="font-weight: 700; color: #02025b; font-size: 15px;">{{ $booking->start_time }} - {{ $booking->end_time }}</span>
                    </div>
                </div>

                @php
                    $adminFee = 2000;
                @endphp
                <div style="border-top: 2px dashed rgba(0,0,77,.1); padding-top: 24px;">
                    @if($booking->discount_amount > 0)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 14px; color: #999;">Harga Asli</span>
                        <span style="font-size: 14px; color: #999; text-decoration: line-through;">Rp{{ number_format($booking->original_total_price ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 14px; color: #dc2626; font-weight: 600;">Diskon</span>
                        <span style="font-size: 14px; color: #dc2626; font-weight: 700;">-Rp{{ number_format($booking->discount_amount ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px dashed rgba(0,0,77,.1); padding-bottom: 12px;">
                        <span style="font-size: 15px; font-weight: 700; color: #000;">Harga Setelah Diskon</span>
                        <span style="font-size: 17px; font-weight: 800; color: #16a34a;">Rp{{ number_format($booking->subtotal_price ?? 0, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 14px; color: #666;">Subtotal</span>
                        <span style="font-size: 14px; font-weight: 700; color: #000;">Rp{{ number_format($booking->subtotal_price ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 14px; color: #666;">Biaya Admin</span>
                        <span style="font-size: 14px; font-weight: 700; color: #000;">Rp{{ number_format($adminFee, 0, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px;">
                        <span style="font-size: 16px; color: #000; font-weight: 800;">Total</span>
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
                                    <div style="padding: 14px 16px; border-radius: 14px; background: #eafaf1; color: #155724; font-weight: 700; text-align:center;">
                                        Scan QR Code atau klik gambar QR untuk konfirmasi pembayaran.
                                    </div>
                                @elseif($booking->status === 'waiting_confirmation')
                                    <!-- payment success shown in QR card -->
                                @elseif($booking->status === 'confirmed')
                                    <div style="padding: 14px 16px; border-radius: 14px; background: #d4edda; color: #155724; font-weight: 700;">Pemesanan sudah dikonfirmasi.</div>
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

                        @php
                            $payUrl = route('booking.payment', $booking->id);
                            $isPaid = in_array($booking->status, ['waiting_confirmation', 'confirmed', 'paid', 'completed']);
                        @endphp
                        <div class="qr-card" style="width: 100%; background: white; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,77,.08); padding: 24px; text-align: center;">
                            @if($isPaid)
                                <div style="width: 80px; height: 80px; margin: 0 auto 12px auto; background: #d1fae5; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                </div>
                                <div style="font-size: 15px; font-weight: 700; color: #16a34a; margin-bottom: 4px;">Pembayaran Berhasil</div>
                                <div style="font-size: 13px; color: #666;">
                                    {{ $booking->status === 'confirmed' ? 'Pesanan otomatis dikonfirmasi' : ($booking->status === 'completed' ? 'Pesanan selesai' : 'Menunggu konfirmasi owner') }}
                                </div>
                            @else
                                <div style="font-size: 13px; color: #666; margin-bottom: 12px;">Scan QR untuk bayar</div>
                                <a href="{{ $payUrl }}" target="_blank">
                                    <div style="width: 200px; height: 200px; margin: 0 auto 12px auto; background: white; border: 1px solid rgba(0,0,77,.08); border-radius: 20px; display: grid; place-items: center; overflow:hidden;">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($payUrl) }}" alt="QR Code" style="width:200px;height:200px;" onerror="this.style.display='none'">
                                    </div>
                                </a>
                                <div style="font-size: 13px; color: #666;">Scan dengan HP untuk konfirmasi pembayaran</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($isUpcoming)
        <div x-data="{}" x-on:modal-confirmed.window="if ($event.detail.name === 'cancel-booking') $refs.cancelForm.submit()" style="margin-top:24px; background:white; border-radius:16px; padding:24px; box-shadow:0 8px 24px rgba(0,0,77,.06); border:1px solid rgba(0,0,77,.08);">
            <form method="POST" action="{{ route('booking.cancel', $booking->id) }}" x-ref="cancelForm">
                @csrf
                <p style="margin:0 0 16px; color:#666; font-size:14px;">Jika kamu membatalkan pesanan, slot lapangan akan dikembalikan dan tersedia untuk pemesan lain.</p>
                <button type="button" style="width:100%; padding:14px; background:#dc2626; color:white; border:none; border-radius:12px; font-weight:700; font-size:15px; cursor:pointer; transition:all .2s;"
                    onmouseover="this.style.background='#b91c1c';this.style.transform='scale(1.02)'"
                    onmouseout="this.style.background='#dc2626';this.style.transform='scale(1)'"
                    @click="$dispatch('open-modal-cancel-booking')">
                    Batalkan Pesanan
                </button>
            </form>

            <x-custom-modal name="cancel-booking"
                             type="confirm"
                             title="Batalkan Pesanan"
                             message="Anda yakin ingin membatalkan pesanan ini? Pesanan yang dibatalkan akan diproses untuk refund sesuai kebijakan yang berlaku."
                             confirmText="Ya, Batalkan"
                             cancelText="Kembali"
                             confirmVariant="danger" />
        </div>
        @endif

        {{-- REVIEW SECTION --}}
        @if(in_array($booking->status, ['confirmed', 'completed', 'selesai']))
        <div style="margin-top:24px; background:white; border-radius:16px; padding:24px; box-shadow:0 8px 24px rgba(0,0,77,.06); border:1px solid rgba(0,0,77,.08);">
            @php
                $existingReview = \App\Models\Review::where('user_id', Auth::id())->where('booking_id', $booking->id)->first();
            @endphp
            @if($existingReview)
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="display:flex; gap:4px;">
                        @for($i=1; $i<=5; $i++)
                            <span style="color:{{ $i <= $existingReview->rating ? '#f59e0b' : '#d1d5db' }}; font-size:20px;">★</span>
                        @endfor
                    </div>
                    <div style="flex:1;">
                        <p style="margin:0; font-weight:700; color:#02025b; font-size:14px;">Review Anda</p>
                        <p style="margin:4px 0 0; color:#666; font-size:13px;">{{ $existingReview->review }}</p>
                    </div>
                </div>
            @else
                <button onclick="openReviewModal({{ $booking->field_id }}, {{ $booking->id }})" style="width:100%; padding:14px; background:linear-gradient(135deg,#02025b,#11114b); color:white; border:none; border-radius:12px; font-weight:700; font-size:15px; cursor:pointer; transition:all .2s;" onmouseover="this.style.opacity='.9'" onmouseout="this.style.opacity='1'">
                    ⭐ Beri Review & Rating
                </button>
            @endif
        </div>
        @endif

    </section>
</main>
</div>

{{-- REVIEW MODAL --}}
<div id="reviewModal" style="display:none;position:fixed;inset:0;z-index:10000;background:rgba(0,0,0,.5);justify-content:center;align-items:center;padding:20px;" onclick="if(event.target===this)closeReviewModal()">
    <div style="background:white;border-radius:20px;padding:28px;max-width:440px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.25);position:relative;max-height:90vh;overflow-y:auto;">
        <button type="button" onclick="closeReviewModal()" style="position:absolute;top:12px;right:12px;background:none;border:none;font-size:22px;cursor:pointer;color:#94a3b8;line-height:1;">&times;</button>
        <h3 style="margin:0 0 4px;font-size:18px;font-weight:800;color:#02025b;">Beri Review</h3>
        <p id="reviewModalFieldName" style="margin:0 0 16px;font-size:13px;color:#64748b;">{{ $booking->field->name ?? 'Lapangan' }}</p>

        <form id="reviewForm" method="POST" action="{{ route('review.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="field_id" id="reviewFieldId">
            <input type="hidden" name="booking_id" id="reviewBookingId">

            {{-- Rating --}}
            <div style="margin-bottom:16px;">
                <p style="margin:0 0 8px;font-size:13px;font-weight:600;color:#02025b;">Rating <span style="color:#dc2626;">*</span></p>
                <div id="halfStarContainer" style="display:flex;gap:2px;flex-direction:row-reverse;justify-content:flex-end;">
                    @for($i = 5; $i >= 1; $i--)
                    <div class="hstar" data-star="{{ $i }}" style="position:relative;width:32px;height:32px;cursor:pointer;">
                        <span class="hstar-bg" style="position:absolute;inset:0;font-size:32px;line-height:1;color:#e2e8f0;pointer-events:none;">★</span>
                        <span class="hstar-fill" id="hsf-{{ $i }}" style="position:absolute;inset:0;font-size:32px;line-height:1;color:#f59e0b;overflow:hidden;width:0%;pointer-events:none;">★</span>
                        <span class="hstar-left" onclick="setHalfRating({{ $i - 0.5 }})" style="position:absolute;top:0;left:0;bottom:0;width:50%;z-index:2;cursor:pointer;"></span>
                        <span class="hstar-right" onclick="setHalfRating({{ $i }})" style="position:absolute;top:0;right:0;bottom:0;width:50%;z-index:2;cursor:pointer;"></span>
                    </div>
                    @endfor
                </div>
                <p id="ratingDisplay" style="margin:6px 0 0;font-size:12px;color:#94a3b8;">Klik bintang untuk memberi rating</p>
                <input type="hidden" name="rating" id="reviewRating" value="0">
            </div>

            {{-- Review text --}}
            <div style="margin-bottom:16px;">
                <p style="margin:0 0 8px;font-size:13px;font-weight:600;color:#02025b;">Ulasan <span style="color:#dc2626;">*</span></p>
                <textarea name="review" id="reviewText" rows="4" placeholder="Tulis ulasan kamu di sini (minimal 10 karakter)..." style="width:100%;padding:12px 14px;border-radius:12px;border:1px solid rgba(0,0,77,.15);font-size:14px;outline:none;resize:none;box-sizing:border-box;font-family:inherit;"></textarea>
            </div>

            {{-- Photo upload --}}
            <div style="margin-bottom:16px;">
                <p style="margin:0 0 8px;font-size:13px;font-weight:600;color:#02025b;">Foto <span style="color:#94a3b8;font-weight:400;">(opsional)</span></p>
                <label style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:12px;border:1px dashed rgba(0,0,77,.2);background:#f8fafc;cursor:pointer;transition:all .2s;font-size:13px;color:#64748b;" onmouseover="this.style.borderColor='#EB5436'" onmouseout="this.style.borderColor='rgba(0,0,77,.2)'">
                    <svg viewBox="0 0 24 24" fill="none" width="20" height="20"><rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.6"/><circle cx="7.5" cy="9.5" r="1.5" fill="currentColor"/><path d="M3 16L8 11L12 15L16 10L21 16" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span id="reviewPhotoLabel">Tambahkan foto</span>
                    <input type="file" name="photos[]" id="reviewPhotos" accept="image/jpeg,image/png,image/webp" multiple style="display:none;" onchange="updatePhotoLabel(this)">
                </label>
                <p style="margin:6px 0 0;font-size:11px;color:#94a3b8;">Maksimal 5 foto (JPEG, PNG, WebP). Maks 5MB per foto.</p>
            </div>

            <p id="reviewError" style="display:none;color:#dc2626;font-size:12px;margin:6px 0 0;"></p>
            <button type="submit" id="reviewSubmitBtn" style="width:100%;margin-top:8px;padding:14px;background:#EB5436;color:white;border:none;border-radius:12px;font-weight:700;font-size:15px;cursor:pointer;">Kirim Review</button>
        </form>
    </div>
</div>

<script>
function setHalfRating(val) {
    document.getElementById('reviewRating').value = val;
    document.getElementById('ratingDisplay').textContent = val + ' dari 5 bintang';
    for (var i = 1; i <= 5; i++) {
        var fill = document.getElementById('hsf-' + i);
        if (val >= i) fill.style.width = '100%';
        else if (val >= i - 0.5) fill.style.width = '50%';
        else fill.style.width = '0%';
    }
}
function resetHalfRating() {
    setHalfRating(0);
    document.getElementById('ratingDisplay').textContent = 'Klik bintang untuk memberi rating';
}
function openReviewModal(fieldId, bookingId) {
    document.getElementById('reviewFieldId').value = fieldId;
    document.getElementById('reviewBookingId').value = bookingId;
    resetHalfRating();
    document.getElementById('reviewText').value = '';
    document.getElementById('reviewError').style.display = 'none';
    document.getElementById('reviewPhotoLabel').textContent = 'Tambahkan foto';
    document.getElementById('reviewPhotos').value = '';
    document.getElementById('reviewModal').style.display = 'flex';
}
function closeReviewModal() {
    document.getElementById('reviewModal').style.display = 'none';
}
function updatePhotoLabel(input) {
    var label = document.getElementById('reviewPhotoLabel');
    label.textContent = input.files.length > 0 ? input.files.length + ' foto dipilih' : 'Tambahkan foto';
}
document.getElementById('reviewForm').addEventListener('submit', function(e) {
    var ratingEl = document.getElementById('reviewRating');
    var review = document.getElementById('reviewText').value.trim();
    var errorEl = document.getElementById('reviewError');
    if (parseFloat(ratingEl.value) === 0) { e.preventDefault(); errorEl.textContent = 'Pilih rating terlebih dahulu.'; errorEl.style.display = 'block'; return; }
    if (review.length < 10) { e.preventDefault(); errorEl.textContent = 'Review minimal 10 karakter.'; errorEl.style.display = 'block'; return; }
    errorEl.style.display = 'none';
});
</script>

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
