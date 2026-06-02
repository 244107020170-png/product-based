<!DOCTYPE html>

<html class="light" lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Ketentuan Layanan - Spies Sport</title>
@vite(['resources/css/pages.css', 'resources/js/app.js'])
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>

<style>
        body {
            background-color: #fdf8f1; /* Soft cream background */
            font-family: 'Poppins', sans-serif;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px 0 rgba(186, 0, 19, 0.05);
        }
        .cartoon-border {
            border: 3px solid #141b2c;
            box-shadow: 6px 6px 0px #ba0013;
        }
        .scribble-bg {
            background-image: radial-gradient(#e7bdb8 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="antialiased text-on-surface scribble-bg">
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
<main class="pt-xl mt-lg px-margin-mobile md:px-margin-desktop">
<!-- Hero Section with Character Illustration -->
<section class="flex flex-col md:flex-row items-center gap-lg mb-xl py-lg">
<div class="flex-1 space-y-md">
<span class="bg-primary/10 text-primary px-md py-xs rounded-full font-label-sm text-label-sm uppercase tracking-wider">Penting Banget!</span>
<h1 class="font-display-lg text-headline-lg md:text-display-lg text-on-surface leading-tight">
                    Main Bareng Aman, <br/><span class="text-primary">Nyaman &amp; Asik!</span>
</h1>
<p class="font-body-lg text-body-lg text-secondary max-w-xl">
                    Sebelum mulai nge-game atau booking lapangan favoritmu, yuk baca dulu aturan main di Spies Sport biar makin seru dan gak ada drama.
                </p>
</div>
<div class="flex-1 flex justify-center relative">
<div class="absolute inset-0 bg-primary/5 rounded-full blur-3xl -z-10"></div>
<img alt="Layanan" class="w-full max-w-[400px] object-contain drop-shadow-xl" src="{{ asset('assets/images/characters/priv.png') }}"/>
</div>
</section>
<!-- Bento Grid Content -->
<div class="grid grid-cols-1 md:grid-cols-12 gap-gutter">
<!-- Rules Usage -->
<div class="md:col-span-8 glass-card p-lg rounded-lg cartoon-border">
<div class="flex items-center gap-sm mb-md">
<span class="material-symbols-outlined text-primary text-[32px]">sports_handball</span>
<h2 class="font-headline-md text-headline-md text-on-surface">Aturan Main &amp; Penggunaan Platform</h2>
</div>
<div class="space-y-md font-body-md text-body-md text-secondary">
<p>1. <strong>Identitas Asli:</strong> Gunakan nama asli dan data yang valid saat mendaftar. Akun palsu akan kami 'kick' dari lapangan!</p>
<p>2. <strong>Sportivitas Digital:</strong> Dilarang keras melakukan spam, pelecehan, atau tindakan tidak menyenangkan lainnya kepada pengguna lain di platform Spies Sport.</p>
<p>3. <strong>Hak Milik:</strong> Semua konten, logo, dan teknologi di platform ini adalah milik Spies Sport. Jangan di-copy sembarangan ya!</p>
</div>
</div>
<!-- Fast Booking -->
<div class="md:col-span-4 bg-tertiary-container p-lg rounded-lg cartoon-border text-on-tertiary-container">
<span class="material-symbols-outlined text-[48px] mb-sm" style="font-variation-settings: 'FILL' 1;">calendar_today</span>
<h3 class="font-headline-md text-headline-md mb-sm">Prosedur Booking</h3>
<ul class="space-y-sm font-label-md text-label-md">
<li class="flex gap-xs items-start"><span class="material-symbols-outlined text-sm">check_circle</span> Pilih lapangan &amp; waktu yang tersedia.</li>
<li class="flex gap-xs items-start"><span class="material-symbols-outlined text-sm">check_circle</span> Konfirmasi pesanan &amp; lakukan pembayaran.</li>
<li class="flex gap-xs items-start"><span class="material-symbols-outlined text-sm">check_circle</span> Dapatkan kode QR untuk akses masuk!</li>
</ul>
</div>
<!-- Cancellation Policy -->
<div class="md:col-span-6 glass-card p-lg rounded-lg cartoon-border border-primary/20">
<div class="flex items-center gap-sm mb-md">
<span class="material-symbols-outlined text-primary text-[32px]">event_busy</span>
<h2 class="font-headline-md text-headline-md text-on-surface">Kebijakan Pembatalan</h2>
</div>
<div class="space-y-md font-body-md text-body-md text-secondary">
<div class="p-md bg-primary/5 rounded-md border-l-4 border-primary">
<p class="font-bold text-primary mb-xs">Batal H-24 Jam</p>
<p>Pengembalian dana (refund) 100% dalam bentuk saldo aplikasi (Spies Credit).</p>
</div>
<div class="p-md bg-secondary/5 rounded-md border-l-4 border-secondary">
<p class="font-bold text-on-surface mb-xs">Batal &lt; 24 Jam</p>
<p>Mohon maaf, dana tidak dapat dikembalikan namun jadwal bisa di-reschedule 1x sesuai ketersediaan.</p>
</div>
</div>
</div>
<!-- User Responsibilities -->
<div class="md:col-span-6 bg-primary text-white p-lg rounded-lg cartoon-border shadow-xl">
<div class="flex items-center gap-sm mb-md">
<span class="material-symbols-outlined text-white text-[32px]">verified_user</span>
<h2 class="font-headline-md text-headline-md">Tanggung Jawab Pengguna</h2>
</div>
<div class="grid grid-cols-2 gap-md font-label-md text-label-md">
<div class="bg-white/10 p-sm rounded-md backdrop-blur-sm">
<p class="font-bold">Keamanan Akun</p>
<p class="text-white/80 text-xs">Jaga password-mu seerat menjaga bola dari lawan!</p>
</div>
<div class="bg-white/10 p-sm rounded-md backdrop-blur-sm">
<p class="font-bold">Kerusakan Fasilitas</p>
<p class="text-white/80 text-xs">Main yang asik, jangan rusak properti lapangan ya.</p>
</div>
<div class="bg-white/10 p-sm rounded-md backdrop-blur-sm">
<p class="font-bold">Ketepatan Waktu</p>
<p class="text-white/80 text-xs">Datang 10 menit sebelum jam main dimulai.</p>
</div>
<div class="bg-white/10 p-sm rounded-md backdrop-blur-sm">
<p class="font-bold">Kebersihan</p>
<p class="text-white/80 text-xs">Sampahmu adalah tanggung jawabmu sendiri.</p>
</div>
</div>
</div>
</div>
<!-- Help Section -->
<section class="mt-xl glass-card rounded-lg p-xl flex flex-col md:flex-row items-center justify-between gap-lg cartoon-border border-tertiary">
<div class="text-center md:text-left">
<h2 class="font-headline-lg text-headline-lg text-on-surface mb-sm">Masih Bingung?</h2>
<p class="font-body-lg text-body-lg text-secondary">Tim support kami siap membantu 24/7 dengan gaya yang paling asik!</p>
</div>
<div class="flex gap-md">
<a href="https://wa.me/6281234567890" target="_blank" class="bg-primary text-white px-lg py-md rounded-full font-label-md text-label-md cartoon-border hover:translate-y-[-4px] transition-all no-underline">
                    Chat WhatsApp
                </a>
<a href="mailto:halo@spiessport.id" class="bg-secondary text-white px-lg py-md rounded-full font-label-md text-label-md cartoon-border hover:translate-y-[-4px] transition-all no-underline">
                    Kirim Email
                </a>
</div>
</section>
</main>
@include('partials.footer')
<!-- FAB for Quick Support -->
<button class="fixed bottom-gutter right-gutter bg-primary-container text-on-primary-container w-16 h-16 rounded-full flex items-center justify-center shadow-lg active:scale-90 transition-transform z-50 cartoon-border border-white" id="fab" onclick="window.location.href='https://wa.me/6281234567890'">
<span class="material-symbols-outlined text-[32px]">question_answer</span>
</button>
<script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('mainNav');
            if (nav) nav.classList.toggle('scrolled', window.scrollY > 50);
        });
        // Micro-interaction for hover states on bento grid items
        document.querySelectorAll('.cartoon-border').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translate(-4px, -4px)';
                card.style.boxShadow = '10px 10px 0px #ba0013';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translate(0px, 0px)';
                card.style.boxShadow = '6px 6px 0px #ba0013';
            });
        });

        // Simple smooth scroll behavior (if needed for internal links)
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body></html>