<!DOCTYPE html>

<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Tentang Kami | Spies Sport</title>
@vite(['resources/css/pages.css', 'resources/js/app.js'])
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>

<style>
        body {
            background-color: #E0F2F1; /* Base Teal Background from Style Guidance */
            background-image: radial-gradient(circle at 20% 30%, rgba(186, 0, 19, 0.05) 0%, transparent 40%),
                              radial-gradient(circle at 80% 70%, rgba(38, 102, 88, 0.05) 0%, transparent 40%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .primary-glow {
            box-shadow: 0 24px 24px -4px rgba(186, 0, 19, 0.1);
        }
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="font-body-md text-on-surface antialiased overflow-x-hidden">
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

<main class="pt-32 pb-xl">
<!-- Hero Section -->
<section class="px-margin-mobile md:px-margin-desktop mb-xl">
<div class="grid grid-cols-1 md:grid-cols-12 gap-lg items-center">
<div class="md:col-span-7">
<span class="bg-primary/10 text-primary px-md py-xs rounded-full font-label-sm text-label-sm uppercase tracking-widest mb-md inline-block">Membangun Masa Depan</span>
<h1 class="font-display-lg text-headline-lg-mobile md:text-display-lg text-on-surface mb-md">
                        Semangat Juara di Setiap <span class="text-primary italic">Detak Jantung.</span>
</h1>
<p class="font-body-lg text-body-lg text-secondary max-w-2xl">
                        Spies Sport hadir sebagai katalisator kemajuan olahraga di Indonesia. Kami bukan sekadar platform; kami adalah gerakan untuk menghidupkan budaya kompetisi yang sehat dan profesionalisme atlet nusantara.
                    </p>
</div>
<div class="md:col-span-5 relative flex justify-center">
<div class="absolute -z-10 w-64 h-64 bg-primary/20 rounded-full blur-3xl floating"></div>
<img alt="Hero Character" class="w-full max-w-sm drop-shadow-2xl floating" src="{{ asset('assets/images/characters/team.png') }}"/>
</div>
</div>
</section>
<!-- Vision & Mission Bento Grid -->
<section class="px-margin-mobile md:px-margin-desktop mb-xl">
<div class="grid grid-cols-1 md:grid-cols-12 gap-md">
<div class="md:col-span-4 glass-card p-lg rounded-lg flex flex-col justify-between">
<div>
<span class="material-symbols-outlined text-primary text-4xl mb-md">visibility</span>
<h2 class="font-headline-md text-headline-md text-on-surface mb-sm">Visi Kami</h2>
<p class="text-secondary">Menjadikan Indonesia sebagai kiblat prestasi olahraga global melalui integrasi teknologi dan semangat sportivitas tanpa batas.</p>
</div>
</div>
<div class="md:col-span-8 p-lg rounded-lg bg-primary text-white relative overflow-hidden">
<div class="relative z-10 h-full flex flex-col justify-center">
<h2 class="font-headline-md text-headline-md mb-md font-bold">Misi Utama</h2>
<ul class="space-y-md">
<li class="flex items-start gap-sm">
<span class="material-symbols-outlined mt-1 text-white">check_circle</span>
<p class="font-body-lg text-body-lg text-white">Menyediakan platform data performa atlet tercanggih di Indonesia.</p>
</li>
<li class="flex items-start gap-sm">
<span class="material-symbols-outlined mt-1 text-white">check_circle</span>
<p class="font-body-lg text-body-lg text-white">Membangun ekosistem yang menghubungkan talenta muda dengan peluang profesional.</p>
</li>
<li class="flex items-start gap-sm">
<span class="material-symbols-outlined mt-1 text-white">check_circle</span>
<p class="font-body-lg text-body-lg text-white">Mempromosikan gaya hidup sehat dan aktif melalui kampanye digital yang inspiratif.</p>
</li>
</ul>
</div>
<div class="absolute right-0 bottom-0 opacity-10 transform translate-x-1/4 translate-y-1/4">
<span class="material-symbols-outlined text-[300px]" style="font-variation-settings: 'FILL' 1;">rocket_launch</span>
</div>
</div>
</div>
</section>
<!-- Core Values Section -->
<section class="px-margin-mobile md:px-margin-desktop mb-xl">
<div class="text-center mb-lg">
<h2 class="font-headline-lg text-headline-lg text-on-surface mb-sm">Nilai-Nilai Spies Sport</h2>
<p class="text-secondary max-w-xl mx-auto">Fondasi kuat yang membentuk identitas kami dalam setiap langkah.</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
<div class="text-center p-md group">
<div class="w-20 h-20 bg-surface-container rounded-full flex items-center justify-center mx-auto mb-md group-hover:scale-110 transition-transform duration-300">
<span class="material-symbols-outlined text-primary text-3xl">bolt</span>
</div>
<h3 class="font-title-lg text-title-lg mb-sm">Energi Tinggi</h3>
<p class="text-secondary">Kami bekerja dengan antusiasme yang menular untuk mendorong perubahan positif.</p>
</div>
<div class="text-center p-md group">
<div class="w-20 h-20 bg-surface-container rounded-full flex items-center justify-center mx-auto mb-md group-hover:scale-110 transition-transform duration-300">
<span class="material-symbols-outlined text-primary text-3xl">groups</span>
</div>
<h3 class="font-title-lg text-title-lg mb-sm">Kolaborasi</h3>
<p class="text-secondary">Olahraga adalah kerja tim, begitu pula cara kami membangun inovasi bersama komunitas.</p>
</div>
<div class="text-center p-md group">
<div class="w-20 h-20 bg-surface-container rounded-full flex items-center justify-center mx-auto mb-md group-hover:scale-110 transition-transform duration-300">
<span class="material-symbols-outlined text-primary text-3xl">verified</span>
</div>
<h3 class="font-title-lg text-title-lg mb-sm">Integritas</h3>
<p class="text-secondary">Kejujuran dan transparansi adalah skor tertinggi yang kami jaga setiap hari.</p>
</div>
</div>
</section>
<!-- Representative Characters / Team Section -->
<section class="px-margin-mobile md:px-margin-desktop bg-surface-container py-xl rounded-xl">
<div class="text-center mb-xl">
<h2 class="font-headline-lg text-headline-lg text-on-surface mb-sm">Karakter Kami</h2>
<p class="text-secondary">Mengenal lebih dekat para penggerak di balik layar.</p>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-md">
<!-- Character Card 1 -->
<div class="glass-card p-md rounded-lg text-center hover:translate-y-[-10px] transition-all">
<div class="aspect-square mb-md overflow-hidden rounded-full bg-primary/5">
<img alt="Aris - CEO" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" data-alt="A stylized cartoonist illustration of a charismatic Indonesian man with a friendly smile, wearing a modern red polo shirt. He is depicted as a visionary leader with glowing lines suggesting creative thinking. The background is a clean, glassmorphic office setting with soft teal highlights and bright, optimistic lighting. The style is comic-book professional, energetic and cheerful." src="https://lh3.googleusercontent.com/aida-public/AB6AXuClO_e_8V-hXA4q83jV9o_8PRT0NvIYd8vrpzJgeQRQesmzcD89TDah6e3RffwjWqScjdJJnS_3M_petXtzGn3i5SxGoJceZ5c_i75Jr3JoVL6pKMn_BaCdLlUEvMfdlHwfR3iZkqOhHsx_AmTIJ953SGXm3vwOAVcp0P2-YHKsgYJjrK2qJotqCDjc4itJuDJKDy59N3XxwqOFMZeR3QWeSnxUwdS_h3Xw4yWN-HwNag4nz_xDAjinmbnuCNZAdp2GzPLcDOnhJW28"/>
</div>
<h4 class="font-title-lg text-title-lg mb-xs">Nasywa</h4>
<p class="text-primary font-label-md">Visionary Captain</p>
</div>
<!-- Character Card 2 -->
<div class="glass-card p-md rounded-lg text-center hover:translate-y-[-10px] transition-all">
<div class="aspect-square mb-md overflow-hidden rounded-full bg-primary/5">
<img alt="Sita - CTO" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" data-alt="A cheerful cartoonist-style portrait of a modern Indonesian woman with glasses, looking tech-savvy and confident. She wears a cream-colored smart jacket with subtle red accents. Digital floating icons related to sports technology and coding surround her in a soft, glowing glassmorphic style. The mood is high-tech, professional yet very approachable and vibrant." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCLP5CgZUoMVzGDlhzDA0Jxvr5tHsBLj4w58VnYCzlE4nEGdNNc1iSWF6MN99iWhG8pyeoxY22jwH4UmJfHIPEyP8ddJ7shXUB6gb_lYwgDpB9Wb17mabz4cOlx3YMO-yR-ms9HFmoJecQ2WOjmvYPa2wwszC9RdY0LPrRwy1XUtHwA73KSuGZBV2JzBnVkYqT-rC02jQC-jGyozY5ZXafgsYob3zaMO9_n5TYS2LLe-ajI-fgsXB9YT1-VkPL584UyQBorC1YiXMrT"/>
</div>
<h4 class="font-title-lg text-title-lg mb-xs">Nasywa</h4>
<p class="text-primary font-label-md">Tech Strategist</p>
</div>
<!-- Character Card 3 -->
<div class="glass-card p-md rounded-lg text-center hover:translate-y-[-10px] transition-all">
<div class="aspect-square mb-md overflow-hidden rounded-full bg-primary/5">
<img alt="Bimo - Creative" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" data-alt="A playful cartoonist illustration of a young Indonesian man with headphones around his neck, looking energetic and creative. He is wearing a red hoodie. Splashes of artistic paint in red and teal colors form a dynamic halo around him. The background is a translucent glass pane with blurred studio lighting, reflecting a youthful and energetic brand personality." src="https://lh3.googleusercontent.com/aida-public/AB6AXuD1YzBFcwwD6NR6n9ZC6LZOoH8JolzmCKJ43xlupt-spw87OkTmGB_ObBos-SdshG4fBtgKurP1Bp-rpkW0V_zXjC1aGubUfp8Ftp-L5Etuec8nXWcif4OG4pJbXYnTqW63a1bUK3iYumD69p_NIZKorf0L4p6NNqdr5Mthy3yVeoYWgJ1Te4CXGjWDwv_peYZkJm2LuLeVw5SaquqEID2fKtFwMIXtpsSfhcl9R7NfgX-_tMK55gQCiPrnfK1Q4ZSnnaCkfY33YPk7"/>
</div>
<h4 class="font-title-lg text-title-lg mb-xs">Nasywa</h4>
<p class="text-primary font-label-md">Vibe Master</p>
</div>
<!-- Character Card 4 -->
<div class="glass-card p-md rounded-lg text-center hover:translate-y-[-10px] transition-all">
<div class="aspect-square mb-md overflow-hidden rounded-full bg-primary/5">
<img alt="Maya - Community" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" data-alt="A vibrant cartoonist illustration of a friendly Indonesian woman with an open posture, suggesting inclusivity and community. She is wearing a modern cream athletic vest with red trim. Speech bubbles and heart icons in a glassmorphic style float nearby. The lighting is bright and warm, emphasizing a cheerful and welcoming atmosphere. Professional clean line art." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCS9YW_trmfGkm3JCsetDzf3sF8vKAs5ha12fDnVAsg_RtIFSdAD9KJV0_pYNi7RKRbBwVsEMRBpW9nDcvJmJaTgnjQn1h_S6lnqn0qu-83gXpaTHCh7hYDQtJdJlugZsqklhnEzNUPIYi2U80oBz_aX7mqNWxKrg5qTn1TH6eBU8MDukGzgArhr9E_VysahGJi02OkOqMeL5hrvbv2VIbhR8vL6zsPf-lhtKbu8jUkgl9aLxaD1IaV7CBMoKZf74PYJfEd7-gZ3O0k"/>
</div>
<h4 class="font-title-lg text-title-lg mb-xs">Nasywa</h4>
<p class="text-primary font-label-md">Heart of Community</p>
</div>
</div>
</section>
<!-- CTA Section -->
<section class="px-margin-mobile md:px-margin-desktop mt-xl">
<div class="bg-primary rounded-xl p-xl text-center relative overflow-hidden primary-glow">
<div class="relative z-10">
<h2 class="font-headline-lg text-on-primary mb-md">Siap Menjadi Bagian dari Perubahan?</h2>
<p class="text-primary-fixed-dim font-body-lg mb-lg max-w-2xl mx-auto">Mari berkolaborasi membangun ekosistem olahraga yang lebih baik untuk Indonesia.</p>
<div class="flex flex-col sm:flex-row gap-md justify-center">
<a href="{{ route('contact') }}" class="bg-surface text-primary px-xl py-md rounded-full font-label-md hover:scale-105 transition-transform no-underline">Hubungi Kami</a>
<a href="{{ route('lapangan') }}" class="border-2 border-surface/30 text-surface px-xl py-md rounded-full font-label-md hover:bg-surface/10 transition-colors no-underline">Lihat Program Kami</a>
</div>
</div>
<div class="absolute -left-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
<div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
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
        // Optional subtle parallax or interaction scripts
        document.addEventListener('mousemove', (e) => {
            const cards = document.querySelectorAll('.glass-card');
            const x = (window.innerWidth / 2 - e.pageX) / 50;
            const y = (window.innerHeight / 2 - e.pageY) / 50;
            
            // Subtle tilt effect for the hero image only for better UX
            const heroImg = document.querySelector('img[alt="Hero Character"]');
            if(heroImg) {
                heroImg.style.transform = `translate(${x}px, ${y}px)`;
            }
        });
    </script>
</body></html>