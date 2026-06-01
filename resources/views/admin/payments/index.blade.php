@extends('layouts.admin')
@section('title', 'Monitoring Pembayaran - SPIES SPORT Admin')

@section('content')
<div class="space-y-8">
    <div class="flex items-end justify-between">
        <div>
            <h2 class="font-adm-headline text-adm-headline-lg text-adm-primary tracking-tight">Monitoring Pembayaran</h2>
            <p class="text-adm-on-surface-variant font-adm-body text-adm-body-md mt-1">Pantau seluruh transaksi pembayaran platform.</p>
        </div>
        <div class="text-right">
            <p class="text-adm-label-sm text-adm-outline">Total Pendapatan</p>
            <p class="text-adm-headline-sm font-bold text-adm-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6">
        <div class="bg-adm-surface-lowest p-6 rounded-[20px] soft-shadow border border-adm-outline-variant">
            <p class="text-adm-label-sm text-adm-outline">Berhasil</p>
            <p class="text-adm-headline-md font-bold text-adm-success">{{ number_format($successCount) }}</p>
        </div>
        <div class="bg-adm-surface-lowest p-6 rounded-[20px] soft-shadow border border-adm-outline-variant">
            <p class="text-adm-label-sm text-adm-outline">Pending</p>
            <p class="text-adm-headline-md font-bold text-adm-warning">{{ number_format($pendingCount) }}</p>
        </div>
        <div class="bg-adm-surface-lowest p-6 rounded-[20px] soft-shadow border border-adm-outline-variant">
            <p class="text-adm-label-sm text-adm-outline">Dibatalkan / Gagal</p>
            <p class="text-adm-headline-md font-bold text-adm-error">{{ number_format($cancelledCount) }}</p>
        </div>
    </div>

    <div class="bg-adm-surface-lowest rounded-[20px] soft-shadow border border-adm-outline-variant overflow-hidden">
        <div class="px-8 py-5 border-b border-adm-outline-variant">
            <form method="GET" class="flex items-center gap-4">
                <div class="relative flex-1 max-w-md">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-adm-outline text-[20px]">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 bg-adm-surface-low border border-adm-outline-variant rounded-lg font-adm-body text-adm-body-sm focus:ring-2 focus:ring-adm-secondary-container outline-none" placeholder="Cari transaksi...">
                </div>
                <select name="status" class="bg-adm-surface border border-adm-outline-variant rounded-lg font-adm-body text-adm-body-sm px-3 py-2 focus:ring-2 outline-none" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Dibayar</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <button type="submit" class="bg-adm-dark text-adm-on-primary px-4 py-2 rounded-lg font-adm-body text-adm-label-sm hover:opacity-90">Filter</button>
                @if(request('search') || request('status'))
                <a href="{{ route('admin.payments') }}" class="text-adm-dark font-adm-body text-adm-label-sm hover:underline">Reset</a>
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
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase">Tanggal Bayar</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-adm-outline-variant">
                    @forelse ($payments as $payment)
                    <tr class="hover:bg-adm-surface-low/50 transition-colors">
                        <td class="px-8 py-4 font-adm-body text-adm-label-md text-adm-primary">{{ $payment->user->name ?? '-' }}</td>
                        <td class="px-8 py-4 font-adm-body text-adm-label-md text-adm-primary">{{ $payment->field->name ?? '-' }}</td>
                        <td class="px-8 py-4 font-adm-body text-adm-body-sm text-adm-on-surface-variant">{{ $payment->field->owner->name ?? '-' }}</td>
                        <td class="px-8 py-4 font-adm-body text-adm-body-sm text-adm-on-surface-variant">{{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '-' }}</td>
                        <td class="px-8 py-4">
                            @php
                                $sMap = ['paid' => ['Dibayar', 'bg-adm-accent/10 text-adm-accent'], 'confirmed' => ['Dikonfirmasi', 'bg-adm-success/10 text-adm-success'], 'completed' => ['Selesai', 'bg-adm-success/10 text-adm-success'], 'cancelled' => ['Dibatalkan', 'bg-adm-error/10 text-adm-error']];
                                $sInfo = $sMap[$payment->status] ?? [$payment->status, 'bg-adm-surface text-adm-outline'];
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sInfo[1] }}">{{ $sInfo[0] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-8 py-12 text-center font-adm-body text-adm-body-md text-adm-outline">Belum ada transaksi pembayaran</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($payments->hasPages())
        <div class="px-8 py-4 border-t border-adm-outline-variant">{{ $payments->links() }}</div>
        @endif
    </div>
</div>
@endsection
