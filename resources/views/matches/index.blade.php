@php
    use Carbon\Carbon;

    $user = auth()->user();
    $userName = $user?->name ?: 'Sport Enthusiast';
    $currentDate = Carbon::now()->locale('id')->translatedFormat('j F Y');
    $profileAvatarFile = $user?->avatar_profile ?: (($user?->gender === 'perempuan') ? 'profil2.png' : 'profil1.png');
    $profileAvatar = asset('assets/images/characters/'.$profileAvatarFile);
    $sportOptions = $cards->pluck('sport')->unique()->values();

    $sidebarItems = [
        ['label' => 'Dashboard', 'icon' => asset('assets/images/icons/dashboard.png'), 'href' => route('dashboard'), 'active' => false],
        ['label' => 'Aktivitas', 'icon' => asset('assets/images/icons/aktivitas.png'), 'href' => route('activity.index'), 'active' => false],
        ['label' => 'Favoritmu', 'icon' => asset('assets/images/icons/favoritmu.png'), 'href' => route('favorite.index'), 'active' => false],
        ['label' => 'Histori', 'icon' => asset('assets/images/icons/histori.png'), 'href' => route('history.index'), 'active' => false],
        ['label' => 'Cari tim', 'icon' => asset('assets/images/icons/caritim.png'), 'href' => route('matches.index'), 'active' => true],
        ['label' => 'Booking', 'icon' => asset('assets/images/icons/booking.png'), 'href' => url('/fields'), 'active' => false],
        ['label' => 'Keahlianmu', 'icon' => asset('assets/images/icons/keahlian.png'), 'href' => route('skill.index'), 'active' => false],
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
    <title>Cari Tim – {{ config('app.name', 'Spies Sport') }}</title>
    @vite(['resources/css/app.css', 'resources/css/player-dashboard.css', 'resources/js/player-dashboard.js'])
    <style>
        .team-main { max-width: 1020px; margin: 0 auto; padding: 8px 12px 24px; }
        .team-title { margin-top: 14px; }
        .team-title h1 { margin: 0; font-size: clamp(1.35rem, 2.2vw, 1.65rem); font-weight: 900; color: #02025b; line-height: 1.1; letter-spacing: .01em; }
        .team-title p { margin: 4px 0 0; font-size: clamp(.88rem, 1.5vw, 1.02rem); font-weight: 600; color: #02025b; }

        .team-filter-wrap { margin-top: 16px; }
        .team-filter {
            min-width: 168px;
            height: 40px;
            border: 1.8px solid #14144a;
            border-radius: 10px;
            padding: 0 36px 0 12px;
            background: rgba(255,255,255,.76);
            color: #0b0b44;
            font-size: .98rem;
            font-weight: 600;
            appearance: none;
            outline: none;
            background-image: linear-gradient(45deg, transparent 50%, #11114b 50%), linear-gradient(135deg, #11114b 50%, transparent 50%);
            background-position: calc(100% - 16px) 17px, calc(100% - 10px) 17px;
            background-size: 6px 6px, 6px 6px;
            background-repeat: no-repeat;
        }

        .team-stage {
            margin-top: 12px;
            min-height: clamp(500px, 72vh, 640px);
            border-radius: 16px;
            border: 1px solid rgba(0,0,77,.09);
            background: rgba(239,239,243,.84);
            box-shadow: 0 8px 18px rgba(0,0,77,.09);
            position: relative;
            overflow: hidden;
            padding: 34px 20px 20px;
        }
        .team-stage__ghost {
            position: absolute;
            width: min(64%, 330px);
            height: min(66%, 420px);
            top: 64px;
            border-radius: 18px;
            background: rgba(255,255,255,.54);
            border: 1px solid rgba(0,0,77,.08);
            box-shadow: 0 8px 16px rgba(0,0,0,.08);
            pointer-events: none;
        }
        .team-stage__ghost.g1 { left: calc(50% - 188px); transform: rotate(-7deg); }
        .team-stage__ghost.g2 { left: calc(50% - 158px); transform: rotate(-3.5deg); }
        .team-stage__ghost.g3 { left: calc(50% - 128px); transform: rotate(3.5deg); }
        .team-stage__ghost.g4 { left: calc(50% - 98px); transform: rotate(7deg); }

        .swipe-card {
            width: min(100%, 440px);
            margin: 0 auto;
            border-radius: 18px;
            background: rgba(255,255,255,.96);
            border: 1px solid rgba(0,0,77,.09);
            box-shadow: 0 10px 24px rgba(0,0,0,.19);
            padding: 12px 12px 10px;
            position: relative;
            z-index: 2;
            transform: translateX(0) rotate(0deg);
            transition: transform .22s ease, opacity .22s ease;
            touch-action: pan-y;
        }
        .swipe-card.is-swiping-left { transform: translateX(-150%) rotate(-16deg); opacity: 0; }
        .swipe-card.is-swiping-right { transform: translateX(150%) rotate(16deg); opacity: 0; }

        .swipe-card__photo { height: clamp(240px, 43vh, 330px); border-radius: 14px; overflow: hidden; border: 1px solid rgba(0,0,77,.1); background: #d2dceb; }
        .swipe-card__photo img { width: 100%; height: 100%; object-fit: cover; display: block; }

        .swipe-card__meta { margin-top: 10px; display: grid; gap: 6px; font-size: .82rem; color: #141414; font-weight: 600; }
        .swipe-card__row { display: inline-flex; align-items: center; gap: 7px; }
        .swipe-card__row svg { width: 15px; height: 15px; flex-shrink: 0; }

        .swipe-card__actions { margin-top: 10px; display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .swipe-btn {
            border: 0;
            border-radius: 999px;
            height: 37px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-size: 1.1rem;
            color: #fff;
            font-weight: 800;
            cursor: pointer;
        }
        .swipe-btn svg { width: 15px; height: 15px; }
        .swipe-btn--skip { background: #ef2020; }
        .swipe-btn--join { background: #43a680; }
        .swipe-btn:disabled { opacity: .5; cursor: not-allowed; }

        .swipe-empty {
            display: none;
            height: 100%;
            min-height: 360px;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: rgba(0,0,77,.6);
            font-size: .95rem;
            font-weight: 700;
        }
        .swipe-empty.is-visible { display: flex; }

        @media (max-width: 1024px) {
            .team-main { max-width: 900px; }
            .team-stage { min-height: 560px; }
            .team-stage__ghost { width: 58%; }
        }
        @media (max-width: 768px) {
            .team-main { padding: 6px 8px 20px; }
            .team-title { margin-top: 8px; }
            .team-filter-wrap { margin-top: 10px; }
            .team-stage { min-height: 520px; padding: 20px 10px 14px; border-radius: 14px; }
            .team-stage__ghost { display: none; }
            .swipe-card { width: min(100%, 380px); padding: 10px; border-radius: 14px; }
            .swipe-card__meta { font-size: .78rem; }
            .swipe-btn { font-size: .95rem; height: 34px; }
        }
        @media (max-width: 480px) {
            .team-title h1 { font-size: 1.2rem; }
            .team-title p { font-size: .82rem; }
            .team-filter { width: 100%; min-width: 0; font-size: .92rem; }
            .team-stage { min-height: 470px; }
            .swipe-card__photo { height: 225px; }
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
                <label class="player-search" for="team-search">
                    <span class="player-search__icon">
                        <svg viewBox="0 0 20 20" fill="none"><circle cx="9" cy="9" r="5.75" stroke="currentColor" stroke-width="1.8"/><path d="M13.5 13.5L17 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </span>
                    <input id="team-search" type="search" placeholder="Cari lapangan">
                </label>
            </div>
            <div class="player-dashboard-topbar__right">
                <div class="player-dashboard-topbar__date">
                    <span class="player-inline-icon">
                        <svg viewBox="0 0 24 24" fill="none"><rect x="3.5" y="5.5" width="17" height="15" rx="2.5" stroke="currentColor" stroke-width="1.8"/><path d="M7 3.5V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M17 3.5V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M3.5 9.5H20.5" stroke="currentColor" stroke-width="1.8"/></svg>
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
                        <img src="{{ $profileAvatar }}" alt="Profil" class="player-avatar-image player-avatar-image--profile">
                    </span>
                    <span class="player-profile-pill__name">{{ $userName }}</span>
                </a>
            </div>
        </header>

        <section class="team-main">
            <div class="team-title">
                <h1>SWIPE TEAM</h1>
                <p>Temukan tim terbaikmu</p>
            </div>

            <div class="team-filter-wrap">
                <select id="sport-filter" class="team-filter">
                    <option value="">Pilih Olahraga</option>
                    @foreach($sportOptions as $sport)
                        <option value="{{ $sport }}">{{ $sport }}</option>
                    @endforeach
                </select>
            </div>

            <section class="team-stage">
                <div class="team-stage__ghost g1"></div>
                <div class="team-stage__ghost g2"></div>
                <div class="team-stage__ghost g3"></div>
                <div class="team-stage__ghost g4"></div>

                <div class="swipe-empty" data-swipe-empty>Tidak ada tim untuk filter ini.</div>

                <article class="swipe-card" data-swipe-card>
                    <div class="swipe-card__photo">
                        <img data-card-image src="" alt="Tim olahraga">
                    </div>
                    <div class="swipe-card__meta">
                        <div class="swipe-card__row">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M12 21C12 21 18.5 14.5 18.5 9.75C18.5 6.3 15.7 3.5 12.25 3.5C8.8 3.5 6 6.3 6 9.75C6 14.5 12 21 12 21Z" fill="#EA1E1E"/><circle cx="12.2" cy="9.7" r="2.1" fill="white"/></svg>
                            <span data-card-venue></span>
                        </div>
                        <div class="swipe-card__row">
                            <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8.2" fill="#F28B1D"/><path d="M3.8 12H20.2" stroke="#111" stroke-width="1.5"/></svg>
                            <span data-card-sport></span>
                        </div>
                        <div class="swipe-card__row">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M7 10.5C7 8.57 8.57 7 10.5 7C11.96 7 13.22 7.9 13.75 9.17C14.2 8.88 14.74 8.7 15.33 8.7C16.95 8.7 18.27 10.02 18.27 11.64C18.27 13.26 16.95 14.58 15.33 14.58H8.7C7.21 14.58 6 13.37 6 11.88C6 11.27 6.2 10.72 6.53 10.27" stroke="#111" stroke-width="1.7" stroke-linecap="round"/></svg>
                            <span data-card-needs></span>
                        </div>
                        <div class="swipe-card__row">
                            <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8.5" stroke="#111" stroke-width="1.7"/><path d="M12 7.6V12.4L15.2 14.2" stroke="#111" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span data-card-schedule></span>
                        </div>
                    </div>
                    <div class="swipe-card__actions">
                        <button type="button" class="swipe-btn swipe-btn--skip" data-swipe-skip>
                            <svg viewBox="0 0 24 24" fill="none"><path d="M6.5 6.5L17.5 17.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/><path d="M17.5 6.5L6.5 17.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
                            <span>Lewati</span>
                        </button>
                        <button type="button" class="swipe-btn swipe-btn--join" data-swipe-join>
                            <svg viewBox="0 0 24 24" fill="none"><path d="M12.6 20.2C12.26 20.36 11.86 20.36 11.52 20.2C8.52 18.76 5 15.8 5 11.94C5 8.95 7.42 6.53 10.4 6.53C11.57 6.53 12.67 6.9 13.58 7.59C14.49 6.9 15.59 6.53 16.76 6.53C19.74 6.53 22.16 8.95 22.16 11.94C22.16 15.8 18.64 18.76 15.64 20.2" fill="currentColor"/></svg>
                            <span>Join Tim</span>
                        </button>
                    </div>
                </article>
            </section>
        </section>
    </main>
</div>

<script>
(() => {
    const allCards = @json($cards);
    const sportFilter = document.getElementById('sport-filter');
    const cardEl = document.querySelector('[data-swipe-card]');
    const emptyEl = document.querySelector('[data-swipe-empty]');
    const skipBtn = document.querySelector('[data-swipe-skip]');
    const joinBtn = document.querySelector('[data-swipe-join]');
    const imageEl = document.querySelector('[data-card-image]');
    const venueEl = document.querySelector('[data-card-venue]');
    const sportEl = document.querySelector('[data-card-sport]');
    const needsEl = document.querySelector('[data-card-needs]');
    const scheduleEl = document.querySelector('[data-card-schedule]');

    let deck = [...allCards];
    let pointerStartX = null;
    let dragShiftX = 0;

    const buildDeck = () => {
        const selectedSport = sportFilter?.value || '';
        deck = selectedSport ? allCards.filter((item) => item.sport === selectedSport) : [...allCards];
        renderCard();
    };

    const renderCard = () => {
        if (!deck.length) {
            cardEl.style.display = 'none';
            emptyEl.classList.add('is-visible');
            skipBtn.disabled = true;
            joinBtn.disabled = true;
            return;
        }

        const current = deck[0];
        cardEl.style.display = 'block';
        emptyEl.classList.remove('is-visible');
        skipBtn.disabled = false;
        joinBtn.disabled = false;
        imageEl.src = current.image;
        imageEl.alt = `Tim ${current.sport}`;
        venueEl.textContent = current.venue;
        sportEl.textContent = current.sport;
        needsEl.textContent = `Butuh ${current.neededPlayers} pemain`;
        scheduleEl.textContent = current.schedule;
    };

    const rotateDeck = () => {
        const first = deck.shift();
        if (first) deck.push(first);
        renderCard();
    };

    const swipe = (direction) => {
        if (!deck.length) return;
        cardEl.classList.remove('is-swiping-left', 'is-swiping-right');
        cardEl.classList.add(direction === 'left' ? 'is-swiping-left' : 'is-swiping-right');
        setTimeout(() => {
            cardEl.classList.remove('is-swiping-left', 'is-swiping-right');
            cardEl.style.transform = '';
            rotateDeck();
        }, 210);
    };

    skipBtn?.addEventListener('click', () => swipe('left'));
    joinBtn?.addEventListener('click', () => swipe('right'));
    sportFilter?.addEventListener('change', buildDeck);

    cardEl?.addEventListener('pointerdown', (event) => {
        if (!deck.length) return;
        pointerStartX = event.clientX;
        dragShiftX = 0;
        cardEl.style.transition = 'none';
        cardEl.setPointerCapture(event.pointerId);
    });
    cardEl?.addEventListener('pointermove', (event) => {
        if (pointerStartX === null) return;
        dragShiftX = event.clientX - pointerStartX;
        const rotate = dragShiftX * 0.06;
        cardEl.style.transform = `translateX(${dragShiftX}px) rotate(${rotate}deg)`;
    });
    cardEl?.addEventListener('pointerup', () => {
        if (pointerStartX === null) return;
        cardEl.style.transition = '';
        pointerStartX = null;
        if (dragShiftX < -90) swipe('left');
        else if (dragShiftX > 90) swipe('right');
        else cardEl.style.transform = '';
    });
    cardEl?.addEventListener('pointercancel', () => {
        pointerStartX = null;
        cardEl.style.transition = '';
        cardEl.style.transform = '';
    });

    buildDeck();
})();
</script>
</body>
</html>
