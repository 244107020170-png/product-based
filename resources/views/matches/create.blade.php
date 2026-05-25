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
    <title>Buat Tim – {{ config('app.name', 'Spies Sport') }}</title>
    @vite(['resources/css/app.css', 'resources/css/player-dashboard.css', 'resources/js/player-dashboard.js'])
    <style>
        .team-main { max-width: 1200px; margin: 0 auto; padding: 8px 24px 48px; }
        .team-title { margin-top: 14px; }
        .team-title h1 { margin: 0; font-size: clamp(1.35rem, 2.2vw, 1.65rem); font-weight: 900; color: #02025b; line-height: 1.1; letter-spacing: .01em; }
        .team-title p { margin: 4px 0 0; font-size: clamp(.88rem, 1.5vw, 1.02rem); font-weight: 600; color: #02025b; }

        .btn-back {
            display: inline-flex;
            align-items: center;
            padding: 0 20px;
            height: 40px;
            background: rgba(255,255,255,.76);
            color: #11114b;
            font-size: .95rem;
            font-weight: 700;
            text-decoration: none;
            border-radius: 10px;
            transition: all .2s ease;
            border: 1.8px solid #14144a;
        }
        .btn-back:hover {
            background: #fff;
            transform: translateY(-1px);
        }

        .create-match-card {
            margin: 24px auto 0;
            background: rgba(255,255,255,.9);
            border-radius: 16px;
            border: 1px solid rgba(0,0,77,.08);
            padding: 40px;
            box-shadow: 0 8px 24px rgba(0,0,77,.06);
            max-width: 100%;
        }
        
        .header-container {
            max-width: 100%;
            margin: 0 auto;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-group {
            margin-bottom: 0;
        }
        .form-group--full {
            grid-column: span 2;
        }
        .form-label {
            display: block;
            font-size: .95rem;
            font-weight: 700;
            color: #02025b;
            margin-bottom: 8px;
        }
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border-radius: 10px;
            border: 1px solid rgba(0,0,77,.15);
            background: #fff;
            color: #0b0b44;
            font-size: .95rem;
            outline: none;
            transition: border-color .2s;
        }
        .form-control:focus {
            border-color: #11114b;
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 14px 20px;
            border-radius: 12px;
            background: #43a680;
            color: #fff;
            font-size: 1.05rem;
            font-weight: 800;
            border: none;
            cursor: pointer;
            transition: background .2s;
            margin-top: 10px;
        }
        .btn-submit:hover {
            background: #368d6a;
        }

        @media (max-width: 768px) {
            .team-main { padding: 6px 8px 20px; }
            .team-title { margin-top: 8px; }
            .create-match-card { padding: 24px; }
            .form-grid { grid-template-columns: 1fr; gap: 16px; }
            .form-group--full { grid-column: span 1; }
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
                        <svg viewBox="0 0 24 24" fill="none"><rect x="3.5" y="5.5" width="17" height="15" rx="2.5" stroke="currentColor" stroke-width="1.8"/><path d="M7 3.5V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M17 3.5V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M3.5 9.5H20.5" stroke="currentColor" stroke-width="1.8"/></svg>
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

        <section class="team-main">
            <div class="header-container">
                <div class="team-title">
                    <h1>BUAT PERTANDINGAN</h1>
                    <p>Buat pertandingan barumu</p>
                </div>

                <div style="margin-top: 16px;">
                    <a href="{{ route('matches.index') }}" class="btn-back">
                        &larr; Kembali ke Cari Tim
                    </a>
                </div>
            </div>

            <div class="create-match-card">
                <form action="{{ route('matches.store') }}" method="POST" class="form-grid">
                    @csrf
                    <div class="form-group form-group--full">
                        <label class="form-label">Nama Pertandingan</label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Futsal Santai Bareng" required>
                    </div>

                    <div class="form-group form-group--full">
                        <label class="form-label">Pilih Lapangan</label>
                        <select name="field_id" class="form-control" required>
                            <option value="">Pilih Lapangan</option>
                            @foreach($fields as $field)
                                <option value="{{ $field->id }}">{{ $field->name }} ({{ $field->location }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Waktu</label>
                        <input type="time" name="time" class="form-control" required>
                    </div>

                    <div class="form-group form-group--full">
                        <label class="form-label">Maksimal Pemain (Total)</label>
                        <input type="number" name="max_player" class="form-control" placeholder="Contoh: 10" min="1" required>
                    </div>

                    <div class="form-group form-group--full">
                        <label class="form-label">Tipe Pertandingan</label>
                        <select name="type" class="form-control" required>
                            <option value="">Pilih Tipe Pertandingan</option>
                            <option value="public">Publik - Cari Pemain (Muncul di Cari Tim)</option>
                            <option value="private">Pribadi - Booking Sendiri (Hanya untuk Anda)</option>
                        </select>
                    </div>

                    <div class="form-group form-group--full" style="margin-top: 8px;">
                        <button type="submit" class="btn-submit">Buat Pertandingan Sekarang</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>
</body>
</html>
