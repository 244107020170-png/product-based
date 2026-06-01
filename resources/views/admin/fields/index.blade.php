@extends('layouts.admin')
@section('title', 'Monitoring Lapangan - SPIES SPORT Admin')

@section('content')
<div class="space-y-8">
    <div class="flex items-end justify-between">
        <div>
            <h2 class="font-adm-headline text-adm-headline-lg text-adm-primary tracking-tight">Monitoring Lapangan</h2>
            <p class="text-adm-on-surface-variant font-adm-body text-adm-body-md mt-1">Pantau seluruh lapangan yang terdaftar di platform.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="text-adm-label-sm text-adm-outline">Total Lapangan</p>
                <p class="text-adm-headline-sm font-bold text-adm-primary">{{ number_format($totalFields) }}</p>
            </div>
            <div class="text-right">
                <p class="text-adm-label-sm text-adm-outline">Menunggu Verifikasi</p>
                <p class="text-adm-headline-sm font-bold text-adm-warning">{{ number_format($pendingVerification) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-adm-surface-lowest rounded-[20px] soft-shadow border border-adm-outline-variant overflow-hidden">
        <div class="px-8 py-5 border-b border-adm-outline-variant">
            <form method="GET" class="flex items-center gap-4">
                <div class="relative flex-1 max-w-md">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-adm-outline text-[20px]">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 bg-adm-surface-low border border-adm-outline-variant rounded-lg font-adm-body text-adm-body-sm focus:ring-2 focus:ring-adm-secondary-container outline-none" placeholder="Cari nama lapangan, pemilik...">
                </div>
                <select name="verification_status" class="bg-adm-surface border border-adm-outline-variant rounded-lg font-adm-body text-adm-body-sm px-3 py-2 focus:ring-2 focus:ring-adm-secondary-container outline-none" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('verification_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('verification_status') === 'approved' ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="rejected" {{ request('verification_status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <button type="submit" class="bg-adm-dark text-adm-on-primary px-4 py-2 rounded-lg font-adm-body text-adm-label-sm hover:opacity-90">Cari</button>
                @if(request('search') || request('verification_status'))
                <a href="{{ route('admin.fields') }}" class="text-adm-dark font-adm-body text-adm-label-sm hover:underline">Reset</a>
                @endif
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-adm-surface-low">
                    <tr>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase tracking-wider">Lapangan</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase tracking-wider">Pemilik</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase tracking-wider">Lokasi</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase tracking-wider">Tipe</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase tracking-wider">Total Booking</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase tracking-wider">Status</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-adm-outline-variant">
                    @forelse ($fields as $field)
                    <tr class="hover:bg-adm-surface-low/50 transition-colors">
                        <td class="px-8 py-4">
                            <p class="font-adm-body text-adm-label-md text-adm-primary">{{ $field->name }}</p>
                        </td>
                        <td class="px-8 py-4 font-adm-body text-adm-body-sm text-adm-on-surface-variant">{{ $field->owner->name ?? '-' }}</td>
                        <td class="px-8 py-4 font-adm-body text-adm-body-sm text-adm-on-surface-variant">{{ $field->location }}</td>
                        <td class="px-8 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-adm-surface-high text-adm-on-surface">{{ $field->type }}</span>
                        </td>
                        <td class="px-8 py-4 font-adm-body text-adm-body-sm text-adm-primary font-semibold">{{ number_format($field->bookings_count) }}</td>
                        <td class="px-8 py-4">
                            @php
                                $vMap = ['approved' => ['label' => 'Terverifikasi', 'class' => 'text-adm-success bg-adm-success/10'], 'pending' => ['label' => 'Pending', 'class' => 'text-adm-warning bg-adm-warning/10'], 'rejected' => ['label' => 'Ditolak', 'class' => 'text-adm-error bg-adm-error/10']];
                                $vInfo = $vMap[$field->verification_status] ?? ['label' => $field->verification_status, 'class' => 'text-adm-outline bg-adm-surface'];
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $vInfo['class'] }}">{{ $vInfo['label'] }}</span>
                        </td>
                        <td class="px-8 py-4">
                            <a href="{{ route('admin.fields.show', $field) }}" class="text-adm-dark hover:underline font-adm-body text-adm-label-sm">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-8 py-12 text-center font-adm-body text-adm-body-md text-adm-outline">Tidak ada lapangan ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($fields->hasPages())
        <div class="px-8 py-4 border-t border-adm-outline-variant">{{ $fields->links() }}</div>
        @endif
    </div>
</div>
@endsection
