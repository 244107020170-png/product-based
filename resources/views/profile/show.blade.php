@php
    $gender    = $user->gender ?? 'perempuan';
    $userName  = $user->name   ?? 'Sport Enthusiast';
    $username  = $user->username
        ? '@'.$user->username
        : '@'.strtolower(str_replace(' ', '', $userName));
    $badge     = $user->playerBadge();
    $bio       = $user->bio ?: 'Hei! Saya suka olahraga dan senang bertemu teman baru.';
    $tags      = $user->sportTags();
    $playerChar = asset('assets/images/characters/player.png');
    $coverImg   = asset('assets/images/bg/Explore.png');

    /* Sidebar */
    $sidebarItems = [
        ['label'=>'Beranda',  'icon'=>asset('assets/images/icons/dashboard.png'), 'href'=>route('dashboard'),       'active'=>false],
        ['label'=>'Aktivitas',  'icon'=>asset('assets/images/icons/aktivitas.png'), 'href'=>route('activity.index'),  'active'=>false],
        ['label'=>'Favorit',  'icon'=>asset('assets/images/icons/favoritmu.png'), 'href'=>route('favorite.index'),  'active'=>false],
        ['label'=>'Histori',    'icon'=>asset('assets/images/icons/histori.png'),   'href'=>route('history.index'),   'active'=>false],
        ['label'=>'Cari tim',   'icon'=>asset('assets/images/icons/caritim.png'),   'href'=>route('matches.index'),   'active'=>false],
        ['label'=>'Pemesanan',    'icon'=>asset('assets/images/icons/booking.png'),   'href'=>route('booking.index'),   'active'=>false],
        ['label'=>'Keahlian', 'icon'=>asset('assets/images/icons/keahlian.png'),  'href'=>route('skill.index'),     'active'=>false],
        ['label'=>'Profil',     'icon'=>asset('assets/images/icons/profil.png'),    'href'=>route('profile.show'),    'active'=>true],
    ];
    $sidebarUtilities = [
        ['label'=>'Bantuan',    'icon'=>asset('assets/images/icons/bantuan.png'),    'href'=>route('preview.help')],
        ['label'=>'Pengaturan', 'icon'=>asset('assets/images/icons/pengaturan.png'), 'href'=>route('profile.edit')],
    ];

    /* Badge indo label */
    $badgeLabel = match($badge) {
        'Pro Player'    => 'Pemain Pro',
        'Active Player' => 'Pemain Aktif',
        default         => 'Pemula',
    };
    /* Badge color */
    $badgeColor = match($badge) {
        'Pro Player'    => '#7c3aed',
        'Active Player' => '#1d6fcf',
        default         => '#1d6fcf',
    };

    /* Helper: build match card data from DB record */
    $buildCard = function($m) {
        $cover = asset('assets/images/bg/Explore.png');
        $memberCount = optional($m->players)->count() ?? 0;
        $memberCount += 1; // +1 creator
        return [
            'img'     => $cover,
            'type'    => ucfirst($m->type ?? 'Public Match'),
            'title'   => $m->title,
            'host'    => optional($m->creator)->name ?? '-',
            'members' => $memberCount.' Member',
            'lokasi'  => optional($m->field)->location ?? (optional($m->field)->name ?? '-'),
            'waktu'   => $m->timeRange(),
            'tanggal' => $m->formattedDate(),
        ];
    };

    $historiCards  = $historiTim->map($buildCard);
    $privateCards  = $privateMatch->map($buildCard);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profil Player – {{ config('app.name', 'Spies Sport') }}</title>
    @vite([
        'resources/css/app.css',
        'resources/css/player-dashboard.css',
        'resources/css/player-profile-view.css',
        'resources/js/player-dashboard.js',
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

    {{-- Topbar --}}
    <header class="profview-topbar">
        <button type="button" class="player-dashboard-topbar__menu" data-sidebar-open aria-label="Menu"><span></span><span></span><span></span></button>
        <h1 class="profview-topbar__title">Profil Player</h1>
        <div class="profview-topbar__right">
            <button type="button" class="player-dashboard-topbar__icon" aria-label="Pesan">
                <span class="player-inline-icon">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M21 15C21 15.53 20.79 16.04 20.41 16.41C20.04 16.79 19.53 17 19 17H7L3 21V5C3 4.47 3.21 3.96 3.59 3.59C3.96 3.21 4.47 3 5 3H19C19.53 3 20.04 3.21 20.41 3.59C20.79 3.96 21 4.47 21 5V15Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
            </button>
            <button type="button" class="player-dashboard-topbar__icon" aria-label="Notifikasi">
                <span class="player-inline-icon">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M9 18H15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M6.5 17.5H17.5L16.3 15.6C15.9 15 15.7 14.3 15.7 13.6V10.8C15.7 8.49 14.04 6.54 11.8 6.16V5.5C11.8 4.67 11.13 4 10.3 4 9.47 4 8.8 4.67 8.8 5.5V6.16C6.56 6.54 4.9 8.49 4.9 10.8V13.6C4.9 14.3 4.7 15 4.3 15.6L3.1 17.5H6.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                </span>
            </button>
        </div>
    </header>

    <section class="profview-content">

        {{-- Flash: profil berhasil diperbarui --}}
        @if(session('status') === 'profile-updated')
        <div class="profview-flash profview-flash--success" id="profview-flash" role="alert">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                <path d="M5 12L10 17L19 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Profil berhasil diperbarui!
        </div>
        @endif

        {{-- Cover --}}
        <div class="profview-cover">
            <img src="{{ $coverImg }}" alt="Cover" class="profview-cover__img">
        </div>

        {{-- Card --}}
        <div class="profview-card">

            {{-- Avatar + Edit --}}
            <div class="profview-avatar-row">
                <div class="profview-avatar-wrap">
                    <img src="{{ $user->avatarUrl() }}" alt="{{ $userName }}" class="profview-avatar-wrap__img" id="profview-avatar-img">
                </div>
                <a href="{{ route('profile.edit') }}" class="profview-edit-btn">Edit profil</a>
            </div>

            {{-- Identity --}}
            <div class="profview-identity">
                <h2 class="profview-name">{{ $userName }}</h2>
                <span class="profview-badge-pill" style="--badge-color: {{ $badgeColor }};">
                    <span class="profview-badge-pill__icon" style="background:{{ $badgeColor }};">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M5 12L10 17L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                    <span class="profview-badge-pill__label">{{ $badgeLabel }}</span>
                </span>
            </div>
            <span class="profview-username">{{ $username }}</span>

            {{-- Bio --}}
            <p class="profview-bio">{{ $bio }}</p>

            {{-- Tags --}}
            @if(count($tags))
            <div class="profview-tags">
                @foreach($tags as $tag)<span class="profview-tag">{{ $tag }}</span>@endforeach
            </div>
            @endif

            {{-- Progress Points Component --}}
            <div class="mt-8 mb-8">
                <x-progress-points :user="$user" />
            </div>

            {{-- TABS --}}
            <div class="profview-tabs" role="tablist">
                <button class="profview-tab is-active" data-profview-tab="histori" role="tab" aria-selected="true" aria-controls="panel-histori">Histori Tim</button>
                <button class="profview-tab" data-profview-tab="private" role="tab" aria-selected="false" aria-controls="panel-private">Private Match</button>
                <button class="profview-tab" data-profview-tab="favorit" role="tab" aria-selected="false" aria-controls="panel-favorit">Favorit</button>
                <button class="profview-tab" data-profview-tab="review"  role="tab" aria-selected="false" aria-controls="panel-review">Review</button>
            </div>

            {{-- Panel: Histori Tim --}}
            <div class="profview-panel is-active" id="panel-histori" role="tabpanel">
                @if($historiCards->isEmpty())
                    <div class="profview-empty-char">
                        <img src="{{ $playerChar }}" alt="" class="profview-empty-char__img">
                        <p class="profview-empty-char__text">Wah, kayanya kamu belum pernah ikut match nih!</p>
                    </div>
                @else
                <div class="profview-match-list" id="scroll-histori">
                    @foreach($historiCards as $m)
                        @include('profile.partials.match-card', ['match'=>$m, 'scrollListId'=>'scroll-histori'])
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Panel: Private Match --}}
            <div class="profview-panel" id="panel-private" role="tabpanel">
                @if($privateCards->isEmpty())
                    <div class="profview-empty-char">
                        <img src="{{ $playerChar }}" alt="" class="profview-empty-char__img">
                        <p class="profview-empty-char__text">Wah, kayanya kamu belum pernah bikin tim nih!</p>
                    </div>
                @else
                <div class="profview-match-list" id="scroll-private">
                    @foreach($privateCards as $m)
                        @include('profile.partials.match-card', ['match'=>$m, 'scrollListId'=>'scroll-private'])
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Panel: Favorit --}}
            <div class="profview-panel" id="panel-favorit" role="tabpanel">
                <div class="profview-empty-char">
                    <img src="{{ $playerChar }}" alt="" class="profview-empty-char__img">
                    <p class="profview-empty-char__text">Wah, kayanya kamu belum pernah tambah favorit nih!</p>
                </div>
            </div>

            {{-- Panel: Review --}}
            <div class="profview-panel" id="panel-review" role="tabpanel">
                <div class="profview-empty-char">
                    <img src="{{ $playerChar }}" alt="" class="profview-empty-char__img">
                    <p class="profview-empty-char__text">Wah, kayanya kamu belum pernah me-review nih!</p>
                </div>
            </div>

        </div>{{-- /card --}}
    </section>
</main>
</div>

<style>
.profview-flash {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 18px;
    border-radius: 14px;
    font-size: .9rem;
    font-weight: 700;
    margin-bottom: 16px;
    animation: slideDown .35s ease;
}
.profview-flash--success {
    background: rgba(30,143,103,.1);
    color: #1e8f67;
    border: 1px solid rgba(30,143,103,.22);
}
@keyframes slideDown {
    from { opacity:0; transform:translateY(-10px); }
    to   { opacity:1; transform:translateY(0); }
}
</style>

<script>
(function(){
    /* tabs */
    const tabs=document.querySelectorAll('[data-profview-tab]');
    const panels=document.querySelectorAll('.profview-panel');
    tabs.forEach(t=>t.addEventListener('click',()=>{
        tabs.forEach(x=>{x.classList.remove('is-active');x.setAttribute('aria-selected','false');});
        panels.forEach(p=>p.classList.remove('is-active'));
        t.classList.add('is-active');t.setAttribute('aria-selected','true');
        document.getElementById('panel-'+t.dataset.profviewTab)?.classList.add('is-active');
    }));
    /* scroll arrows */
    document.addEventListener('click',e=>{
        const btn=e.target.closest('[data-scroll-dir]');if(!btn)return;
        const list=document.getElementById(btn.dataset.scrollList)||document.querySelector('.profview-panel.is-active .profview-match-list');
        list?.scrollBy({top:btn.dataset.scrollDir==='up'?-170:170,behavior:'smooth'});
    });
    /* Auto-dismiss flash */
    const flash=document.getElementById('profview-flash');
    if(flash) setTimeout(()=>{
        flash.style.transition='opacity .5s ease, transform .5s ease';
        flash.style.opacity='0';
        flash.style.transform='translateY(-8px)';
        setTimeout(()=>flash.remove(),500);
    },3500);
})();
</script>

</body>
</html>
