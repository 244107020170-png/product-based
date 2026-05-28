<!DOCTYPE html>

<html class="light" lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Kebijakan Privasi - Spies Sport</title>
@vite(['resources/css/pages.css', 'resources/js/app.js'])
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>


<style>
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .cartoon-blob {
            border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
            animation: morph 8s ease-in-out infinite;
        }
        @keyframes morph {
            0%, 100% { border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; }
            50% { border-radius: 60% 40% 30% 70% / 50% 60% 40% 60%; }
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-[#FDFBF7] text-on-surface font-body-md selection:bg-primary-container selection:text-white">
<!-- TopAppBar -->
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
<a class="text-secondary font-medium hover:text-primary transition-all duration-300" href="{{ route('komunitas') }}">Komunitas</a>
<a class="text-secondary font-medium hover:text-primary transition-all duration-300" href="{{ route('bantuan') }}">Bantuan</a>
</div>
<div class="flex items-center gap-md">
<a href="{{ route('login') }}" class="hidden sm:block font-label-md text-secondary hover:text-primary active:scale-95 transition-all">Masuk</a>
<a href="{{ route('choose.role') }}" class="bg-primary text-on-primary px-lg py-sm rounded-full font-label-md shadow-lg shadow-primary/20 hover:brightness-110 active:scale-95 transition-all">Daftar</a>
</div>
</div>
</nav>
<main class="pt-[120px] overflow-hidden">
<!-- Hero Section with Cartoonist Character -->
<section class="relative min-h-[500px] flex items-center px-margin-mobile md:px-margin-desktop bg-gradient-to-br from-[#FFF5F5] to-[#FDFBF7]">
<div class="absolute top-0 right-0 w-1/2 h-full opacity-10 pointer-events-none">
<div class="cartoon-blob bg-primary w-full h-full transform translate-x-1/4 -translate-y-1/4"></div>
</div>
<div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-lg items-center z-10">
<div class="space-y-md text-center md:text-left">
<span class="bg-primary-container/10 text-primary-container px-md py-xs rounded-full font-label-md text-label-md border border-primary-container/20">
                        Update Terakhir: 1 November 2024
                    </span>
<h1 class="font-display-lg text-headline-lg-mobile md:text-display-lg text-on-surface">Privasi Anda Adalah <span class="text-primary">Prioritas Kami.</span></h1>
<p class="font-body-lg text-body-lg text-secondary max-w-xl">
                        Di Spies Sport, kami menghargai kepercayaan Anda. Kami berkomitmen untuk melindungi data Anda sambil memberikan pengalaman olahraga terbaik yang personal dan aman.
                    </p>
</div>
<div class="relative flex justify-center items-center">
<div class="glass-card p-sm rounded-xl rotate-3 relative z-20 shadow-2xl">
<img alt="Friendly Sports Character" class="w-full max-w-[400px] rounded-lg shadow-inner" data-alt="A friendly, high-energy cartoonist character representing a modern athlete, wearing vibrant red and white sports gear. The character is smiling warmly with a thumbs up, set against a soft cream-colored background with subtle glassmorphic geometric shapes and floating sports icons like a stopwatch and a medal. The lighting is bright and cheerful, reflecting a professional yet approachable health-conscious brand aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuC3aYdoltHbvqmS8ZiInEtOcaYpWiGg9ivdeQvGhEiTaRk1RHbdnSnWFPoW4xvbLwGWlZ-pZlURpl9WwoJqD_c__UJGPiEqyJFlAWb6x562xL0DxYzIlgTCADFnrsTVOgf08Vm_b9BogmSY85reiMEWsstwbJGofHgaHOPt5tgFWGRu365ojUic-I4W7ALLfOWoQ-dm2RAI4TUyZoN5zx_SSsL1rIZ3mKOpxkZzu_AW9ZV7iahZWIvXXNzbDgXv21nSMn7Avr2eDuKt"/>
</div>
<!-- Floating decorations -->
<div class="absolute -top-10 -left-10 w-24 h-24 bg-tertiary-fixed rounded-full blur-2xl opacity-40"></div>
<div class="absolute -bottom-10 -right-10 w-32 h-32 bg-primary-fixed rounded-full blur-3xl opacity-30"></div>
</div>
</div>
</section>
<!-- Privacy Content - Bento Style Layout -->
<section class="py-xl px-margin-mobile md:px-margin-desktop max-w-7xl mx-auto">
<div class="grid grid-cols-1 md:grid-cols-3 gap-md">
<!-- Data Collection -->
<div class="md:col-span-2 glass-card p-lg rounded-lg border border-primary/10 hover:shadow-xl transition-all duration-300">
<div class="flex items-center gap-sm mb-md">
<div class="p-sm bg-primary/10 rounded-full text-primary">
<span class="material-symbols-outlined" data-icon="database">database</span>
</div>
<h2 class="font-headline-md text-headline-md">Informasi yang Kami Kumpulkan</h2>
</div>
<div class="grid md:grid-cols-2 gap-md">
<div class="space-y-sm">
<h3 class="font-title-lg text-title-lg text-primary">Data Identitas</h3>
<p class="text-secondary">Nama lengkap, alamat email, dan nomor telepon saat Anda mendaftar untuk memberikan akses personal ke platform kami.</p>
</div>
<div class="space-y-sm">
<h3 class="font-title-lg text-title-lg text-primary">Data Aktivitas</h3>
<p class="text-secondary">Statistik olahraga, riwayat pertandingan, dan preferensi tim favorit untuk menyajikan konten yang relevan bagi Anda.</p>
</div>
<div class="space-y-sm">
<h3 class="font-title-lg text-title-lg text-primary">Data Teknis</h3>
<p class="text-secondary">Alamat IP, jenis perangkat, dan data log aktivitas untuk meningkatkan keamanan dan performa aplikasi kami.</p>
</div>
<div class="space-y-sm">
<h3 class="font-title-lg text-title-lg text-primary">Kuki (Cookies)</h3>
<p class="text-secondary">Kami menggunakan kuki untuk mengingat preferensi Anda dan mempermudah proses masuk ke akun Anda.</p>
</div>
</div>
</div>
<!-- Usage of Data -->
<div class="bg-primary p-lg rounded-lg text-on-primary flex flex-col justify-between shadow-lg shadow-primary/20 relative overflow-hidden">
<span class="material-symbols-outlined text-[120px] absolute -bottom-10 -right-10 opacity-10" data-icon="security">security</span>
<div class="space-y-md relative z-10">
<div class="p-sm bg-white/20 w-fit rounded-full">
<span class="material-symbols-outlined" data-icon="hub">hub</span>
</div>
<h2 class="font-headline-md text-headline-md">Bagaimana Kami Menggunakan Data?</h2>
<ul class="space-y-sm font-body-md text-body-md opacity-90">
<li class="flex items-start gap-xs">
<span class="material-symbols-outlined text-sm pt-1" data-icon="check_circle" data-weight="fill">check_circle</span>
                                Menyediakan konten olahraga yang dipersonalisasi.
                            </li>
<li class="flex items-start gap-xs">
<span class="material-symbols-outlined text-sm pt-1" data-icon="check_circle" data-weight="fill">check_circle</span>
                                Mengirimkan notifikasi pertandingan penting.
                            </li>
<li class="flex items-start gap-xs">
<span class="material-symbols-outlined text-sm pt-1" data-icon="check_circle" data-weight="fill">check_circle</span>
                                Menganalisis tren untuk fitur baru.
                            </li>
</ul>
</div>
</div>
<!-- Security Commitment -->
<div class="md:col-span-1 glass-card p-lg rounded-lg border border-outline-variant/30 flex flex-col gap-md">
<div class="p-sm bg-tertiary-container/10 w-fit rounded-full text-tertiary-container">
<span class="material-symbols-outlined" data-icon="lock_open">lock_open</span>
</div>
<h2 class="font-headline-md text-headline-md">Keamanan Informasi</h2>
<p class="text-secondary font-body-md">
                        Kami menggunakan enkripsi tingkat militer (AES-256) untuk melindungi data sensitif Anda. Keamanan Anda adalah komitmen tanpa kompromi kami.
                    </p>
<a class="text-primary font-label-md flex items-center gap-xs hover:gap-sm transition-all" href="#">
                        Pelajari Protokol Kami <span class="material-symbols-outlined">arrow_forward</span>
</a>
</div>
<!-- Shared Data -->
<div class="md:col-span-2 glass-card p-lg rounded-lg border border-outline-variant/30 bg-gradient-to-r from-surface-container-low to-surface">
<div class="flex flex-col md:flex-row gap-lg items-center">
<div class="space-y-sm flex-1">
<h2 class="font-headline-md text-headline-md">Pembagian Data</h2>
<p class="text-secondary font-body-md">
                                Spies Sport <strong class="text-on-surface">tidak akan pernah menjual data pribadi Anda</strong> kepada pihak ketiga untuk tujuan pemasaran. Kami hanya membagikan data kepada mitra layanan (seperti penyedia database) yang terikat kontrak kerahasiaan ketat.
                            </p>
</div>
<div class="flex gap-sm">
<div class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center">
<span class="material-symbols-outlined text-primary text-3xl" data-icon="no_accounts">no_accounts</span>
</div>
<div class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center">
<span class="material-symbols-outlined text-tertiary text-3xl" data-icon="verified_user">verified_user</span>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- Interaction Section -->
<section class="py-xl bg-[#FFF9F0] px-margin-mobile md:px-margin-desktop">
<div class="max-w-4xl mx-auto glass-card p-xl rounded-xl text-center space-y-md border-primary/5">
<h2 class="font-headline-lg text-headline-lg">Ada Pertanyaan Mengenai Privasi?</h2>
<p class="font-body-lg text-body-lg text-secondary">
                    Tim perlindungan data kami siap membantu menjelaskan bagaimana informasi Anda dikelola.
                </p>
<div class="flex flex-col md:flex-row gap-md justify-center items-center pt-md">
<button class="bg-primary text-on-primary px-xl py-md rounded-full font-label-md text-label-md hover:shadow-lg hover:shadow-primary/30 transition-all active:scale-95">
                        Hubungi Tim Kami
                    </button>
<button class="bg-white border-2 border-outline-variant text-on-surface-variant px-xl py-md rounded-full font-label-md text-label-md hover:bg-surface-container-low transition-all">
                        Unduh PDF Kebijakan
                    </button>
</div>
</div>
</section>
</main>
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
<script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('mainNav');
            if (nav) nav.classList.toggle('scrolled', window.scrollY > 50);
        });
        // Simple micro-interaction for glass cards
        document.querySelectorAll('.glass-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-4px)';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });
    </script>
</body></html>