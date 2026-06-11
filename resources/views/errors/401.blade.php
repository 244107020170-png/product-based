@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#FFFEF0] to-[#FFF6D7] p-4">
    <div class="text-center max-w-md">
        <!-- Error Icon -->
        <div class="mb-6">
            <svg class="mx-auto w-24 h-24 text-[#EB5436]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>

        <!-- Error Code & Message -->
        <h1 class="text-7xl font-bold text-[#EB5436] mb-4">401</h1>
        <h2 class="text-3xl font-bold text-[#00004D] mb-2">Silakan Masuk</h2>
        <p class="text-gray-600 mb-6">Anda harus login terlebih dahulu untuk mengakses halaman ini.</p>
        
        <!-- Info Alert -->
        <div class="bg-[#FFF6D7] border-l-4 border-[#FED56F] rounded-lg p-4 mb-8 text-left text-sm text-[#00004D]">
            <p class="font-semibold mb-2">ℹ️ Catatan:</p>
            <p>Halaman yang Anda akses memerlukan autentikasi. Silakan login dengan akun Anda untuk melanjutkan.</p>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
            <a href="{{ route('login') }}" 
               class="inline-block bg-[#EB5436] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#d93d2a] transition-colors duration-300 shadow-md hover:shadow-lg w-full">
                Masuk Sekarang
            </a>
            
            <a href="{{ route('home') }}" 
               class="inline-block bg-[#FED56F] text-[#00004D] px-8 py-3 rounded-lg font-semibold hover:bg-[#FEC840] transition-colors duration-300 w-full">
                Halaman Utama
            </a>
        </div>

        <!-- Register Link -->
        <div class="mt-8 text-sm text-gray-600">
            <p>Belum memiliki akun? <a href="{{ route('register') }}" class="text-[#EB5436] font-semibold hover:underline">Daftar di sini</a></p>
        </div>
    </div>
</div>
@endsection
