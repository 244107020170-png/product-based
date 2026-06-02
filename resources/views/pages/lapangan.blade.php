<!DOCTYPE html><html class="light" lang="id"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Daftar Lapangan - Spies Sport</title>
@vite(['resources/css/pages.css', 'resources/js/app.js'])
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<style>
        .glass-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }
        .glass-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 24px 24px -4px rgba(186,0,19,0.1);
        }
        .custom-scrollbar::-webkit-scrollbar {
            height: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #ba0013;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-[#E0F2F1] font-body-md text-on-surface">
<!-- Top Navigation -->
<nav id="mainNav" class="fixed top-0 w-full z-50 transition-all duration-300">
<div class="flex items-center justify-between px-margin-desktop h-[80px] w-full max-w-[1440px] mx-auto">
<a href="{{ route('home') }}" class="flex items-center gap-3">
    <img src="{{ asset('assets/images/logo/logo3.png') }}" alt="Logo" class="h-8 w-auto">
    <span class="font-headline-md text-headline-md font-extrabold tracking-tight">
        <span style="color: #EB5436;">Spies</span> <span style="color: #00004D;">Sport</span>
    </span>
</a>
<div class="hidden md:flex gap-lg items-center">
<a class="text-secondary font-medium hover:text-primary transition-all duration-300" href="{{ route('home') }}">Beranda</a>
<a class="text-primary font-bold border-b-2 border-primary transition-all duration-300" href="{{ route('lapangan') }}">Lapangan</a>
<a class="text-secondary font-medium hover:text-primary transition-all duration-300" href="{{ route('komunitas') }}">Komunitas</a>
<a class="text-secondary font-medium hover:text-primary transition-all duration-300" href="{{ route('bantuan') }}">Bantuan</a>
</div>
<div class="flex gap-md items-center">
<a href="{{ route('login') }}" class="hidden sm:block font-label-md text-secondary hover:text-primary active:scale-95 transition-all">Masuk</a>
<a href="{{ route('choose.role') }}" class="bg-primary text-on-primary px-lg py-sm rounded-full font-label-md shadow-lg shadow-primary/20 hover:brightness-110 active:scale-95 transition-all">Daftar</a>
</div>
</div>
</nav>
<main class="pt-[120px] pb-xl px-margin-mobile md:px-margin-desktop max-w-[1440px] mx-auto">
<!-- Hero / Greeting Header -->
<header class="glass-card rounded-lg p-lg mb-lg flex flex-col md:flex-row items-center justify-between overflow-hidden relative">
<div class="z-10 flex flex-col gap-sm">
<h1 class="font-display-lg text-display-lg text-primary">Cari Lapangan Favoritmu!</h1>
<p class="font-body-lg text-body-lg text-secondary max-w-xl">Ayo olahraga hari ini! Temukan berbagai jenis lapangan olahraga dengan fasilitas terbaik di sekitarmu.</p>
<div class="mt-md flex flex-wrap gap-sm">
<div class="flex items-center gap-xs text-primary font-bold">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">verified</span>
<span class="text-label-md">Fasilitas Lengkap</span>
</div>
<div class="flex items-center gap-xs text-primary font-bold">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">payments</span>
<span class="text-label-md">Harga Transparan</span>
</div>
</div>
</div>
<div class="mt-md md:mt-0 flex items-center justify-center relative w-[240px] h-[240px]">
<!-- Character Illustration -->
<img alt="Spies Sport Characters" class="w-full h-auto drop-shadow-2xl" src="{{ asset('assets/images/characters/player3.png') }}">
<!-- Abstract floating blobs for depth -->
<div class="absolute -z-10 w-48 h-48 bg-primary/10 blur-[60px] rounded-full top-0 right-0"></div>
</div>
</header>
<!-- Search and Filter Section -->
<form method="GET" action="{{ route('lapangan') }}" class="mb-lg space-y-md">
<div class="flex flex-col md:flex-row gap-md">
<div class="flex-1 relative">
<span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-secondary">search</span>
<input name="search" value="{{ request('search') }}" class="w-full pl-[56px] pr-md py-md rounded-full bg-white/60 border border-secondary/20 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all font-body-md" placeholder="Cari nama lapangan atau lokasi..." type="text">
</div>
<div class="flex gap-sm">
<select name="type" onchange="this.form.submit()" class="px-lg py-md rounded-full bg-white/60 border border-secondary/20 text-secondary hover:text-primary transition-all font-label-md outline-none appearance-none cursor-pointer">
<option value="">Semua Olahraga</option>
@foreach($types as $t)
<option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ $t }}</option>
@endforeach
</select>
<select name="sort" onchange="this.form.submit()" class="px-lg py-md rounded-full bg-white/60 border border-secondary/20 text-secondary hover:text-primary transition-all font-label-md outline-none appearance-none cursor-pointer">
<option value="terbaru" {{ request('sort', 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
<option value="termurah" {{ request('sort') === 'termurah' ? 'selected' : '' }}>Termurah</option>
<option value="ternilai" {{ request('sort') === 'ternilai' ? 'selected' : '' }}>Nilai Tertinggi</option>
<option value="terlama" {{ request('sort') === 'terlama' ? 'selected' : '' }}>Terlama</option>
</select>
</div>
</div>
<!-- Categories Chips -->
<div class="flex gap-sm overflow-x-auto pb-sm custom-scrollbar whitespace-nowrap">
<a href="{{ route('lapangan') }}" class="px-lg py-sm rounded-full {{ !request('type') ? 'bg-primary text-on-primary' : 'bg-white/40 border border-white/60 text-secondary hover:bg-white/60' }} font-label-md transition-all no-underline">Semua</a>
@foreach($types as $t)
<a href="{{ route('lapangan', ['type' => $t, 'search' => request('search'), 'sort' => request('sort')]) }}" class="px-lg py-sm rounded-full {{ request('type') === $t ? 'bg-primary text-on-primary' : 'bg-white/40 border border-white/60 text-secondary hover:bg-white/60' }} font-label-md transition-all no-underline">{{ $t }}</a>
@endforeach
</div>
</form>
<!-- Fields Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-md">
@forelse($fields as $f)
<div class="glass-card rounded-lg overflow-hidden flex flex-col group">
<div class="relative h-[200px]">
<img class="w-full h-full object-cover" alt="{{ $f->name }}" src="{{ $f->image_url ?? 'https://via.placeholder.com/400x300?text=' . urlencode($f->name) }}" onerror="this.style.display='none'">
<div class="absolute top-sm right-sm bg-primary/90 text-on-primary px-sm py-xs rounded-full text-label-sm flex items-center gap-xs">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                        {{ number_format($f->rating ?? 0, 1) }}
                    </div>
<div class="absolute bottom-sm left-sm bg-white/80 backdrop-blur-md px-sm py-xs rounded-full text-label-sm text-primary font-bold">{{ $f->type ?? 'Olahraga' }}</div>
</div>
<div class="p-md flex flex-col flex-1 gap-sm">
<h3 class="font-headline-md text-primary truncate">{{ $f->name }}</h3>
<div class="flex items-center gap-xs text-secondary text-label-md">
<span class="material-symbols-outlined text-[18px]">location_on</span>
                        {{ $f->location ?: 'Lokasi tidak tersedia' }}
                    </div>
<div class="mt-auto pt-md flex items-center justify-between">
<div class="flex flex-col">
<span class="text-label-sm text-secondary uppercase tracking-wider">MULAI DARI</span>
<span class="text-title-lg text-primary font-bold">Rp {{ number_format($f->price_per_hour, 0, ',', '.') }}<span class="text-label-sm font-normal text-secondary">/jam</span></span>
</div>
</div>
<a href="{{ route('booking.show', $f->id) }}" class="block w-full mt-sm py-sm rounded-full border-2 border-primary text-primary font-label-md text-center hover:bg-primary hover:text-on-primary transition-all active:scale-95 no-underline">Cek Jadwal</a>
</div>
</div>
@empty
<div class="col-span-full text-center py-xl">
<p class="text-secondary text-body-lg">Tidak ada lapangan ditemukan.</p>
</div>
@endforelse
</div>
<!-- Pagination -->
@if($fields->hasPages())
<div class="mt-xl">
{{ $fields->links() }}
</div>
@endif
</main>
@include('partials.footer')
<script>
        // Navbar scroll effect
        const mainNav = document.getElementById('mainNav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                mainNav.classList.add('scrolled');
            } else {
                mainNav.classList.remove('scrolled');
            }
        });

        // Micro-interaction for buttons and cards
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('mousedown', () => {
                button.classList.add('scale-95');
            });
            button.addEventListener('mouseup', () => {
                button.classList.remove('scale-95');
            });
            button.addEventListener('mouseleave', () => {
                button.classList.remove('scale-95');
            });
        });
    </script>


</body></html>