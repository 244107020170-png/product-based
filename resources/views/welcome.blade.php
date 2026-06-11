<!DOCTYPE html><html class="light" lang="id"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Spies Sport - Main Olahraga Jadi Lebih Seru!</title>
@vite(['resources/css/pages.css', 'resources/js/app.js'])
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<style>
        body {
            background-image: url("{{ asset('assets/images/bg/bg-regform.png') }}");
            background-size: cover;
            background-attachment: fixed;
            background-repeat: repeat;
        }

        .character-float {
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        .swipe-card {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        .swipe-card:hover {
            transform: rotate(-3deg) translateX(-10px);
        }

        @media (max-width: 400px) {
            .text-display-lg { font-size: clamp(1.8rem, 10vw, 2.5rem) !important; }
            .text-headline-lg { font-size: clamp(1.3rem, 6vw, 1.8rem) !important; }
            .px-margin-mobile { padding-left: 12px !important; padding-right: 12px !important; }
            .gap-lg { gap: 16px !important; }
            .gap-md { gap: 10px !important; }
            .h-64 { height: 200px !important; }
        }
        @media (max-width: 360px) {
            .flex-col.sm\:flex-row { flex-direction: column !important; }
            .flex-col.sm\:flex-row a { width: 100% !important; text-align: center !important; }
        }
    </style>
</head>
<body class="font-body-md text-on-background bg-background overflow-x-hidden">
<!-- TopNavBar -->
<nav id="mainNav" class="fixed top-0 w-full z-50 transition-all duration-300">
<div class="flex items-center justify-between px-margin-mobile md:px-margin-desktop h-[80px] w-full max-w-[1440px] mx-auto">
<a href="{{ route('home') }}" class="flex items-center gap-3">
    <img src="{{ asset('assets/images/logo/logo3.png') }}" alt="Logo" class="h-8 w-auto">
    <span class="font-headline-md text-headline-md font-extrabold tracking-tight">
        <span style="color: #EB5436;">Spies</span> <span style="color: #00004D;">Sport</span>
    </span>
</a>
<div class="hidden md:flex items-center gap-lg">
<a class="text-primary font-bold border-b-2 border-primary py-2 transition-all duration-300" href="{{ route('home') }}">Beranda</a>
<a class="text-secondary font-medium hover:text-primary transition-all duration-300" href="{{ route('lapangan') }}">Lapangan</a>
<a class="text-secondary font-medium hover:text-primary transition-all duration-300" href="{{ route('komunitas') }}">Komunitas</a>
<a class="text-secondary font-medium hover:text-primary transition-all duration-300" href="{{ route('bantuan') }}">Bantuan</a>
</div>
<div class="flex items-center gap-md">
<a href="{{ route('login') }}" class="hidden sm:block font-label-md text-secondary hover:text-primary active:scale-95 transition-all">Masuk</a>
<a href="{{ route('choose.role') }}" class="bg-primary text-on-primary px-lg py-sm rounded-full font-label-md shadow-lg shadow-primary/20 hover:brightness-110 active:scale-95 transition-all">Daftar</a>
<!-- Mobile Hamburger -->
<button id="mobileNavToggle" class="md:hidden flex items-center justify-center w-10 h-10 rounded-full bg-white/80 text-primary shadow-sm hover:bg-white transition-all" onclick="toggleMobileNav()" aria-label="Menu">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path id="navOpenIcon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        <path id="navCloseIcon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
    </svg>
</button>
</div>
</div>
</nav>

<!-- Mobile Navigation Menu -->
<div id="mobileNavMenu" class="hidden md:hidden fixed inset-x-0 top-[80px] z-40 bg-white/95 backdrop-blur-md shadow-lg border-b border-gray-200 transform -translate-y-full transition-transform duration-300">
    <div class="flex flex-col p-6 space-y-4">
        <a class="text-primary font-bold text-lg border-b-2 border-primary pb-2" href="{{ route('home') }}" onclick="toggleMobileNav()">Beranda</a>
        <a class="text-secondary font-medium text-lg hover:text-primary transition-all pb-2 border-b border-gray-100" href="{{ route('lapangan') }}" onclick="toggleMobileNav()">Lapangan</a>
        <a class="text-secondary font-medium text-lg hover:text-primary transition-all pb-2 border-b border-gray-100" href="{{ route('komunitas') }}" onclick="toggleMobileNav()">Komunitas</a>
        <a class="text-secondary font-medium text-lg hover:text-primary transition-all pb-2 border-b border-gray-100" href="{{ route('bantuan') }}" onclick="toggleMobileNav()">Bantuan</a>
        <div class="pt-2">
            <a href="{{ route('login') }}" class="block w-full text-center border border-gray-300 text-secondary py-3 rounded-full font-label-md hover:bg-gray-50 transition-all mb-3">Masuk</a>
            <a href="{{ route('choose.role') }}" class="block w-full text-center bg-primary text-on-primary py-3 rounded-full font-label-md shadow-lg shadow-primary/20 hover:brightness-110 transition-all">Daftar</a>
        </div>
    </div>
</div>

<!-- Hero Section -->
<section class="relative pt-[120px] pb-xl md:py-xl min-h-[90vh] flex items-center overflow-hidden">
<div class="container mx-auto px-margin-mobile md:px-margin-desktop grid grid-cols-1 md:grid-cols-2 gap-lg items-center relative z-10">
<div class="space-y-md">
<div class="inline-flex items-center gap-xs bg-primary/10 text-primary px-md py-xs rounded-full font-label-sm">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">sports_soccer</span>
                    Platform Olahraga #1 di Indonesia
                </div>
<h1 class="font-display-lg text-display-lg md:text-[64px] leading-tight">
                    Main Olahraga Jadi <span class="text-primary">Lebih Seru!</span>
</h1>
<p class="font-body-lg text-body-lg text-secondary max-w-[500px]">
                    Temukan teman baru, pesan lapangan favorit, dan raih prestasi dalam satu platform yang ceria dan bertenaga.
                </p>
<div class="flex flex-col sm:flex-row gap-md pt-base">
<a href="{{ route('choose.role') }}" class="no-underline bg-primary text-on-primary px-[32px] py-[16px] rounded-full font-label-md shadow-xl shadow-primary/25 hover:-translate-y-1 transition-all active:scale-95">
                        Main Sekarang
                    </a>
@guest
<a href="javascript:void(0)" onclick="showLoginPopup()" class="no-underline glass-card text-on-background px-[32px] py-[16px] rounded-full font-label-md border-white border hover:bg-white/80 transition-all active:scale-95 flex items-center justify-center gap-base">
@else
<a href="{{ route('lapangan') }}" class="no-underline glass-card text-on-background px-[32px] py-[16px] rounded-full font-label-md border-white border hover:bg-white/80 transition-all active:scale-95 flex items-center justify-center gap-base">
@endguest
                            <span class="material-symbols-outlined">search</span>
                        Cari Lapangan
                    </a>
</div>
</div>
<div class="relative character-float" id="character-guide" style="transform: translate(2.9375px, 9.98022px);">
<div class="absolute -z-10 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-primary/5 rounded-full blur-[80px]"></div>
<img alt="Cartoon sports characters" class="w-full max-w-[500px] mx-auto" src="{{ asset('assets/images/characters/player1.png') }}">
</div>
</div>
</section>
<!-- Feature Walkthrough -->
<section class="py-xl bg-white/40 backdrop-blur-md">
<div class="container mx-auto px-margin-mobile md:px-margin-desktop space-y-[100px]">
<!-- Mode Swipe -->
<div class="reveal flex flex-col md:flex-row items-center gap-xl">
<div class="flex-1 space-y-md">
<div class="w-16 h-16 rounded-2xl bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/20">
<span class="material-symbols-outlined text-[32px]">swipe</span>
</div>
<h2 class="font-headline-lg text-headline-lg">Mode Swipe Pertandingan</h2>
<p class="font-body-lg text-body-lg text-secondary">
                        Cari lawan tanding atau tim baru semudah swipe kiri dan kanan. Temukan kecocokan berdasarkan level keahlian dan lokasi terdekat.
                    </p>
</div>
<div class="flex-1 flex justify-center relative py-lg">
<div class="glass-card swipe-card w-[280px] h-[400px] rounded-lg p-md absolute transform -translate-x-8 -rotate-6 z-0"></div>
<div class="glass-card swipe-card w-[280px] h-[400px] rounded-lg p-md relative z-10 border-2 border-primary/20">
<div class="w-full h-48 bg-surface-container rounded-md mb-md overflow-hidden">
<img class="w-full h-full object-cover" data-alt="A group of smiling diverse young athletes standing together on a bright indoor basketball court, captured in a vibrant sports-media style with cinematic lighting and a energetic, optimistic mood." src="https://lh3.googleusercontent.com/aida-public/AB6AXuC-T3S3oyLb0_eJu69mfPEfYL6kKsyB8dsgbYrNuTgWoWqpxh-Vn6BveIqiWt1Rg9A1j5j5bYRG-lLzB6_WDzch-6UnFep6qEsBDfWq6zh1ErFBNR-hEM0Ofyrga5F5LMMPFfa1PVAgTG2Xtb_GQfsEOMh2IjamhCeIb_Zfmy4qc-ilzfxJB5m9ic8ebRPuHKPRjx4kLgFx2FV7mjcHWEhhR7JtSOVjEBcXY3Zm29-VZ3edcwqXY3PK_DuO1KkiR8_ombnifiBADTtI">
</div>
<div class="font-title-lg text-title-lg mb-xs">Futsal Veteran Muda</div>
<div class="flex items-center gap-xs text-secondary mb-md">
<span class="material-symbols-outlined text-[16px]">location_on</span>
<span class="text-label-sm">3.5 KM • Malang</span>
</div>
<div class="flex justify-between items-center">
<div class="bg-primary/10 text-primary px-md py-xs rounded-full text-label-sm">Pertandingan Ditemukan!</div>
<div class="flex -space-x-2">
<div class="w-8 h-8 rounded-full border-2 border-white bg-slate-300"></div>
<div class="w-8 h-8 rounded-full border-2 border-white bg-slate-400"></div>
<div class="w-8 h-8 rounded-full border-2 border-white bg-primary text-white text-[10px] flex items-center justify-center">+5</div>
</div>
</div>
</div>
</div>
</div>
<!-- Smart Booking -->
<div class="reveal flex flex-col md:flex-row-reverse items-center gap-xl active">
<div class="flex-1 space-y-md">
<div class="w-16 h-16 rounded-2xl bg-tertiary text-white flex items-center justify-center shadow-lg shadow-tertiary/20">
<span class="material-symbols-outlined text-[32px]">calendar_month</span>
</div>
<h2 class="font-headline-lg text-headline-lg">Sistem Pemesanan Cerdas</h2>
<p class="font-body-lg text-body-lg text-secondary">
                        Booking lapangan favoritmu tanpa drama. Cek jadwal real-time, pilih slot yang tersedia, dan bayar instan dalam hitungan detik.
                    </p>
</div>
<div class="flex-1 glass-card p-lg rounded-lg max-w-[500px]">
<div class="flex items-center justify-between mb-lg">
<h4 class="font-title-lg text-title-lg">Pilih Jadwal</h4>
<div class="text-primary font-bold">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('j F Y') }}</div>
</div>
<div class="grid grid-cols-3 gap-md">
<div class="p-md rounded-md bg-primary text-white text-center shadow-md">15:00</div>
<div class="p-md rounded-md bg-white border border-outline-variant text-center cursor-pointer hover:border-primary transition-all">16:00</div>
<div class="p-md rounded-md bg-white border border-outline-variant text-center cursor-pointer hover:border-primary transition-all">17:00</div>
<div class="p-md rounded-md bg-surface-container-low text-secondary text-center line-through">18:00</div>
<div class="p-md rounded-md bg-white border border-outline-variant text-center cursor-pointer hover:border-primary transition-all">19:00</div>
<div class="p-md rounded-md bg-white border border-outline-variant text-center cursor-pointer hover:border-primary transition-all">20:00</div>
</div>
<button class="w-full mt-lg bg-tertiary text-on-tertiary-container py-md rounded-full font-label-md active:scale-95 transition-all">Konfirmasi Slot</button>
</div>
</div>
<!-- Achievements -->
<div class="reveal flex flex-col md:flex-row items-center gap-xl">
<div class="flex-1 space-y-md">
<div class="w-16 h-16 rounded-2xl bg-on-primary-fixed-variant text-white flex items-center justify-center shadow-lg shadow-primary/20">
<span class="material-symbols-outlined text-[32px]">military_tech</span>
</div>
<h2 class="font-headline-lg text-headline-lg">Poin Prestasi</h2>
<p class="font-body-lg text-body-lg text-secondary">
                        Kumpulkan poin setiap kali kamu bermain dan naikkan level karaktermu. Dapatkan badge spesial yang bisa dipamerkan di profilmu!
                    </p>
</div>
<div class="flex-1 glass-card p-lg rounded-lg border-2 border-primary/10">
<div class="flex items-center gap-md mb-lg">
<div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center border-2 border-primary">
<span class="material-symbols-outlined text-primary text-[40px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<div>
<div class="font-headline-md text-headline-md">Level: Pemain Aktif</div>
<div class="text-secondary text-body-md">12 / 20 Poin</div>
</div>
</div>
<div class="w-full h-4 bg-surface-container rounded-full overflow-hidden mb-lg">
<div class="w-[80%] h-full bg-primary rounded-full transition-all duration-1000 ease-out"></div>
</div>
<div class="flex justify-between gap-md">
<div class="flex-1 p-md rounded-md bg-white/50 text-center border border-white">
<span class="material-symbols-outlined text-primary mb-xs" style="font-variation-settings: 'FILL' 1;">workspace_premium</span>
<div class="text-label-sm">Local Hero</div>
</div>
<div class="flex-1 p-md rounded-md bg-white/50 text-center border border-white">
<span class="material-symbols-outlined text-tertiary mb-xs" style="font-variation-settings: 'FILL' 1;">bolt</span>
<div class="text-label-sm">Speed Star</div>
</div>
<div class="flex-1 p-md rounded-md bg-white/50 text-center border border-white">
<span class="material-symbols-outlined text-orange-400 mb-xs" style="font-variation-settings: 'FILL' 1;">shield</span>
<div class="text-label-sm">Iron Wall</div>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- Featured Fields -->
<section class="py-xl">
<div class="container mx-auto px-margin-mobile md:px-margin-desktop">
<div class="flex justify-between items-end mb-xl">
<div>
<h2 class="font-headline-lg text-headline-lg">Lapangan Populer</h2>
<p class="text-secondary">Pilih arena terbaik untuk performa maksimalmu.</p>
</div>
@guest
<a href="javascript:void(0)" onclick="showLoginPopup()" class="text-primary font-bold flex items-center gap-xs hover:gap-md transition-all">
@else
<a href="{{ route('lapangan') }}" class="text-primary font-bold flex items-center gap-xs hover:gap-md transition-all">
@endguest
                        Lihat Semua <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-md">
@php
    $popularFields = \App\Models\Field::withCount('bookings')->orderBy('bookings_count', 'desc')->take(3)->get();
@endphp
@forelse($popularFields as $popularField)
<div class="group relative bg-white rounded-lg overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
<div class="h-64 overflow-hidden relative">
<img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $popularField->name }}" src="{{ $popularField->image_url }}" onerror="this.style.display='none'">
<div class="absolute top-md right-md bg-white/90 backdrop-blur-md px-md py-xs rounded-full font-label-sm flex items-center gap-xs">
<span class="material-symbols-outlined text-orange-400 text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            {{ number_format($popularField->rating ?? 0, 1) }}
</div>
@if($popularField->bookings_count >= 5)
<div class="absolute top-md left-md bg-primary text-white px-md py-xs rounded-full font-label-sm flex items-center gap-xs">
<span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">local_fire_department</span>
                            Populer
</div>
@endif
</div>
<div class="p-lg">
<div class="text-primary text-label-sm mb-xs">{{ strtoupper($popularField->type ?? 'OLAHRAGA') }}</div>
<h5 class="font-title-lg text-title-lg mb-base">{{ $popularField->name }}</h5>
<div class="flex items-center gap-xs text-secondary mb-md">
<span class="material-symbols-outlined text-[16px]">location_on</span>
<span class="text-label-sm">{{ $popularField->location ?: 'Lokasi tidak tersedia' }}</span>
</div>
<div class="flex items-center gap-2 mb-md">
@if($popularField->bookings_count >= 5)
<span class="bg-primary/10 text-primary px-md py-xs rounded-full text-label-sm font-bold">Populer</span>
@endif
@if($popularField->bookings_count > 0)
<span class="bg-amber-50 text-amber-700 px-md py-xs rounded-full text-label-sm font-bold">Paling Dibooking</span>
@endif
</div>
@guest
<a href="javascript:void(0)" onclick="showLoginPopup()" class="block w-full border-2 border-primary/20 text-primary py-md rounded-full font-label-md group-hover:bg-primary group-hover:text-white transition-all text-center">
@else
<a href="{{ route('lapangan') }}" class="block w-full border-2 border-primary/20 text-primary py-md rounded-full font-label-md group-hover:bg-primary group-hover:text-white transition-all text-center">
@endguest
                            Cek Jadwal
</a>
</div>
</div>
@empty
<div class="col-span-full text-center py-xl">
<p class="text-secondary">Belum ada data lapangan populer.</p>
</div>
@endforelse
</div>
</div>
</section>
<!-- Promo & Diskon -->
<section class="py-xl">
<div class="container mx-auto px-margin-mobile md:px-margin-desktop">
<div class="flex justify-between items-end mb-xl">
<div>
<h2 class="font-headline-lg text-headline-lg">Promo & Diskon</h2>
<p class="text-secondary">Jangan lewatkan penawaran spesial dari berbagai lapangan.</p>
</div>
@guest
<a href="javascript:void(0)" onclick="showLoginPopup()" class="text-primary font-bold flex items-center gap-xs hover:gap-md transition-all">
@else
<a href="{{ route('lapangan') }}" class="text-primary font-bold flex items-center gap-xs hover:gap-md transition-all">
@endguest
    Lihat Semua <span class="material-symbols-outlined">arrow_forward</span>
</a>
</div>
@php
    $promoDiscounts = \App\Models\Discount::with('fields')
        ->active()
        ->orderBy('value', 'desc')
        ->get();

    $promoFieldItems = collect();
    foreach ($promoDiscounts as $promo) {
        foreach ($promo->fields->where('is_available', true) as $f) {
            $promoFieldItems->push((object)[
                'promo' => $promo,
                'field' => $f,
            ]);
        }
    }
    $promoFieldItems = $promoFieldItems->shuffle()->take(3);
@endphp
@if($promoFieldItems->isNotEmpty())
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-md">
    @foreach($promoFieldItems as $item)
    @php $promo = $item->promo; $f = $item->field; @endphp
    <div class="group relative bg-white rounded-lg overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
        <div class="h-64 overflow-hidden relative">
            <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $f->name }}" src="{{ $f->image_url }}" onerror="this.style.display='none'">
            <div class="absolute top-md left-md flex gap-xs flex-wrap">
                @if($promo->type === 'percentage')
                <div class="bg-red-500 text-white px-lg py-xs rounded-full font-label-sm font-bold flex items-center gap-xs shadow-lg shadow-red-500/30">
                    <span>🔥</span>
                    {{ (int)$promo->value }}% OFF
                </div>
                @else
                <div class="bg-red-500 text-white px-lg py-xs rounded-full font-label-sm font-bold flex items-center gap-xs shadow-lg shadow-red-500/30">
                    <span>🔥</span>
                    Hemat Rp{{ number_format((int)$promo->value, 0, ',', '.') }}
                </div>
                @endif
            </div>
            <div class="absolute top-md right-md bg-white/90 backdrop-blur-md px-md py-xs rounded-full font-label-sm flex items-center gap-xs">
                <span class="material-symbols-outlined text-orange-400 text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                {{ number_format($f->rating ?? 0, 1) }}
            </div>
        </div>
        <div class="p-lg">
            <div class="flex items-center gap-2 mb-xs">
                <span class="text-primary text-label-sm">{{ strtoupper($f->type ?? 'OLAHRAGA') }}</span>
                <span class="px-md py-[2px] rounded-full text-[10px] font-bold" style="background:#fef3c7;color:#92400e;">
                    {{ \Carbon\Carbon::parse($promo->end_date)->locale('id')->translatedFormat('d M') }}
                </span>
            </div>
            <h5 class="font-title-lg text-title-lg mb-base">{{ $f->name }}</h5>
            <p class="font-label-sm text-secondary mb-base">{{ $promo->name }}</p>
            <div class="flex items-center gap-xs text-secondary mb-md">
                <span class="material-symbols-outlined text-[16px]">location_on</span>
                <span class="text-label-sm">{{ $f->location ?: 'Lokasi tidak tersedia' }}</span>
            </div>
            <div class="flex flex-col gap-1 mb-md">
                @php
                    $discountedPrice = $promo->type === 'percentage'
                        ? (int)round($f->price_per_hour * (1 - $promo->value / 100))
                        : max(0, $f->price_per_hour - (int)$promo->value);
                @endphp
                <span class="text-xl font-bold text-red-500">Rp{{ number_format($discountedPrice, 0, ',', '.') }}/jam</span>
                <span class="text-sm text-gray-400 line-through">Rp{{ number_format($f->price_per_hour, 0, ',', '.') }}/jam</span>
            </div>
            @guest
<a href="javascript:void(0)" onclick="showLoginPopup()" class="block w-full bg-red-500 text-white text-center py-md rounded-full font-label-md font-bold shadow-lg shadow-red-500/20 hover:bg-red-600 transition-all no-underline">
@else
<a href="{{ route('booking.show', $f->id) }}" class="block w-full bg-red-500 text-white text-center py-md rounded-full font-label-md font-bold shadow-lg shadow-red-500/20 hover:bg-red-600 transition-all no-underline">
@endguest
                Pesan Sekarang
            </a>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="text-center py-xl">
    <p class="text-secondary">Belum ada promo aktif saat ini.</p>
</div>
@endif
</div>
</section>
<!-- CTA Section -->
<section class="py-xl relative">
<div class="container mx-auto px-margin-mobile md:px-margin-desktop">
<div class="relative bg-primary rounded-xl p-xl overflow-hidden shadow-2xl shadow-primary/30">
<div class="absolute top-0 right-0 w-1/2 h-full opacity-20 pointer-events-none">
<img alt="" class="w-full h-full object-contain scale-150 rotate-12" src="{{ asset('assets/images/bg/explore5.png') }}">
</div>
<div class="relative z-10 max-w-[600px] text-on-primary">
<h2 class="font-display-lg text-display-lg md:text-[48px] leading-tight mb-md">
                        Siap Untuk Berkeringat Hari Ini?
                    </h2>
<p class="font-body-lg text-body-lg mb-xl opacity-90">
                        Gabung dengan 10.000+ penggiat olahraga lainnya. Rasakan pengalaman berolahraga yang lebih modern, sosial, dan seru.
                    </p>
<a href="{{ route('choose.role') }}" class="bg-white text-primary px-xl py-md rounded-full font-headline-md shadow-xl hover:scale-105 active:scale-95 transition-all">
                        Daftar Sekarang Secara Gratis
                    </a>
</div>
<!-- Abstract Pattern Overlay -->
<div class="absolute inset-0 bg-gradient-to-r from-primary via-primary to-transparent pointer-events-none"></div>
</div>
</div>
</section>
@include('partials.footer')
<!-- Login Popup -->
<div id="loginPopup" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <div id="popupOverlay" class="absolute inset-0 bg-black/50"></div>
    <div class="relative bg-white rounded-2xl p-xl max-w-md w-full mx-4 shadow-2xl text-center">
        <span class="material-symbols-outlined text-6xl text-primary mb-md" style="font-variation-settings: 'FILL' 1;">lock</span>
        <h3 class="font-headline-md text-headline-md mb-sm">Masuk Dulu Yuk!</h3>
        <p class="text-secondary mb-lg">Kamu perlu login untuk bisa lihat jadwal dan booking lapangan.</p>
        <a href="{{ route('login') }}" class="block w-full bg-primary text-on-primary py-md rounded-full font-label-md mb-md hover:brightness-110 transition-all">Masuk Sekarang</a>
        <button id="closePopupBtn" class="text-secondary hover:text-primary transition-all font-label-md">Nanti Aja</button>
    </div>
</div>
<!-- FAQ BOT - Floating Icon -->
<div id="faqFab" onclick="toggleFaqPopup()" style="position:fixed; bottom:24px; right:24px; width:56px; height:56px; background:#EB5436; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 4px 20px rgba(235,84,54,.4); z-index:999; transition:all .3s ease;">
    <span class="material-symbols-outlined" style="font-size:28px; font-variation-settings:'FILL' 1;">live_help</span>
</div>

<!-- FAQ POPUP -->
<div id="faqPopup" style="display:none; position:fixed; bottom:90px; right:24px; width:340px; max-width:calc(100vw - 48px); background:white; border-radius:20px; box-shadow:0 20px 60px rgba(0,0,0,.2); z-index:1000; overflow:hidden;">
    <div style="background:#EB5436; color:white; padding:16px 20px; display:flex; align-items:center; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:10px;">
            <span class="material-symbols-outlined" style="font-size:22px; font-variation-settings:'FILL' 1;">support_agent</span>
            <span style="font-weight:700; font-size:15px;">Pusat Bantuan</span>
        </div>
        <span onclick="toggleFaqPopup()" style="cursor:pointer; font-size:20px; line-height:1;">&times;</span>
    </div>
    <div style="padding:16px 20px;">
        <p style="font-size:13px; color:#666; margin-bottom:12px;">Ada yang bisa kami bantu?</p>
        <div onclick="faqAnswer('booking')" style="padding:12px 14px; border-radius:12px; border:1px solid rgba(0,0,77,.08); margin-bottom:8px; cursor:pointer; transition:all .2s; display:flex; align-items:center; gap:10px;" onmouseover="this.style.borderColor='#EB5436';this.style.background='#fff5f2'" onmouseout="this.style.borderColor='rgba(0,0,77,.08)';this.style.background='transparent'">
            <span class="material-symbols-outlined" style="color:#EB5436; font-size:20px;">calendar_month</span>
            <div><div style="font-weight:700; font-size:13px; color:#02025b;">Cara Booking</div><div style="font-size:11px; color:#888;">Panduan memesan lapangan</div></div>
        </div>
        <div onclick="faqAnswer('join_match')" style="padding:12px 14px; border-radius:12px; border:1px solid rgba(0,0,77,.08); margin-bottom:8px; cursor:pointer; transition:all .2s; display:flex; align-items:center; gap:10px;" onmouseover="this.style.borderColor='#EB5436';this.style.background='#fff5f2'" onmouseout="this.style.borderColor='rgba(0,0,77,.08)';this.style.background='transparent'">
            <span class="material-symbols-outlined" style="color:#EB5436; font-size:20px;">group_add</span>
            <div><div style="font-weight:700; font-size:13px; color:#02025b;">Cara Gabung Pertandingan Umum</div><div style="font-size:11px; color:#888;">Bergabung pertandingan publik</div></div>
        </div>
        <div onclick="faqAnswer('payment')" style="padding:12px 14px; border-radius:12px; border:1px solid rgba(0,0,77,.08); margin-bottom:8px; cursor:pointer; transition:all .2s; display:flex; align-items:center; gap:10px;" onmouseover="this.style.borderColor='#EB5436';this.style.background='#fff5f2'" onmouseout="this.style.borderColor='rgba(0,0,77,.08)';this.style.background='transparent'">
            <span class="material-symbols-outlined" style="color:#EB5436; font-size:20px;">payments</span>
            <div><div style="font-weight:700; font-size:13px; color:#02025b;">Cara Pembayaran</div><div style="font-size:11px; color:#888;">Informasi metode pembayaran</div></div>
        </div>
        <div onclick="faqAnswer('cs')" style="padding:12px 14px; border-radius:12px; border:1px solid rgba(0,0,77,.08); cursor:pointer; transition:all .2s; display:flex; align-items:center; gap:10px;" onmouseover="this.style.borderColor='#EB5436';this.style.background='#fff5f2'" onmouseout="this.style.borderColor='rgba(0,0,77,.08)';this.style.background='transparent'">
            <span class="material-symbols-outlined" style="color:#EB5436; font-size:20px;">headset_mic</span>
            <div><div style="font-weight:700; font-size:13px; color:#02025b;">Hubungi Customer Service</div><div style="font-size:11px; color:#888;">Chat dengan admin via WhatsApp</div></div>
        </div>
    </div>
    <div id="faqAnswerBox" style="display:none; padding:16px 20px; border-top:1px solid rgba(0,0,77,.06); background:#f8fafc;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
            <span style="font-weight:700; font-size:13px; color:#02025b;" id="faqAnswerTitle"></span>
            <span onclick="closeFaqAnswer()" style="cursor:pointer; font-size:16px; color:#999;">&times;</span>
        </div>
        <p style="font-size:13px; color:#555; line-height:1.6;" id="faqAnswerText"></p>
    </div>
</div>

<script>
    function toggleFaqPopup() {
        const popup = document.getElementById('faqPopup');
        popup.style.display = popup.style.display === 'none' ? 'block' : 'none';
        document.getElementById('faqAnswerBox').style.display = 'none';
    }

    function faqAnswer(type) {
        const titleEl = document.getElementById('faqAnswerTitle');
        const textEl = document.getElementById('faqAnswerText');
        const boxEl = document.getElementById('faqAnswerBox');

        const answers = {
            booking: {
                title: 'Cara Booking',
                text: '1. Pilih lapangan yang kamu inginkan.\n2. Pilih tanggal dan jam yang tersedia.\n3. Klik "Pesan" dan ikuti instruksi pembayaran.\n4. Laporkan pembayaran ke owner untuk konfirmasi.\n5. Setelah dikonfirmasi, booking kamu aktif!'
            },
            join_match: {
                title: 'Cara Gabung Pertandingan Umum',
                text: '1. Buka halaman "Cari Tim".\n2. Geser kartu pertandingan yang tersedia.\n3. Klik "Bergabung" pada pertandingan yang diinginkan.\n4. Lanjutkan pembayaran kontribusi jika ada.\n5. Tunggu konfirmasi dari host pertandingan.'
            },
            payment: {
                title: 'Cara Pembayaran',
                text: 'Pembayaran dilakukan dengan transfer ke rekening owner lapangan. Setelah transfer, laporkan pembayaran melalui halaman detail booking. Owner akan mengkonfirmasi pembayaran kamu.'
            },
            cs: {
                title: 'Hubungi Customer Service',
                text: 'Kamu akan diarahkan ke WhatsApp admin. Klik tombol di bawah untuk memulai chat.'
            }
        };

        const answer = answers[type];
        if (!answer) return;

        titleEl.textContent = answer.title;
        textEl.textContent = answer.text;
        boxEl.style.display = 'block';

        if (type === 'cs') {
            textEl.style.display = 'none';
            boxEl.innerHTML = '<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;"><span style="font-weight:700; font-size:13px; color:#02025b;">Hubungi Customer Service</span><span onclick="closeFaqAnswer()" style="cursor:pointer; font-size:16px; color:#999;">&times;</span></div><p style="font-size:13px; color:#555; margin-bottom:12px;">Kamu akan dihubungkan dengan admin kami melalui WhatsApp.</p><a href="https://wa.me/6281234567890?text=Halo%20Spies%20Sport%2C%20saya%20butuh%20bantuan" target="_blank" style="display:block; text-align:center; background:#25D366; color:white; padding:12px; border-radius:12px; font-weight:700; text-decoration:none;"><span style="margin-right:8px;">&#x1F4AC;</span> Chat WhatsApp</a>';
        } else {
            textEl.style.display = 'block';
        }
    }

    function closeFaqAnswer() {
        document.getElementById('faqAnswerBox').style.display = 'none';
    }
</script>

<script>
        // Reveal on Scroll
        function reveal() {
            var reveals = document.querySelectorAll(".reveal");
            for (var i = 0; i < reveals.length; i++) {
                var windowHeight = window.innerHeight;
                var elementTop = reveals[i].getBoundingClientRect().top;
                var elementVisible = 150;
                if (elementTop < windowHeight - elementVisible) {
                    reveals[i].classList.add("active");
                }
            }
        }

        window.addEventListener("scroll", reveal);
        // Initial check
        reveal();

        // Navbar scroll
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('mainNav');
            nav.classList.toggle('scrolled', window.scrollY > 50);
        });

        // Subtle parallax for character-guide
        window.addEventListener('mousemove', (e) => {
            const character = document.getElementById('character-guide');
            const amount = 20;
            const x = (e.clientX / window.innerWidth - 0.5) * amount;
            const y = (e.clientY / window.innerHeight - 0.5) * amount;
            character.style.transform = `translate(${x}px, ${y}px)`;
        });

        // Mobile Nav Toggle
        function toggleMobileNav() {
            const menu = document.getElementById('mobileNavMenu');
            const openIcon = document.getElementById('navOpenIcon');
            const closeIcon = document.getElementById('navCloseIcon');
            menu.classList.toggle('hidden');
            menu.classList.toggle('-translate-y-full');
            openIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        }

        // Close mobile nav on resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                const menu = document.getElementById('mobileNavMenu');
                if (!menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                    menu.classList.add('-translate-y-full');
                    document.getElementById('navOpenIcon').classList.remove('hidden');
                    document.getElementById('navCloseIcon').classList.add('hidden');
                }
            }
        });

        // Login Popup
        function showLoginPopup() {
            document.getElementById('loginPopup').classList.remove('hidden');
        }
        document.getElementById('popupOverlay').addEventListener('click', function() {
            document.getElementById('loginPopup').classList.add('hidden');
        });
        document.getElementById('closePopupBtn').addEventListener('click', function() {
            document.getElementById('loginPopup').classList.add('hidden');
        });
    </script>


</body></html>