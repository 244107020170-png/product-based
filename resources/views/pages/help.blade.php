@php
    $referer = request()->headers->get('referer');
    $previousUrl = url()->previous();
    $currentUrl = url()->current();
    $isInternalReferer = $referer && parse_url($referer, PHP_URL_HOST) === request()->getHost();
    $backUrl = $isInternalReferer && $previousUrl !== $currentUrl ? $previousUrl : route('login');

    $categories = [
        'Booking & Lapangan' => [
            [
                'q' => 'Bagaimana cara booking lapangan?',
                'a' => 'Masuk ke menu Lapangan, pilih lapangan, tentukan tanggal dan jam, lalu klik booking. Pastikan kamu sudah login agar proses bisa dilakukan.',
            ],
            [
                'q' => 'Apakah saya bisa memilih jadwal sendiri?',
                'a' => 'Ya, kamu bisa memilih jadwal sesuai slot yang tersedia. Sistem hanya menampilkan waktu yang masih kosong.',
            ],
            [
                'q' => 'Bagaimana jika jadwal sudah penuh?',
                'a' => 'Kamu bisa memilih waktu lain atau mencari lapangan lain yang masih tersedia.',
            ],
            [
                'q' => 'Apakah saya bisa membatalkan booking?',
                'a' => 'Tergantung fitur yang tersedia. Jika ada, kamu bisa membatalkan melalui halaman booking. Jika tidak, hubungi owner.',
            ],
            [
                'q' => 'Bagaimana cara melihat detail lapangan?',
                'a' => 'Klik lapangan pada daftar, lalu detail seperti lokasi, harga, dan fasilitas akan ditampilkan.',
            ],
        ],
        'Public Match' => [
            [
                'q' => 'Apa itu public match?',
                'a' => 'Public match adalah pertandingan terbuka yang bisa diikuti oleh pengguna lain tanpa harus membuat tim sendiri.',
            ],
            [
                'q' => 'Bagaimana cara join match?',
                'a' => 'Masuk ke menu Match, pilih match yang tersedia, lalu klik join. Pastikan slot masih tersedia.',
            ],
            [
                'q' => 'Apakah saya bisa membuat match sendiri?',
                'a' => 'Bisa. Klik buat match, isi detail, lalu match kamu akan muncul dan bisa diikuti pengguna lain.',
            ],
            [
                'q' => 'Apakah ada batasan jumlah pemain?',
                'a' => 'Ya, setiap match memiliki batas pemain. Jika penuh, kamu tidak bisa join.',
            ],
            [
                'q' => 'Bagaimana jika tidak jadi ikut match?',
                'a' => 'Jika tersedia fitur keluar match, kamu bisa keluar. Jika tidak, hubungi pembuat match.',
            ],
        ],
        'Akun' => [
            [
                'q' => 'Bagaimana cara membuat akun?',
                'a' => 'Klik register, isi data, lalu akun kamu siap digunakan untuk login.',
            ],
            [
                'q' => 'Saya lupa password, bagaimana?',
                'a' => 'Gunakan fitur lupa password di halaman login dan ikuti langkah reset.',
            ],
            [
                'q' => 'Apakah saya bisa mengubah data akun?',
                'a' => 'Bisa, kamu bisa edit data melalui halaman profil.',
            ],
        ],
        'Fitur Tambahan' => [
            [
                'q' => 'Apa fungsi fitur favorit?',
                'a' => 'Untuk menyimpan lapangan favorit agar mudah diakses kembali.',
            ],
            [
                'q' => 'Bagaimana cara menghubungi pemain?',
                'a' => 'Setelah join match, kamu bisa klik tombol WhatsApp untuk menghubungi pemain lain.',
            ],
            [
                'q' => 'Apa itu rekomendasi match?',
                'a' => 'Sistem akan menampilkan match sesuai minat dan aktivitas kamu.',
            ],
            [
                'q' => 'Apa fungsi countdown timer?',
                'a' => 'Menampilkan waktu penting seperti jadwal match agar kamu tidak terlambat.',
            ],
            [
                'q' => 'Bagaimana cara memberikan rating?',
                'a' => 'Setelah menggunakan lapangan, kamu bisa memberikan rating dan ulasan.',
            ],
        ],
        'Masalah Umum' => [
            [
                'q' => 'Kenapa saya tidak bisa booking?',
                'a' => 'Pastikan kamu sudah login dan slot tersedia. Jika masih error, coba refresh atau cek koneksi.',
            ],
            [
                'q' => 'Kenapa tidak bisa join match?',
                'a' => 'Kemungkinan slot penuh atau match sudah dimulai.',
            ],
            [
                'q' => 'Apakah sistem bisa diakses kapan saja?',
                'a' => 'Ya, sistem tersedia 24/7 kecuali saat maintenance.',
            ],
            [
                'q' => 'Apakah data saya aman?',
                'a' => 'Data kamu disimpan dengan aman dan dilindungi oleh sistem autentikasi.',
            ],
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Bantuan - {{ config('app.name', 'Spies Sport') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body
        class="min-h-screen bg-cover bg-center bg-no-repeat text-[#00004d]"
        style="background-image: url('{{ asset('assets/images/bg/bg-daftar.png') }}');"
    >
        <div class="min-h-screen bg-[rgba(255,246,215,0.72)] px-4 py-6 sm:px-6 lg:px-10">
            <div class="mx-auto max-w-6xl">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <a
                        href="{{ $backUrl }}"
                        class="inline-flex items-center gap-2 rounded-full border border-[#00004d]/15 bg-[#FFF6D7] px-4 py-2 text-sm font-semibold text-[#00004d] transition duration-200 hover:-translate-y-0.5 hover:bg-[#fdf0c5]"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.56l3.72 3.72a.75.75 0 1 1-1.06 1.06l-5-5a.75.75 0 0 1 0-1.06l5-5a.75.75 0 0 1 1.06 1.06L5.56 9.25h10.69A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                        </svg>
                        <span>Kembali</span>
                    </a>

                    <img
                        src="{{ asset('assets/images/logo/logo.png') }}"
                        alt="Logo"
                        class="h-10 w-auto sm:h-12"
                    >
                </div>

                <div class="overflow-hidden rounded-[32px] border border-[#00004d]/10 bg-[rgba(255,246,215,0.94)] shadow-[0_20px_60px_rgba(0,0,77,0.18)]">
                    <div class="grid gap-10 px-6 py-8 lg:grid-cols-[minmax(0,1.35fr)_320px] lg:px-10 lg:py-10">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#EB5436]">
                                Pusat Bantuan
                            </p>
                            <h1 class="mt-3 text-4xl font-black leading-tight sm:text-5xl">
                                Butuh Bantuan?
                            </h1>
                            <p class="mt-4 max-w-2xl text-base leading-7 text-[#00004d]/80 sm:text-lg">
                                Temukan jawaban paling cepat untuk booking lapangan, public match, akun, dan fitur lain di Spies Sport.
                            </p>

                            <div class="mt-6 rounded-[28px] border border-[#00004d]/10 bg-[#FFF6D7] p-4 shadow-[0_12px_32px_rgba(0,0,77,0.08)]">
                                <label for="searchInput" class="text-sm font-semibold text-[#EB5436]">
                                    Cari pertanyaan
                                </label>

                                <div class="relative mt-3">
                                    <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-[#00004d]/40">
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 3.473 9.766l3.63 3.63a.75.75 0 1 0 1.06-1.06l-3.629-3.63A5.5 5.5 0 0 0 9 3.5ZM5 9a4 4 0 1 1 8 0a4 4 0 0 1-8 0Z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    <input
                                        id="searchInput"
                                        type="text"
                                        placeholder="Cari booking, match, akun, atau password..."
                                        class="w-full rounded-2xl border border-[#00004d]/10 bg-[#FFF6D7] py-3 pl-11 pr-4 text-sm text-[#00004d] outline-none transition focus:border-[#EB5436] focus:ring-2 focus:ring-[#EB5436]/20"
                                    >
                                </div>

                                <p class="mt-3 text-sm leading-6 text-[#00004d]/70">
                                    Cari kata kunci yang kamu butuhkan, lalu buka jawaban yang paling sesuai.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-end justify-center">
                            <div class="relative w-full max-w-[300px]">
                                <div class="absolute inset-x-6 bottom-2 top-10 rounded-[32px] bg-[#EB5436]/15 blur-2xl"></div>
                                <img
                                    src="{{ asset('assets/images/characters/help.png') }}"
                                    alt="Karakter bantuan"
                                    class="floating relative z-10 mx-auto h-auto w-full max-w-[260px] object-contain"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-[#00004d]/10 px-6 pb-8 pt-6 lg:px-10">
                        <div id="faqList" class="space-y-8">
                            @foreach ($categories as $category => $faqs)
                                <section class="faq-category">
                                    <div class="mb-4 flex items-center gap-3">
                                        <span class="h-2.5 w-2.5 rounded-full bg-[#EB5436]"></span>
                                        <h2 class="text-xl font-bold text-[#EB5436] sm:text-2xl">
                                            {{ $category }}
                                        </h2>
                                    </div>

                                    <div class="space-y-3">
                                        @foreach ($faqs as $faq)
                                            <article class="faq-item overflow-hidden rounded-2xl border border-[#00004d]/10 bg-[#FFF6D7] shadow-[0_10px_24px_rgba(0,0,77,0.08)]">
                                                <button
                                                    type="button"
                                                    class="faq-question flex w-full items-center justify-between gap-4 px-5 py-4 text-left text-base font-semibold text-[#00004d] transition duration-200 hover:bg-[#fff0c0] focus:outline-none"
                                                >
                                                    <span>{{ $faq['q'] }}</span>
                                                    <span class="arrow flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#EB5436] text-[#FFF6D7] transition-transform duration-200" aria-hidden="true">
                                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </button>

                                                <div class="faq-answer hidden border-t border-[#00004d]/10 px-5 py-4 text-sm leading-7 text-[#00004d]/80 sm:text-base">
                                                    {{ $faq['a'] }}
                                                </div>
                                            </article>
                                        @endforeach
                                    </div>
                                </section>
                            @endforeach
                        </div>

                        <div id="emptyState" class="hidden rounded-2xl border border-dashed border-[#00004d]/20 bg-[#FFF6D7] px-6 py-8 text-center text-sm leading-6 text-[#00004d]/70 sm:text-base">
                            Tidak ada pertanyaan yang cocok. Coba pakai kata kunci lain ya.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const faqQuestions = document.querySelectorAll('.faq-question');
                const searchInput = document.getElementById('searchInput');
                const categories = document.querySelectorAll('.faq-category');
                const emptyState = document.getElementById('emptyState');

                faqQuestions.forEach((button) => {
                    button.addEventListener('click', () => {
                        const answer = button.nextElementSibling;
                        const arrow = button.querySelector('.arrow');

                        answer.classList.toggle('hidden');
                        arrow.classList.toggle('rotate-180');
                    });
                });

                if (!searchInput) {
                    return;
                }

                searchInput.addEventListener('input', () => {
                    const keyword = searchInput.value.toLowerCase().trim();
                    let visibleItems = 0;

                    categories.forEach((category) => {
                        let visibleInCategory = 0;

                        category.querySelectorAll('.faq-item').forEach((item) => {
                            const matches = item.innerText.toLowerCase().includes(keyword);

                            item.classList.toggle('hidden', !matches);

                            if (matches) {
                                visibleInCategory += 1;
                                visibleItems += 1;
                            }
                        });

                        category.classList.toggle('hidden', visibleInCategory === 0);
                    });

                    emptyState.classList.toggle('hidden', visibleItems !== 0);
                });
            });
        </script>
    </body>
</html>
