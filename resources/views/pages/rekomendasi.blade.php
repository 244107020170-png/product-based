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
<img alt="{{ $field->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="{{ $field->image_url }}" loading="lazy"/>
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
<img alt="{{ $field->name }}" class="w-full sm:w-32 h-48 sm:h-32 rounded-[15px] object-cover" src="{{ $field->image_url }}" loading="lazy"/>
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
<span class="ml-1 text-[#5c403c] text-[12px] md:text-[14px]">({{ number_format($field->review_count) }} ulasan)</span>
</div>
<div class="hidden sm:block h-4 w-[1px] bg-[#e6bdb8]"></div>
<div class="text-[#5c403c] text-[12px] md:text-[14px] font-medium">{{ number_format($field->bookings_count ?? 0) }}+ Booking</div>
</div>
</div>
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-3 md:mt-4 gap-3 sm:gap-0">
<div class="flex -space-x-2">
<img class="w-7 h-7 md:w-8 md:h-8 rounded-full border-2 border-white object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAZkeJKJgO2eELZDmyvNBM8d86qvSCfYwhxURy_rjSGTCxo0Wkj39tZ9a1cQR_lzfbyf_ll315hsjPnc-4KCuY2wKMk69HPZi2MtjnhmFL2vM6Z7dTDxNN3WCUlk-W-iz1s_cnOeSXeFLAmlvejhsWwGctD5vi_L823v9qHRGWALsD6uND_P04eC6masqwps0WBiSby3FCvpbGglyhRZbUEL9vpVFyYuf6qzaZS36-dPcM9XOJWL76BHZRG0kGRMP85Vh8067Kvp6Y" loading="lazy"/>
<img class="w-7 h-7 md:w-8 md:h-8 rounded-full border-2 border-white object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA3KawdWFJ5HLidU3_hZwm3anTrleHDvQz5OBymj2XmTel8mr6zuVO8_Vv8VlLWRWuy2-F6Tae67pzQemEyZHNJn93D7gkmS2CK9Qwq9Iup-AcL7fqgYnZdT3MsFcgz7KHiSdi-Y5PV4xUH7fYLGPLGuDqhL26jUtMVWuihmEv0OV_Y0Uo5ZTzYC7WiAqHRWLucYe-TUDjNfngqttYaQprqu8vSfl-Ejx2JwXLI7pQjyWfCTOh2qMIyRi255Dc-JBgGoJrXwM8r--Q" loading="lazy"/>
<img class="w-7 h-7 md:w-8 md:h-8 rounded-full border-2 border-white object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAG7ccToYoyj-MyUJEQxd0yJrTUpEhQ0d95mU8449D2Fq3sMzmOdFiJDvpZk6Z9pHSm38VcpHwfQP9_-fWi4eWLb3Di-3ank3zW2bF7TXehXRRXUYAf4xZmfkn9848by46v746SKiqmQsYNj6yItWWRQZBSbN4HNx-tzBxHGFnaqbEGfRQZ2NifyOSwZpR4PbO3fBxGS_o_SY0kAkOMKZiYY_Z8_F7dNqqmaDCXyEKVT--gjA8ObNbyD5ZytnATMPYwa6946h995hA" loading="lazy"/>
<div class="w-7 h-7 md:w-8 md:h-8 rounded-full border-2 border-white bg-[#e4e2e1] flex items-center justify-center text-[10px] font-bold">+{{ number_format(min(999, ($field->bookings_count ?? 0))) }}</div>
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
<h3 class="font-headline-md text-headline-md text-[20px] md:text-[24px] mb-4 md:mb-stack-lg flex items-center">
<span class="material-symbols-outlined text-[#EB5436] mr-2">groups</span>
                            Komunitas</h3>
<div class="space-y-4 md:space-y-stack-lg relative before:content-[''] before:absolute before:left-[11px] before:top-4 before:bottom-4 before:w-[2px] before:bg-[#e4e2e1]">
<div class="relative pl-8">
<div class="absolute left-0 top-1 w-6 h-6 rounded-full bg-[#006c49]/20 border-2 border-white flex items-center justify-center z-10">
<div class="w-2 h-2 rounded-full bg-[#006c49]"></div>
</div>
<div class="flex justify-between items-start">
<div>
<p class="font-bold text-body-md text-[14px]">Pertandingan Baru Dibuat</p>
<p class="text-[10px] md:text-[12px] text-[#5c403c]">Spartan Futsal Club mencari lawan</p>
</div>
<span class="text-[10px] text-[#5c403c] font-medium shrink-0">Baru saja</span>
</div>
<div class="mt-2 bg-[#f6f3f2] p-2 rounded-lg text-[10px] md:text-[12px] flex items-center">
<span class="material-symbols-outlined text-[14px] md:text-[16px] mr-1">stadium</span>
                                    Elite Futsal, 20:00 WIB
                                </div>
</div>
<div class="relative pl-8">
<div class="absolute left-0 top-1 w-6 h-6 rounded-full bg-[#EB5436]/20 border-2 border-white flex items-center justify-center z-10">
<div class="w-2 h-2 rounded-full bg-[#EB5436]"></div>
</div>
<div class="flex justify-between items-start">
<div>
<p class="font-bold text-body-md text-[14px]">Pendaftaran Tim Penuh</p>
<p class="text-[10px] md:text-[12px] text-[#5c403c]">Turnamen Badminton Minggu Ceria</p>
</div>
<span class="text-[10px] text-[#5c403c] font-medium shrink-0">10 menit lalu</span>
</div>
</div>
<div class="relative pl-8">
<div class="absolute left-0 top-1 w-6 h-6 rounded-full bg-[#e4e2e1] border-2 border-white flex items-center justify-center z-10">
<div class="w-2 h-2 rounded-full bg-[#5c403c]"></div>
</div>
<div class="flex justify-between items-start">
<div>
<p class="font-bold text-body-md text-[14px]">Ulasan Baru</p>
<p class="text-[10px] md:text-[12px] text-[#5c403c]">Budi Santoso mengulas Stadium Grande</p>
</div>
<span class="text-[10px] text-[#5c403c] font-medium shrink-0">45 menit lalu</span>
</div>
<div class="mt-1 flex text-yellow-500">
<span class="material-symbols-outlined text-[12px] md:text-[14px]">star</span>
<span class="material-symbols-outlined text-[12px] md:text-[14px]">star</span>
<span class="material-symbols-outlined text-[12px] md:text-[14px]">star</span>
<span class="material-symbols-outlined text-[12px] md:text-[14px]">star</span>
<span class="material-symbols-outlined text-[12px] md:text-[14px]">star</span>
</div>
</div>
</div>
<div class="mt-4 md:mt-stack-lg bg-[#ffdad6]/30 rounded-[15px] p-4 border border-[#ffdad6]">
<h4 class="font-bold text-[#EB5436] mb-1 text-[14px]">Mulai Bermain?</h4>
<p class="text-[10px] md:text-[12px] text-[#93000b] mb-3">Buat jadwal rutin dan dapatkan harga khusus langganan hingga 30%!</p>
<button class="bg-[#EB5436] text-white px-4 py-2 rounded-[15px] text-[12px] font-bold">Daftar Membership</button>
</div>
</div>
</div>
</div>
</div>
</div>

<button class="fixed bottom-6 right-4 md:bottom-8 md:right-8 w-12 h-12 md:w-14 md:h-14 bg-[#EB5436] text-white rounded-full shadow-lg flex items-center justify-center hover:scale-110 active:scale-95 transition-all z-50 group" id="fab-chat">
<span class="material-symbols-outlined text-[24px] md:text-[28px]">chat</span>
<span class="absolute right-full mr-4 bg-[#1b1c1b] text-white px-3 py-1 rounded-[15px] text-body-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none text-[12px] md:text-[14px]">Tanya Kami</span>
</button>
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
