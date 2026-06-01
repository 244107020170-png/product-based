@extends('layouts.admin')
@section('title', 'Monitoring Pesanan - SPIES SPORT Admin')

@section('content')
<div class="space-y-8">
    <div class="flex items-end justify-between">
        <div>
            <h2 class="font-adm-headline text-adm-headline-lg text-adm-primary tracking-tight">Monitoring Pesanan</h2>
            <p class="text-adm-on-surface-variant font-adm-body text-adm-body-md mt-1">Pantau seluruh transaksi pemesanan lapangan.</p>
        </div>
        <div class="text-right">
            <p class="text-adm-label-sm text-adm-outline">Total Pesanan</p>
            <p class="text-adm-headline-sm font-bold text-adm-primary">{{ number_format($totalBookings) }}</p>
        </div>
    </div>

    <div class="bg-adm-surface-lowest rounded-[20px] soft-shadow border border-adm-outline-variant overflow-hidden">
        <div class="px-8 py-5 border-b border-adm-outline-variant">
            <form method="GET" class="flex items-center gap-4 flex-wrap">
                <div class="relative flex-1 min-w-[200px] max-w-sm">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-adm-outline text-[20px]">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 bg-adm-surface-low border border-adm-outline-variant rounded-lg font-adm-body text-adm-body-sm focus:ring-2 focus:ring-adm-secondary-container outline-none" placeholder="Cari user atau lapangan...">
                </div>
                <select name="status" class="bg-adm-surface border border-adm-outline-variant rounded-lg font-adm-body text-adm-body-sm px-3 py-2 focus:ring-2 outline-none" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="waiting_payment" {{ request('status') === 'waiting_payment' ? 'selected' : '' }}>Menunggu Bayar</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Dibayar</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="bg-adm-surface border border-adm-outline-variant rounded-lg font-adm-body text-adm-body-sm px-3 py-2 focus:ring-2 outline-none">
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="bg-adm-surface border border-adm-outline-variant rounded-lg font-adm-body text-adm-body-sm px-3 py-2 focus:ring-2 outline-none">
                <button type="submit" class="bg-adm-dark text-adm-on-primary px-4 py-2 rounded-lg font-adm-body text-adm-label-sm hover:opacity-90">Filter</button>
                @if(request()->anyFilled(['search', 'status', 'date_from', 'date_to']))
                <a href="{{ route('admin.bookings') }}" class="text-adm-dark font-adm-body text-adm-label-sm hover:underline">Reset</a>
                @endif
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-adm-surface-low">
                    <tr>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase">User</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase">Lapangan</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase">Pemilik</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase">Tanggal</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-adm-outline-variant">
                    @forelse ($bookings as $booking)
                    <tr class="hover:bg-adm-surface-low/50 transition-colors">
                        <td class="px-8 py-4">
                            <p class="font-adm-body text-adm-label-md text-adm-primary">{{ $booking->user->name ?? '-' }}</p>
                            <p class="text-[12px] text-adm-outline">{{ $booking->user->email ?? '' }}</p>
                        </td>
                        <td class="px-8 py-4 font-adm-body text-adm-label-md text-adm-primary">{{ $booking->field->name ?? '-' }}</td>
                        <td class="px-8 py-4 font-adm-body text-adm-body-sm text-adm-on-surface-variant">{{ $booking->field->owner->name ?? '-' }}</td>
                        <td class="px-8 py-4 font-adm-body text-adm-body-sm text-adm-on-surface-variant">{{ $booking->created_at->format('d M Y H:i') }}</td>
                        <td class="px-8 py-4">
                            @php
                                $sMap = ['pending' => ['Pending', 'bg-adm-warning/10 text-adm-warning'], 'waiting_payment' => ['Menunggu Bayar', 'bg-adm-warning/10 text-adm-warning'], 'paid' => ['Dibayar', 'bg-adm-accent/10 text-adm-accent'], 'confirmed' => ['Dikonfirmasi', 'bg-adm-success/10 text-adm-success'], 'completed' => ['Selesai', 'bg-adm-success/10 text-adm-success'], 'cancelled' => ['Dibatalkan', 'bg-adm-error/10 text-adm-error'], 'expired' => ['Kedaluwarsa', 'bg-adm-error/10 text-adm-error'], 'rejected' => ['Ditolak', 'bg-adm-error/10 text-adm-error']];
                                $sInfo = $sMap[$booking->status] ?? [$booking->status, 'bg-adm-surface text-adm-outline'];
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sInfo[1] }}">{{ $sInfo[0] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-8 py-12 text-center font-adm-body text-adm-body-md text-adm-outline">Tidak ada pesanan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($bookings->hasPages())
        <div class="px-8 py-4 border-t border-adm-outline-variant">{{ $bookings->links() }}</div>
        @endif
    </div>
</div>
@endsection
