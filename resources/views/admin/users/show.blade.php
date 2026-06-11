@extends('layouts.admin')
@section('title', 'Detail Pengguna - SPIES SPORT Admin')

@section('content')
<div class="space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.users') }}" class="flex items-center gap-2 text-adm-outline hover:text-adm-dark transition-colors no-underline">
            <span class="material-symbols-outlined">arrow_back</span>
            <span class="font-adm-body text-adm-label-md">Kembali</span>
        </a>
    </div>

    <div class="bg-adm-surface-lowest rounded-[20px] soft-shadow border border-adm-outline-variant p-8">
        <div class="flex items-start gap-6">
            <div class="w-20 h-20 rounded-full bg-adm-secondary-container flex items-center justify-center font-bold text-adm-on-secondary-container text-2xl flex-shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h2 class="font-adm-headline text-adm-headline-md text-adm-primary">{{ $user->name }}</h2>
                <p class="text-adm-on-surface-variant font-adm-body text-adm-body-md mt-1">{{ $user->email }}</p>
                <div class="flex items-center gap-4 mt-4">
                    <span class="px-3 py-1 bg-adm-secondary-container/20 text-adm-on-secondary-container rounded-full font-adm-body text-adm-label-sm">Pemain</span>
                    <span class="text-adm-body-sm text-adm-outline">Bergabung {{ $user->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-4 gap-6 mt-8 pt-8 border-t border-adm-outline-variant">
            <div class="text-center">
                <p class="text-adm-headline-sm font-bold text-adm-primary">{{ number_format($user->bookings_count) }}</p>
                <p class="text-adm-label-sm text-adm-outline">Pesanan</p>
            </div>
            <div class="text-center">
                <p class="text-adm-headline-sm font-bold text-adm-primary">{{ number_format($user->reviews_count) }}</p>
                <p class="text-adm-label-sm text-adm-outline">Ulasan</p>
            </div>
            <div class="text-center">
                <p class="text-adm-headline-sm font-bold text-adm-primary">{{ number_format($user->joined_matches_count) }}</p>
                <p class="text-adm-label-sm text-adm-outline">Pertandingan</p>
            </div>
            <div class="text-center">
                <p class="text-adm-headline-sm font-bold text-adm-primary">{{ number_format($user->favorites_count) }}</p>
                <p class="text-adm-label-sm text-adm-outline">Favorit</p>
            </div>
        </div>
    </div>

    @if ($user->bookings->isNotEmpty())
    <div class="bg-adm-surface-lowest rounded-[20px] soft-shadow border border-adm-outline-variant overflow-hidden">
        <div class="px-8 py-5 border-b border-adm-outline-variant">
            <h3 class="font-adm-headline text-adm-headline-sm text-adm-primary">Riwayat Pesanan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-adm-surface-low">
                    <tr>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase">Lapangan</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase">Tanggal</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-adm-outline-variant">
                    @foreach ($user->bookings as $booking)
                    <tr class="hover:bg-adm-surface-low/50 transition-colors">
                        <td class="px-8 py-4 font-adm-body text-adm-label-md text-adm-primary">{{ $booking->field->name ?? '-' }}</td>
                        <td class="px-8 py-4 font-adm-body text-adm-body-sm text-adm-on-surface-variant">{{ $booking->created_at->format('d M Y H:i') }}</td>
                        <td class="px-8 py-4">
                            @php
                                $sMap = ['pending' => ['Menunggu', 'bg-adm-warning/10 text-adm-warning'], 'waiting_payment' => ['Menunggu Bayar', 'bg-adm-warning/10 text-adm-warning'], 'paid' => ['Dibayar', 'bg-adm-accent/10 text-adm-accent'], 'confirmed' => ['Dikonfirmasi', 'bg-adm-accent/10 text-adm-accent'], 'completed' => ['Selesai', 'bg-adm-success/10 text-adm-success'], 'cancelled' => ['Dibatalkan', 'bg-adm-error/10 text-adm-error'], 'expired' => ['Kedaluwarsa', 'bg-adm-error/10 text-adm-error'], 'rejected' => ['Ditolak', 'bg-adm-error/10 text-adm-error']];
                                $sInfo = $sMap[$booking->status] ?? [$booking->status, 'bg-adm-surface text-adm-outline'];
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sInfo[1] }}">{{ $sInfo[0] }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
