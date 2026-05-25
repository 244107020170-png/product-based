@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#FFFEF0] to-[#FFF6D7] p-4">
    <div class="text-center max-w-md">
        <!-- Error Icon -->
        <div class="mb-6">
            <svg class="mx-auto w-24 h-24 text-[#EB5436]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4v2m0-12a9 9 0 110 18 9 9 0 010-18z"></path>
            </svg>
        </div>

        <!-- Error Code & Message -->
        <h1 class="text-7xl font-bold text-[#EB5436] mb-4">403</h1>
        <h2 class="text-3xl font-bold text-[#00004D] mb-2">Akses Ditolak</h2>
        <p class="text-gray-600 mb-2">{{ $exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}</p>
        
        <!-- Detailed Info -->
        <div class="bg-[#FFF6D7] border-l-4 border-[#FED56F] rounded-lg p-4 mb-8 text-left text-sm text-[#00004D]">
            <p class="font-semibold mb-2">ℹ️ Informasi:</p>
            <ul class="list-disc list-inside space-y-1">
                <li>Role Anda: <strong>{{ auth()->user()?->role ?? 'Guest' }}</strong></li>
                <li>Halaman: <strong>{{ request()->path() }}</strong></li>
                <li>Hanya pengguna dengan role yang tepat yang dapat mengakses halaman ini.</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
            <a href="{{ route('dashboard') }}" 
               class="inline-block bg-[#EB5436] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#d93d2a] transition-colors duration-300 shadow-md hover:shadow-lg w-full">
                Kembali ke Dashboard
            </a>
            
            <a href="{{ route('home') }}" 
               class="inline-block bg-[#FED56F] text-[#00004D] px-8 py-3 rounded-lg font-semibold hover:bg-[#FEC840] transition-colors duration-300 w-full">
                Halaman Utama
            </a>
        </div>

        <!-- Help Section -->
        <div class="mt-8 text-sm text-gray-500">
            <p>Jika Anda merasa ini adalah kesalahan, silakan <a href="{{ route('home') }}" class="text-[#EB5436] hover:underline">hubungi administrator</a>.</p>
        </div>
    </div>
</div>
@endsection
