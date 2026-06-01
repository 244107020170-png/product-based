@extends('layouts.admin')
@section('title', 'Monitoring Pemilik - SPIES SPORT Admin')

@section('content')
<div class="space-y-8">
    <div class="flex items-end justify-between">
        <div>
            <h2 class="font-adm-headline text-adm-headline-lg text-adm-primary tracking-tight">Monitoring Pemilik</h2>
            <p class="text-adm-on-surface-variant font-adm-body text-adm-body-md mt-1">Pantau seluruh pemilik lapangan di platform.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="text-adm-label-sm text-adm-outline">Total Pemilik</p>
                <p class="text-adm-headline-sm font-bold text-adm-primary">{{ number_format($totalOwners) }}</p>
            </div>
            <div class="text-right">
                <p class="text-adm-label-sm text-adm-outline">Aktif Hari Ini</p>
                <p class="text-adm-headline-sm font-bold text-adm-success">{{ number_format($activeToday) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-adm-surface-lowest rounded-[20px] soft-shadow border border-adm-outline-variant overflow-hidden">
        <div class="px-8 py-5 border-b border-adm-outline-variant">
            <form method="GET" class="flex items-center gap-4">
                <div class="relative flex-1 max-w-md">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-adm-outline text-[20px]">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 bg-adm-surface-low border border-adm-outline-variant rounded-lg font-adm-body text-adm-body-sm focus:ring-2 focus:ring-adm-secondary-container outline-none" placeholder="Cari nama pemilik...">
                </div>
                <button type="submit" class="bg-adm-dark text-adm-on-primary px-4 py-2 rounded-lg font-adm-body text-adm-label-sm hover:opacity-90">Cari</button>
                @if(request('search'))
                <a href="{{ route('admin.owners') }}" class="text-adm-dark font-adm-body text-adm-label-sm hover:underline">Reset</a>
                @endif
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-adm-surface-low">
                    <tr>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase tracking-wider">Pemilik</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase tracking-wider">Total Lapangan</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase tracking-wider">Rating</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase tracking-wider">Bergabung</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-adm-outline-variant">
                    @forelse ($owners as $owner)
                    <tr class="hover:bg-adm-surface-low/50 transition-colors">
                        <td class="px-8 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-adm-primary-container flex items-center justify-center font-bold text-white text-sm flex-shrink-0">
                                    {{ strtoupper(substr($owner->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-adm-body text-adm-label-md text-adm-primary">{{ $owner->name }}</p>
                                    <p class="text-[12px] text-adm-outline">{{ $owner->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-4 font-adm-body text-adm-body-sm text-adm-primary font-semibold">{{ number_format($owner->fields_count) }}</td>
                        <td class="px-8 py-4">
                            <span class="flex items-center gap-1 text-adm-warning">
                                <span class="material-symbols-outlined text-[16px]">star</span>
                                {{ number_format($owner->fields_avg_rating ?? 0, 1) }}
                            </span>
                        </td>
                        <td class="px-8 py-4 font-adm-body text-adm-body-sm text-adm-on-surface-variant">{{ $owner->created_at->format('d M Y') }}</td>
                        <td class="px-8 py-4">
                            <a href="{{ route('admin.owners.show', $owner) }}" class="text-adm-dark hover:underline font-adm-body text-adm-label-sm">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center font-adm-body text-adm-body-md text-adm-outline">Tidak ada pemilik ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($owners->hasPages())
        <div class="px-8 py-4 border-t border-adm-outline-variant">{{ $owners->links() }}</div>
        @endif
    </div>
</div>
@endsection
