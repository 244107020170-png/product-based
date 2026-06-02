@extends('layouts.admin')
@section('title', 'Monitoring Pemilik - SPIES SPORT Admin')

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h2 class="font-adm-headline text-[22px] font-bold text-adm-primary tracking-tight">Monitoring Pemilik</h2>
            <p class="text-adm-on-surface-variant font-adm-body text-[13px] mt-0.5">Pantau seluruh pemilik lapangan di platform.</p>
        </div>
        <div class="flex items-center gap-6">
            <div>
                <p class="text-[11px] font-medium text-adm-outline tracking-wide">Total Pemilik</p>
                <p class="text-[20px] font-bold text-adm-primary leading-tight">{{ number_format($totalOwners) }}</p>
            </div>
            <div>
                <p class="text-[11px] font-medium text-adm-outline tracking-wide">Aktif Hari Ini</p>
                <p class="text-[20px] font-bold text-adm-success leading-tight">{{ number_format($activeToday) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-adm-surface-lowest rounded-[20px] soft-shadow border border-adm-outline-variant overflow-hidden">
        <div data-realtime-filter class="px-6 py-4 border-b border-adm-outline-variant">
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div class="w-[45%] min-w-[220px]">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 inset-y-0 flex items-center leading-none text-adm-outline text-[18px]">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" class="w-full h-10 pl-10 pr-4 bg-adm-surface-low border border-adm-outline-variant rounded-lg font-adm-body text-[13px] placeholder:text-adm-outline/50 focus:ring-2 focus:ring-adm-secondary-container outline-none" placeholder="Cari nama pemilik...">
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="sort" id="sort-input-owners" value="{{ request('sort', 'latest') }}">
                    <button type="button" data-sort="latest" class="sort-btn w-[100px] h-10 flex-shrink-0 rounded-lg font-adm-body text-[12px] font-semibold transition-all cursor-pointer border-none {{ request('sort', 'latest') === 'latest' ? 'bg-adm-dark text-adm-on-primary' : 'bg-adm-surface-low text-adm-on-surface-variant hover:bg-adm-surface-high' }}">Terbaru</button>
                    <button type="button" data-sort="oldest" class="sort-btn w-[100px] h-10 flex-shrink-0 rounded-lg font-adm-body text-[12px] font-semibold transition-all cursor-pointer border-none {{ request('sort') === 'oldest' ? 'bg-adm-dark text-adm-on-primary' : 'bg-adm-surface-low text-adm-on-surface-variant hover:bg-adm-surface-high' }}">Terlama</button>
                    @if(request('search') || request('sort'))
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
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase tracking-wider">Pemilik</th>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase tracking-wider">Total Lapangan</th>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase tracking-wider">Rating</th>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase tracking-wider">Bergabung</th>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-adm-outline-variant">
                    @forelse ($owners as $owner)
                    <tr class="hover:bg-adm-surface-low/50 transition-colors">
                        <td class="px-6 py-3.5">
                <div class="flex items-center gap-2">
                                <div class="w-9 h-9 rounded-full bg-adm-primary-container flex items-center justify-center font-bold text-white text-sm flex-shrink-0">
                                    {{ strtoupper(substr($owner->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-adm-body text-[13px] font-semibold text-adm-primary">{{ $owner->name }}</p>
                                    <p class="text-[12px] text-adm-outline">{{ $owner->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3.5 font-adm-body text-[13px] text-adm-primary font-semibold">{{ number_format($owner->fields_count) }}</td>
                        <td class="px-6 py-3.5">
                            <span class="flex items-center gap-1 text-adm-warning">
                                <span class="material-symbols-outlined text-[16px]">star</span>
                                {{ number_format($owner->fields_avg_rating ?? 0, 1) }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 font-adm-body text-[13px] text-adm-on-surface-variant">{{ $owner->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-3.5">
                            <a href="{{ route('admin.owners.show', $owner) }}" class="text-adm-dark font-medium font-adm-body text-[12px] hover:opacity-80">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center font-adm-body text-[13px] text-adm-outline">Tidak ada pemilik ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($owners->hasPages())
        <div class="px-6 py-3.5 border-t border-adm-outline-variant">{{ $owners->links() }}</div>
        @endif
        </div>
    </div>
</div>
@endsection
