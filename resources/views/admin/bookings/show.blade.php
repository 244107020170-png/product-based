@extends('layouts.admin')
@section('title', 'Detail Pesanan - SPIES SPORT Admin')

@section('content')
<div class="space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.bookings') }}" class="flex items-center gap-2 text-adm-outline hover:text-adm-dark transition-colors no-underline">
            <span class="material-symbols-outlined">arrow_back</span>
            <span class="font-adm-body text-adm-label-md">Kembali</span>
        </a>
    </div>

    <div class="bg-adm-surface-lowest rounded-[20px] soft-shadow border border-adm-outline-variant p-8">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="font-adm-headline text-adm-headline-md text-adm-primary">Detail Pesanan #{{ $booking->id }}</h2>
                <p class="text-adm-on-surface-variant font-adm-body text-adm-body-md mt-1">{{ $booking->created_at->format('d M Y H:i') }}</p>
            </div>
            @php
                $sMap = ['pending' => ['Pending', 'bg-adm-warning/10 text-adm-warning'], 'waiting_payment' => ['Menunggu Bayar', 'bg-adm-warning/10 text-adm-warning'], 'paid' => ['Dibayar', 'bg-adm-accent/10 text-adm-accent'], 'confirmed' => ['Dikonfirmasi', 'bg-adm-accent/10 text-adm-accent'], 'completed' => ['Selesai', 'bg-adm-success/10 text-adm-success'], 'cancelled' => ['Dibatalkan', 'bg-adm-error/10 text-adm-error'], 'expired' => ['Kedaluwarsa', 'bg-adm-error/10 text-adm-error']];
                $sInfo = $sMap[$booking->status] ?? [$booking->status, 'bg-adm-surface text-adm-outline'];
            @endphp
            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $sInfo[1] }}">{{ $sInfo[0] }}</span>
        </div>

        <div class="grid grid-cols-2 gap-8 mt-8 pt-8 border-t border-adm-outline-variant">
            <div>
                <h3 class="font-adm-body text-adm-label-md text-adm-outline uppercase mb-4">Informasi Pengguna</h3>
                <p class="font-adm-body text-adm-label-md text-adm-primary">{{ $booking->user->name ?? '-' }}</p>
                <p class="text-adm-body-sm text-adm-outline">{{ $booking->user->email ?? '-' }}</p>
            </div>
            <div>
                <h3 class="font-adm-body text-adm-label-md text-adm-outline uppercase mb-4">Informasi Lapangan</h3>
                <p class="font-adm-body text-adm-label-md text-adm-primary">{{ $booking->field->name ?? '-' }}</p>
                <p class="text-adm-body-sm text-adm-outline">Pemilik: {{ $booking->field->owner->name ?? '-' }}</p>
                <p class="text-adm-body-sm text-adm-outline">{{ $booking->field->location ?? '' }}</p>
            </div>
        </div>

        <div class="mt-8 pt-8 border-t border-adm-outline-variant">
            <h3 class="font-adm-body text-adm-label-md text-adm-outline uppercase mb-4">Detail Pesanan</h3>
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-adm-surface-low rounded-xl p-4">
                    <p class="text-adm-label-sm text-adm-outline">Tanggal</p>
                    <p class="font-adm-body text-adm-label-md text-adm-primary">{{ $booking->date->format('d M Y') }}</p>
                </div>
                <div class="bg-adm-surface-low rounded-xl p-4">
                    <p class="text-adm-label-sm text-adm-outline">Jam</p>
                    <p class="font-adm-body text-adm-label-md text-adm-primary">{{ $booking->start_time }} - {{ $booking->end_time }}</p>
                </div>
                <div class="bg-adm-surface-low rounded-xl p-4">
                    <p class="text-adm-label-sm text-adm-outline">Total Bayar</p>
                    <p class="font-adm-body text-adm-label-md text-adm-dark font-bold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
