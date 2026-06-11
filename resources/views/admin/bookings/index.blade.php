@extends('layouts.admin')
@section('title', 'Monitoring Pesanan - SPIES SPORT Admin')

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h2 class="font-adm-headline text-[22px] font-bold text-adm-primary tracking-tight">Monitoring Pesanan</h2>
            <p class="text-adm-on-surface-variant font-adm-body text-[13px] mt-0.5">Pantau seluruh transaksi pemesanan lapangan.</p>
        </div>
        <div>
            <p class="text-[11px] font-medium text-adm-outline tracking-wide">Total Pesanan</p>
            <p class="text-[20px] font-bold text-adm-primary leading-tight">{{ number_format($totalBookings) }}</p>
        </div>
    </div>

    <div class="bg-adm-surface-lowest rounded-[20px] soft-shadow border border-adm-outline-variant overflow-hidden">
        <div data-realtime-filter class="px-6 py-4 border-b border-adm-outline-variant">
            <div class="flex flex-col gap-3">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div class="w-[45%] min-w-[220px]">
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 inset-y-0 flex items-center leading-none text-adm-outline text-[18px]">search</span>
                            <input type="text" name="search" value="{{ request('search') }}" class="w-full h-10 pl-10 pr-4 bg-adm-surface-low border border-adm-outline-variant rounded-lg font-adm-body text-[13px] placeholder:text-adm-outline/50 focus:ring-2 focus:ring-adm-secondary-container outline-none" placeholder="Cari user atau lapangan...">
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="relative w-[180px] h-10 flex-shrink-0">
                            <select name="status" class="w-full h-full appearance-none bg-adm-surface border border-adm-outline-variant rounded-lg font-adm-body text-[13px] pl-3 pr-8 focus:ring-2 outline-none cursor-pointer">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="waiting_payment" {{ request('status') === 'waiting_payment' ? 'selected' : '' }}>Menunggu Bayar</option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Dibayar</option>
                                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-1.5 inset-y-0 flex items-center leading-none text-adm-outline text-[18px] pointer-events-none">expand_more</span>
                        </div>
                        <input type="hidden" name="sort" id="sort-input-bookings" value="{{ request('sort', 'latest') }}">
                        <button type="button" data-sort="latest" class="sort-btn w-[100px] h-10 flex-shrink-0 rounded-lg font-adm-body text-[12px] font-semibold transition-all cursor-pointer border-none {{ request('sort', 'latest') === 'latest' ? 'bg-adm-dark text-adm-on-primary' : 'bg-adm-surface-low text-adm-on-surface-variant hover:bg-adm-surface-high' }}">Terbaru</button>
                        <button type="button" data-sort="oldest" class="sort-btn w-[100px] h-10 flex-shrink-0 rounded-lg font-adm-body text-[12px] font-semibold transition-all cursor-pointer border-none {{ request('sort') === 'oldest' ? 'bg-adm-dark text-adm-on-primary' : 'bg-adm-surface-low text-adm-on-surface-variant hover:bg-adm-surface-high' }}">Terlama</button>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <div class="flex flex-col gap-1">
                        <span class="text-[11px] font-medium text-adm-outline">Dari Tanggal</span>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-[180px] h-10 flex-shrink-0 bg-adm-surface border border-adm-outline-variant rounded-lg font-adm-body text-[13px] px-3 focus:ring-2 outline-none">
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-[11px] font-medium text-adm-outline">Sampai Tanggal</span>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-[180px] h-10 flex-shrink-0 bg-adm-surface border border-adm-outline-variant rounded-lg font-adm-body text-[13px] px-3 focus:ring-2 outline-none">
                    </div>
                    @if(request()->anyFilled(['search', 'status', 'date_from', 'date_to']))
                    <a href="#" onclick="resetFilters(event)" class="flex-shrink-0 text-adm-dark font-adm-body text-[12px] font-medium hover:opacity-80">Reset</a>
                    @endif
                </div>
            </div>
        </div>
        <div data-realtime-results>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-adm-surface-low">
                    <tr>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase">Pengguna</th>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase">Lapangan</th>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase">Pemilik</th>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase">Tanggal</th>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-adm-outline-variant">
                    @forelse ($bookings as $booking)
                    <tr class="hover:bg-adm-surface-low/50 transition-colors">
                        <td class="px-6 py-3.5">
                            <p class="font-adm-body text-[13px] font-semibold text-adm-primary">{{ $booking->user->name ?? '-' }}</p>
                            <p class="text-[12px] text-adm-outline">{{ $booking->user->email ?? '' }}</p>
                        </td>
                        <td class="px-6 py-3.5 font-adm-body text-[13px] text-adm-primary">{{ $booking->field->name ?? '-' }}</td>
                        <td class="px-6 py-3.5 font-adm-body text-[13px] text-adm-on-surface-variant">{{ $booking->field->owner->name ?? '-' }}</td>
                        <td class="px-6 py-3.5 font-adm-body text-[13px] text-adm-on-surface-variant">{{ $booking->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-3.5">
                            @php
                                $sMap = ['pending' => ['Menunggu', 'bg-adm-warning/10 text-adm-warning'], 'waiting_payment' => ['Menunggu Bayar', 'bg-adm-warning/10 text-adm-warning'], 'paid' => ['Dibayar', 'bg-adm-accent/10 text-adm-accent'], 'confirmed' => ['Dikonfirmasi', 'bg-adm-accent/10 text-adm-accent'], 'completed' => ['Selesai', 'bg-adm-success/10 text-adm-success'], 'cancelled' => ['Dibatalkan', 'bg-adm-error/10 text-adm-error'], 'expired' => ['Kedaluwarsa', 'bg-adm-error/10 text-adm-error'], 'rejected' => ['Ditolak', 'bg-adm-error/10 text-adm-error']];
                                $sInfo = $sMap[$booking->status] ?? [$booking->status, 'bg-adm-surface text-adm-outline'];
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sInfo[1] }}">{{ $sInfo[0] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center font-adm-body text-[13px] text-adm-outline">Tidak ada pesanan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($bookings->hasPages())
        <div class="px-6 py-3.5 border-t border-adm-outline-variant">{{ $bookings->links() }}</div>
        @endif
        </div>
    </div>
</div>
@endsection
