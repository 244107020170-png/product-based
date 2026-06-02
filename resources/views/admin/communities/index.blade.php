@extends('layouts.admin')
@section('title', 'Monitoring Komunitas - SPIES SPORT Admin')

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="font-adm-headline text-[22px] font-bold text-adm-primary tracking-tight">Monitoring Komunitas</h2>
            <p class="text-adm-on-surface-variant font-adm-body text-[13px] mt-0.5">Pantau seluruh komunitas olahraga di platform.</p>
        </div>
        <div class="bg-adm-primary rounded-[25px] px-5 flex items-center gap-3 shadow-sm h-[68px] mr-4">
            <p class="text-[11px] font-medium text-white/70 tracking-wide">Total Komunitas</p>
            <p class="text-[20px] font-bold text-white leading-none">{{ number_format($totalCommunities) }}</p>
        </div>
    </div>

    <div class="bg-adm-surface-lowest rounded-[20px] soft-shadow border border-adm-outline-variant overflow-hidden">
        <div data-realtime-filter class="px-6 py-4 border-b border-adm-outline-variant">
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div class="w-[45%] min-w-[220px]">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 inset-y-0 flex items-center leading-none text-adm-outline text-[18px]">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" class="w-full h-10 pl-10 pr-4 bg-adm-surface-low border border-adm-outline-variant rounded-lg font-adm-body text-[13px] placeholder:text-adm-outline/50 focus:ring-2 focus:ring-adm-secondary-container outline-none" placeholder="Cari komunitas...">
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <select name="sport" class="appearance-none h-10 bg-adm-surface border border-adm-outline-variant rounded-lg font-adm-body text-[13px] pl-3 pr-8 focus:ring-2 outline-none cursor-pointer">
                            <option value="">Semua Olahraga</option>
                            @foreach ($sports as $sport)
                            <option value="{{ $sport }}" {{ request('sport') === $sport ? 'selected' : '' }}>{{ $sport }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-1.5 inset-y-0 flex items-center leading-none text-adm-outline text-[18px] pointer-events-none">expand_more</span>
                    </div>
                    <input type="hidden" name="sort" id="sort-input-communities" value="{{ request('sort', 'latest') }}">
                    <button type="button" data-sort="latest" class="sort-btn w-[100px] h-10 flex-shrink-0 rounded-lg font-adm-body text-[12px] font-semibold transition-all cursor-pointer border-none {{ request('sort', 'latest') === 'latest' ? 'bg-adm-dark text-adm-on-primary' : 'bg-adm-surface-low text-adm-on-surface-variant hover:bg-adm-surface-high' }}">Terbaru</button>
                    <button type="button" data-sort="oldest" class="sort-btn w-[100px] h-10 flex-shrink-0 rounded-lg font-adm-body text-[12px] font-semibold transition-all cursor-pointer border-none {{ request('sort') === 'oldest' ? 'bg-adm-dark text-adm-on-primary' : 'bg-adm-surface-low text-adm-on-surface-variant hover:bg-adm-surface-high' }}">Terlama</button>
                    @if(request('search') || request('sport'))
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
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase tracking-wider">Kota</th>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase tracking-wider">Olahraga</th>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase tracking-wider">Anggota</th>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase tracking-wider">Dibuat Oleh</th>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-adm-outline-variant">
                    @forelse ($communities as $community)
                    <tr class="hover:bg-adm-surface-low/50 transition-colors">
                        <td class="px-6 py-3.5">
                            <p class="font-adm-body text-[13px] font-semibold text-adm-primary">{{ $community->name }}</p>
                        </td>
                        <td class="px-6 py-3.5 font-adm-body text-[13px] text-adm-on-surface-variant">{{ $community->city ?? '-' }}</td>
                        <td class="px-6 py-3.5">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-adm-surface-high text-adm-on-surface">{{ $community->sport_category ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-3.5 font-adm-body text-[13px] text-adm-primary font-semibold">{{ number_format($community->members_count) }}</td>
                        <td class="px-6 py-3.5 font-adm-body text-[13px] text-adm-on-surface-variant">{{ $community->creator->name ?? '-' }}</td>
                        <td class="px-6 py-3.5 font-adm-body text-[13px] text-adm-on-surface-variant">{{ $community->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-3.5">
                            <a href="{{ route('admin.communities.show', $community) }}" class="text-adm-dark font-medium font-adm-body text-[12px] hover:opacity-80">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center font-adm-body text-[13px] text-adm-outline">Tidak ada komunitas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($communities->hasPages())
        <div class="px-6 py-3.5 border-t border-adm-outline-variant">{{ $communities->links() }}</div>
        @endif
        </div>
    </div>
</div>
@endsection
