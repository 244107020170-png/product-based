<!DOCTYPE html>

<html class="light" lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Hubungi Kami | Spies Sport</title>
@vite(['resources/css/pages.css', 'resources/js/app.js'])
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>

<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .bg-soft-teal {
            background-color: #E0F2F1;
        }
        .hero-pattern {
            background-image: radial-gradient(circle at 2px 2px, rgba(186, 0, 19, 0.05) 1px, transparent 0);
            background-size: 32px 32px;
        }
        .cartoon-shadow {
            filter: drop-shadow(8px 12px 0px rgba(186, 0, 19, 0.1));
        }
    </style>
</head>
<body class="bg-soft-teal text-on-surface font-body-md hero-pattern min-h-screen">
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
<button class="md:hidden flex items-center justify-center w-10 h-10 rounded-full bg-white/80 text-primary shadow-sm hover:bg-white transition-all" onclick="toggleMobileNav()" aria-label="Menu">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path id="navOpenIcon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        <path id="navCloseIcon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
    </svg>
</button>
</div>
</div>
</nav>

<!-- Mobile Navigation Menu -->
<div id="mobileNavMenu" class="hidden md:hidden fixed inset-x-0 top-[80px] z-40 bg-white/95 backdrop-blur-md shadow-lg border-b border-gray-200">
    <div class="flex flex-col p-6 space-y-4">
        <a class="text-secondary font-medium text-lg hover:text-primary transition-all pb-2 border-b border-gray-100" href="{{ route('home') }}" onclick="toggleMobileNav()">Beranda</a>
        <a class="text-secondary font-medium text-lg hover:text-primary transition-all pb-2 border-b border-gray-100" href="{{ route('lapangan') }}" onclick="toggleMobileNav()">Lapangan</a>
        <a class="text-secondary font-medium text-lg hover:text-primary transition-all pb-2 border-b border-gray-100" href="{{ route('komunitas') }}" onclick="toggleMobileNav()">Komunitas</a>
        <a class="text-secondary font-medium text-lg hover:text-primary transition-all pb-2 border-b border-gray-100" href="{{ route('bantuan') }}" onclick="toggleMobileNav()">Bantuan</a>
        <div class="pt-2">
            <a href="{{ route('login') }}" class="block w-full text-center border border-gray-300 text-secondary py-3 rounded-full font-label-md hover:bg-gray-50 transition-all mb-3">Masuk</a>
            <a href="{{ route('choose.role') }}" class="block w-full text-center bg-primary text-on-primary py-3 rounded-full font-label-md shadow-lg shadow-primary/20 hover:brightness-110 transition-all">Daftar</a>
        </div>
    </div>
</div>

<script>
function toggleMobileNav() {
    const menu = document.getElementById('mobileNavMenu');
    const openIcon = document.getElementById('navOpenIcon');
    const closeIcon = document.getElementById('navCloseIcon');
    menu.classList.toggle('hidden');
    openIcon.classList.toggle('hidden');
    closeIcon.classList.toggle('hidden');
}
window.addEventListener('resize', function() {
    if (window.innerWidth >= 768) {
        const menu = document.getElementById('mobileNavMenu');
        if (!menu.classList.contains('hidden')) {
            menu.classList.add('hidden');
            document.getElementById('navOpenIcon').classList.remove('hidden');
            document.getElementById('navCloseIcon').classList.add('hidden');
        }
    }
});
</script>

<main class="pt-32 pb-xl px-4 md:px-margin-desktop max-w-[1440px] mx-auto">
<!-- Hero Section with Cartoon Character -->
<section class="flex flex-col md:flex-row items-center gap-xl mb-xl">
<div class="flex-1 space-y-md">
<div class="inline-flex items-center gap-xs px-md py-xs bg-primary/10 text-primary rounded-full">
<span class="material-symbols-outlined text-[18px]">waving_hand</span>
<span class="font-label-sm text-label-sm uppercase tracking-wider">Halo Atlet!</span>
</div>
<h1 class="font-display-lg text-headline-lg-mobile md:text-display-lg text-on-surface">Ada yang bisa <span class="text-primary">kami bantu?</span></h1>
<p class="font-body-lg text-body-lg text-secondary max-w-xl">
                    Tim kami selalu siap sedia 24/7 untuk menjawab pertanyaan seputar data olahraga, langganan premium, atau sekadar menyapa. Jangan ragu untuk menghubungi kami!
                </p>
</div>
<div class="flex-1 flex justify-center relative">
<div class="absolute inset-0 bg-primary/5 rounded-full blur-3xl transform scale-125"></div>
<img alt="Cartoon character waving" class="w-full max-w-[400px] cartoon-shadow relative z-10 transition-transform hover:rotate-3 duration-500" data-alt="A vibrant and playful 3D cartoon character of a friendly sports coach wearing a red track jacket, smiling and waving enthusiastically. The character has an expressive face and modern athletic gear, rendered in a high-quality 3D animation style with soft lighting. The background is a clean, minimalist cream tone with subtle teal accents, reflecting a premium health and wellness app aesthetic. The overall mood is energetic, welcoming, and professional." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCMZhxJWPtjNrBT6kG_jCq5rbgMp1N_QKko-kmu2X75Fw_uLMcrtJnPgeMz--i8YmajNEe4hz95TnTRxOJpLIEms0YUWzJGvKo1b0pwXlIwyrn47gly7DmjU56gdxgGYEYM68uvGo1mCnKDhZhAxTTUwUAinhqDcC2m0w42y46W12tYqTGyVftQeSFhthb9J-hGEuhBwf67uqqkk-3s2e9l265A8ibUkx3YiNh4C8jvaRfJNY7YB6Culb-4hJH3MKRm23m8W8Jc-ojB"/>
</div>
</section>
<!-- Main Content: Form & Contacts (Bento Layout) -->
<div class="grid grid-cols-1 md:grid-cols-12 gap-gutter">
<!-- Contact Form Card -->
<div class="md:col-span-7 glass-card rounded-lg p-lg shadow-xl shadow-primary/5">
<h2 class="font-headline-lg text-headline-lg mb-lg flex items-center gap-sm">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">send</span>
                    Kirim Pesan
                </h2>
<form class="space-y-md" id="contactForm">
<div class="grid grid-cols-1 md:grid-cols-2 gap-md">
<div class="space-y-xs">
<label class="font-label-md text-label-md text-secondary px-base">Nama Lengkap</label>
<input name="name" class="w-full bg-surface-container-lowest border-2 border-primary/10 rounded-DEFAULT px-md py-sm focus:border-primary focus:ring-0 transition-all outline-none" placeholder="Misal: Budi Santoso" type="text"/>
</div>
<div class="space-y-xs">
<label class="font-label-md text-label-md text-secondary px-base">Email</label>
<input name="email" class="w-full bg-surface-container-lowest border-2 border-primary/10 rounded-DEFAULT px-md py-sm focus:border-primary focus:ring-0 transition-all outline-none" placeholder="budi@email.com" type="email"/>
</div>
</div>
<div class="space-y-xs">
<label class="font-label-md text-label-md text-secondary px-base">Subjek</label>
<select name="subject" class="w-full bg-surface-container-lowest border-2 border-primary/10 rounded-DEFAULT px-md py-sm focus:border-primary focus:ring-0 transition-all outline-none">
<option>Pertanyaan Umum</option>
<option>Masalah Akun</option>
<option>Kerjasama Bisnis</option>
<option>Lainnya</option>
</select>
</div>
<div class="space-y-xs">
<label class="font-label-md text-label-md text-secondary px-base">Pesan Anda</label>
<textarea name="message" class="w-full bg-surface-container-lowest border-2 border-primary/10 rounded-DEFAULT px-md py-sm focus:border-primary focus:ring-0 transition-all outline-none resize-none" placeholder="Tuliskan detail pertanyaan atau masukan Anda..." rows="5"></textarea>
</div>
<button class="w-full bg-primary text-on-primary font-title-lg text-title-lg py-md rounded-DEFAULT hover:shadow-2xl hover:shadow-primary/40 transition-all active:scale-95 flex justify-center items-center gap-sm group" type="submit">
                        Kirim Pesan Sekarang
                        <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
</button>
</form>
</div>
<!-- Quick Contact & Map Column -->
<div class="md:col-span-5 flex flex-col gap-gutter">
<!-- Contact Info Glass Card -->
<div class="glass-card rounded-lg p-lg shadow-xl shadow-primary/5 flex flex-col gap-lg">
<h3 class="font-title-lg text-title-lg text-on-surface">Kontak Cepat</h3>
<a class="flex items-center gap-md group" href="https://wa.me/6281234567890" target="_blank">
<div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">phone_iphone</span>
</div>
<div>
<p class="font-label-sm text-label-sm text-secondary uppercase tracking-tighter">WhatsApp</p>
<p class="font-title-lg text-title-lg text-on-surface">+62 812 3456 7890</p>
</div>
</a>
<a class="flex items-center gap-md group" href="mailto:halo@spiessport.id">
<div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">mail</span>
</div>
<div>
<p class="font-label-sm text-label-sm text-secondary uppercase tracking-tighter">Email</p>
<p class="font-title-lg text-title-lg text-on-surface">halo@spiessport.id</p>
</div>
</a>
<div class="flex items-center gap-md group">
<div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">location_on</span>
</div>
<div>
<p class="font-label-sm text-label-sm text-secondary uppercase tracking-tighter">Kantor Pusat</p>
<p class="font-title-lg text-title-lg text-on-surface">Jl. Atletik No. 42, Jakarta Selatan</p>
</div>
</div>
</div>
<!-- Map Card -->
<div class="glass-card rounded-lg overflow-hidden flex flex-col shadow-xl shadow-primary/5 h-full min-h-[300px]">
<div class="p-md bg-white/50 border-b border-white/50 flex justify-between items-center">
<h3 class="font-label-md text-label-md">Peta Lokasi</h3>
<a href="https://maps.google.com/?q=-7.982,112.631" target="_blank" class="text-primary font-bold text-label-sm no-underline">Buka di Maps →</a>
</div>
<div class="flex-grow relative bg-surface-variant overflow-hidden group">
<img class="w-full h-full object-cover transition-transform duration-[2s] group-hover:scale-110" data-alt="An artistic, illustrated 3D map of a modern urban neighborhood in Jakarta featuring clean streets, stylized trees, and sports-themed buildings. The map is designed in a vibrant cartoonist style with soft pastel cream and rich red accents. Small icons representing the Spies Sport office and nearby athletic centers are visible. The perspective is an isometric aerial view with soft, diffuse sunlight creating a high-end wellness atmosphere." data-location="Jakarta South" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA4x1FFlGItzYNmVw6fQlIBCAK4BrsVz7n_dbBsnRpTPiAlExQ6Q841hAP8gyoMorjdjNxaWSorcxFz-SLSVfqhdz35Uo3HJhI2g7sSRKZaI_iRHKyrT9SfbxEk05d76GJC5ICGiB34fhpng48LASxkPuADmkVRrPNhlddHoEMamDNTU2RM-x7t8Zqc6jLC68JXIh1Os0egZpcb1g4oaPZlweLpbSbwR-69iFURzPZhqehJUZdJeyNtCsD9J1L8PVxcnQ5OHpAjTSkN"/>
<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center">
<div class="w-10 h-10 bg-primary rounded-full border-4 border-white shadow-2xl flex items-center justify-center animate-bounce">
<span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings: 'FILL' 1;">location_on</span>
</div>
<div class="mt-xs bg-white px-md py-xs rounded-full shadow-lg border border-primary/10">
<p class="text-label-sm font-bold text-primary">Spies Sport HQ</p>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- FAQ CTA Section -->
<section class="mt-xl glass-card rounded-lg p-xl text-center relative overflow-hidden">
<div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
<div class="relative z-10">
<h3 class="font-headline-lg text-headline-lg mb-sm">Masih Punya Pertanyaan?</h3>
<p class="text-secondary font-body-lg text-body-lg mb-lg max-w-2xl mx-auto">
                    Mungkin jawaban yang Anda cari ada di halaman Bantuan kami. Kami telah merangkum pertanyaan yang paling sering diajukan untuk kemudahan Anda.
                </p>
<a href="{{ route('bantuan') }}" class="bg-secondary text-white px-xl py-md rounded-full font-title-lg hover:bg-on-surface transition-colors no-underline inline-block">
                    Lihat FAQ
                </a>
</div>
</section>
</main>
@include('partials.footer')
<script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('mainNav');
            if (nav) nav.classList.toggle('scrolled', window.scrollY > 50);
        });
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button');
            const originalText = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span> Mengirim...';
            
            setTimeout(() => {
                btn.classList.replace('bg-primary', 'bg-tertiary-container');
                btn.innerHTML = '<span class="material-symbols-outlined">check_circle</span> Pesan Anda telah terkirim';
                e.target.reset();
                
                setTimeout(() => {
                    btn.disabled = false;
                    btn.classList.replace('bg-tertiary-container', 'bg-primary');
                    btn.innerHTML = originalText;
                }, 3000);
            }, 1500);
        });

        // Simple floating animation for character
        const char = document.querySelector('img[alt="Cartoon character waving"]');
        if (char) {
            let angle = 0;
            function animate() {
                angle += 0.05;
                const y = Math.sin(angle) * 10;
                char.style.transform = `translateY(${y}px)`;
                requestAnimationFrame(animate);
            }
            animate();
        }
    </script>
</body></html>