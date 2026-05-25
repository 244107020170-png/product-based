@php
    use Carbon\Carbon;
    $user        = auth()->user();
$userName = $user?->name ?: 'Pecinta Olahraga';
    $currentDate = Carbon::now()->locale('id')->translatedFormat('j F Y');
    $profileAvatar = $user?->avatarUrl();

    $sidebarItems = [
        ['label' => 'Beranda',  'icon' => asset('assets/images/icons/dashboard.png'),  'href' => route('dashboard'),       'active' => false],
        ['label' => 'Aktivitas',  'icon' => asset('assets/images/icons/aktivitas.png'),  'href' => url('/matches'),          'active' => true],
        ['label' => 'Favorit', 'icon' => asset('assets/images/icons/favoritmu.png'),  'href' => route('favorite.index'),  'active' => false],
        ['label' => 'Histori',   'icon' => asset('assets/images/icons/histori.png'),    'href' => route('history.index'),   'active' => false],
        ['label' => 'Cari tim',  'icon' => asset('assets/images/icons/caritim.png'),   'href' => route('matches.index'),   'active' => false],
        ['label' => 'Pemesanan',   'icon' => asset('assets/images/icons/booking.png'),   'href' => route('booking.index'),           'active' => false],
        ['label' => 'Keahlian','icon' => asset('assets/images/icons/keahlian.png'),  'href' => route('skill.index'),     'active' => false],
        ['label' => 'Profil',    'icon' => asset('assets/images/icons/profil.png'),    'href' => route('profile.show'),    'active' => false],
    ];
    $sidebarUtilities = [
        ['label' => 'Bantuan',    'icon' => asset('assets/images/icons/bantuan.png'),    'href' => route('preview.help')],
        ['label' => 'Pengaturan','icon' => asset('assets/images/icons/pengaturan.png'), 'href' => route('profile.edit')],
    ];

    $sportIcons = [
        'Futsal'    => asset('assets/images/icons/futsal.png'),
        'Badminton' => asset('assets/images/icons/bultang.png'),
        'Voli'      => asset('assets/images/icons/gor.png'),
        'Basket'    => asset('assets/images/icons/gor.png'),
        'Renang'    => asset('assets/images/icons/gor.png'),
        'Olahraga'  => asset('assets/images/icons/gor.png'),
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Aktivitas – {{ config('app.name','Spies Sport') }}</title>
@vite(['resources/css/app.css','resources/css/player-dashboard.css'])
<style>
/* ═══════════════════════════════════════
   AKTIVITAS PAGE — inline styles
   ═══════════════════════════════════════ */

/* wrapper – no max-width, fills the main column */
.act-main {
    padding: 8px 24px 56px;
    box-sizing: border-box;
    width: 100%;
}

/* ── Page title row ── */
.act-title-row {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 28px;
}
.act-title-text {
    font-size: clamp(1.4rem, 2.2vw, 2rem);
    font-weight: 900;
    color: #00004d;
    margin: 0;
    line-height: 1.15;
}

/* ── Hero (floats on page bg, no card) ── */
.act-hero {
    display: flex;
    align-items: center;
    gap: 32px;
    padding: 0 0 32px;
    width: 100%;
    box-sizing: border-box;
}
.act-trophy {
    flex: 0 0 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.act-trophy svg {
    width: 100%;
    max-width: 200px;
    height: auto;
    animation: trophyFloat 3.2s ease-in-out infinite;
    filter: drop-shadow(0 12px 24px rgba(0,0,77,.12));
}
@keyframes trophyFloat {
    0%,100%{ transform: translateY(0) rotate(-2deg); }
    50%     { transform: translateY(-12px) rotate(2deg); }
}

/* info col – takes remaining space */
.act-hero__info {
    flex: 1;
    min-width: 0;       /* critical: allows flex child to shrink below content size */
    width: 100%;
    box-sizing: border-box;
}
.act-hero__name {
    font-size: clamp(1.2rem, 2vw, 1.55rem);
    font-weight: 900;
    color: #4f46e5;
    margin: 0 0 2px;
}
.act-hero__level {
    font-size: clamp(1rem, 1.6vw, 1.25rem);
    font-weight: 800;
    color: #00004d;
    margin: 0 0 3px;
}
.act-hero__pts {
    font-size: .88rem;
    font-weight: 700;
    color: rgba(0,0,77,.55);
    margin: 0 0 14px;
}

/* progress bar – fills 100% of its parent */
.act-pbar-wrap {
    width: 100%;
    box-sizing: border-box;
}
.act-pbar-track {
    width: 100%;          /* fills .act-hero__info */
    height: 18px;
    border-radius: 999px;
    background: rgba(239,68,68,.18);
    overflow: hidden;
    margin-bottom: 10px;
    box-sizing: border-box;
    display: block;
}
.act-pbar-fill {
    display: block;
    height: 100%;
    border-radius: 999px;
    background: linear-gradient(90deg, #ef4444 0%, #f87171 100%);
    width: 0%;            /* animated by JS */
    transition: width 1.4s cubic-bezier(.22,1,.36,1);
}
.act-pbar-hint {
    font-size: .82rem;
    font-weight: 700;
    color: #4f46e5;
    margin: 0;
}

/* ── Big card ── */
.act-card {
    background: rgba(255,255,255,.94);
    border: 1px solid rgba(0,0,77,.07);
    border-radius: 24px;
    padding: 32px 36px;
    box-shadow: 0 20px 48px rgba(0,0,77,.09);
    width: 100%;
    box-sizing: border-box;
}

/* ── Section heading inside card ── */
.act-card__section-title {
    font-size: 1.2rem;
    font-weight: 800;
    color: #00004d;
    margin: 0 0 8px;
}
.act-card__section-sub {
    font-size: .88rem;
    color: rgba(0,0,77,.55);
    font-weight: 600;
    margin: 0 0 22px;
}

/* ── Point rules list ── */
.act-rules { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 16px; }
.act-rule {
    display: flex;
    align-items: center;
    gap: 14px;
    font-size: .95rem;
    font-weight: 700;
    color: #00004d;
}
.act-rule__icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: rgba(0,0,77,.07);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.act-rule__icon img { width: 22px; height: 22px; object-fit: contain; }
.act-rule__pts {
    margin-left: auto;
    font-size: .85rem;
    color: #1e8f67;
    font-weight: 800;
    white-space: nowrap;
    padding-left: 8px;
}

/* ── Divider ── */
.act-divider {
    border: none;
    border-top: 1px solid rgba(0,0,77,.1);
    margin: 28px 0;
}

/* ── Activity list ── */
.act-list { display: flex; flex-direction: column; gap: 14px; }
.act-item {
    display: flex;
    align-items: center;
    gap: 14px;
    font-size: .92rem;
    font-weight: 700;
    color: #00004d;
    animation: itemIn .35s ease both;
}
@keyframes itemIn {
    from { opacity:0; transform:translateX(-10px); }
    to   { opacity:1; transform:translateX(0); }
}
.act-item__sport-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(0,0,77,.1);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    overflow: hidden;
}
.act-item__sport-icon img { width: 26px; height: 26px; object-fit: contain; }
.act-item__pts {
    margin-left: auto;
    font-size: .82rem;
    font-weight: 800;
    color: #1e8f67;
    white-space: nowrap;
    padding-left: 8px;
}
.act-empty-activity {
    padding: 28px 0;
    text-align: center;
    color: rgba(0,0,77,.38);
    font-size: .9rem;
    font-weight: 700;
}

/* ══ RESPONSIVE ══════════════════════════ */

/* tablet */
@media(max-width: 860px) {
    .act-main { padding: 8px 16px 48px; }
    .act-trophy { flex: 0 0 150px; }
    .act-hero { gap: 20px; }
    .act-card { padding: 24px 22px; }
}

/* large phone */
@media(max-width: 640px) {
    .act-main { padding: 6px 12px 40px; }
    .act-trophy { flex: 0 0 120px; }
    .act-hero { gap: 16px; }
    .act-pbar-track { height: 14px; }
    .act-card { padding: 20px 16px; border-radius: 18px; }
    .act-card__section-title { font-size: 1.05rem; }
}

/* small phone – stack vertically */
@media(max-width: 480px) {
    .act-hero {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 12px;
        padding-bottom: 24px;
    }
    .act-trophy { flex: none; }
    .act-trophy svg { max-width: 140px; }
    .act-hero__info { width: 100%; }
    .act-pbar-track { width: 100%; }
    .act-rule__pts { font-size: .78rem; }
}
</style>
</head>
<body class="player-dashboard-page" style="--player-dashboard-bg:url('{{ asset('assets/images/bg/bg-login.png') }}');">
<div class="player-dashboard-shell">

{{-- ══════ SIDEBAR ══════ --}}
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

{{-- ══════ MAIN ══════ --}}
<main class="player-dashboard-main">

  {{-- Topbar --}}
  <header class="player-dashboard-topbar">
    <div class="player-dashboard-topbar__left">
      <button type="button" class="player-dashboard-topbar__menu" data-sidebar-open><span></span><span></span><span></span></button>
      <label class="player-search" for="act-search">
        <span class="player-search__icon">
          <svg viewBox="0 0 20 20" fill="none"><circle cx="9" cy="9" r="5.75" stroke="currentColor" stroke-width="1.8"/><path d="M13.5 13.5L17 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        </span>
        <input id="act-search" type="search" placeholder="Cari lapangan...">
      </label>
    </div>
    <div class="player-dashboard-topbar__right">
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

  {{-- ══════ ACTIVITY PAGE ══════ --}}
  <div class="act-main">

    {{-- Title --}}
    <div class="act-title-row">
      <span class="act-title-icon">
        <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
          <rect x="4" y="8" width="28" height="4" rx="2" fill="#4f46e5"/>
          <rect x="4" y="15" width="28" height="4" rx="2" fill="#4f46e5" opacity=".7"/>
          <rect x="4" y="22" width="20" height="4" rx="2" fill="#4f46e5" opacity=".45"/>
        </svg>
      </span>
      <h1 class="act-title-text">Lencana Kemajuan Pemain</h1>
    </div>

    {{-- Hero --}}
    <div class="act-hero">
      {{-- Trophy SVG --}}
      <div class="act-trophy">
        <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
          <ellipse cx="80" cy="60" rx="42" ry="12" fill="#C8A96E"/>
          <path d="M38 60 Q30 110 50 125 Q65 135 80 135 Q95 135 110 125 Q130 110 122 60Z" fill="#E8C97A"/>
          <path d="M42 60 Q35 105 54 120 Q68 130 80 130 Q92 130 106 120 Q125 105 118 60Z" fill="#F5D98A"/>
          <path d="M55 72 Q58 85 60 98" stroke="rgba(255,255,255,0.5)" stroke-width="4" stroke-linecap="round"/>
          <path d="M38 68 Q18 68 18 88 Q18 108 38 108" stroke="#C8A96E" stroke-width="8" stroke-linecap="round" fill="none"/>
          <path d="M122 68 Q142 68 142 88 Q142 108 122 108" stroke="#C8A96E" stroke-width="8" stroke-linecap="round" fill="none"/>
          <rect x="72" y="135" width="16" height="24" rx="4" fill="#C8A96E"/>
          <rect x="58" y="156" width="44" height="10" rx="5" fill="#A07840"/>
          <circle cx="80" cy="95" r="26" fill="none" stroke="#D4A060" stroke-width="2"/>
          <circle cx="80" cy="95" r="18" fill="none" stroke="#D4A060" stroke-width="2"/>
          <circle cx="80" cy="95" r="10" fill="none" stroke="#D4A060" stroke-width="2"/>
          <circle cx="80" cy="95" r="4" fill="#E05050"/>
          <line x1="130" y1="55" x2="84" y2="93" stroke="#5B3A1A" stroke-width="3" stroke-linecap="round"/>
          <path d="M130 55 L122 52 L126 60Z" fill="#5B3A1A"/>
          <path d="M155 130 L158 140 L168 140 L160 146 L163 156 L155 150 L147 156 L150 146 L142 140 L152 140Z" fill="#F5C518"/>
          <circle cx="155" cy="143" r="12" fill="none" stroke="#E8A000" stroke-width="1.5"/>
          <path d="M62 165 Q55 178 48 185" stroke="#E05050" stroke-width="5" stroke-linecap="round"/>
          <path d="M98 165 Q105 178 112 185" stroke="#3B82F6" stroke-width="5" stroke-linecap="round"/>
          <circle cx="48" cy="185" r="8" fill="#E05050"/>
          <circle cx="112" cy="185" r="8" fill="#3B82F6"/>
          <circle cx="80" cy="170" r="12" fill="#F5C518" stroke="#E8A000" stroke-width="2"/>
          <text x="80" y="175" text-anchor="middle" font-size="12" font-weight="bold" fill="#7B5200">1</text>
        </svg>
      </div>

      {{-- Info --}}
      <div class="act-hero__info">
        <p class="act-hero__name">{{ $userName }}</p>
        <p class="act-hero__level">{{ $currentLevel['name'] }} Player</p>
        <p class="act-hero__pts">{{ $totalPoints }} Points</p>

        <div class="act-pbar-wrap">
          <div class="act-pbar-track">
            <div class="act-pbar-fill" id="act-prog" data-target="{{ $progressPct }}" style="width:0%"></div>
          </div>
          @if($nextLevel)
            <p class="act-pbar-hint">Butuh {{ $pointsToNext }} poin lagi nih untuk jadi {{ $nextLevel['name'] }}, Semangat!</p>
          @else
            <p class="act-pbar-hint"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" style="display:inline-block;vertical-align:-2px;margin-right:4px;"><path d="M8 21H16M12 17V21M7 4H17V11C17 14.314 14.761 17 12 17C9.239 17 7 14.314 7 11V4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 6H4C4 6 3 10 6 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M17 6H20C20 6 21 10 18 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>Kamu sudah mencapai level tertinggi!</p>
          @endif
        </div>
      </div>
    </div>

    {{-- Big card --}}
    <div class="act-card">

      {{-- Section 1: Cara dapat poin --}}
      <h2 class="act-card__section-title">Cara Kamu Dapat Poin</h2>
      <p class="act-card__section-sub">Kumpulin poin kamu di Spies Sport:</p>

      <ul class="act-rules">
        <li class="act-rule">
          <span class="act-rule__icon">
            <img src="{{ asset('assets/images/icons/booking.png') }}" alt="booking">
          </span>
          Booking lapangan
          <span class="act-rule__pts">+1 poin</span>
        </li>
        <li class="act-rule">
          <span class="act-rule__icon">
            <img src="{{ asset('assets/images/icons/caritim.png') }}" alt="match">
          </span>
          Gabung pertandingan publik
          <span class="act-rule__pts">+2 poin</span>
        </li>
        <li class="act-rule">
          <span class="act-rule__icon">
            <img src="{{ asset('assets/images/icons/badge.png') }}" alt="review">
          </span>
          Memberi ulasan lapangan
          <span class="act-rule__pts">+3 poin</span>
        </li>
      </ul>

      <hr class="act-divider">

      {{-- Section 2: Aktivitas terakhir --}}
      <h2 class="act-card__section-title">Aktivitas Terakhir Kamu</h2>
      <p class="act-card__section-sub">Berikut aktivitas terbaru kamu di Spies Sport:</p>

      @if($activities->isEmpty())
        <div class="act-empty-activity">Belum ada aktivitas tercatat.</div>
      @else
        <div class="act-list">
          @foreach($activities as $i => $act)
            @php
              $icon = $sportIcons[$act['sport']] ?? asset('assets/images/icons/gor.png');
              $pts  = ($act['points'] > 0 ? '+' : '') . $act['points'];
            @endphp
            <div class="act-item" style="animation-delay: {{ $i * 0.06 }}s">
              <div class="act-item__sport-icon">
                <img src="{{ $icon }}" alt="{{ $act['sport'] }}">
              </div>
              <span>{{ $act['label'] }}</span>
              <span class="act-item__pts">({{ $pts }})</span>
            </div>
          @endforeach
        </div>
      @endif

    </div>{{-- /act-card --}}
  </div>{{-- /act-main --}}

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
  document.querySelector('[data-sidebar-open]')?.addEventListener('click',  () => setOpen(true));
  document.querySelector('[data-sidebar-close]')?.addEventListener('click', () => setOpen(false));
  backdrop?.addEventListener('click', () => setOpen(false));

  /* ── animate progress bar ── */
  function runAnim() {
    const bar = document.getElementById('act-prog');
    if (!bar) return;
    const target = parseFloat(bar.dataset.target) || 0;
    // force reflow so transition plays
    bar.style.width = '0%';
    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        bar.style.width = target + '%';
      });
    });
  }

  // Run immediately when DOM is ready (no waiting for intersection)
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', runAnim);
  } else {
    // Small delay to allow CSS transition to register
    setTimeout(runAnim, 80);
  }
})();
</script>
</body>
</html>
