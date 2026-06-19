@php
    use Carbon\Carbon;
    $userName = Auth::check() ? Auth::user()->name : 'Pemain';
    $userAvatar = Auth::check() ? Auth::user()->avatarUrl() : asset('assets/images/characters/profil1.png');
    $currentDate = Carbon::now()->locale('id')->translatedFormat('j F Y');

    $sidebarItems = [
        ['label'=>'Beranda',  'icon'=>asset('assets/images/icons/dashboard.png'), 'href'=>route('dashboard'),    'active'=>false],
        ['label'=>'Aktivitas',  'icon'=>asset('assets/images/icons/aktivitas.png'), 'href'=>route('activity.index'),       'active'=>false],
        ['label'=>'Favorit',  'icon'=>asset('assets/images/icons/favoritmu.png'), 'href'=>route('favorite.index'),                  'active'=>false],
        ['label'=>'Histori',    'icon'=>asset('assets/images/icons/histori.png'),   'href'=>route('history.index'),                  'active'=>false],
        ['label'=>'Cari tim',   'icon'=>asset('assets/images/icons/caritim.png'),   'href'=>route('matches.index'),'active'=>false],
        ['label'=>'Pemesanan',    'icon'=>asset('assets/images/icons/booking.png'),   'href'=>route('booking.index'),                  'active'=>false],
        ['label'=>'Keahlian', 'icon'=>asset('assets/images/icons/keahlian.png'),  'href'=>route('skill.index'),                  'active'=>false],
        ['label'=>'Profil',     'icon'=>asset('assets/images/icons/profil.png'),    'href'=>route('profile.show'), 'active'=>false],
    ];
    $sidebarUtilities = [
        ['label'=>'Bantuan',    'icon'=>asset('assets/images/icons/bantuan.png'),    'href'=>route('preview.help')],
        ['label'=>'Pengaturan', 'icon'=>asset('assets/images/icons/pengaturan.png'), 'href'=>route('profile.edit')],
    ];
@endphp
<!DOCTYPE html>
<html class="light" lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Rekomendasi &amp; Komunitas - {{ config('app.name', 'Spies Sport') }}</title>
@vite(['resources/css/pages.css', 'resources/css/player-dashboard.css', 'resources/js/app.js', 'resources/js/player-dashboard.js'])
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=Poppins:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<style>
        body { background-color: #FDF9ED; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .custom-shadow { box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.08); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        #promo-container {
            scroll-snap-type: x mandatory;
            overflow-x: auto;
            display: flex;
            gap: 24px;
            will-change: scroll-position;
            -webkit-overflow-scrolling: touch;
        }
        .promo-card {
            scroll-snap-align: start;
            will-change: transform;
        }
        @media (max-width: 640px) {
            #promo-container { gap: 16px; }
            .promo-card { min-width: 280px; }
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
@auth
<form method="POST" action="{{ route('logout') }}">@csrf
<button type="submit" class="player-sidebar__item player-sidebar__item--logout">
<span class="player-sidebar__icon-wrap"><img src="{{ asset('assets/images/icons/keluar.png') }}" alt="" class="player-sidebar__icon"></span>
<span class="player-sidebar__label">Keluar</span>
</button>
</form>
@endauth
</div>
</div>
</aside>
<button type="button" class="player-sidebar__backdrop" data-sidebar-backdrop aria-label="Tutup sidebar"></button>
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
@auth
@include('partials.notification-bell')
<a href="{{ route('profile.show') }}" class="player-profile-pill">
<span class="player-profile-pill__avatar">
<img src="{{ $userAvatar }}" alt="Profil" class="player-avatar-image player-avatar-image--profile">
</span>
<span class="player-profile-pill__name">{{ $userName }}</span>
</a>
@endauth
</div>
</header>

<div style="background: #FDF9ED; border-radius: 15px; padding: 20px 24px 32px; margin-top: 12px;">
<div class="flex flex-col gap-6 md:gap-stack-lg mb-6 md:mb-stack-lg">
<div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 md:gap-0 mt-2 md:mt-8">
<div>
<a class="hidden md:flex items-center text-[12px] text-[#5c403c] hover:text-[#EB5436] transition-colors mb-2 cursor-pointer group" href="{{ route('dashboard') }}">
<span class="material-symbols-outlined text-[16px] mr-1 group-hover:-translate-x-1 transition-transform">arrow_back</span>
<span class="font-medium">Kembali</span>
</a>
<h2 class="font-display-lg text-display-lg text-on-surface text-[28px] md:text-[36px] leading-tight md:leading-[44px]">Rekomendasi &amp; Komunitas</h2>
<p class="text-body-lg text-[#5c403c] max-w-2xl mt-1 md:mt-2">
        Temukan lapangan terbaik untuk pertandingan Anda dan bergabunglah dengan komunitas olahraga terbesar di Spiessport.
      </p>
</div>
<div class="flex flex-col sm:flex-row gap-3 md:gap-stack-md shrink-0">
<button class="flex items-center justify-center px-5 md:px-6 py-3 rounded-[15px] bg-[#EB5436] text-white font-bold custom-shadow hover:translate-y-[-2px] transition-all active:scale-[0.98]">
<span class="material-symbols-outlined mr-2 text-[20px]">sports_soccer</span>
        Buat Pertandingan
      </button>
<button class="flex items-center justify-center px-5 md:px-6 py-3 rounded-[15px] bg-[#00004D] border-2 border-[#00004D] text-white font-bold custom-shadow hover:bg-[#000066] hover:translate-y-[-2px] transition-all active:scale-[0.98]">
<span class="material-symbols-outlined mr-2 text-[20px]">group</span>
        Cari Tim
      </button>
</div>
</div>
<div class="flex flex-col md:flex-row gap-4 md:gap-gutter items-center">
<div class="flex-1 w-full">
<div class="relative group">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#5c403c]">search</span>
<input class="w-full pl-12 pr-4 py-4 bg-white border border-[#e6bdb8] rounded-[15px] focus:ring-2 focus:ring-[#EB5436] focus:border-[#EB5436] transition-all outline-none text-body-lg custom-shadow" placeholder="Cari nama lapangan, lokasi, atau kategori..." type="text" id="search-input"/>
</div>
</div>
<div class="shrink-0 w-full md:w-auto">
<div class="relative inline-block text-left w-full sm:w-auto">
<button class="inline-flex items-center justify-between w-full rounded-[15px] bg-[#EB5436] px-5 md:px-6 py-4 text-body-lg font-bold text-white custom-shadow hover:brightness-110 transition-all sm:w-auto min-w-[180px] md:min-w-[200px]" id="category-btn" type="button">
<span id="category-label">Kategori Olahraga</span>
<span class="material-symbols-outlined ml-2">keyboard_arrow_down</span>
</button>
<div class="hidden absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-[15px] bg-white shadow-lg ring-1 ring-black ring-opacity-5" id="category-menu">
<div class="py-1">
<a class="text-on-surface block px-4 py-2 text-body-md hover:bg-surface-container-low cursor-pointer rounded-[15px]" data-category="">Semua</a>
@foreach($sportCategories as $cat)
<a class="text-on-surface block px-4 py-2 text-body-md hover:bg-surface-container-low cursor-pointer rounded-[15px]" data-category="{{ $cat['key'] }}">{{ $cat['label'] }}</a>
@endforeach
</div>
</div>
</div>
</div>
</div>
</div>

<div class="grid grid-cols-12 gap-4 md:gap-gutter">
<div class="col-span-12 mt-2 md:mt-4 relative group/carousel overflow-hidden">
<div class="flex justify-between items-center mb-4 md:mb-stack-md">
<div class="flex items-center">
<span class="material-symbols-outlined text-[#EB5436] mr-2" style='font-variation-settings: "FILL" 1;'>campaign</span>
<h3 class="font-headline-md text-headline-md text-[20px] md:text-[24px]">Lapangan Promo</h3>
</div>
</div>
<button class="carousel-arrow absolute left-0 top-1/2 -translate-y-1/2 z-20 w-10 h-10 md:w-12 md:h-12 bg-white rounded-full custom-shadow flex items-center justify-center text-[#EB5436] hover:bg-[#ffdad6] transition-all opacity-0 group-hover/carousel:opacity-100" id="promo-prev" aria-label="Sebelumnya">
<span class="material-symbols-outlined text-[24px] md:text-[32px]">chevron_left</span>
</button>
<button class="carousel-arrow absolute right-0 top-1/2 -translate-y-1/2 z-20 w-10 h-10 md:w-12 md:h-12 bg-white rounded-full custom-shadow flex items-center justify-center text-[#EB5436] hover:bg-[#ffdad6] transition-all opacity-0 group-hover/carousel:opacity-100" id="promo-next" aria-label="Berikutnya">
<span class="material-symbols-outlined text-[24px] md:text-[32px]">chevron_right</span>
</button>
<div class="flex gap-4 md:gap-gutter overflow-x-auto pb-6 hide-scrollbar scroll-smooth" id="promo-container">
@forelse($promoFields as $field)
<div class="promo-card min-w-[280px] sm:min-w-[320px] bg-white rounded-[15px] custom-shadow group cursor-pointer overflow-hidden border border-[#e6bdb8]/30 transition-all hover:translate-y-[-4px]">
<div class="relative h-40 sm:h-48 overflow-hidden">
<img alt="{{ $field->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="{{ $field->image_url }}" loading="lazy" onerror="this.onerror=null;this.src='{{ $field->fallback_image }}'"/>
<div class="absolute top-3 left-3 md:top-4 md:left-4 bg-[#EB5436] text-white px-2 md:px-3 py-1 rounded-full text-[10px] md:text-label-caps font-bold">{{ $field->promo_badge ?? 'Promo' }}</div>
<div class="absolute bottom-3 left-3 md:bottom-4 md:left-4 bg-black/50 backdrop-blur-md text-white px-2 md:px-3 py-1 rounded-full text-[10px] md:text-[12px] flex items-center">
<span class="material-symbols-outlined text-[12px] md:text-[14px] mr-1">local_offer</span>
@if($field->promo_end)
                                    Hingga {{ $field->promo_end }}
@else
                                    Slot Terbatas
@endif
</div>
</div>
<div class="p-3 md:p-stack-md">
<h4 class="font-headline-sm text-headline-sm text-[16px] md:text-[20px] mb-1">{{ $field->name }}</h4>
<p class="text-[#5c403c] text-body-md mb-3 md:mb-stack-md flex items-center text-[12px] md:text-[14px]">
<span class="material-symbols-outlined text-[14px] md:text-[16px] mr-1">location_on</span> {{ $field->location }}
                                </p>
<div class="flex items-baseline space-x-2">
<span class="text-[#EB5436] font-bold text-[16px] md:text-[20px]">Rp {{ number_format($field->promo_price_raw, 0, ',', '.') }}</span>
@if($field->price_per_hour > ($field->promo_price_raw ?? $field->price_per_hour))
<span class="text-[#5c403c] line-through text-[12px] md:text-[14px]">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</span>
@endif
<span class="text-[#5c403c] text-[12px] md:text-[14px]">/ jam</span>
</div>
</div>
</div>
@empty
<div class="flex items-center justify-center w-full h-48 text-[#5c403c] text-body-lg">
Belum ada promo tersedia saat ini.
</div>
@endforelse
</div>
</div>

<div class="col-span-12 lg:col-span-8 mt-4">
<div class="flex justify-between items-center mb-4 md:mb-stack-md">
<div class="flex items-center">
<span class="material-symbols-outlined text-[#006c49] mr-2" style='font-variation-settings: "FILL" 1;'>stars</span>
<h3 class="font-headline-md text-headline-md text-[20px] md:text-[24px]">Lapangan Populer</h3>
</div>
</div>
<div class="space-y-4 md:space-y-stack-md">
@forelse($popularFields as $field)
<div class="flex flex-col sm:flex-row bg-white rounded-[15px] p-3 md:p-4 custom-shadow hover:translate-x-1 transition-all group border border-transparent hover:border-[#EB5436]/20">
<img alt="{{ $field->name }}" class="w-full sm:w-32 h-48 sm:h-32 rounded-[15px] object-cover" src="{{ $field->image_url }}" loading="lazy" onerror="this.onerror=null;this.src='{{ $field->fallback_image }}'"/>
<div class="sm:ml-6 flex-1 flex flex-col justify-between mt-3 sm:mt-0">
<div>
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 sm:gap-0">
<h4 class="font-headline-sm text-headline-sm text-[16px] md:text-[20px]">{{ $field->name }}</h4>
<div class="flex items-center bg-[#006c49]/10 px-2 py-1 rounded text-[#006c49] font-bold text-[12px] w-fit">
<span class="material-symbols-outlined text-[14px] mr-1" style='font-variation-settings: "FILL" 1;'>trending_up</span>
                                            Terpopuler #{{ $loop->iteration }}
                                        </div>
</div>
<p class="text-[#5c403c] flex items-center text-body-md mt-1 text-[12px] md:text-[14px]">
<span class="material-symbols-outlined text-[14px] md:text-[16px] mr-1">location_on</span> {{ $field->location }}
                                    </p>
<div class="flex flex-col sm:flex-row sm:items-center mt-2 gap-2 sm:gap-4">
<div class="flex items-center">
@php
$full = min(5, max(0, floor($field->rating)));
$half = ($field->rating - $full) >= 0.3 && $full < 5 ? 1 : 0;
$empty = 5 - $full - $half;
@endphp
@for($i = 0; $i < $full; $i++)
<span class="material-symbols-outlined text-yellow-500 text-[16px] md:text-[18px]" style='font-variation-settings: "FILL" 1;'>star</span>
@endfor
@if($half)
<span class="material-symbols-outlined text-yellow-500 text-[16px] md:text-[18px]" style='font-variation-settings: "FILL" 1;'>star_half</span>
@endif
@for($i = 0; $i < $empty; $i++)
<span class="material-symbols-outlined text-yellow-500 text-[16px] md:text-[18px]">star</span>
@endfor
<span class="ml-1 font-bold text-[14px]">{{ number_format($field->rating, 1) }}</span>
<span class="ml-1 text-[#5c403c] text-[12px] md:text-[14px]">({{ number_format($field->review_count ?? 0) }} ulasan)</span>
</div>
<div class="hidden sm:block h-4 w-[1px] bg-[#e6bdb8]"></div>
<div class="text-[#5c403c] text-[12px] md:text-[14px] font-medium">{{ number_format($field->bookings_count ?? 0) }}+ Booking</div>
</div>
</div>
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-3 md:mt-4 gap-3 sm:gap-0">
                        <div class="flex -space-x-2">
                                @php
                                    $bookers = $field->recentBookers ?? collect();
                                    $totalBookers = $bookers->count();
                                    $displayCount = min($totalBookers, 3);
                                @endphp
                                @for($bi = 0; $bi < $displayCount; $bi++)
                                    @php $buser = $bookers[$bi]; $bavatar = $buser->avatarUrl(); @endphp
                                    @if($bavatar && !str_contains($bavatar, 'characters/'))
                                        <img class="w-7 h-7 md:w-8 md:h-8 rounded-full border-2 border-white object-cover" src="{{ $bavatar }}" alt="{{ $buser->name }}" loading="lazy"/>
                                    @else
                                        <div class="w-7 h-7 md:w-8 md:h-8 rounded-full border-2 border-white bg-[#02025b] text-white flex items-center justify-center text-[10px] font-bold shrink-0">{{ mb_substr($buser->name, 0, 1) }}</div>
                                    @endif
                                @endfor
                                @if($totalBookers > 3)
                                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-full border-2 border-white bg-[#e4e2e1] flex items-center justify-center text-[10px] font-bold">+{{ $totalBookers - 3 }}</div>
                                @endif
                            </div>
<a href="{{ route('booking.show', $field->id) }}" class="text-[#EB5436] font-bold hover:underline flex items-center text-[14px]">
                                        Cek Ketersediaan <span class="material-symbols-outlined ml-1">chevron_right</span>
</a>
</div>
</div>
</div>
@empty
<div class="bg-white rounded-[15px] p-6 md:p-8 custom-shadow text-center">
<p class="text-[#5c403c] text-body-lg">Belum ada data lapangan populer.</p>
</div>
@endforelse
</div>
</div>

<div class="col-span-12 lg:col-span-4 mt-4">
<div class="bg-white rounded-[15px] custom-shadow p-4 md:p-stack-lg border border-[#e6bdb8]/30">
<div class="flex items-center justify-between mb-4 md:mb-stack-lg">
<h3 class="font-headline-md text-headline-md text-[20px] md:text-[24px] flex items-center">
<span class="material-symbols-outlined text-[#EB5436] mr-2">groups</span>
                        Komunitas
</h3>
@if(!empty($allSportCategories))
<select onchange="filterRekomendasiKomunitas(this.value)" class="text-[11px] font-bold bg-slate-50 border border-slate-200 text-slate-600 rounded-xl px-2.5 py-1.5 focus:ring-2 focus:ring-emerald-300/40 outline-none transition-shadow cursor-pointer">
<option value="">Semua</option>
@foreach($allSportCategories as $cat)
<option value="{{ $cat }}">{{ $cat }}</option>
@endforeach
</select>
@endif
</div>
@if($communities->isNotEmpty())
<div class="space-y-3" id="rekomendasi-komunitas-list">
@foreach($communities as $cm)
@php
$cmIsCreator = $cm->created_by === (Auth::id() ?? 0);
$cmIsMember = in_array($cm->id, $myCommunityIds);
$cmSportEmoji = ['Futsal'=>'⚽','Badminton'=>'🏸','Basket'=>'🏀','Voli'=>'🏐','Tennis'=>'🎾','Golf'=>'🏌️','Renang'=>'🏊','Panahan'=>'🏹','Lari'=>'🏃','Sepeda'=>'🚴','Tinju'=>'🥊','Bela Diri'=>'🥋','Yoga'=>'🧘','Fitness'=>'🏋️','Hiking'=>'🥾','Padel'=>'🎾','Baseball'=>'⚾','Rugby'=>'🏉','Senam'=>'🤸'][$cm->sport_category] ?? '🏅';
$cmPhotoUrl = $cm->photo ? asset('storage/'.$cm->photo) : null;
@endphp
<div class="community-rekom-card rounded-[20px] border border-slate-100 bg-white p-3.5 shadow-sm hover:shadow-md transition-all" data-cm-sport="{{ $cm->sport_category }}">
<div class="flex items-start gap-3">
<div class="w-11 h-11 rounded-xl overflow-hidden flex-shrink-0 bg-emerald-50 flex items-center justify-center text-xl">
@if($cmPhotoUrl)
<img src="{{ $cmPhotoUrl }}" alt="{{ $cm->name }}" class="w-full h-full object-cover">
@else
{{ $cmSportEmoji }}
@endif
</div>
<div class="flex-1 min-w-0">
<div class="flex items-center gap-1.5 flex-wrap">
<h4 class="text-[13px] font-extrabold text-[#02025b] truncate">{{ $cm->name }}</h4>
@if($cmIsCreator)
<span class="text-[8px] font-extrabold tracking-wider text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded-full uppercase">Milik Saya</span>
@endif
</div>
<div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
<span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">{{ $cm->sport_category }}</span>
<span class="text-[10px] text-slate-400 font-semibold">{{ $cm->city }}</span>
</div>
<p class="text-[11px] text-slate-500 mt-1 leading-relaxed line-clamp-2">{{ $cm->description }}</p>
<div class="flex items-center justify-between mt-2">
<span class="text-[10px] font-bold text-slate-400">
<svg class="w-3 h-3 inline-block align-text-bottom mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
{{ $cm->members_count }} Anggota
</span>
@if($cmIsCreator)
<a href="{{ $cm->whatsapp_link }}" target="_blank" class="text-[10px] font-extrabold tracking-wider text-emerald-600 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-1 hover:bg-emerald-100 transition-colors">Kelola</a>
@elseif($cmIsMember)
<a href="{{ $cm->whatsapp_link }}" target="_blank" rel="noopener noreferrer" class="text-[10px] font-extrabold tracking-wider text-emerald-600 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-1 hover:bg-emerald-100 transition-colors flex items-center gap-1">
<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
WhatsApp</a>
@else
<button onclick="joinRekomendasiKomunitas({{ $cm->id }}, this)" class="text-[10px] font-extrabold tracking-wider text-white bg-emerald-600 rounded-lg px-3 py-1 hover:bg-emerald-700 active:scale-[0.97] transition-all">Gabung</button>
@endif
</div>
</div>
</div>
</div>
@endforeach
</div>
@else
<div class="flex flex-col items-center justify-center text-center py-8 bg-slate-50/30 border border-slate-100 rounded-xl">
<div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-2xl mb-2">👥</div>
<h4 class="text-sm font-extrabold text-[#02025b] mb-1">Belum ada komunitas</h4>
<p class="text-[11px] font-semibold text-slate-400">Jadilah yang pertama membuat komunitas!</p>
<a href="{{ route('matches.index') }}" class="mt-3 inline-block text-[11px] font-extrabold tracking-wider text-white px-4 py-2 rounded-xl" style="background:linear-gradient(135deg,#059669,#10b981);">Buat Komunitas</a>
</div>
@endif
</div>
</div>
</div>
</div>
</div>

</main>
</div>

<script>
(function() {
    'use strict';

    // Category dropdown
    var categoryBtn = document.getElementById('category-btn');
    var categoryMenu = document.getElementById('category-menu');
    var categoryLabel = document.getElementById('category-label');

    if (categoryBtn && categoryMenu) {
        categoryBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            categoryMenu.classList.toggle('hidden');
        });
        document.addEventListener('click', function() {
            categoryMenu.classList.add('hidden');
        });
        categoryMenu.querySelectorAll('a').forEach(function(item) {
            item.addEventListener('click', function() {
                var cat = item.dataset.category;
                categoryLabel.textContent = cat ? item.textContent.trim() : 'Kategori Olahraga';
                categoryMenu.classList.add('hidden');
            });
        });
    }

    // Search filter
    var searchInput = document.getElementById('search-input');
    if (searchInput) {
        var searchTimer;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            var q = this.value.toLowerCase().trim();
            searchTimer = setTimeout(function() {
                document.querySelectorAll('.promo-card, [class*="flex flex-col sm:flex-row bg-white rounded-[15px]"]').forEach(function(card) {
                    var name = (card.querySelector('h4') || {}).textContent || '';
                    var loc = (card.querySelector('[class*="location_on"]') || {}).parentElement.textContent || '';
                    card.style.display = (!q || name.toLowerCase().includes(q) || loc.toLowerCase().includes(q)) ? '' : 'none';
                });
            }, 150);
        });
    }

    // Button micro-interactions
    document.querySelectorAll('button').forEach(function(btn) {
        btn.addEventListener('mousedown', function() { this.classList.add('scale-[0.98]'); });
        btn.addEventListener('mouseup', function() { this.classList.remove('scale-[0.98]'); });
        btn.addEventListener('mouseleave', function() { this.classList.remove('scale-[0.98]'); });
    });

    // Komunitas filter by sport
    function filterRekomendasiKomunitas(sport) {
        var cards = document.querySelectorAll('.community-rekom-card');
        cards.forEach(function(c) {
            c.style.display = !sport || c.getAttribute('data-cm-sport') === sport ? '' : 'none';
        });
    }

    // Komunitas join
    function joinRekomendasiKomunitas(id, btn) {
        if (btn.disabled) return;
        btn.disabled = true;
        btn.textContent = 'Memproses...';
        var csrf = document.querySelector('meta[name="csrf-token"]');
        if (!csrf) return;
        var fd = new FormData();
        fd.append('_token', csrf.getAttribute('content'));
        fetch('{{ url('/komunitas') }}/' + id + '/join', {
            method: 'POST',
            body: fd,
        }).then(function(r) { return r.json(); }).then(function(data) {
            if (data.joined) {
                var card = btn.closest('.community-rekom-card');
                var countEl = card.querySelector('.text-slate-400');
                if (countEl) countEl.innerHTML = '<svg class="w-3 h-3 inline-block align-text-bottom mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> ' + data.count + ' Anggota';
                btn.outerHTML = '<a href="' + data.whatsapp + '" target="_blank" rel="noopener noreferrer" class="text-[10px] font-extrabold tracking-wider text-emerald-600 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-1 hover:bg-emerald-100 transition-colors flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg> WhatsApp</a>';
            } else {
                btn.disabled = false;
                btn.textContent = 'Gabung';
            }
        }).catch(function() {
            btn.disabled = false;
            btn.textContent = 'Gabung';
        });
    }

    // Carousel with infinite scroll + drag/swipe
    (function() {
        var container = document.getElementById('promo-container');
        var nextBtn = document.getElementById('promo-next');
        var prevBtn = document.getElementById('promo-prev');
        if (!container || !nextBtn || !prevBtn) return;

        var cards = Array.from(container.querySelectorAll('.promo-card'));
        var total = cards.length;
        if (total === 0) return;

        var n = Math.min(3, total);

        // Clone last n cards before the first real card
        var frag = document.createDocumentFragment();
        cards.slice(-n).reverse().forEach(function(c) { frag.appendChild(c.cloneNode(true)); });
        container.insertBefore(frag, container.firstChild);

        // Clone first n cards after the last real card
        var frag2 = document.createDocumentFragment();
        cards.slice(0, n).forEach(function(c) { frag2.appendChild(c.cloneNode(true)); });
        container.appendChild(frag2);

        function getStep() {
            var card = container.querySelector('.promo-card');
            var gap = window.innerWidth < 640 ? 16 : 24;
            return (card ? card.offsetWidth : 280) + gap;
        }

        var step = getStep();
        var offset = step * n;
        container.scrollLeft = offset;

        // Boundary check — runs only after all scrolling/snapping fully stops
        function onScrollEnd() {
            var cs = container.scrollLeft;
            var max = container.scrollWidth - container.clientWidth;
            if (cs <= step * 0.5) {
                container.style.scrollBehavior = 'auto';
                container.scrollLeft = max - offset;
                container.style.scrollBehavior = '';
            } else if (cs >= max - step * 0.5) {
                container.style.scrollBehavior = 'auto';
                container.scrollLeft = offset;
                container.style.scrollBehavior = '';
            }
        }

        // Prefer scrollend (modern browsers), fallback to debounced scroll
        if ('onscrollend' in container) {
            container.addEventListener('scrollend', onScrollEnd);
        } else {
            var debounceTimer;
            container.addEventListener('scroll', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(onScrollEnd, 300);
            }, { passive: true });
        }

        // Arrow buttons
        nextBtn.addEventListener('click', function() {
            container.scrollBy({ left: step, behavior: 'smooth' });
        });
        prevBtn.addEventListener('click', function() {
            container.scrollBy({ left: -step, behavior: 'smooth' });
        });

        // --- Drag / Swipe ---
        var sx = 0, ss = 0, dragging = false;

        function dragStart(x) {
            sx = x;
            ss = container.scrollLeft;
            dragging = false;
        }

        function dragMove(x) {
            var d = sx - x;
            if (Math.abs(d) > 10) dragging = true;
            if (dragging) container.scrollLeft = ss + d;
        }

        function dragEnd(x) {
            if (!dragging) return;
            dragging = false;
            var d = sx - x;
            if (Math.abs(d) > step * 0.3) {
                container.scrollBy({ left: d > 0 ? step : -step, behavior: 'smooth' });
            } else {
                container.scrollTo({ left: ss, behavior: 'smooth' });
            }
        }

        // Mouse
        container.addEventListener('mousedown', function(e) { dragStart(e.pageX); });
        document.addEventListener('mousemove', function(e) {
            if (e.buttons !== 1) return;
            dragMove(e.pageX);
        });
        document.addEventListener('mouseup', function(e) { dragEnd(e.pageX); });
        container.addEventListener('mouseleave', function() {
            if (dragging) { dragging = false; container.scrollTo({ left: ss, behavior: 'smooth' }); }
        });

        // Touch
        container.addEventListener('touchstart', function(e) { dragStart(e.touches[0].pageX); }, { passive: true });
        container.addEventListener('touchmove', function(e) { dragMove(e.touches[0].pageX); }, { passive: true });
        container.addEventListener('touchend', function(e) { dragEnd(e.changedTouches[0].pageX); }, { passive: true });

        // Responsive — recalc step & offset on resize
        window.addEventListener('resize', function() {
            step = getStep();
            offset = step * n;
        });
    })();
})();
</script>
</body>
</html>
