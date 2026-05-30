@php
    use Carbon\Carbon;
    $user        = auth()->user();
$userName = $user?->name ?: 'Pecinta Olahraga';
    $currentDate = Carbon::now()->locale('id')->translatedFormat('j F Y');
    $profileAvatar = $user?->avatarUrl();

    $sidebarItems = [
        ['label' => 'Beranda',  'icon' => asset('assets/images/icons/dashboard.png'),  'href' => route('dashboard'),        'active' => false],
        ['label' => 'Aktivitas',  'icon' => asset('assets/images/icons/aktivitas.png'),  'href' => url('/matches'),           'active' => false],
        ['label' => 'Favorit', 'icon' => asset('assets/images/icons/favoritmu.png'),  'href' => route('favorite.index'),   'active' => true],
        ['label' => 'Histori',   'icon' => asset('assets/images/icons/histori.png'),    'href' => route('history.index'),    'active' => false],
        ['label' => 'Cari tim',  'icon' => asset('assets/images/icons/caritim.png'),   'href' => route('matches.index'),    'active' => false],
        ['label' => 'Pemesanan',   'icon' => asset('assets/images/icons/booking.png'),   'href' => route('booking.index'),            'active' => false],
        ['label' => 'Keahlian','icon' => asset('assets/images/icons/keahlian.png'),  'href' => route('skill.index'),      'active' => false],
        ['label' => 'Profil',    'icon' => asset('assets/images/icons/profil.png'),    'href' => route('profile.show'),     'active' => false],
    ];
    $sidebarUtilities = [
        ['label' => 'Bantuan',    'icon' => asset('assets/images/icons/bantuan.png'),    'href' => route('preview.help')],
        ['label' => 'Pengaturan','icon' => asset('assets/images/icons/pengaturan.png'), 'href' => route('profile.edit')],
    ];

    // Sport-based gradient bg colors untuk placeholder gambar lapangan
    $allFavFields = collect();
    $sportTypes = [];
    foreach ($grouped ?? [] as $sport => $fields) {
        $sportTypes[] = $sport;
        foreach ($fields as $f) {
            $allFavFields->push((object)['sport' => $sport, 'field' => $f]);
        }
    }

    $favAllSports = ['Futsal','Badminton','Basket','Voli','Tennis','Golf','Renang','Panahan','Lari','Sepeda','Tinju','Bela Diri','Yoga','Fitness','Hiking','Padel','Baseball','Rugby','Senam'];
    $favSportEmoji = [
        'Futsal'=>'⚽','Badminton'=>'🏸','Basket'=>'🏀','Voli'=>'🏐','Tennis'=>'🎾',
        'Golf'=>'🏌️','Renang'=>'🏊','Panahan'=>'🏹','Lari'=>'🏃','Sepeda'=>'🚴',
        'Tinju'=>'🥊','Bela Diri'=>'🥋','Yoga'=>'🧘','Fitness'=>'🏋️','Hiking'=>'🥾',
        'Padel'=>'🎾','Baseball'=>'⚾','Rugby'=>'🏉','Senam'=>'🤸','Lainnya'=>'🏆',
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Favoritmu – {{ config('app.name','Spies Sport') }}</title>
@vite(['resources/css/app.css','resources/css/player-dashboard.css','resources/js/app.js'])
<style>
.fav-main { padding: 8px 20px 56px; max-width: 1200px; }

.fav-page-title {
    font-size: clamp(1.4rem, 2.2vw, 1.9rem);
    font-weight: 800;
    color: #00004d;
    margin: 6px 0 24px 4px;
}

/* ── Filter button ── */
.fav-filters {
    display: flex;
    margin-bottom: 24px;
}
.fav-filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 12px;
    border: 1.5px solid #e2e8f0;
    background: #fff;
    font-size: 14px;
    font-weight: 700;
    color: #02025b;
    cursor: pointer;
    transition: all .18s;
    font-family: inherit;
}
.fav-filter-btn:hover {
    border-color: #6366f1;
    background: #eef2ff;
}

/* ── Card grid ── */
.fav-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
}

/* ── Field card ── */
.fav-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all .3s ease;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.fav-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0,0,0,0.12); }

.fav-card__img-wrap {
    position: relative;
    height: 200px;
    overflow: hidden;
}
.fav-card__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Heart toggle */
.fav-card__heart {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(0,0,0,0.6);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 18px;
    z-index: 2;
    border: 0;
    transition: all .2s;
}
.fav-card__heart:hover { transform: scale(1.1); }

/* Rating badge */
.fav-card__rating {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(0,0,0,0.8);
    color: #fff;
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
    z-index: 2;
}

/* Content */
.fav-card__body {
    padding: 16px 18px 18px;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.fav-card__name {
    font-size: 18px;
    font-weight: 700;
    color: #001a4d;
    margin: 0 0 4px;
    line-height: 1.3;
}
.fav-card__loc {
    display: flex;
    align-items: center;
    gap: 4px;
    color: #666;
    font-size: 13px;
    margin: 0 0 14px;
}
.fav-card__footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 12px;
    border-top: 1px solid #f0f0f0;
    margin-top: auto;
}
.fav-card__price {
    font-size: 16px;
    font-weight: 700;
    color: #001a4d;
}
.fav-card__btn {
    padding: 8px 16px;
    background: #f59e0b;
    color: #fff;
    border: 0;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    font-size: 12px;
    white-space: nowrap;
    text-decoration: none;
}
.fav-card__btn:hover { opacity: .9; }

/* ── Empty state ── */
.fav-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 72px 20px;
    gap: 14px;
    text-align: center;
    background: rgba(255,255,255,.88);
    border: 1px solid rgba(0,0,77,.07);
    border-radius: 24px;
    box-shadow: 0 16px 38px rgba(0,0,77,.07);
}
.fav-empty__icon {
    width: 64px;
    height: 64px;
    opacity: .35;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.fav-empty__icon svg { width: 100%; height: 100%; }
.fav-empty__title { font-size: 1.1rem; font-weight: 800; color: #00004d; margin: 0; }
.fav-empty__sub   { font-size: .85rem; color: rgba(0,0,77,.52); font-weight: 600; margin: 0; }
.fav-empty__btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 28px;
    background: #eb5436;
    color: #fff;
    border-radius: 14px;
    font-size: .88rem;
    font-weight: 800;
    text-decoration: none;
    margin-top: 4px;
    transition: transform .18s ease, box-shadow .18s ease;
}
.fav-empty__btn:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(235,84,54,.3); }

/* ── Toast ── */
.fav-toast {
    position: fixed;
    bottom: 28px;
    right: 28px;
    padding: 12px 22px;
    border-radius: 14px;
    background: #1e8f67;
    color: #fff;
    font-size: .88rem;
    font-weight: 700;
    box-shadow: 0 8px 24px rgba(0,0,0,.2);
    z-index: 9999;
    transform: translateY(80px);
    opacity: 0;
    transition: all .3s cubic-bezier(.22,1,.36,1);
    pointer-events: none;
}
.fav-toast.is-visible { transform: translateY(0); opacity: 1; }
.fav-toast.is-error   { background: #ef4444; }

[x-cloak] { display: none !important; }
[data-search-hidden] { display: none !important; }
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
      <label class="player-search" for="fav-search">
        <span class="player-search__icon">
          <svg viewBox="0 0 20 20" fill="none"><circle cx="9" cy="9" r="5.75" stroke="currentColor" stroke-width="1.8"/><path d="M13.5 13.5L17 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        </span>
        <input id="fav-search" type="search" placeholder="Cari lapangan...">
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

  {{-- ══════ FAVORIT PAGE ══════ --}}
  <div class="fav-main" x-data="{ activeSport: 'all', openSportModal: false }">
    <h1 class="fav-page-title">Favoritmu</h1>

    @if(empty($grouped))
      {{-- Empty state --}}
      <div class="fav-empty">
        <span class="fav-empty__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M6 4.5H18C18.6 4.5 19 4.9 19 5.5V21L12 17L5 21V5.5C5 4.9 5.4 4.5 6 4.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
          </svg>
        </span>
        <p class="fav-empty__title">Belum ada lapangan favorit</p>
        <p class="fav-empty__sub">Tandai lapangan favoritmu saat booking untuk memunculkannya di sini.</p>
        <a href="{{ url('/fields') }}" class="fav-empty__btn">Jelajahi Lapangan</a>
      </div>
    @else
      {{-- Filter trigger --}}
      <div class="fav-filters">
        <button @click="openSportModal = true" class="fav-filter-btn">
          <span x-text="activeSport === 'all' ? '🏅 Semua Olahraga' : activeSport"></span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
        </button>
      </div>

      {{-- Sport filter modal --}}
      <div x-show="openSportModal" x-cloak
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100"
           x-transition:leave="transition ease-in duration-150"
           x-transition:leave-start="opacity-100"
           x-transition:leave-end="opacity-0"
           class="fixed inset-0 bg-[#11114b]/50 backdrop-blur-md z-[999] flex items-center justify-center p-4"
           @click.self="openSportModal = false"
           @keydown.escape.window="openSportModal = false">
        <div class="bg-white rounded-[32px] w-full max-w-[420px] p-6 sm:p-8 shadow-2xl border border-slate-100"
             x-show="openSportModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="scale-90 translate-y-6 opacity-0"
             x-transition:enter-end="scale-100 translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="scale-100 translate-y-0 opacity-100"
             x-transition:leave-end="scale-90 translate-y-6 opacity-0">
          <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-100 flex items-center justify-center text-lg">🏅</div>
              <div>
                <h3 class="text-lg font-extrabold text-[#02025b] m-0">Pilih Olahraga</h3>
                <p class="text-xs font-semibold text-slate-400 m-0 mt-0.5">Filter lapangan favorit berdasarkan kategori</p>
              </div>
            </div>
            <button @click="openSportModal = false" class="p-2 bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 rounded-full transition-all">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>
          <div class="grid grid-cols-3 gap-2.5 max-h-[320px] overflow-y-auto pr-1">
            <button @click="activeSport='all'; openSportModal=false"
                    class="flex flex-col items-center gap-1.5 p-3.5 border-2 rounded-2xl cursor-pointer transition-all duration-200 font-inherit text-xs font-extrabold uppercase tracking-wide"
                    :class="activeSport==='all' ? 'border-indigo-400 bg-indigo-50/80 text-indigo-700' : 'border-slate-100 bg-white text-slate-600 hover:border-indigo-200 hover:bg-indigo-50/30'">
              <span class="text-xl">🏅</span>
              <span>Semua</span>
            </button>
            @foreach($favAllSports as $fs)
            <button @click="activeSport='{{ $fs }}'; openSportModal=false"
                    class="flex flex-col items-center gap-1.5 p-3.5 border-2 rounded-2xl cursor-pointer transition-all duration-200 font-inherit text-xs font-extrabold uppercase tracking-wide"
                    :class="activeSport==='{{ $fs }}' ? 'border-indigo-400 bg-indigo-50/80 text-indigo-700' : 'border-slate-100 bg-white text-slate-600 hover:border-indigo-200 hover:bg-indigo-50/30'">
              <span class="text-xl">{{ $favSportEmoji[$fs] ?? '🏆' }}</span>
              <span>{{ $fs }}</span>
            </button>
            @endforeach
          </div>
        </div>
      </div>

      {{-- Card grid --}}
      <div class="fav-grid">
        @foreach($allFavFields as $item)
        @php $field = $item->field; $sport = $item->sport; @endphp
        <div class="fav-card" x-show="activeSport==='all' || activeSport==='{{ $sport }}'" data-field-id="{{ $field->id }}" data-sport="{{ $sport }}" onclick="window.location.href='{{ route('booking.show', $field->id) }}'">
          {{-- Image wrap --}}
          <div class="fav-card__img-wrap">
            <img src="{{ $field->image_url }}" alt="{{ $field->name }}" class="fav-card__img">

            {{-- Heart toggle --}}
            <button type="button" class="fav-card__heart" onclick="event.stopPropagation();" data-toggle="{{ $field->id }}">❤️</button>

            {{-- Rating --}}
            <div class="fav-card__rating">
              <span>⭐</span>
              <span>{{ ($field->review_count ?? 0) > 0 ? number_format($field->rating ?? 0, 1) : 'Baru' }}</span>
            </div>
          </div>

          {{-- Body --}}
          <div class="fav-card__body">
            <h3 class="fav-card__name">{{ $field->name }}</h3>
            <p class="fav-card__loc">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              {{ $field->location }}
            </p>
            <div class="fav-card__footer">
              <span class="fav-card__price">{{ $field->formattedPrice() }}</span>
              <a href="{{ route('booking.show', $field->id) }}" class="fav-card__btn">Pesan →</a>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    @endif
  </div>

  {{-- Toast --}}
  <div class="fav-toast" id="fav-toast"></div>

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
  document.querySelector('[data-sidebar-open]')?.addEventListener('click',   () => setOpen(true));
  document.querySelector('[data-sidebar-close]')?.addEventListener('click',  () => setOpen(false));
  backdrop?.addEventListener('click', () => setOpen(false));

  /* ── toast helper ── */
  const toast = document.getElementById('fav-toast');
  let toastTimer;
  function showToast(msg, isError = false) {
    clearTimeout(toastTimer);
    toast.textContent = msg;
    toast.classList.toggle('is-error', isError);
    toast.classList.add('is-visible');
    toastTimer = setTimeout(() => toast.classList.remove('is-visible'), 2800);
  }

  /* ── toggle favorite via heart ── */
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

  document.querySelectorAll('[data-toggle]').forEach(btn => {
    btn.addEventListener('click', async e => {
      e.stopPropagation();
      const fieldId = btn.dataset.toggle;
      const card    = btn.closest('.fav-card');

      try {
        const res = await fetch('{{ route("favorite.toggle") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
          },
          body: JSON.stringify({ field_id: fieldId }),
        });

        if (!res.ok) throw new Error('Gagal');
        const data = await res.json();

        if (data.status === 'removed') {
          card.style.transition = 'all .3s ease';
          card.style.opacity    = '0';
          card.style.transform  = 'scale(.85)';
          setTimeout(() => {
            card.remove();
            if (document.querySelectorAll('.fav-card').length === 0) {
              location.reload();
            }
          }, 300);
          showToast('Dihapus dari favorit');
        }
      } catch {
        showToast('Gagal menghapus favorit', true);
      }
    });
  });

  /* ── search filter ── */
  document.getElementById('fav-search')?.addEventListener('input', e => {
    const kw = e.target.value.toLowerCase().trim();
    document.querySelectorAll('.fav-card').forEach(card => {
      const name = card.querySelector('.fav-card__name')?.textContent.toLowerCase() || '';
      const loc  = card.querySelector('.fav-card__loc')?.textContent.toLowerCase() || '';
      card.toggleAttribute('data-search-hidden', !!kw && !name.includes(kw) && !loc.includes(kw));
    });
  });
})();
</script>
</body>
</html>
