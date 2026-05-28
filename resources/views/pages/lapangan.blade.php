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
<div class="mb-lg space-y-md">
<div class="flex flex-col md:flex-row gap-md">
<!-- Search Bar -->
<div class="flex-1 relative">
<span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-secondary">search</span>
<input class="w-full pl-[56px] pr-md py-md rounded-full bg-white/60 border border-secondary/20 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all font-body-md" placeholder="Cari nama lapangan atau lokasi..." type="text">
</div>
<!-- Dropdown Sort -->
<div class="flex gap-sm">
<button class="flex items-center gap-xs px-lg py-md rounded-full bg-white/60 border border-secondary/20 text-secondary hover:text-primary transition-all font-label-md">
<span class="material-symbols-outlined">filter_list</span>
                        Filter
                    </button>
<button class="flex items-center gap-xs px-lg py-md rounded-full bg-white/60 border border-secondary/20 text-secondary hover:text-primary transition-all font-label-md">
                        Urutkan
                        <span class="material-symbols-outlined">keyboard_arrow_down</span>
</button>
</div>
</div>
<!-- Categories Chips -->
<div class="flex gap-sm overflow-x-auto pb-sm custom-scrollbar whitespace-nowrap">
<button class="px-lg py-sm rounded-full bg-primary text-on-primary font-label-md">Semua</button>
<button class="px-lg py-sm rounded-full bg-white/40 border border-white/60 text-secondary font-label-md hover:bg-white/60 transition-all">Futsal</button>
<button class="px-lg py-sm rounded-full bg-white/40 border border-white/60 text-secondary font-label-md hover:bg-white/60 transition-all">Badminton</button>
<button class="px-lg py-sm rounded-full bg-white/40 border border-white/60 text-secondary font-label-md hover:bg-white/60 transition-all">Tennis</button>
<button class="px-lg py-sm rounded-full bg-white/40 border border-white/60 text-secondary font-label-md hover:bg-white/60 transition-all">Basket</button>
<button class="px-lg py-sm rounded-full bg-white/40 border border-white/60 text-secondary font-label-md hover:bg-white/60 transition-all">Voli</button>
<button class="px-lg py-sm rounded-full bg-white/40 border border-white/60 text-secondary font-label-md hover:bg-white/60 transition-all">Renang</button>
</div>
</div>
<!-- Fields Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-md">
<!-- Card 1 -->
<div class="glass-card rounded-lg overflow-hidden flex flex-col group">
<div class="relative h-[200px]">
<img class="w-full h-full object-cover" data-alt="A high-quality indoor futsal court with vibrant green artificial turf and bright overhead professional stadium lighting. The atmosphere is energetic and modern, featuring clean white lines and a high-end sports facility aesthetic with soft teal undertones. The scene captures the precision and premium quality of a professional sports venue." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDLTDV9I-cGlPqF4rdDnfEUtkohT6ShfDAafeGTbLMgv1FCx0ro8fUpslOqsKhUKlz2zHDEnATXB3VhxNE0bYX9Zfi9mEmiN-l_R72THNXDKm0xuuME4AHQtFB0M3omK8gCo_4-A3fzuer9r9a3XerK9z-hS-0E7e85rSOfRullT_g9r6TXyfA98YQAVsToz1rjW3DkATurnYxq-aPD4zZVY1qauRxjjxKualhLtBmmIk8oyk43x_zvbTrainYXIEk0EHkr5qTYKRgl">
<div class="absolute top-sm right-sm bg-primary/90 text-on-primary px-sm py-xs rounded-full text-label-sm flex items-center gap-xs">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                        4.8
                    </div>
<div class="absolute bottom-sm left-sm bg-white/80 backdrop-blur-md px-sm py-xs rounded-full text-label-sm text-primary font-bold">Futsal</div>
</div>
<div class="p-md flex flex-col flex-1 gap-sm">
<h3 class="font-headline-md text-primary truncate">Stadion Futsal Kencana</h3>
<div class="flex items-center gap-xs text-secondary text-label-md">
<span class="material-symbols-outlined text-[18px]">location_on</span>
                        Jakarta Selatan, DKI Jakarta
                    </div>
<div class="mt-auto pt-md flex items-center justify-between">
<div class="flex flex-col">
<span class="text-label-sm text-secondary uppercase tracking-wider">MULAI DARI</span>
<span class="text-title-lg text-primary font-bold">Rp 150.000<span class="text-label-sm font-normal text-secondary">/jam</span></span>
</div>
</div>
<button class="w-full mt-sm py-sm rounded-full border-2 border-primary text-primary font-label-md hover:bg-primary hover:text-on-primary transition-all active:scale-95">Cek Jadwal</button>
</div>
</div>
<!-- Card 2 -->
<div class="glass-card rounded-lg overflow-hidden flex flex-col group">
<div class="relative h-[200px]">
<img class="w-full h-full object-cover" data-alt="A professional indoor badminton court with multiple blue-surfaced courts and bright, even lighting. The visual style is crisp and technological, highlighting the clean lines of the nets and the vibrant indoor arena. The lighting is high-key and clean, suggesting a premium health-conscious and professional sports facility." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAS1AeU8tPZ4yeMJSwHVaFXrMKCoPXHwaq2EjbWxY6iQTYxcGECtEGfaHiHiKKDFe9xuLzuSJPl_HRstRGiWW2YUcXxFXbyeMWsWwvbiAFLTG9QL5q0ZtoyIPTc1W-6-6xKJKx4RiI6YMG3iM6uN3ccppHna13Nod4AYwIWhxhkrX1bPnAT2rX6Yp36A0KYoYuU29einYKn_Zg43oK0UmN4-rQwuH7R4Hduhuft1EUT9WzQJ0ikNmEVDsMWkwDbX0gQXw3iJrQ6dKwr">
<div class="absolute top-sm right-sm bg-primary/90 text-on-primary px-sm py-xs rounded-full text-label-sm flex items-center gap-xs">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                        4.9
                    </div>
<div class="absolute bottom-sm left-sm bg-white/80 backdrop-blur-md px-sm py-xs rounded-full text-label-sm text-primary font-bold">Badminton</div>
</div>
<div class="p-md flex flex-col flex-1 gap-sm">
<h3 class="font-headline-md text-primary truncate">Spies Shuttle Arena</h3>
<div class="flex items-center gap-xs text-secondary text-label-md">
<span class="material-symbols-outlined text-[18px]">location_on</span>
                        Tangerang, Banten
                    </div>
<div class="mt-auto pt-md flex items-center justify-between">
<div class="flex flex-col">
<span class="text-label-sm text-secondary uppercase tracking-wider">MULAI DARI</span>
<span class="text-title-lg text-primary font-bold">Rp 80.000<span class="text-label-sm font-normal text-secondary">/jam</span></span>
</div>
</div>
<button class="w-full mt-sm py-sm rounded-full border-2 border-primary text-primary font-label-md hover:bg-primary hover:text-on-primary transition-all active:scale-95">Cek Jadwal</button>
</div>
</div>
<!-- Card 3 -->
<div class="glass-card rounded-lg overflow-hidden flex flex-col group">
<div class="relative h-[200px]">
<img class="w-full h-full object-cover" data-alt="A bright, modern indoor basketball court with polished hardwood floors reflecting the bright arena lights. The setting is high-end and professional, featuring professional-grade hoops and clear court markings. The aesthetic is energetic and precise, with a clean light-mode atmosphere and vibrant red accents typical of elite sports venues." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDTgButrnbjsUp8y_DaHwc6CcXyKOTYngIFlFgWfpY7Xcdy-0fztMGmaocLyTR4YD1eB6WwQ8ZOMz6i-QN6u8JClA25f0vVDK18CNhEoJls_bPkrxG0FLTSz8MFvNmS4CV2Vuwv5dp_JGpn69xt62bUYxnk74w9o3Tv9ffz2Frsor-XT4JTVM0hmZ_pGXLExZQhWXNX9_DyQoluRN9NzRK22lxWKOWcRsAX-vdD6EemasbadutR1OttiHquMr4f6NNJPbikbRN7_-4l">
<div class="absolute top-sm right-sm bg-primary/90 text-on-primary px-sm py-xs rounded-full text-label-sm flex items-center gap-xs">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                        4.7
                    </div>
<div class="absolute bottom-sm left-sm bg-white/80 backdrop-blur-md px-sm py-xs rounded-full text-label-sm text-primary font-bold">Basket</div>
</div>
<div class="p-md flex flex-col flex-1 gap-sm">
<h3 class="font-headline-md text-primary truncate">Elite Hoops Center</h3>
<div class="flex items-center gap-xs text-secondary text-label-md">
<span class="material-symbols-outlined text-[18px]">location_on</span>
                        Jakarta Pusat, DKI Jakarta
                    </div>
<div class="mt-auto pt-md flex items-center justify-between">
<div class="flex flex-col">
<span class="text-label-sm text-secondary uppercase tracking-wider">MULAI DARI</span>
<span class="text-title-lg text-primary font-bold">Rp 200.000<span class="text-label-sm font-normal text-secondary">/jam</span></span>
</div>
</div>
<button class="w-full mt-sm py-sm rounded-full border-2 border-primary text-primary font-label-md hover:bg-primary hover:text-on-primary transition-all active:scale-95">Cek Jadwal</button>
</div>
</div>
<!-- Card 4 -->
<div class="glass-card rounded-lg overflow-hidden flex flex-col group">
<div class="relative h-[200px]">
<img class="w-full h-full object-cover" data-alt="A pristine outdoor clay tennis court at sunset, with soft golden hour light hitting the warm orange clay surface. The background features lush green landscaping and modern sports facilities. The mood is serene and premium, emphasizing a wellness-oriented sports experience with high-contrast slate-tinted text and vibrant red branding elements." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCepUrTfaYRwDFuMaGBPxcQj_fk81QlaIe6z_0E2tudCIkgUFEvV9-gDcwi16a63hRWsjGsHB9Z8FvTpwraejOYRTsDezNWCQDbNFmYW-tFyxXzJfAoE0mwDBW5cuIfDGDfZtCXztrxUk6VQOd3gm7n63GqeDnIgrckPyn11kR_IpPx3Mk8G_Gu5lneJt6QNNJbMTr6-iI87LsIH0Si76T14t6IqxMoM_bfMQOjhdn4qQy7SkSwpBB5_qFMa5EK8E2HqKk_fsXyxLd4">
<div class="absolute top-sm right-sm bg-primary/90 text-on-primary px-sm py-xs rounded-full text-label-sm flex items-center gap-xs">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                        4.5
                    </div>
<div class="absolute bottom-sm left-sm bg-white/80 backdrop-blur-md px-sm py-xs rounded-full text-label-sm text-primary font-bold">Tennis</div>
</div>
<div class="p-md flex flex-col flex-1 gap-sm">
<h3 class="font-headline-md text-primary truncate">Grand Slam Court</h3>
<div class="flex items-center gap-xs text-secondary text-label-md">
<span class="material-symbols-outlined text-[18px]">location_on</span>
                        Bandung, Jawa Barat
                    </div>
<div class="mt-auto pt-md flex items-center justify-between">
<div class="flex flex-col">
<span class="text-label-sm text-secondary uppercase tracking-wider">MULAI DARI</span>
<span class="text-title-lg text-primary font-bold">Rp 120.000<span class="text-label-sm font-normal text-secondary">/jam</span></span>
</div>
</div>
<button class="w-full mt-sm py-sm rounded-full border-2 border-primary text-primary font-label-md hover:bg-primary hover:text-on-primary transition-all active:scale-95">Cek Jadwal</button>
</div>
</div>
<!-- Card 5 -->
<div class="glass-card rounded-lg overflow-hidden flex flex-col group">
<div class="relative h-[200px]">
<img class="w-full h-full object-cover" data-alt="A high-end indoor volleyball court with specialized synthetic flooring and bright LED stadium lights. The space is clean and professional, with clear blue and white court markings. The atmosphere is focused and athletic, utilizing a sophisticated palette of deep slate and vibrant primary red highlights to emphasize high-performance data and elite training." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBJiiel1SYo-0NEovUK5GDekey5r0sQfhGAry_061WFlf60peT09rs7dm8w312ErAZpoChIIX2HiFj8zgDLXUEp-OY3RErblzDa8PBjS34xyWk1SJjapVWPE9yAsFYHdriS5zyNYkv-yFQnyKi4US8xbDBN5EzytMvB_YtwXFUnqWOovVdaFkVIv53RJkVkJdIbqLsCcBchHPzLHIaZlqRLUcUcY85Ogb3Ut1usEcsfBYx-FOQPVofBKScL3TPAd_lSbDCJTnpmJqr_">
<div class="absolute top-sm right-sm bg-primary/90 text-on-primary px-sm py-xs rounded-full text-label-sm flex items-center gap-xs">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                        4.6
                    </div>
<div class="absolute bottom-sm left-sm bg-white/80 backdrop-blur-md px-sm py-xs rounded-full text-label-sm text-primary font-bold">Voli</div>
</div>
<div class="p-md flex flex-col flex-1 gap-sm">
<h3 class="font-headline-md text-primary truncate">Volley Pro Hub</h3>
<div class="flex items-center gap-xs text-secondary text-label-md">
<span class="material-symbols-outlined text-[18px]">location_on</span>
                        Bekasi, Jawa Barat
                    </div>
<div class="mt-auto pt-md flex items-center justify-between">
<div class="flex flex-col">
<span class="text-label-sm text-secondary uppercase tracking-wider">MULAI DARI</span>
<span class="text-title-lg text-primary font-bold">Rp 95.000<span class="text-label-sm font-normal text-secondary">/jam</span></span>
</div>
</div>
<button class="w-full mt-sm py-sm rounded-full border-2 border-primary text-primary font-label-md hover:bg-primary hover:text-on-primary transition-all active:scale-95">Cek Jadwal</button>
</div>
</div>
<!-- Card 6 -->
<div class="glass-card rounded-lg overflow-hidden flex flex-col group">
<div class="relative h-[200px]">
<img class="w-full h-full object-cover" data-alt="A luxurious indoor swimming pool with crystal clear blue water and elegant architectural lighting. The scene is tranquil and high-end, featuring white stone surfaces and minimalist design. The atmosphere is one of serene wellness and professional precision, with soft teal accents and bright, clean reflections on the water's surface." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBUGeV_nl1wOBIfXHLzsHIM-j8DsF-M1FTNyOhw1yLjzMHEoC78dDxr2BPsa0V-Ogk_sACBWuiEVpD1bpCj_M6r_BNNpXdUsbDqeAev5LNmddIn1v6xAqZ4U-UKn3mQQ0fKbcYcyFM9LAf3IO6yFAk4izVYSWpd_SOobFjBKUDxWYayBehFWLepe0UPfz1VvDJxbe2eOaairgKIxzPL0bHBUGB7h8Cu4nfcS-We-nXp1dUitGEQ1-idjEYBbrvN1qN_2d89CAs0kFme">
<div class="absolute top-sm right-sm bg-primary/90 text-on-primary px-sm py-xs rounded-full text-label-sm flex items-center gap-xs">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                        4.9
                    </div>
<div class="absolute bottom-sm left-sm bg-white/80 backdrop-blur-md px-sm py-xs rounded-full text-label-sm text-primary font-bold">Renang</div>
</div>
<div class="p-md flex flex-col flex-1 gap-sm">
<h3 class="font-headline-md text-primary truncate">Aquatic Spies Club</h3>
<div class="flex items-center gap-xs text-secondary text-label-md">
<span class="material-symbols-outlined text-[18px]">location_on</span>
                        Jakarta Selatan, DKI Jakarta
                    </div>
<div class="mt-auto pt-md flex items-center justify-between">
<div class="flex flex-col">
<span class="text-label-sm text-secondary uppercase tracking-wider">MULAI DARI</span>
<span class="text-title-lg text-primary font-bold">Rp 60.000<span class="text-label-sm font-normal text-secondary">/jam</span></span>
</div>
</div>
<button class="w-full mt-sm py-sm rounded-full border-2 border-primary text-primary font-label-md hover:bg-primary hover:text-on-primary transition-all active:scale-95">Cek Jadwal</button>
</div>
</div>
</div>
<!-- Pagination -->
<div class="mt-xl flex justify-center items-center gap-sm">
<button class="w-10 h-10 rounded-full glass-card flex items-center justify-center text-secondary hover:text-primary transition-all">
<span class="material-symbols-outlined">chevron_left</span>
</button>
<button class="w-10 h-10 rounded-full bg-primary text-on-primary font-bold">1</button>
<button class="w-10 h-10 rounded-full glass-card text-secondary hover:text-primary font-bold transition-all">2</button>
<button class="w-10 h-10 rounded-full glass-card text-secondary hover:text-primary font-bold transition-all">3</button>
<span class="text-secondary">...</span>
<button class="w-10 h-10 rounded-full glass-card flex items-center justify-center text-secondary hover:text-primary transition-all">
<span class="material-symbols-outlined">chevron_right</span>
</button>
</div>
</main>
<!-- Footer -->
<footer class="bg-surface-container-low w-full rounded-t-lg border-t border-outline-variant/30">
<div class="flex flex-col md:flex-row justify-between items-center px-margin-desktop py-lg gap-md max-w-[1440px] mx-auto">
<div class="font-headline-md text-headline-md text-primary font-extrabold">Spies Sport</div>
<div class="flex gap-md">
<a class="text-on-surface-variant font-medium hover:text-primary underline-offset-4 hover:underline transition-all" href="{{ route('kebijakanpriv') }}">Kebijakan Privasi</a>
<a class="text-on-surface-variant font-medium hover:text-primary underline-offset-4 hover:underline transition-all" href="{{ route('layanan') }}">Ketentuan Layanan</a>
<a class="text-on-surface-variant font-medium hover:text-primary underline-offset-4 hover:underline transition-all" href="{{ route('contact') }}">Hubungi Kami</a>
<a class="text-on-surface-variant font-medium hover:text-primary underline-offset-4 hover:underline transition-all" href="{{ route('about') }}">Tentang Kami</a>
</div>
<div class="font-label-md text-label-md text-on-surface-variant">                ) 2024 Spies Sport. Tingkatkan permainanmu.
            </div>
</div>
</footer>
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