@extends('layouts.admin')
@section('title', 'Monitoring Sistem - SPIES SPORT Admin')

@section('content')
<div class="space-y-8">
    <div class="flex items-end justify-between">
        <div>
            <h2 class="font-adm-headline text-adm-headline-lg text-adm-primary tracking-tight">Monitoring Sistem</h2>
            <p class="text-adm-on-surface-variant font-adm-body text-adm-body-md mt-1">Status layanan dan performa platform.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($services as $key => $service)
        <div class="bg-adm-surface-lowest p-6 rounded-[20px] soft-shadow border border-adm-outline-variant">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full {{ $service['status'] ? 'bg-adm-success/10 text-adm-success' : 'bg-adm-error/10 text-adm-error' }} flex items-center justify-center">
                    <span class="material-symbols-outlined">{{ $service['icon'] }}</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-adm-body text-adm-label-md text-adm-primary">{{ $service['name'] }}</h3>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="w-2 h-2 rounded-full {{ $service['status'] ? 'bg-adm-success' : 'bg-adm-error' }}"></span>
                        <span class="font-adm-body text-adm-label-sm {{ $service['status'] ? 'text-adm-success' : 'text-adm-error' }}">
                            {{ $service['status'] ? 'Online' : 'Offline' }}
                        </span>
                    </div>
                </div>
                <span class="material-symbols-outlined text-adm-outline">check_circle</span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="bg-adm-surface-lowest rounded-[20px] soft-shadow border border-adm-outline-variant p-8">
        <h3 class="font-adm-headline text-adm-headline-sm text-adm-primary mb-6">Statistik Sistem</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center p-4 bg-adm-surface-low rounded-xl">
                <p class="text-adm-label-sm text-adm-outline">Total User</p>
                <p class="text-adm-headline-md font-bold text-adm-primary">{{ number_format($stats['total_users']) }}</p>
            </div>
            <div class="text-center p-4 bg-adm-surface-low rounded-xl">
                <p class="text-adm-label-sm text-adm-outline">Total Booking</p>
                <p class="text-adm-headline-md font-bold text-adm-primary">{{ number_format($stats['total_bookings']) }}</p>
            </div>
            <div class="text-center p-4 bg-adm-surface-low rounded-xl">
                <p class="text-adm-label-sm text-adm-outline">Total Komunitas</p>
                <p class="text-adm-headline-md font-bold text-adm-primary">{{ number_format($stats['total_communities']) }}</p>
            </div>
            <div class="text-center p-4 bg-adm-surface-low rounded-xl">
                <p class="text-adm-label-sm text-adm-outline">Ukuran Database</p>
                <p class="text-adm-headline-md font-bold text-adm-primary">{{ $stats['db_size'] }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
