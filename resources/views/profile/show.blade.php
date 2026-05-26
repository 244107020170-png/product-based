@php
    $gender    = $user->gender ?? 'perempuan';
    $userName  = $user->name   ?? 'Pecinta Olahraga';
    $username  = $user->username
        ? '@'.$user->username
        : '@'.strtolower(str_replace(' ', '', $userName));
    $bio       = $user->bio ?: 'Hei! Saya suka olahraga dan senang bertemu teman baru.';
    $tags      = $user->sportTags();

    /* Badge logic — sama persis dengan halaman Keahlian */
    $badgeBookings = \App\Models\Booking::where('user_id', $user->id)
        ->whereIn('status', ['selesai', 'confirmed', 'pending'])
        ->count();
    $badgeMatches = \Illuminate\Support\Facades\DB::table('match_players')
        ->where('user_id', $user->id)
        ->count();
    $badgePoints = $badgeBookings + $badgeMatches;

    $badgeDefs = [
        ['name' => 'Pemula', 'range' => '1-5 Match', 'min' => 0, 'max' => 5, 'icon' => '👶', 'earned' => $badgePoints >= 1, 'color' => '#6b7280'],
        ['name' => 'Aktif', 'range' => '6-20 Match', 'min' => 6, 'max' => 20, 'icon' => '🌱', 'earned' => $badgePoints >= 6, 'color' => '#1d6fcf'],
        ['name' => 'Pro', 'range' => '>20 Match', 'min' => 21, 'max' => PHP_INT_MAX, 'icon' => '🌟', 'earned' => $badgePoints >= 21, 'color' => '#7c3aed'],
    ];

    $currentBadgeLevel = 'Pemula';
    foreach ($badgeDefs as $bd) {
        if ($badgePoints >= $bd['min']) { $currentBadgeLevel = $bd['name']; }
    }

    $badgeLabel = $currentBadgeLevel;
    $badgeColor = match($currentBadgeLevel) {
        'Pro' => '#7c3aed',
        'Aktif' => '#1d6fcf',
        default => '#6b7280',
    };
    $playerChar = asset('assets/images/characters/player.png');
    $reviewChar = asset('assets/images/characters/review.png');
    $coverImg   = $user->cover_photo ? (str_starts_with($user->cover_photo, 'covers/') ? asset('storage/' . $user->cover_photo) : $user->cover_photo) : asset('assets/images/bg/Explore.png');

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


    /* Helper: build match card data from DB record */
    $buildCard = function($m) {
        $cover = asset('assets/images/bg/Explore.png');
        $memberCount = optional($m->players)->count() ?? 0;
        $memberCount += 1; // +1 creator
        return [
            'img'     => $cover,
            'type'    => $m->type === 'public' ? 'Publik' : 'Pribadi',
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

    $favFieldCards = $favoriteFields->map(function ($fav) {
        $f = $fav->field;
        if (!$f) return null;
        return [
            'id'       => $f->id,
            'name'     => $f->name,
            'location' => $f->location ?? 'Lokasi tidak tersedia',
            'rating'   => $f->rating ?? '4.8',
            'image'    => $f->image ? asset('storage/' . $f->image) : asset('assets/images/bg/Explore.png'),
        ];
    })->filter();
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
            <div style="position: relative;">
                @include('partials.notification-bell')
            </div>
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

            {{-- Badge Section — logika sama persis dengan Keahlian --}}
            <div class="mt-8 mb-8" style="background: rgba(255,255,255,.94); border-radius: 20px; padding: 24px; box-shadow: 0 4px 18px rgba(0,0,0,.06); border: 1px solid rgba(0,0,77,.07);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; gap: 12px;">
                    <div>
                        <p style="margin: 0; font-size: .9rem; font-weight: 700; color: #64748b;">Total Aktivitas</p>
                        <p style="margin: 2px 0 0; font-size: 1.8rem; font-weight: 900; color: #02025b;">{{ $badgePoints }}</p>
                    </div>
                    <div style="text-align: right;">
                        <p style="margin: 0; font-size: .9rem; font-weight: 700; color: #64748b;">Level Saat Ini</p>
                        <span style="display: inline-block; margin-top: 4px; padding: 6px 16px; border-radius: 999px; font-size: .85rem; font-weight: 800; color: white; background: {{ $badgeColor }};">{{ $badgeLabel }}</span>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
                    @foreach($badgeDefs as $bd)
                    <div style="text-align: center; padding: 20px 12px 16px; border-radius: 16px; border: 1.5px solid {{ $bd['earned'] ? $bd['color'].'44' : 'rgba(0,0,0,.08)' }}; background: {{ $bd['earned'] ? $bd['color'].'18' : '#f8fafc' }}; {{ !$bd['earned'] ? 'filter: saturate(.35);' : '' }}">
                        <div style="font-size: 2rem; margin-bottom: 8px; line-height: 1;">{{ $bd['icon'] }}</div>
                        <p style="margin: 0 0 2px; font-size: 1rem; font-weight: 800; color: {{ $bd['earned'] ? $bd['color'] : '#6b7280' }};">{{ $bd['name'] }}</p>
                        <p style="margin: 0 0 12px; font-size: .75rem; font-weight: 600; color: #94a3b8;">{{ $bd['range'] }}</p>
                        <span style="display: inline-block; padding: 4px 14px; border-radius: 999px; font-size: .72rem; font-weight: 800; {{ $bd['earned'] ? 'background: #16a34a; color: white;' : 'background: rgba(0,0,77,.1); color: rgba(0,0,77,.5);' }}">{{ $bd['earned'] ? 'Diperoleh' : 'Terkunci' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- TABS --}}
            <div class="profview-tabs" role="tablist">
                <button class="profview-tab is-active" data-profview-tab="histori" role="tab" aria-selected="true" aria-controls="panel-histori">Histori Tim</button>
                <button class="profview-tab" data-profview-tab="private" role="tab" aria-selected="false" aria-controls="panel-private">Pribadi</button>
                <button class="profview-tab" data-profview-tab="favorit" role="tab" aria-selected="false" aria-controls="panel-favorit">Favorit</button>
                <button class="profview-tab" data-profview-tab="ulasan"  role="tab" aria-selected="false" aria-controls="panel-ulasan">Ulasan</button>
            </div>

            {{-- Panel: Histori Tim --}}
            <div class="profview-panel is-active" id="panel-histori" role="tabpanel">
                @if($historiCards->isEmpty())
                    <div class="profview-empty-char">
                        <img src="{{ $playerChar }}" alt="" class="profview-empty-char__img">
                        <p class="profview-empty-char__text">Wah, kayanya kamu belum pernah ikut match nih!</p>
                    </div>
                @else
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <span style="font-size: 14px; font-weight: 700; color: #02025b;">Riwayat pertandingan yang kamu ikuti</span>
                    <a href="{{ route('history.index') }}" style="padding: 8px 16px; border-radius: 8px; background: rgba(0,0,77,.06); color: #02025b; font-weight: 700; font-size: 12px; text-decoration: none; transition: background .2s;" onmouseover="this.style.background='rgba(0,0,77,.12)'" onmouseout="this.style.background='rgba(0,0,77,.06)'">Lihat semua</a>
                </div>
                <div class="profview-match-list" id="scroll-histori">
                    @foreach($historiCards as $m)
                        @include('profile.partials.match-card', ['match'=>$m])
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Panel: Pribadi --}}
            <div class="profview-panel" id="panel-private" role="tabpanel">
                @if($privateCards->isEmpty())
                    <div class="profview-empty-char">
                        <img src="{{ $playerChar }}" alt="" class="profview-empty-char__img">
                        <p class="profview-empty-char__text">Wah, kayanya kamu belum pernah bikin tim nih!</p>
                    </div>
                @else
                <div class="profview-match-list" id="scroll-private">
                    @foreach($privateCards as $m)
                        @include('profile.partials.match-card', ['match'=>$m])
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Panel: Favorit --}}
            <div class="profview-panel" id="panel-favorit" role="tabpanel">
                @if($favFieldCards->isEmpty())
                <div class="profview-empty-char">
                    <img src="{{ $playerChar }}" alt="" class="profview-empty-char__img">
                    <p class="profview-empty-char__text">Wah, kayanya kamu belum pernah tambah favorit nih!</p>
                </div>
                @else
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px;">
                    @foreach($favFieldCards as $fc)
                    <a href="{{ route('booking.show', $fc['id']) }}" style="text-decoration: none; color: inherit; display: block; background: #fff; border-radius: 14px; border: 1px solid rgba(0,0,77,.08); overflow: hidden; transition: all .2s; box-shadow: 0 2px 8px rgba(0,0,0,.04);" onmouseover="this.style.boxShadow='0 8px 20px rgba(0,0,0,.1)';this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,.04)';this.style.transform='none'">
                        <div style="height: 140px; overflow: hidden; background: #e2e8f0;">
                            <img src="{{ $fc['image'] }}" alt="{{ $fc['name'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div style="padding: 14px;">
                            <h4 style="margin: 0 0 4px; font-size: 15px; font-weight: 800; color: #02025b;">{{ $fc['name'] }}</h4>
                            <p style="margin: 0 0 6px; font-size: 12px; color: #666; display: flex; align-items: center; gap: 4px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                {{ $fc['location'] }}
                            </p>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 13px; font-weight: 700; color: #02025b;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="#f59e0b" style="display: inline; vertical-align: -2px;"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    {{ $fc['rating'] }}
                                </span>
                                <span style="background: #02025b; color: #fff; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">Pesan</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Panel: Review --}}
            <div class="profview-panel" id="panel-ulasan" role="tabpanel">
                <div class="profview-empty-char">
                    <img src="{{ $reviewChar }}" alt="" class="profview-empty-char__img">
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
