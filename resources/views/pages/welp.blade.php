<!DOCTYPE html><html class="light" lang="id"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Pusat Bantuan - Spies Sport</title>
@vite(['resources/css/pages.css', 'resources/js/app.js'])
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9ff;
            background-image: radial-gradient(at 0% 0%, rgba(224, 242, 241, 0.4) 0px, transparent 50%),
                              radial-gradient(at 100% 100%, rgba(224, 242, 241, 0.4) 0px, transparent 50%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="text-on-background overflow-x-hidden">
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
<a class="text-secondary font-medium hover:text-primary transition-all duration-300" href="{{ route('komunitas') }}">Komunitas</a>
<a class="text-primary font-bold border-b-2 border-primary py-2 transition-all duration-300" href="{{ route('bantuan') }}">Bantuan</a>
</div>
<div class="flex items-center gap-md">
<a href="{{ route('login') }}" class="hidden sm:block font-label-md text-secondary hover:text-primary active:scale-95 transition-all">Masuk</a>
<a href="{{ route('choose.role') }}" class="bg-primary text-on-primary px-lg py-sm rounded-full font-label-md shadow-lg shadow-primary/20 hover:brightness-110 active:scale-95 transition-all">Daftar</a>
</div>
</div>
</nav>
<main class="pt-[120px] pb-xl px-margin-mobile md:px-margin-desktop max-w-[1440px] mx-auto">
<!-- Hero Section -->
<section class="flex flex-col md:flex-row items-center justify-between gap-lg mb-xl">
<div class="w-full md:w-1/2 space-y-md">
<div class="inline-flex items-center gap-xs bg-primary/10 px-sm py-xs rounded-full">
<span class="material-symbols-outlined text-[18px] text-primary">support_agent</span>
<span class="text-primary font-label-md text-label-md uppercase tracking-wider">Pusat Bantuan 24/7</span>
</div>
<h2 class="font-display-lg text-display-lg text-primary">Ada yang bisa kami bantu?</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-[500px]">
                    Kami hadir untuk memastikan pengalaman olahraga Anda tetap seru dan tanpa kendala. Temukan jawaban instan di bawah ini!
                </p>
<div class="relative group max-w-[540px]">
<span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-primary group-focus-within:scale-110 transition-transform">search</span>
<input class="w-full pl-[56px] pr-md py-md rounded-xl glass-card border-outline-variant/30 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all shadow-sm" placeholder="Cari topik bantuan (misal: booking, refund...)" type="text">
</div>
</div>
<div class="w-full md:w-1/2 flex justify-center">
<div class="relative">
<div class="absolute inset-0 bg-primary/10 blur-[100px] rounded-full -z-10 animate-pulse"></div>
<img alt="Mascot" class="w-[400px] object-contain drop-shadow-2xl rounded-2xl" src="{{ asset('assets/images/characters/welp.png') }}">
</div>
</div>
</section>
<!-- Categories Bento Grid -->
<section class="grid grid-cols-1 md:grid-cols-4 gap-md mb-xl">
<div class="md:col-span-2 glass-card p-lg rounded-lg flex flex-col justify-between hover:shadow-xl transition-all group cursor-pointer border-l-4 border-primary">
<div class="space-y-sm">
<div class="w-[56px] h-[56px] bg-primary/10 rounded-full flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-[32px]">calendar_today</span>
</div>
<h3 class="font-headline-md text-headline-md">Booking &amp; Jadwal</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Panduan lengkap cara pesan lapangan, reschedule, dan melihat ketersediaan slot secara real-time.</p>
</div>
<span class="text-primary font-bold flex items-center gap-xs mt-md">Lihat FAQ <span class="material-symbols-outlined">arrow_forward</span></span>
</div>
<div class="glass-card p-lg rounded-lg flex flex-col items-center text-center gap-sm hover:shadow-xl transition-all group cursor-pointer">
<div class="w-[56px] h-[56px] bg-primary/10 rounded-full flex items-center justify-center text-primary group-hover:rotate-12 transition-transform">
<span class="material-symbols-outlined text-[32px]">payments</span>
</div>
<h3 class="font-title-lg text-title-lg">Pembayaran</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Metode bayar &amp; e-wallet.</p>
</div>
<div class="glass-card p-lg rounded-lg flex flex-col items-center text-center gap-sm hover:shadow-xl transition-all group cursor-pointer">
<div class="w-[56px] h-[56px] bg-primary/10 rounded-full flex items-center justify-center text-primary group-hover:rotate-12 transition-transform">
<span class="material-symbols-outlined text-[32px]">person</span>
</div>
<h3 class="font-title-lg text-title-lg">Akun</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Profil &amp; Keamanan.</p>
</div>
<div class="glass-card p-lg rounded-lg flex flex-col items-center text-center gap-sm hover:shadow-xl transition-all group cursor-pointer">
<div class="w-[56px] h-[56px] bg-primary/10 rounded-full flex items-center justify-center text-primary group-hover:rotate-12 transition-transform">
<span class="material-symbols-outlined text-[32px]">cancel_schedule_send</span>
</div>
<h3 class="font-title-lg text-title-lg">Pembatalan</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Refund &amp; Prosedur.</p>
</div>
<div class="md:col-span-3 glass-card p-lg rounded-lg flex flex-col md:flex-row items-center gap-lg hover:shadow-xl transition-all group cursor-pointer border-r-4 border-tertiary">
<div class="md:w-1/3">
<img alt="Support" class="rounded-xl w-full h-40 object-cover" src="{{ asset('assets/images/characters/help2.png') }}">
</div>
<div class="md:w-2/2 space-y-sm">
<h3 class="font-headline-md text-headline-md">Belum menemukan jawaban?</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Tim Support Spies Sport siap sedia membantu Anda melalui Live Chat atau WhatsApp secara instan.</p>
<button class="bg-tertiary text-on-tertiary px-lg py-sm rounded-full font-bold flex items-center gap-sm shadow-lg shadow-tertiary/20">
<span class="material-symbols-outlined">chat</span> Hubungi Kami Sekarang
                    </button>
</div>
</div>
</section>
<!-- FAQ Accordion -->
<section class="max-w-[800px] mx-auto space-y-md">
<h2 class="font-headline-lg text-headline-lg text-center mb-lg">Pertanyaan Sering Diajukan</h2>
<div class="glass-card rounded-lg overflow-hidden border border-outline-variant/20">
<button class="w-full flex items-center justify-between p-md hover:bg-primary/5 transition-colors" onclick="toggleFaq(1)">
<span class="font-title-lg text-title-lg text-left">Bagaimana cara membatalkan pesanan lapangan?</span>
<span class="material-symbols-outlined text-primary transition-transform duration-300" id="icon-1">expand_more</span>
</button>
<div class="hidden px-md pb-md" id="faq-1">
<p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                        Anda dapat membatalkan pesanan melalui menu 'Aktivitas Saya'. Klik pada pesanan yang ingin dibatalkan, lalu pilih 'Ajukan Pembatalan'. Harap perhatikan kebijakan refund yang berlaku minimal 24 jam sebelum jadwal dimulai.
                    </p>
</div>
</div>
<div class="glass-card rounded-lg overflow-hidden border border-outline-variant/20">
<button class="w-full flex items-center justify-between p-md hover:bg-primary/5 transition-colors" onclick="toggleFaq(2)">
<span class="font-title-lg text-title-lg text-left">Metode pembayaran apa saja yang tersedia?</span>
<span class="material-symbols-outlined text-primary transition-transform duration-300" id="icon-2">expand_more</span>
</button>
<div class="hidden px-md pb-md" id="faq-2">
<p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                        Kami mendukung berbagai metode pembayaran mulai dari Transfer Bank (VA), GoPay, OVO, Dana, hingga Kartu Kredit untuk memudahkan transaksi Anda kapan pun dan di mana pun.
                    </p>
</div>
</div>
<div class="glass-card rounded-lg overflow-hidden border border-outline-variant/20">
<button class="w-full flex items-center justify-between p-md hover:bg-primary/5 transition-colors" onclick="toggleFaq(3)">
<span class="font-title-lg text-title-lg text-left">Apakah saya bisa mengubah jadwal booking?</span>
<span class="material-symbols-outlined text-primary transition-transform duration-300" id="icon-3">expand_more</span>
</button>
<div class="hidden px-md pb-md" id="faq-3">
<p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                        Ya, fitur Reschedule tersedia hingga 12 jam sebelum jadwal dimulai, tergantung ketersediaan slot di lapangan tersebut. Biaya administrasi mungkin berlaku sesuai kebijakan mitra lapangan.
                    </p>
</div>
</div>
</section>
</main>
<!-- Footer -->
<footer class="bg-surface-container-low border-t border-outline-variant/30 w-full rounded-t-lg mt-xl">
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

        function toggleFaq(id) {
            const faq = document.getElementById(`faq-${id}`);
            const icon = document.getElementById(`icon-${id}`);
            const isHidden = faq.classList.contains('hidden');
            
            // Close all first
            document.querySelectorAll('[id^="faq-"]').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('[id^="icon-"]').forEach(el => el.classList.remove('rotate-180'));
            
            if (isHidden) {
                faq.classList.remove('hidden');
                icon.classList.add('rotate-180');
            }
        }
    </script>
</body></html>