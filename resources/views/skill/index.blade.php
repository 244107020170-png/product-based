@php
    use Carbon\Carbon;
    $user     = auth()->user();
    $userName = $user?->name ?: 'Pecinta Olahraga';
    $currentDate = Carbon::now()->locale('id')->translatedFormat('j F Y');
    $profileAvatar = $user?->avatarUrl();

    $sidebarItems = [
        ['label' => 'Beranda',  'icon' => asset('assets/images/icons/dashboard.png'),  'href' => route('dashboard'),     'active' => false],
        ['label' => 'Aktivitas',  'icon' => asset('assets/images/icons/aktivitas.png'),  'href' => url('/matches'),        'active' => false],
        ['label' => 'Favorit', 'icon' => asset('assets/images/icons/favoritmu.png'),  'href' => route('favorite.index'),                   'active' => false],
        ['label' => 'Histori',   'icon' => asset('assets/images/icons/histori.png'),    'href' => route('history.index'), 'active' => false],
        ['label' => 'Cari tim',  'icon' => asset('assets/images/icons/caritim.png'),   'href' => route('matches.index'), 'active' => false],
        ['label' => 'Pemesanan',   'icon' => asset('assets/images/icons/booking.png'),   'href' => route('booking.index'),         'active' => false],
        ['label' => 'Keahlian','icon' => asset('assets/images/icons/keahlian.png'),  'href' => route('skill.index'),   'active' => true],
        ['label' => 'Profil',    'icon' => asset('assets/images/icons/profil.png'),    'href' => route('profile.show'),  'active' => false],
    ];
    $sidebarUtilities = [
        ['label' => 'Bantuan',    'icon' => asset('assets/images/icons/bantuan.png'),    'href' => route('preview.help')],
        ['label' => 'Pengaturan','icon' => asset('assets/images/icons/pengaturan.png'), 'href' => route('profile.edit')],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Keahlianmu – {{ config('app.name','Spies Sport') }}</title>
@vite(['resources/css/app.css','resources/css/player-dashboard.css'])
<style>
/* ═══════════════════════════════════════════════
   SKILL PAGE — inline styles
   ═══════════════════════════════════════════════ */

/* ── wrapper ── */
.sk-main { padding: 8px 24px 56px; }

/* ── Hero (no card bg – floats on page bg) ── */
.sk-hero {
    display: grid;
    grid-template-columns: 220px 1fr auto;
    align-items: center;
    gap: 32px;
    padding: 20px 10px 24px;
    margin-bottom: 20px;
}

.sk-hero__trophy {
    display: flex;
    align-items: center;
    justify-content: center;
}

.sk-hero__trophy img {
    width: 200px;
    height: auto;
    animation: trophyFloat 3.2s ease-in-out infinite;
    filter: drop-shadow(0 12px 24px rgba(0,0,77,.14));
}

@keyframes trophyFloat {
    0%,100%{ transform: translateY(0) rotate(-2deg); }
    50%{ transform: translateY(-12px) rotate(2deg); }
}

.sk-hero__info { display: flex; flex-direction: column; gap: 2px; }

.sk-hero__name {
    font-size: 1.55rem;
    font-weight: 900;
    color: #4f46e5;
    margin: 0;
}
.sk-hero__level {
    font-size: 1.25rem;
    font-weight: 800;
    color: #00004d;
    margin: 0;
}
.sk-hero__points {
    font-size: .9rem;
    font-weight: 700;
    color: #4f46e5;
    margin: 0 0 14px;
}

/* two-tone progress bar */
.sk-pbar-wrap { margin-bottom: 10px; }
.sk-pbar-track {
    width: 100%;
    height: 16px;
    border-radius: 999px;
    background: rgba(239,68,68,.22);
    overflow: hidden;
    position: relative;
}
.sk-pbar-fill {
    height: 100%;
    border-radius: 999px;
    background: linear-gradient(90deg, #ef4444 0%, #f87171 100%);
    width: 0%;
    transition: width 1.4s cubic-bezier(.22,1,.36,1);
}
.sk-pbar-hint {
    font-size: .82rem;
    font-weight: 700;
    color: #4f46e5;
    margin: 8px 0 0;
}

/* team button */
.sk-team-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 11px 32px;
    border: 2px solid rgba(0,0,77,.2);
    border-radius: 999px;
    background: rgba(255,255,255,.9);
    font-size: .92rem;
    font-weight: 800;
    color: #00004d;
    text-decoration: none;
    white-space: nowrap;
    transition: all .22s ease;
    box-shadow: 0 6px 18px rgba(0,0,77,.07);
}
.sk-team-btn:hover {
    background: #00004d;
    color: #fff;
    border-color: #00004d;
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(0,0,77,.16);
}

/* ── Stat cards ── */
.sk-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
    margin-bottom: 30px;
}
.sk-stat {
    background: rgba(255,255,255,.94);
    border: 1px solid rgba(0,0,77,.07);
    border-radius: 22px;
    padding: 32px 24px 26px;
    text-align: center;
    box-shadow: 0 16px 36px rgba(0,0,77,.08);
}
.sk-stat__num {
    font-size: 3.2rem;
    font-weight: 900;
    color: #00004d;
    line-height: 1;
    display: block;
    margin-bottom: 10px;
}
.sk-stat__lbl {
    font-size: .95rem;
    font-weight: 700;
    color: rgba(0,0,77,.52);
    margin: 0;
}

/* ── Section title ── */
.sk-title {
    font-size: .9rem;
    font-weight: 900;
    letter-spacing: .1em;
    color: #4f46e5;
    text-transform: uppercase;
    margin: 0 0 12px;
}

/* ── Sport list card ── */
.sk-sports {
    background: rgba(255,255,255,.94);
    border: 1px solid rgba(0,0,77,.07);
    border-radius: 22px;
    padding: 8px 24px;
    box-shadow: 0 16px 36px rgba(0,0,77,.08);
    margin-bottom: 30px;
}
.sk-sport-row {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 18px 0;
    border-bottom: 1px solid rgba(0,0,77,.07);
}
.sk-sport-row:last-child { border-bottom: none; }

.sk-dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    flex-shrink: 0;
}

.sk-sport-meta { flex: 1; min-width: 0; }
.sk-sport-meta h3 {
    font-size: 1rem;
    font-weight: 800;
    color: #00004d;
    margin: 0 0 3px;
}
.sk-sport-meta p {
    font-size: .78rem;
    color: rgba(0,0,77,.52);
    font-weight: 600;
    margin: 0;
}

/* two-tone sport bar */
.sk-sport-bar {
    width: 150px;
    flex-shrink: 0;
}
.sk-sport-bar-track {
    height: 10px;
    border-radius: 999px;
    overflow: hidden;
    position: relative;
}
.sk-sport-bar-fill {
    height: 100%;
    border-radius: 999px;
    width: 0%;
    transition: width 1.4s cubic-bezier(.22,1,.36,1);
}

.sk-sport-empty {
    text-align: center;
    padding: 40px 0;
    color: rgba(0,0,77,.38);
    font-size: .9rem;
    font-weight: 700;
}

/* ── Badge grid ── */
.sk-badges {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}
.sk-badge {
    background: #d1ebe5;
    border: 1px solid rgba(0,0,77,.06);
    border-radius: 20px;
    padding: 28px 16px 22px;
    text-align: center;
    box-shadow: 0 8px 22px rgba(0,0,77,.07);
    transition: transform .2s ease, box-shadow .2s ease;
    position: relative;
    overflow: hidden;
}
.sk-badge:hover { transform: translateY(-3px); box-shadow: 0 14px 30px rgba(0,0,77,.1); }
.sk-badge.is-locked { filter: saturate(.35); }

.sk-badge__icon-wrap {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #22c55e;
    margin-bottom: 12px;
    font-size: 1.8rem;
    line-height: 1;
}
.sk-badge__icon-wrap > span { width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; }
.sk-badge__icon-wrap > span svg { width: 100%; height: 100%; }
.sk-badge.is-locked .sk-badge__icon-wrap { background: #6b7280; }

.sk-badge__lock {
    position: absolute;
    bottom: -4px;
    right: -4px;
    width: 24px;
    height: 24px;
    background: #374151;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .7rem;
    color: #fff;
}

.sk-badge__name {
    font-size: 1rem;
    font-weight: 800;
    color: #00004d;
    margin: 0 0 4px;
}
.sk-badge__range {
    font-size: .75rem;
    color: rgba(0,0,77,.52);
    font-weight: 600;
    margin: 0 0 14px;
}
.sk-badge__chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 999px;
    padding: 5px 18px;
    font-size: .72rem;
    font-weight: 800;
}
.sk-badge__chip--earned { background: #16a34a; color: #fff; }
.sk-badge__chip--locked { background: rgba(0,0,77,.16); color: rgba(0,0,77,.55); }

/* ── Responsive ── */
@media(max-width: 860px){
    .sk-hero { grid-template-columns: 140px 1fr; }
    .sk-hero__trophy img { width: 130px; }
    .sk-team-btn { grid-column: 1/-1; }
    .sk-sport-bar { width: 90px; }
}
@media(max-width: 600px){
    .sk-main { padding: 8px 12px 40px; }
    .sk-hero { grid-template-columns: 1fr; text-align: center; }
    .sk-stats { grid-template-columns: 1fr 1fr; }
    .sk-badges { grid-template-columns: 1fr 1fr; }
    .sk-sport-bar { display: none; }
}
</style>
</head>
<body class="player-dashboard-page" style="--player-dashboard-bg:url('{{ asset('assets/images/bg/bg-login.png') }}');">
<div class="player-dashboard-shell">

{{-- ── SIDEBAR ── --}}
<aside class="player-sidebar" data-sidebar>
  <div class="player-sidebar__inner">
    <div class="player-sidebar__header">
      <a href="{{ route('dashboard') }}" class="player-sidebar__brand">
        <img src="{{ asset('assets/images/logo/logodb.png') }}" alt="Spies Sport" class="player-sidebar__logo">
      </a>
      <button type="button" class="player-sidebar__close" data-sidebar-close aria-label="Tutup"><span></span><span></span></button>
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

{{-- ── MAIN ── --}}
<main class="player-dashboard-main">

  {{-- Topbar --}}
  <header class="player-dashboard-topbar">
    <div class="player-dashboard-topbar__left">
      <button type="button" class="player-dashboard-topbar__menu" data-sidebar-open><span></span><span></span><span></span></button>
      <label class="player-search" for="skill-search">
        <span class="player-search__icon">
          <svg viewBox="0 0 20 20" fill="none"><circle cx="9" cy="9" r="5.75" stroke="currentColor" stroke-width="1.8"/><path d="M13.5 13.5L17 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        </span>
        <input id="skill-search" type="search" placeholder="Cari lapangan...">
      </label>
    </div>
    <div class="player-dashboard-topbar__right">
      <div class="player-dashboard-topbar__date">
        <span class="player-inline-icon">
          <svg viewBox="0 0 24 24" fill="none"><rect x="3.5" y="5.5" width="17" height="15" rx="2.5" stroke="currentColor" stroke-width="1.8"/><path d="M7 3.5V7M17 3.5V7M3.5 9.5H20.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        </span>
        <span>{{ $currentDate }}</span>
      </div>
      <div style="position: relative;">
        @include('partials.notification-bell')
      </div>
      <a href="{{ route('profile.show') }}" class="player-profile-pill">
        <span class="player-profile-pill__avatar">
          <img src="{{ $profileAvatar }}" alt="Profil" class="player-avatar-image player-avatar-image--profile">
        </span>
        <span class="player-profile-pill__name">{{ $userName }}</span>
      </a>
    </div>
  </header>

  {{-- ════════ SKILL PAGE ════════ --}}
  <div class="sk-main">

    {{-- ── HERO ── --}}
    <div class="sk-hero">

      {{-- Trophy illustration --}}
      <div class="sk-hero__trophy">
        <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
          <!-- Cup body -->
          <ellipse cx="80" cy="60" rx="42" ry="12" fill="#C8A96E"/>
          <path d="M38 60 Q30 110 50 125 Q65 135 80 135 Q95 135 110 125 Q130 110 122 60Z" fill="#E8C97A"/>
          <path d="M42 60 Q35 105 54 120 Q68 130 80 130 Q92 130 106 120 Q125 105 118 60Z" fill="#F5D98A"/>
          <!-- Cup shine -->
          <path d="M55 72 Q58 85 60 98" stroke="rgba(255,255,255,0.5)" stroke-width="4" stroke-linecap="round"/>
          <!-- Handles -->
          <path d="M38 68 Q18 68 18 88 Q18 108 38 108" stroke="#C8A96E" stroke-width="8" stroke-linecap="round" fill="none"/>
          <path d="M122 68 Q142 68 142 88 Q142 108 122 108" stroke="#C8A96E" stroke-width="8" stroke-linecap="round" fill="none"/>
          <!-- Stem -->
          <rect x="72" y="135" width="16" height="24" rx="4" fill="#C8A96E"/>
          <rect x="58" y="156" width="44" height="10" rx="5" fill="#A07840"/>
          <!-- Target/Archery circles on cup -->
          <circle cx="80" cy="95" r="26" fill="none" stroke="#D4A060" stroke-width="2"/>
          <circle cx="80" cy="95" r="18" fill="none" stroke="#D4A060" stroke-width="2"/>
          <circle cx="80" cy="95" r="10" fill="none" stroke="#D4A060" stroke-width="2"/>
          <circle cx="80" cy="95" r="4" fill="#E05050"/>
          <!-- Arrow -->
          <line x1="130" y1="55" x2="84" y2="93" stroke="#5B3A1A" stroke-width="3" stroke-linecap="round"/>
          <path d="M130 55 L122 52 L126 60Z" fill="#5B3A1A"/>
          <!-- Star -->
          <path d="M155 130 L158 140 L168 140 L160 146 L163 156 L155 150 L147 156 L150 146 L142 140 L152 140Z" fill="#F5C518"/>
          <circle cx="155" cy="143" r="12" fill="none" stroke="#E8A000" stroke-width="1.5"/>
          <!-- Medal ribbon -->
          <path d="M62 165 Q55 178 48 185" stroke="#E05050" stroke-width="5" stroke-linecap="round"/>
          <path d="M98 165 Q105 178 112 185" stroke="#3B82F6" stroke-width="5" stroke-linecap="round"/>
          <circle cx="48" cy="185" r="8" fill="#E05050"/>
          <circle cx="112" cy="185" r="8" fill="#3B82F6"/>
          <circle cx="80" cy="170" r="12" fill="#F5C518" stroke="#E8A000" stroke-width="2"/>
          <text x="80" y="175" text-anchor="middle" font-size="12" font-weight="bold" fill="#7B5200">1</text>
        </svg>
      </div>

      {{-- Info --}}
      <div class="sk-hero__info">
        <p class="sk-hero__name">{{ $userName }}</p>
        <p class="sk-hero__level">Pemain {{ $currentLevel['name'] }}</p>
        <p class="sk-hero__points">{{ $totalPoints }} Poin</p>

        <div class="sk-pbar-wrap">
          <div class="sk-pbar-track">
            <div class="sk-pbar-fill" id="sk-prog" data-target="{{ $progressPct }}" style="width:0%"></div>
          </div>
          @if($nextLevel)
            <p class="sk-pbar-hint">Butuh {{ $pointsToNext }} poin lagi nih untuk jadi {{ $nextLevel['name'] }}, Semangat!</p>
          @else
            <p class="sk-pbar-hint"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" style="display:inline-block;vertical-align:-2px;margin-right:4px;"><path d="M8 21H16M12 17V21M7 4H17V11C17 14.314 14.761 17 12 17C9.239 17 7 14.314 7 11V4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 6H4C4 6 3 10 6 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M17 6H20C20 6 21 10 18 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>Kamu sudah mencapai level tertinggi, Pro!</p>
          @endif
        </div>
      </div>

      {{-- Button --}}
      <a href="{{ url('/matches') }}" class="sk-team-btn">Temukan Tim</a>
    </div>

    {{-- ── STATS ── --}}
    <div class="sk-stats">
      <div class="sk-stat">
        <span class="sk-stat__num" data-count="{{ $totalBookings }}">0</span>
        <p class="sk-stat__lbl">Pesanan Lapangan</p>
      </div>
      <div class="sk-stat">
        <span class="sk-stat__num" data-count="{{ $totalMatches }}">0</span>
        <p class="sk-stat__lbl">Gabung Pertandingan Umum</p>
      </div>
    </div>

    {{-- ── OLAHRAGA FAVORIT ── --}}
    <p class="sk-title">Olahraga Favorit Kamu</p>
    <div class="sk-sports">
      @forelse($sports as $sport)
        <div class="sk-sport-row">
          <span class="sk-dot" style="background:{{ $sport['color'] }}"></span>
          <div class="sk-sport-meta">
            <h3>{{ $sport['name'] }}</h3>
            <p>{{ $sport['bookings'] }}x pesanan • {{ $sport['matches'] }}x pertandingan umum</p>
          </div>
          <div class="sk-sport-bar">
            <div class="sk-sport-bar-track" style="background:{{ $sport['bg'] }}">
              <div class="sk-sport-bar-fill" style="background:{{ $sport['color'] }};width:0%" data-target="{{ $sport['pct'] }}"></div>
            </div>
          </div>
        </div>
      @empty
        <div class="sk-sport-empty">Belum ada aktivitas olahraga tercatat.</div>
      @endforelse
    </div>

    {{-- ── BADGE ── --}}
    <p class="sk-title">Level</p>
    <div class="sk-badges">
      @foreach($badges as $badge)
        <div class="sk-badge{{ !$badge['earned'] ? ' is-locked' : '' }}">
          <div class="sk-badge__icon-wrap">
            <span>{!! $badge['icon'] !!}</span>
            @if(!$badge['earned'])
              <div class="sk-badge__lock">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                  <rect x="5" y="11" width="14" height="10" rx="2" stroke="currentColor" stroke-width="2"/>
                  <path d="M8 11V8A4 4 0 0 1 16 8V11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
              </div>
            @endif
          </div>
          <p class="sk-badge__name">{{ $badge['name'] }}</p>
          <p class="sk-badge__range">{{ $badge['range'] }}</p>
          <span class="sk-badge__chip {{ $badge['earned'] ? 'sk-badge__chip--earned' : 'sk-badge__chip--locked' }}">
            {{ $badge['earned'] ? 'Diperoleh' : 'Terkunci' }}
          </span>
        </div>
      @endforeach
    </div>

  </div>{{-- /sk-main --}}
</main>
</div>

<script>
(function () {
  /* ── sidebar ── */
  const sidebar  = document.querySelector('[data-sidebar]');
  const backdrop = document.querySelector('[data-sidebar-backdrop]');
  const setOpen  = v => {
    sidebar?.classList.toggle('is-open', v);
    backdrop?.classList.toggle('is-visible', v);
  };
  document.querySelector('[data-sidebar-open]')?.addEventListener('click', () => setOpen(true));
  document.querySelector('[data-sidebar-close]')?.addEventListener('click', () => setOpen(false));
  backdrop?.addEventListener('click', () => setOpen(false));

  /* ── count-up ── */
  function countUp(el, target, duration) {
    const start = performance.now();
    const step  = now => {
      const p = Math.min((now - start) / duration, 1);
      el.textContent = Math.round(p * target);
      if (p < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
  }

  /* ── animate ── */
  function runAnimations() {
    /* progress bar */
    const prog = document.getElementById('sk-prog');
    if (prog) prog.style.width = prog.dataset.target + '%';

    /* sport bars */
    document.querySelectorAll('.sk-sport-bar-fill').forEach(el => {
      el.style.width = el.dataset.target + '%';
    });

    /* counters */
    document.querySelectorAll('[data-count]').forEach(el => {
      countUp(el, parseInt(el.dataset.count || '0'), 900);
    });
  }

  /* run on intersection */
  if ('IntersectionObserver' in window) {
    const obs = new IntersectionObserver(entries => {
      if (entries[0].isIntersecting) { runAnimations(); obs.disconnect(); }
    }, { threshold: 0.05 });
    const hero = document.querySelector('.sk-hero');
    if (hero) obs.observe(hero); else runAnimations();
  } else {
    runAnimations();
  }
})();
</script>
</body>
</html>
