<!DOCTYPE html><html class="light" lang="id"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Komunitas &amp; Pertandingan | Spies Sport</title>
@vite(['resources/css/pages.css', 'resources/js/app.js'])
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<style>
        body {
            background-color: #E0F2F1;
            background-image: radial-gradient(at 0% 0%, rgba(186, 0, 19, 0.05) 0px, transparent 50%),
                              radial-gradient(at 100% 100%, rgba(38, 102, 88, 0.05) 0px, transparent 50%);
            min-height: 100vh;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .glass-card:hover {
            transform: translateY(-8px);
        }
        .red-glow {
            box-shadow: 0 24px 24px -4px rgba(186, 0, 19, 0.1);
        }
        .swipe-card {
            touch-action: none;
            transition: transform 0.3s ease-out;
        }
        .character-overlay {
            pointer-events: none;
            filter: drop-shadow(0 12px 24px rgba(0,0,0,0.1));
        }
    </style>
</head>
<body class="font-body-md text-on-surface">
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
<a class="text-secondary font-medium hover:text-primary transition-all duration-300" href="{{ route('home') }}">Beranda</a>
<a class="text-secondary font-medium hover:text-primary transition-all duration-300" href="{{ route('lapangan') }}">Lapangan</a>
<a class="text-primary font-bold border-b-2 border-primary py-2 transition-all duration-300" href="{{ route('komunitas') }}">Komunitas</a>
<a class="text-secondary font-medium hover:text-primary transition-all duration-300" href="{{ route('bantuan') }}">Bantuan</a>
</div>
<div class="flex items-center gap-md">
<a href="{{ route('login') }}" class="hidden sm:block font-label-md text-secondary hover:text-primary active:scale-95 transition-all">Masuk</a>
<a href="{{ route('choose.role') }}" class="bg-primary text-on-primary px-lg py-sm rounded-full font-label-md shadow-lg shadow-primary/20 hover:brightness-110 active:scale-95 transition-all">Daftar</a>
</div>
</div>
</nav>
<main class="pt-[120px] pb-xl px-margin-desktop max-w-[1440px] mx-auto overflow-hidden">
<!-- Hero & Match Swipe Preview -->
<section class="grid grid-cols-12 gap-gutter mb-xl relative">
<div class="col-span-12 lg:col-span-5 flex flex-col justify-center">
<span class="text-primary font-label-md uppercase tracking-widest mb-sm">Arena Publik</span>
<h1 class="font-display-lg text-display-lg text-on-surface mb-md">Temukan Lawan, Bangun <span class="text-primary">Legenda</span>.</h1>
<p class="font-body-lg text-body-lg text-secondary mb-lg leading-relaxed">
                    Swipe untuk menemukan pertandingan publik yang sesuai dengan level permainanmu. Bergabunglah dengan ribuan pemain di seluruh Indonesia.
                </p>
    <div class="flex gap-md">
<a href="{{ route('login') }}" class="flex-1 border-2 border-primary text-primary py-sm rounded-full font-label-md hover:bg-primary/5 transition-colors no-underline text-center">Tolak</a>
<a href="{{ route('login') }}" class="flex-1 bg-primary text-on-primary py-sm rounded-full font-label-md red-glow no-underline text-center">Gabung!</a>
</div>
</div>
<!-- Swipe Preview Card -->
<div class="col-span-12 lg:col-span-7 flex justify-center items-center relative py-lg">
<div class="relative w-full max-w-[400px] h-[520px]">
<!-- Background Cards for stacking effect -->
<div class="absolute inset-0 glass-card rounded-xl rotate-6 translate-x-4 opacity-30"></div>
<div class="absolute inset-0 glass-card rounded-xl -rotate-3 -translate-x-4 opacity-50"></div>
<!-- Active Swipe Card -->
<div class="absolute inset-0 glass-card rounded-xl p-md flex flex-col shadow-2xl swipe-card" id="swipe-card">
<div class="relative w-full h-[260px] rounded-lg overflow-hidden mb-md">
<img class="w-full h-full object-cover" data-alt="A professional futsal court interior with high-performance lighting and polished wooden floors. The atmosphere is energetic and premium, featuring the Spies Sport brand colors of vibrant red and soft teal. The lighting is crisp, highlighting the high-end sports facility and creating a professional user experience." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDBnUdalTjLvCYCUFCd8sIlwDuyXyqNl_ABLPICP4jJPVS3XZ5kA8dT-bl4-2Hfe9ui39LmORH1Z0AVuqothzMzBd3aqO4go9OW4o8C2veGvWS7JDGs-yPoSBMs7pxrpgDXvbOP10TqR84M5vwts6KdoqassHVkSBgnf1GTkjA4469TlkgEUXHxpgAFZvcQkkxjw8-149E3aD5-P8SJ9AHK7zuhjd8GVg3HnobPXBnpvotTbYRPDyxq_5w4SBEDJIPBrFuOFPfFX2RP">
<div class="absolute top-sm right-sm bg-primary/90 text-on-primary px-sm py-xs rounded-full font-label-sm backdrop-blur-md">Sedang Berlangsung</div>
</div>
<div class="flex flex-col gap-xs">
<div class="flex justify-between items-start">
<h3 class="font-headline-md text-headline-md">Futsal Pro Series</h3>
<span class="text-primary font-bold text-headline-md">Rp 50k</span>
</div>
<div class="flex items-center gap-sm text-secondary">
<span class="material-symbols-outlined text-[18px]">location_on</span>
<span class="font-label-md">Velo Arena, Jakarta Selatan</span>
</div>
<div class="flex items-center gap-sm text-secondary mt-xs">
<span class="material-symbols-outlined text-[18px]">group</span>
<span class="font-label-md">8/10 Pemain • Kompetitif</span>
</div>
</div>
<div class="mt-auto flex gap-md">
<button class="flex-1 border-2 border-primary text-primary py-sm rounded-full font-label-md hover:bg-primary/5 transition-colors">Tolak</button>
<button class="flex-1 bg-primary text-on-primary py-sm rounded-full font-label-md red-glow">Gabung!</button>
</div>
</div>
</div>
</div>
</section>
<!-- Cari Teman Main Section (Bento Layout) -->
<section class="mb-xl">
<div class="flex justify-between items-end mb-lg">
<div>
<h2 class="font-headline-lg text-headline-lg">Cari Teman Main</h2>
<p class="text-secondary font-body-md">Koneksi cepat dengan pemain di sekitarmu.</p>
</div>
    <a href="{{ route('login') }}" class="text-primary font-label-md flex items-center gap-xs no-underline">Lihat Semua <span class="material-symbols-outlined">arrow_forward</span></a>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
<!-- Profile Card 1 -->
<div class="glass-card p-md rounded-xl flex flex-col items-center text-center">
<div class="w-24 h-24 rounded-full p-1 border-2 border-primary mb-md overflow-hidden bg-white">
<img class="w-full h-full object-cover rounded-full" data-alt="A portrait of a sporty, energetic young man in casual athletic wear. He is smiling warmly in an outdoor setting with soft, natural lighting. The image has a clean, premium health-conscious aesthetic with a minimalist background that aligns with a high-end wellness app style." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCpecOMZ0-CsLBhBp59fDU3zbqKj5AEE8wYoE_6nTynl16KPjEyLrg1aRbLMmXg_wOujvBd31oRLQpmEBecqbHrNBFvvx0ZCoWCBgPmYi5ALkCQAWg4pOI91D5hOVML3-o4qDgdwD2XxsBzhRJAn9IhJvjSp5Hhu1B1Pq8BGGM0SaOeIGDgD7EoFcmGPC3aNhIo1qJpMSJpdRMKkMlno4EUaP3TJ1XHNXdzflLDtfeeMTpFhO92vIZKYOV0qId0lpWztr-gdKdh_RqF">
</div>
<h4 class="font-title-lg text-title-lg">Andi Pratama</h4>
<p class="text-secondary font-label-md mb-md">Badminton • Level: Menengah</p>
    <div class="flex gap-sm w-full">
<a href="{{ route('login') }}" class="flex-1 bg-primary text-on-primary py-xs rounded-full font-label-md active:scale-95 transition-all no-underline text-center">Ajak Main</a>
<a href="{{ route('login') }}" class="w-10 h-10 flex items-center justify-center border border-outline rounded-full text-secondary hover:text-primary transition-colors no-underline">
<span class="material-symbols-outlined">chat</span>
</a>
</div>
</div>
<!-- Profile Card 2 -->
<div class="glass-card p-md rounded-xl flex flex-col items-center text-center">
<div class="w-24 h-24 rounded-full p-1 border-2 border-tertiary mb-md overflow-hidden bg-white">
<img class="w-full h-full object-cover rounded-full" data-alt="A professional close-up of a young woman in high-performance sports attire. She exudes confidence and a sense of wellness, set against a softly blurred, bright teal-tinted background. The lighting is high-key and optimistic, following a modern glassmorphism design language with clarity and precision." src="https://lh3.googleusercontent.com/aida-public/AB6AXuB8ST0jcDOTvsD4Ttg4KdGBlqUE_ORDJCM1y-TYYwENsulNz8caMWLL63GBi9Bg99SWW1a-_Tyrw-QGumab83dcj-0MUFy5D8-o1WmOcBdG29YiIkNSTQrMW_JiVg7sKwVxs1hKHGiLwhVNyfLROMsYEreB2C-owCcrLntWJCROVLV2eG0eF37bFDEHjQDjFkn5-edE_lMmuuYbsyhFnlzxywG5oS_LTqTNyeIAHWpNFZ4aHe7idxGzj_yd082nt7D8wwMuK9xd49gI">
</div>
<h4 class="font-title-lg text-title-lg">Siska Amelia</h4>
<p class="text-secondary font-label-md mb-md">Tennis • Level: Profesional</p>
<div class="flex gap-sm w-full">
<a href="{{ route('login') }}" class="flex-1 bg-primary text-on-primary py-xs rounded-full font-label-md active:scale-95 transition-all no-underline text-center">Ajak Main</a>
<a href="{{ route('login') }}" class="w-10 h-10 flex items-center justify-center border border-outline rounded-full text-secondary hover:text-primary transition-colors no-underline">
<span class="material-symbols-outlined">chat</span>
</a>
</div>
</div>
<!-- Recruitment Card -->
<a href="{{ route('login') }}" class="bg-primary/5 border-2 border-dashed border-primary/30 rounded-xl p-md flex flex-col items-center justify-center text-center group hover:bg-primary/10 transition-all no-underline">
<div class="w-16 h-16 rounded-full bg-primary text-on-primary flex items-center justify-center mb-md shadow-lg group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-[32px]">add</span>
</div>
<h4 class="font-title-lg text-title-lg text-primary">Cari Partner</h4>
<p class="text-secondary font-body-md mt-sm px-md">Buka rekrutmen untuk sesi olahraga yang kamu buat sendiri.</p>
</a>
</div>
</section>
<!-- Active Communities -->
<section class="mb-xl">
<h2 class="font-headline-lg text-headline-lg mb-lg">Komunitas Aktif</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
<!-- Community 1 -->
<a href="{{ route('login') }}" class="glass-panel p-sm rounded-xl flex items-center gap-md hover:border-primary/50 transition-colors no-underline">
<div class="w-14 h-14 bg-secondary-container rounded-lg flex items-center justify-center text-primary">
<span class="material-symbols-outlined text-[28px]">sports_soccer</span>
</div>
<div class="flex-1">
<h5 class="font-label-md">Jakarta Football Club</h5>
<p class="text-on-secondary-container font-label-sm">2,4k Anggota</p>
</div>
</a>
<!-- Community 2 -->
<a href="{{ route('login') }}" class="glass-panel p-sm rounded-xl flex items-center gap-md hover:border-primary/50 transition-colors no-underline">
<div class="w-14 h-14 bg-tertiary-fixed rounded-lg flex items-center justify-center text-tertiary">
<span class="material-symbols-outlined text-[28px]">sports_basketball</span>
</div>
<div class="flex-1">
<h5 class="font-label-md">Hoops Indonesia</h5>
<p class="text-on-tertiary-fixed-variant font-label-sm">1,8k Anggota</p>
</div>
</a>
<!-- Community 3 -->
<a href="{{ route('login') }}" class="glass-panel p-sm rounded-xl flex items-center gap-md hover:border-primary/50 transition-colors no-underline">
<div class="w-14 h-14 bg-secondary-container rounded-lg flex items-center justify-center text-primary">
<span class="material-symbols-outlined text-[28px]">sports_tennis</span>
</div>
<div class="flex-1">
<h5 class="font-label-md">Tennis Lovers Hub</h5>
<p class="text-on-secondary-container font-label-sm">950 Anggota</p>
</div>
</a>
<!-- Community 4 -->
<a href="{{ route('login') }}" class="glass-panel p-sm rounded-xl flex items-center gap-md hover:border-primary/50 transition-colors no-underline">
<div class="w-14 h-14 bg-tertiary-fixed rounded-lg flex items-center justify-center text-tertiary">
<span class="material-symbols-outlined text-[28px]">fitness_center</span>
</div>
<div class="flex-1">
<h5 class="font-label-md">Gym Rats Community</h5>
<p class="text-on-tertiary-fixed-variant font-label-sm">3,1k Anggota</p>
</div>
</a>
</div>
</section>
</main>
@include('partials.footer')
<script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('mainNav');
            nav.classList.toggle('scrolled', window.scrollY > 50);
        });

        // Simple Swipe Interaction Mockup
        const card = document.getElementById('swipe-card');
        let startX = 0;
        let currentX = 0;
        let isDragging = false;

        card.addEventListener('mousedown', (e) => {
            startX = e.clientX;
            isDragging = true;
            card.style.transition = 'none';
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            currentX = e.clientX - startX;
            const rotation = currentX / 20;
            card.style.transform = `translateX(${currentX}px) rotate(${rotation}deg)`;
        });

        document.addEventListener('mouseup', () => {
            if (!isDragging) return;
            isDragging = false;
            card.style.transition = 'transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            
            if (Math.abs(currentX) > 150) {
                // Animate out and then back (mockup reset)
                card.style.transform = `translateX(${currentX > 0 ? 1000 : -1000}px) rotate(${currentX / 10}deg)`;
                setTimeout(() => {
                    card.style.transform = 'translateX(0) rotate(0)';
                }, 1000);
            } else {
                card.style.transform = 'translateX(0) rotate(0)';
            }
            currentX = 0;
        });

        // Touch support
        card.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
            card.style.transition = 'none';
        });

        card.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            currentX = e.touches[0].clientX - startX;
            const rotation = currentX / 20;
            card.style.transform = `translateX(${currentX}px) rotate(${rotation}deg)`;
        });

        card.addEventListener('touchend', () => {
            if (!isDragging) return;
            isDragging = false;
            card.style.transition = 'transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            if (Math.abs(currentX) > 150) {
                card.style.transform = `translateX(${currentX > 0 ? 1000 : -1000}px) rotate(${currentX / 10}deg)`;
                setTimeout(() => {
                    card.style.transform = 'translateX(0) rotate(0)';
                }, 1000);
            } else {
                card.style.transform = 'translateX(0) rotate(0)';
            }
            currentX = 0;
        });
    </script>


</body></html>