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
    $backUrl = $isInternalReferer && $previousUrl !== $currentUrl ? $previousUrl : route('matches.index');

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

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            padding: 14px 20px;
            border-radius: 12px;
            background: #43a680;
            color: #fff;
            font-size: 1rem;
            font-weight: 800;
            border: none;
            cursor: pointer;
            transition: all .2s;
        }
        .btn-primary:hover {
            background: #368d6a;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(67,166,128,0.3);
        }
        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            padding: 14px 20px;
            border-radius: 12px;
            background: #e11d48;
            color: #fff;
            font-size: 1rem;
            font-weight: 800;
            border: none;
            cursor: pointer;
            transition: all .2s;
            text-decoration: none;
        }
        .btn-danger:hover {
            background: #be123c;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(225,29,72,0.3);
        }
        .btn-danger:active {
            transform: translateY(0);
        }

        .btn-row {
            display: flex;
            gap: 12px;
            margin-top: 8px;
        }

        .sport-search-wrap {
            position: relative;
        }
        .sport-search-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 50;
            background: #fff;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            margin-top: 4px;
            max-height: 220px;
            overflow-y: auto;
            display: none;
            box-shadow: 0 8px 24px rgba(0,0,0,.12);
        }
        .sport-search-dropdown.open {
            display: block;
        }
        .sport-search-dropdown .sport-item {
            padding: 10px 14px;
            cursor: pointer;
            font-size: .95rem;
            color: #1e293b;
            transition: background .15s;
        }
        .sport-search-dropdown .sport-item:hover,
        .sport-search-dropdown .sport-item.highlighted {
            background: #f1f5f9;
        }
        .sport-search-dropdown .sport-item.selected {
            background: #eef2ff;
            color: #4338ca;
            font-weight: 700;
        }
        .sport-search-dropdown .sport-item .match-em {
            background: #fef08a;
            font-style: normal;
        }
        .sport-search-dropdown .no-result {
            padding: 14px;
            text-align: center;
            color: #94a3b8;
            font-size: .9rem;
        }

        @media (max-width: 768px) {
            .team-main { padding: 6px 8px 20px; }
            .team-title { margin-top: 8px; }
            .create-match-card { padding: 24px; }
            .form-grid { grid-template-columns: 1fr; gap: 16px; }
            .form-group--full { grid-column: span 1; }
        }
        @media (max-width: 480px) {
            .btn-row { flex-direction: column; }
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
                    <a href="{{ $backUrl }}" class="btn-back">
                        &larr; Kembali
                    </a>
                </div>
            </div>

            <div class="create-match-card">
                @if(session('error'))
                <div style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; font-weight: 600;">
                    {{ session('error') }}
                </div>
                @endif

                @if($errors->any())
                <div style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; font-weight: 600;">
                    <ul style="margin: 0; padding-left: 16px;">
                        @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('matches.store') }}" method="POST" class="form-grid">
                    @csrf
                    <input type="hidden" name="type" value="public">

                    <div class="form-group form-group--full" id="sport-search-group">
                        <label class="form-label">Kategori Olahraga</label>
                        <div class="sport-search-wrap">
                            <input type="text" class="form-control sport-search-input"
                                   placeholder="Cari kategori…" value="{{ old('sport') }}"
                                   autocomplete="off">
                            <input type="hidden" name="sport" value="{{ old('sport') }}" required>
                            <div class="sport-search-dropdown"></div>
                        </div>
                        @error('sport') <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group form-group--full">
                        <label class="form-label">Nama Pertandingan</label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Futsal Santai Bareng" value="{{ old('title') }}" required>
                        @error('title') <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group form-group--full">
                        <label class="form-label">Pilih Lapangan</label>
                        <select name="field_id" class="form-control" required>
                            <option value="">Pilih Lapangan</option>
                            @foreach($fields as $field)
                                <option value="{{ $field->id }}" {{ old('field_id') == $field->id ? 'selected' : '' }}>{{ $field->name }} ({{ $field->location }})</option>
                            @endforeach
                        </select>
                        @error('field_id') <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="date" class="form-control" value="{{ old('date') }}" required>
                        @error('date') <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Waktu</label>
                        <input type="time" name="time" class="form-control" value="{{ old('time') }}" required>
                        @error('time') <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group form-group--full">
                        <label class="form-label">Maksimal Pemain (Total)</label>
                        <input type="number" name="max_player" class="form-control" placeholder="Contoh: 10" min="2" value="{{ old('max_player', 10) }}" required>
                        @error('max_player') <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div class="btn-row form-group--full">
                        <a href="{{ route('matches.index') }}" class="btn-danger">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            Batal
                        </a>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                            Buat
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>
<script>
    (function () {
        var sports = [
            @foreach($sportOptions as $s)
                '{{ addslashes($s) }}',
            @endforeach
        ];
        var wrap = document.getElementById('sport-search-group');
        if (!wrap) return;
        var input = wrap.querySelector('.sport-search-input');
        var hidden = wrap.querySelector('input[name="sport"]');
        var dropdown = wrap.querySelector('.sport-search-dropdown');
        var highlightedIdx = -1;

        function render(filter) {
            var q = (filter || '').toLowerCase().trim();
            var html = '';
            var filtered = [];
            for (var i = 0; i < sports.length; i++) {
                var s = sports[i];
                if (!q || s.toLowerCase().indexOf(q) !== -1) {
                    filtered.push({ idx: i, name: s });
                }
            }
            if (filtered.length === 0) {
                html = '<div class="no-result">Tidak ditemukan</div>';
            } else {
                for (var j = 0; j < filtered.length; j++) {
                    var name = filtered[j].name;
                    var display = name;
                    if (q) {
                        var re = new RegExp('(' + q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
                        display = name.replace(re, '<span class="match-em">$1</span>');
                    }
                    var selectedAttr = (name === hidden.value) ? ' selected' : '';
                    html += '<div class="sport-item' + selectedAttr + '" data-value="' + name.replace(/"/g, '&quot;') + '">' + display + '</div>';
                }
            }
            dropdown.innerHTML = html;
            dropdown.classList.add('open');
            highlightedIdx = -1;

            var items = dropdown.querySelectorAll('.sport-item');
            for (var k = 0; k < items.length; k++) {
                items[k].addEventListener('click', function () {
                    selectItem(this);
                });
            }
        }

        function selectItem(el) {
            var val = el.getAttribute('data-value');
            input.value = val;
            hidden.value = val;
            dropdown.classList.remove('open');
            input.setCustomValidity('');
        }

        input.addEventListener('input', function () {
            hidden.value = '';
            var q = this.value.trim();
            if (q === '') {
                dropdown.classList.remove('open');
                return;
            }
            render(q);
        });

        input.addEventListener('focus', function () {
            var q = this.value.trim();
            if (q !== '') render(q);
        });

        input.addEventListener('blur', function () {
            setTimeout(function () {
                dropdown.classList.remove('open');
            }, 180);
        });

        input.addEventListener('keydown', function (e) {
            var items = dropdown.querySelectorAll('.sport-item');
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                highlightedIdx = Math.min(highlightedIdx + 1, items.length - 1);
                highlightItem(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                highlightedIdx = Math.max(highlightedIdx - 1, -1);
                highlightItem(items);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (highlightedIdx >= 0 && items[highlightedIdx]) {
                    selectItem(items[highlightedIdx]);
                }
            } else if (e.key === 'Escape') {
                dropdown.classList.remove('open');
            }
        });

        function highlightItem(items) {
            for (var i = 0; i < items.length; i++) {
                items[i].classList.remove('highlighted');
            }
            if (highlightedIdx >= 0 && items[highlightedIdx]) {
                items[highlightedIdx].classList.add('highlighted');
                items[highlightedIdx].scrollIntoView({ block: 'nearest' });
            }
        }

        document.addEventListener('click', function (e) {
            if (!wrap.contains(e.target)) {
                dropdown.classList.remove('open');
            }
        });

        if (hidden.value) {
            var matched = false;
            for (var m = 0; m < sports.length; m++) {
                if (sports[m] === hidden.value) {
                    matched = true; break;
                }
            }
            if (!matched) hidden.value = '';
        }

        var form = wrap.closest('form');
        if (form) {
            form.addEventListener('submit', function (e) {
                if (!hidden.value) {
                    e.preventDefault();
                    input.focus();
                    input.setCustomValidity('Pilih kategori olahraga');
                    input.reportValidity();
                }
            });
            input.addEventListener('invalid', function () {
                if (!hidden.value) {
                    input.setCustomValidity('Pilih kategori olahraga');
                }
            });
            input.addEventListener('input', function () {
                input.setCustomValidity('');
            });
        }
    })();
</script>
</body>
</html>
