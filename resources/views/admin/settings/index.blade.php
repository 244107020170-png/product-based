@extends('layouts.admin')
@section('title', 'Pengaturan Platform - SPIES SPORT Admin')

@section('content')
<div class="space-y-8">
    <div class="flex items-end justify-between">
        <div>
            <h2 class="font-adm-headline text-adm-headline-lg text-adm-primary tracking-tight">Pengaturan Platform</h2>
            <p class="text-adm-on-surface-variant font-adm-body text-adm-body-md mt-1">Kelola pengaturan umum platform SPIES SPORT.</p>
        </div>
    </div>

    @if (session('success'))
    <div class="bg-adm-success/10 border border-adm-success/30 text-adm-success px-6 py-4 rounded-xl font-adm-body text-adm-body-sm">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-adm-surface-lowest rounded-[20px] soft-shadow border border-adm-outline-variant p-8">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block font-adm-body text-adm-label-md text-adm-primary mb-2">Nama Platform</label>
                    <input type="text" name="platform_name" value="{{ $settings['platform_name'] }}"
                           class="w-full max-w-xl px-4 py-3 bg-adm-surface-low border border-adm-outline-variant rounded-lg font-adm-body text-adm-body-sm focus:ring-2 focus:ring-adm-secondary-container outline-none">
                </div>
                <div>
                    <label class="block font-adm-body text-adm-label-md text-adm-primary mb-2">Email Support</label>
                    <input type="email" name="email_support" value="{{ $settings['email_support'] }}"
                           class="w-full max-w-xl px-4 py-3 bg-adm-surface-low border border-adm-outline-variant rounded-lg font-adm-body text-adm-body-sm focus:ring-2 focus:ring-adm-secondary-container outline-none">
                </div>
                <div>
                    <label class="block font-adm-body text-adm-label-md text-adm-primary mb-2">Nomor WhatsApp Support</label>
                    <input type="text" name="whatsapp_support" value="{{ $settings['whatsapp_support'] }}"
                           class="w-full max-w-xl px-4 py-3 bg-adm-surface-low border border-adm-outline-variant rounded-lg font-adm-body text-adm-body-sm focus:ring-2 focus:ring-adm-secondary-container outline-none">
                </div>
                <div>
                    <label class="block font-adm-body text-adm-label-md text-adm-primary mb-2">Alamat</label>
                    <textarea name="address" rows="3"
                              class="w-full max-w-xl px-4 py-3 bg-adm-surface-low border border-adm-outline-variant rounded-lg font-adm-body text-adm-body-sm focus:ring-2 focus:ring-adm-secondary-container outline-none">{{ $settings['address'] }}</textarea>
                </div>
                <div class="pt-4 border-t border-adm-outline-variant">
                    <button type="submit" class="bg-adm-dark text-adm-on-primary px-8 py-3 rounded-lg font-adm-body text-adm-label-md hover:opacity-90 transition-all active:scale-95">
                        Simpan Pengaturan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
