@php
    use Carbon\Carbon;
    $user        = auth()->user();
    $userName    = $user?->name ?: 'Sport Enthusiast';
    $currentDate = Carbon::now()->locale('id')->translatedFormat('j F Y');
    $profileAvatarFile = $user?->avatar_profile ?: (($user?->gender === 'perempuan') ? 'profil2.png' : 'profil1.png');
    $profileAvatar = asset('assets/images/characters/'.$profileAvatarFile);

    $sidebarItems = [
        ['label' => 'Dashboard',  'icon' => asset('assets/images/icons/dashboard.png'),  'href' => route('dashboard'),        'active' => false],
        ['label' => 'Aktivitas',  'icon' => asset('assets/images/icons/aktivitas.png'),  'href' => url('/matches'),           'active' => false],
        ['label' => 'Favoritmu', 'icon' => asset('assets/images/icons/favoritmu.png'),  'href' => route('favorite.index'),   'active' => true],
        ['label' => 'Histori',   'icon' => asset('assets/images/icons/histori.png'),    'href' => route('history.index'),    'active' => false],
        ['label' => 'Cari tim',  'icon' => asset('assets/images/icons/caritim.png'),   'href' => route('matches.index'),    'active' => false],
        ['label' => 'Booking',   'icon' => asset('assets/images/icons/booking.png'),   'href' => url('/fields'),            'active' => false],
        ['label' => 'Keahlianmu','icon' => asset('assets/images/icons/keahlian.png'),  'href' => route('skill.index'),      'active' => false],
        ['label' => 'Profil',    'icon' => asset('assets/images/icons/profil.png'),    'href' => route('profile.show'),     'active' => false],
    ];
    $sidebarUtilities = [
        ['label' => 'Bantuan',    'icon' => asset('assets/images/icons/bantuan.png'),    'href' => route('preview.help')],
        ['label' => 'Pengaturan','icon' => asset('assets/images/icons/pengaturan.png'), 'href' => route('profile.edit')],
    ];

    // Sport-based gradient bg colors untuk placeholder gambar lapangan
    $sportGradients = [
        'Futsal'    => 'linear-gradient(135deg,#1a237e,#283593)',
        'Voli'      => 'linear-gradient(135deg,#1b5e20,#2e7d32)',
        'Badminton' => 'linear-gradient(135deg,#bf360c,#e64a19)',
        'Basket'    => 'linear-gradient(135deg,#e65100,#f57c00)',
        'Renang'    => 'linear-gradient(135deg,#006064,#00838f)',
        'Tennis'    => 'linear-gradient(135deg,#558b2f,#689f38)',
        'Lainnya'   => 'linear-gradient(135deg,#4a148c,#6a1b9a)',
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Favoritmu – {{ config('app.name','Spies Sport') }}</title>
@vite(['resources/css/app.css','resources/css/player-dashboard.css'])
<style>
/* ═══════════════════════════════════════
   FAVORITMU PAGE — inline
   ═══════════════════════════════════════ */

.fav-main { padding: 8px 20px 56px; max-width: 1200px; }

/* ── Page title ── */
.fav-page-title {
    font-size: clamp(1.4rem, 2.2vw, 1.9rem);
    font-weight: 800;
    color: #00004d;
    margin: 6px 0 24px 4px;
}

/* ── Sport section ── */
.fav-section {
    background: rgba(255,255,255,.93);
    border: 1px solid rgba(0,0,77,.07);
    border-radius: 24px;
    padding: 24px 28px 28px;
    margin-bottom: 20px;
    box-shadow: 0 16px 38px rgba(0,0,77,.07);
    animation: sectionIn .3s ease both;
}
@keyframes sectionIn {
    from { opacity:0; transform:translateY(12px); }
    to   { opacity:1; transform:translateY(0); }
}

.fav-section__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}
.fav-section__sport {
    font-size: 1.65rem;
    font-weight: 900;
    color: #00004d;
    margin: 0;
}
.fav-section__see-all {
    font-size: .82rem;
    font-weight: 700;
    color: rgba(0,0,77,.5);
    text-decoration: none;
    transition: color .18s;
}
.fav-section__see-all:hover { color: #eb5436; }

/* ── Field card grid ── */
.fav-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
}

/* ── Field card ── */
.fav-card {
    border-radius: 16px;
    overflow: hidden;
    transition: transform .2s ease, box-shadow .2s ease;
    cursor: pointer;
    position: relative;
}
.fav-card:hover { transform: translateY(-4px); box-shadow: 0 16px 34px rgba(0,0,77,.14); }

/* image area */
.fav-card__img {
    width: 100%;
    aspect-ratio: 4/3;
    object-fit: cover;
    display: block;
    border-radius: 14px;
}
.fav-card__img-placeholder {
    width: 100%;
    aspect-ratio: 4/3;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.8rem;
}

/* remove (x) button */
.fav-card__remove {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: rgba(0,0,0,.55);
    border: 0;
    color: #fff;
    font-size: .85rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity .18s ease, background .18s ease;
}
.fav-card:hover .fav-card__remove { opacity: 1; }
.fav-card__remove:hover { background: #ef4444; }

/* meta */
.fav-card__meta { padding: 10px 2px 4px; }
.fav-card__name-row {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-bottom: 3px;
}
.fav-card__pin {
    flex-shrink: 0;
    margin-top: 2px;
}
.fav-card__name {
    font-size: .92rem;
    font-weight: 800;
    color: #00004d;
    margin: 0;
    line-height: 1.25;
}
.fav-card__dist {
    font-size: .78rem;
    color: rgba(0,0,77,.52);
    font-weight: 600;
    margin: 0;
    padding-left: 22px;
}

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
.fav-empty__icon { font-size: 4rem; opacity: .35; }
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

/* ── Toast notification ── */
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

/* ── Responsive ── */
@media(max-width: 860px) {
    .fav-grid { grid-template-columns: repeat(2, 1fr); }
}
@media(max-width: 560px) {
    .fav-main { padding: 8px 12px 40px; }
    .fav-section { padding: 18px 16px 22px; }
    .fav-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
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

  {{-- ══════ FAVORIT PAGE ══════ --}}
  <div class="fav-main">
    <h1 class="fav-page-title">Favoritmu</h1>

    @if(empty($grouped))
      {{-- Empty state --}}
      <div class="fav-empty">
        <span class="fav-empty__icon">⭐</span>
        <p class="fav-empty__title">Belum ada lapangan favorit</p>
        <p class="fav-empty__sub">Tandai lapangan favoritmu saat booking untuk memunculkannya di sini.</p>
        <a href="{{ url('/fields') }}" class="fav-empty__btn">Jelajahi Lapangan</a>
      </div>

    @else
      {{-- Grouped by sport --}}
      @foreach($grouped as $sport => $fields)
        @php
          $gradient = $sportGradients[$sport] ?? $sportGradients['Lainnya'];
          $sportEmoji = match($sport) {
              'Futsal'    => '⚽',
              'Voli'      => '🏐',
              'Badminton' => '🏸',
              'Basket'    => '🏀',
              'Renang'    => '🏊',
              'Tennis'    => '🎾',
              default     => '🏟️',
          };
        @endphp
        <div class="fav-section" data-sport-section="{{ $sport }}">
          <div class="fav-section__header">
            <h2 class="fav-section__sport">{{ $sport }}</h2>
            @if(count($fields) > 3)
              <a href="{{ url('/fields') }}" class="fav-section__see-all">Lihat semua</a>
            @endif
          </div>

          <div class="fav-grid">
            @foreach(array_slice($fields, 0, 3) as $field)  {{-- max 3 per row --}}
              <div class="fav-card" data-field-id="{{ $field->id }}">

                {{-- Remove button --}}
                <button type="button" class="fav-card__remove" data-remove="{{ $field->id }}" title="Hapus dari favorit">✕</button>

                {{-- Image --}}
                @if(!empty($field->image))
                  <img src="{{ asset('storage/'.$field->image) }}"
                       alt="{{ $field->name }}"
                       class="fav-card__img"
                       onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                  <div class="fav-card__img-placeholder" style="background:{{ $gradient }};display:none">{{ $sportEmoji }}</div>
                @else
                  <div class="fav-card__img-placeholder" style="background:{{ $gradient }}">{{ $sportEmoji }}</div>
                @endif

                {{-- Meta --}}
                <div class="fav-card__meta">
                  <div class="fav-card__name-row">
                    <span class="fav-card__pin">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M12 20.5C12 20.5 18 14.73 18 10.5C18 7.19 15.31 4.5 12 4.5C8.69 4.5 6 7.19 6 10.5C6 14.73 12 20.5 12 20.5Z" fill="#ef4444" stroke="#ef4444" stroke-width="1"/>
                        <circle cx="12" cy="10.5" r="2.2" fill="#fff"/>
                      </svg>
                    </span>
                    <p class="fav-card__name">{{ $field->name }}</p>
                  </div>
                  <p class="fav-card__dist">{{ $field->location }}</p>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endforeach
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

  /* ── remove favorite ── */
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

  document.querySelectorAll('[data-remove]').forEach(btn => {
    btn.addEventListener('click', async e => {
      e.stopPropagation();
      const fieldId = btn.dataset.remove;
      const card    = btn.closest('.fav-card');
      const section = btn.closest('.fav-section');

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
          /* animate card out */
          card.style.transition = 'all .3s ease';
          card.style.opacity    = '0';
          card.style.transform  = 'scale(.85)';
          setTimeout(() => {
            card.remove();
            /* if section empty, remove section */
            const remaining = section.querySelectorAll('.fav-card');
            if (remaining.length === 0) {
              section.style.transition = 'opacity .3s ease';
              section.style.opacity    = '0';
              setTimeout(() => section.remove(), 300);
            }
            /* if no sections left, show empty state */
            if (document.querySelectorAll('.fav-section').length === 0) {
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
      const loc  = card.querySelector('.fav-card__dist')?.textContent.toLowerCase() || '';
      card.style.display = (!kw || name.includes(kw) || loc.includes(kw)) ? '' : 'none';
    });
  });
})();
</script>
</body>
</html>
