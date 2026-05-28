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
</div>
</div>
</nav>
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
<button class="bg-primary text-on-primary px-[32px] py-[16px] rounded-full font-label-md shadow-xl shadow-primary/25 hover:-translate-y-1 transition-all active:scale-95">
                        Main Sekarang
                    </button>
<button @guest onclick="showLoginPopup()" @endguest class="glass-card text-on-background px-[32px] py-[16px] rounded-full font-label-md border-white border hover:bg-white/80 transition-all active:scale-95 flex items-center justify-center gap-base">
                            <span class="material-symbols-outlined">search</span>
                        Cari Lapangan
                    </button>
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
<h2 class="font-headline-lg text-headline-lg">Smart Booking System</h2>
<p class="font-body-lg text-body-lg text-secondary">
                        Booking lapangan favoritmu tanpa drama. Cek jadwal real-time, pilih slot yang tersedia, dan bayar instan dalam hitungan detik.
                    </p>
</div>
<div class="flex-1 glass-card p-lg rounded-lg max-w-[500px]">
<div class="flex items-center justify-between mb-lg">
<h4 class="font-title-lg text-title-lg">Pilih Jadwal</h4>
<div class="text-primary font-bold">10 April 2026</div>
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
<h2 class="font-headline-lg text-headline-lg">Achievement Points</h2>
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
<button @guest onclick="showLoginPopup()" @endguest class="text-primary font-bold flex items-center gap-xs hover:gap-md transition-all">
                        Lihat Semua <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-md">
<!-- Field Card 1 -->
<div class="group relative bg-white rounded-lg overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
<div class="h-64 overflow-hidden relative">
<img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" data-alt="A premium high-end indoor tennis court with vibrant blue surfacing and crisp white lines. The court is illuminated by large floor-to-ceiling windows showing a lush green garden outside, creating a serene wellness atmosphere. Soft sunlight creates high-key lighting." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBZed3vUQN6cKUwnpeYlk03WDS2uHRPbpEAhjMXGhjJFsv04-I0UBWeM-MTB08Ui5EYBMyyT9GWPZUEs9HPx2F5CW0lqF5M5syafiFBuxodXmKYXahhnUV_nhTpho1KWNo2crORq93M7tI6iYyKchMDKYYulKhYq1zVTFtXK_sfUbLeR2oAvEIwwJ9C2POXhlPrR1Z9P0WtOeVGhMhHnr8YEvTISdbOwd-oJuTMASM0neoWWwY--MuZ-i194f9PLWk5_lWDXG5OPNYJ">
<div class="absolute top-md right-md bg-white/90 backdrop-blur-md px-md py-xs rounded-full font-label-sm flex items-center gap-xs">
<span class="material-symbols-outlined text-orange-400 text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            4.9
                        </div>
</div>
<div class="p-lg">
<div class="text-primary text-label-sm mb-xs">TENNIS</div>
<h5 class="font-title-lg text-title-lg mb-base">Royal Tennis Club</h5>
<div class="flex items-center gap-xs text-secondary mb-md">
<span class="material-symbols-outlined text-[16px]">location_on</span>
<span class="text-label-sm">Malang City Center</span>
</div>
<button @guest onclick="showLoginPopup()" @endguest class="w-full border-2 border-primary/20 text-primary py-md rounded-full font-label-md group-hover:bg-primary group-hover:text-white transition-all">
                            Cek Jadwal
                        </button>
</div>
</div>
<!-- Field Card 2 -->
<div class="group relative bg-white rounded-lg overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
<div class="h-64 overflow-hidden relative">
<img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" data-alt="A modern professional indoor futsal field featuring high-quality green synthetic turf and bright LED lighting. The walls are sleek grey with a glassmorphic gallery area overlooking the pitch. The atmosphere is energetic and clean, designed with high-performance sports aesthetics." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBD4ygR3kSUe6Z75atZ9tYg3dOzWtC0ZklNbyq_irKAbR3OxKuvCGWWkqoj-ZIkBpqr00mSYutq-2C7OeVSlEiWM2UXlLK7zn9MD-gQZKOuqSSenvNyLykNy43gvFOEyUByQxsB_5e0pfbAM_L8F3nqBJxG8rxX5mgipCrydmSk6o9cYiTsKZh4nQmo9PeOImpFCM7_kjHU8HNxJRo1LIjAYichFwBj8fUV0qG_5rnovDzeh2KDvNCutFn1jXUnje_yGOU4iKXB4_1P">
<div class="absolute top-md right-md bg-white/90 backdrop-blur-md px-md py-xs rounded-full font-label-sm flex items-center gap-xs">
<span class="material-symbols-outlined text-orange-400 text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            4.8
                        </div>
</div>
<div class="p-lg">
<div class="text-primary text-label-sm mb-xs">FUTSAL</div>
<h5 class="font-title-lg text-title-lg mb-base">Galaxy Futsal Arena</h5>
<div class="flex items-center gap-xs text-secondary mb-md">
<span class="material-symbols-outlined text-[16px]">location_on</span>
<span class="text-label-sm">Sukarno Hatta, Malang</span>
</div>
<button @guest onclick="showLoginPopup()" @endguest class="w-full border-2 border-primary/20 text-primary py-md rounded-full font-label-md group-hover:bg-primary group-hover:text-white transition-all">
                            Cek Jadwal
                        </button>
</div>
</div>
<!-- Field Card 3 -->
<div class="group relative bg-white rounded-lg overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
<div class="h-64 overflow-hidden relative">
<img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" data-alt="A professional indoor badminton court with multiple courts visible, featuring blue high-grip floors and high-performance overhead lighting. The space is vast and airy with a modern sports complex feel, using soft white and teal accents for a professional wellness environment." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCXNClnlkAKmHPMDswvU8as-OziGbjg9pqAjfQywnzanrPNzOj0otFpVThK0G3alYatIqXgZzXL3wM8t46WWERJFmO8OTulGOv33FeYRC8njFy1u7YwajWqqhpKm7-pRAx35quXz_FN4sEy1YquWrB8nqdVoGcWHZWhRgkvZhIFk356QMIMZl9sl-oAIeAvrfz3Ag0D6A3_8_Wbd3r2hE_idQHPMgSja4xDo6ar9IyOlISmJDajaQZ93VGrTUTYNQYeRcLu-naBTYuE">
<div class="absolute top-md right-md bg-white/90 backdrop-blur-md px-md py-xs rounded-full font-label-sm flex items-center gap-xs">
<span class="material-symbols-outlined text-orange-400 text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            4.7
                        </div>
</div>
<div class="p-lg">
<div class="text-primary text-label-sm mb-xs">BADMINTON</div>
<h5 class="font-title-lg text-title-lg mb-base">Smash Center</h5>
<div class="flex items-center gap-xs text-secondary mb-md">
<span class="material-symbols-outlined text-[16px]">location_on</span>
<span class="text-label-sm">Blimbing, Malang</span>
</div>
<button @guest onclick="showLoginPopup()" @endguest class="w-full border-2 border-primary/20 text-primary py-md rounded-full font-label-md group-hover:bg-primary group-hover:text-white transition-all">
                            Cek Jadwal
                        </button>
</div>
</div>
</div>
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
<!-- Footer -->
<footer class="bg-surface-container-low border-t border-outline-variant/30 w-full rounded-t-lg">
<div class="flex flex-col md:flex-row justify-between items-center px-margin-mobile md:px-margin-desktop py-lg gap-md max-w-[1440px] mx-auto">
<div class="space-y-sm text-center md:text-left">
<div class="font-headline-md text-headline-md text-primary">Spies Sport</div>
<p class="font-label-md text-on-surface-variant max-w-[300px]">© 2024 Spies Sport. Tingkatkan permainanmu di setiap langkah.</p>
</div>
<div class="flex gap-lg flex-wrap justify-center">
<a class="font-label-md text-on-surface-variant hover:text-primary hover:underline underline-offset-4 transition-all" href="{{ route('kebijakanpriv') }}">Kebijakan Privasi</a>
<a class="font-label-md text-on-surface-variant hover:text-primary hover:underline underline-offset-4 transition-all" href="{{ route('layanan') }}">Ketentuan Layanan</a>
<a class="font-label-md text-on-surface-variant hover:text-primary hover:underline underline-offset-4 transition-all" href="{{ route('contact') }}">Hubungi Kami</a>
<a class="font-label-md text-on-surface-variant hover:text-primary hover:underline underline-offset-4 transition-all" href="{{ route('about') }}">Tentang Kami</a>
</div>
<div class="flex gap-md">
<div class="w-10 h-10 rounded-full bg-white flex items-center justify-center border border-outline-variant/20 hover:text-primary transition-all cursor-pointer">
<span class="material-symbols-outlined">public</span>
</div>
<div class="w-10 h-10 rounded-full bg-white flex items-center justify-center border border-outline-variant/20 hover:text-primary transition-all cursor-pointer">
<span class="material-symbols-outlined">alternate_email</span>
</div>
</div>
</div>
</footer>
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