<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview - Spies Sport</title>
    @vite('resources/css/app.css')

    <style>
        .slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            z-index: 0;
            transition: opacity 1.2s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .slide.active {
            opacity: 1;
            z-index: 2;
        }
        .slide.leaving {
            opacity: 0;
            z-index: 1;
        }
        .slide-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            will-change: transform;
        }
        .slide-content {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity 0.9s ease, transform 0.9s ease;
        }
        .slide.active .slide-content {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.12s;
        }
        .slide.leaving .slide-content {
            opacity: 0;
            transform: translateY(-12px);
        }
        @keyframes pan-left {
            0% { transform: scale(1) translateX(0); }
            100% { transform: scale(1.1) translateX(-5%); }
        }

        @keyframes pan-right {
            0% { transform: scale(1) translateX(0); }
            100% { transform: scale(1.1) translateX(5%); }
        }

        .slide.active .slide-image {
            animation-duration: 8s;
            animation-timing-function: linear;
            animation-fill-mode: both;
        }

        .slide:nth-child(odd).active .slide-image {
            animation-name: pan-left;
        }

        .slide:nth-child(even).active .slide-image {
            animation-name: pan-right;
        }

        .slide:not(.active) .slide-image {
            animation: none;
        }
    </style>
</head>
<body class="bg-gray-900">

<div class="relative w-full h-screen overflow-hidden">

    <!-- NAVBAR -->
    <div class="absolute top-0 left-0 w-full flex justify-between items-center px-10 py-6 z-20">
        <!-- LOGO (GANTI) -->
        <img src="{{ asset('assets/images/logo/logo1.png') }}" class="h-6" alt="Logo">

        <div class="flex gap-6 text-orange-100 font-semibold">
            <a href="{{ route('login') }}" class="hover:text-white">Masuk</a>
            <a href="#" class="hover:text-white">Bantuan</a>
        </div>
    </div>

    <!-- SLIDES -->
    <div id="slider">

        <!-- SLIDE 1 -->
        <div class="slide active">
            <img src="{{ asset('assets/images/bg/Explore.png') }}" class="slide-image" alt="Preview slide 1">
            <div class="absolute inset-0 bg-gradient-to-b from-black/20 to-indigo-950/90"></div>

            <div class="slide-content absolute inset-0 flex flex-col items-center justify-center text-center px-6 text-white">
                <h1 class="text-4xl md:text-5xl font-bold">
                    Temukan Lapanganmu,<br>Temukan Timmu!
                </h1>
                <p class="mt-4 text-lg text-white/80">Satu klik, langsung Kick-Off!</p>
            </div>
        </div>

        <!-- SLIDE 2 -->
        <div class="slide">
            <img src="{{ asset('assets/images/bg/Explore2.png') }}" class="slide-image" alt="Preview slide 2">
            <div class="absolute inset-0 bg-gradient-to-b from-black/20 to-indigo-950/90"></div>

            <div class="slide-content absolute inset-0 flex flex-col items-center justify-center text-center px-6 text-white">
                <h1 class="text-4xl md:text-5xl font-bold">
                    Cari Musuh Itu Gampang, <br> Cari Lawan Tanding yang Susah.
                </h1>
                <p class="mt-4 text-lg text-white/80">Cari Lapangan Secepat Cari Lawan.</p>
            </div>
        </div>

        <!-- SLIDE 3 -->
        <div class="slide">
            <img src="{{ asset('assets/images/bg/Explore3.png') }}" class="slide-image" alt="Preview slide 3">
            <div class="absolute inset-0 bg-gradient-to-b from-black/20 to-indigo-950/90"></div>

            <div class="slide-content absolute inset-0 flex flex-col items-center justify-center text-center px-6 text-white">
                <h1 class="text-4xl md:text-5xl font-bold">
                    Cek Jadwal, Booking, <br> Langsung Gas.
                </h1>
                <p class="mt-4 text-lg text-white/80">Sat-set Booking, Langsung Mabar.</p>
            </div>
        </div>

        <!-- SLIDE 4 -->
        <div class="slide">
            <img src="{{ asset('assets/images/bg/Explore4.png') }}" class="slide-image" alt="Preview slide 4">
            <div class="absolute inset-0 bg-gradient-to-b from-black/20 to-indigo-950/90"></div>

            <div class="slide-content absolute inset-0 flex flex-col items-center justify-center text-center px-6 text-white">
                <h1 class="text-4xl md:text-5xl font-bold">
                    Sport for Everyone.
                </h1>
                <p class="mt-4 text-lg text-white/80">Main Bareng, Bukan Jaga Gengsi.</p>
            </div>
        </div>

    </div>

    <!-- DOT INDICATOR -->
    <div class="absolute bottom-44 md:bottom-60 w-full flex justify-center gap-3 z-20">
        <button class="dot w-2 h-2 rounded-full bg-red-500"></button>
        <button class="dot w-2 h-2 rounded-full bg-gray-300"></button>
        <button class="dot w-2 h-2 rounded-full bg-gray-300"></button>
        <button class="dot w-2 h-2 rounded-full bg-gray-300"></button>
    </div>

    <!-- CTA -->
    <div class="absolute bottom-28 md:bottom-40 w-full flex justify-center z-20">
        <a href="{{ route('login') }}"
           class="px-10 py-3 bg-red-400 hover:bg-red-500 text-white font-bold rounded-xl shadow-lg transition">
            Ayo main!
        </a>
    </div>

</div>

<!-- JS SLIDER -->
<script>
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    let current = 0;
    let cleanupTimer;
    let autoSlideTimer;
    const slideInterval = 5200;
    const fadeDuration = 1200;

    function showSlide(index) {
        if (index === current) return;

        const previousSlide = slides[current];
        const nextSlide = slides[index];

        window.clearTimeout(cleanupTimer);

        slides.forEach((s, i) => {
            if (s !== previousSlide && s !== nextSlide) {
                s.classList.remove('active', 'leaving');
            }
            dots[i].classList.remove('bg-red-500');
            dots[i].classList.add('bg-gray-300');
        });

        previousSlide.classList.add('leaving');
        nextSlide.classList.remove('leaving');
        nextSlide.classList.add('active');
        dots[index].classList.add('bg-red-500');
        dots[index].classList.remove('bg-gray-300');
        current = index;

        cleanupTimer = window.setTimeout(() => {
            previousSlide.classList.remove('active', 'leaving');
        }, fadeDuration);
    }

    function startAutoSlide() {
        window.clearInterval(autoSlideTimer);
        autoSlideTimer = window.setInterval(() => {
            let next = (current + 1) % slides.length;
            showSlide(next);
        }, slideInterval);
    }

    // CLICK DOT
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            showSlide(index);
            startAutoSlide();
        });
    });

    startAutoSlide();
</script>

</body>
</html>
