@extends('layouts.admin')
@section('title', 'Monitoring Komunitas - SPIES SPORT Admin')

@section('content')
<div class="space-y-8">
    <div class="flex items-end justify-between">
        <div>
            <h2 class="font-adm-headline text-adm-headline-lg text-adm-primary tracking-tight">Monitoring Komunitas</h2>
            <p class="text-adm-on-surface-variant font-adm-body text-adm-body-md mt-1">Pantau seluruh komunitas olahraga di platform.</p>
        </div>
        <div class="text-right">
            <p class="text-adm-label-sm text-adm-outline">Total Komunitas</p>
            <p class="text-adm-headline-sm font-bold text-adm-primary">{{ number_format($totalCommunities) }}</p>
        </div>
    </div>

    <div class="bg-adm-surface-lowest rounded-[20px] soft-shadow border border-adm-outline-variant overflow-hidden">
        <div class="px-8 py-5 border-b border-adm-outline-variant">
            <form method="GET" class="flex items-center gap-4">
                <div class="relative flex-1 max-w-md">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-adm-outline text-[20px]">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 bg-adm-surface-low border border-adm-outline-variant rounded-lg font-adm-body text-adm-body-sm focus:ring-2 focus:ring-adm-secondary-container outline-none" placeholder="Cari komunitas...">
                </div>
                <select name="sport" class="bg-adm-surface border border-adm-outline-variant rounded-lg font-adm-body text-adm-body-sm px-3 py-2 focus:ring-2 outline-none" onchange="this.form.submit()">
                    <option value="">Semua Olahraga</option>
                    @foreach ($sports as $sport)
                    <option value="{{ $sport }}" {{ request('sport') === $sport ? 'selected' : '' }}>{{ $sport }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-adm-dark text-adm-on-primary px-4 py-2 rounded-lg font-adm-body text-adm-label-sm hover:opacity-90">Cari</button>
                @if(request('search') || request('sport'))
                <a href="{{ route('admin.communities') }}" class="text-adm-dark font-adm-body text-adm-label-sm hover:underline">Reset</a>
                @endif
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-adm-surface-low">
                    <tr>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase">Nama</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase">Kota</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase">Olahraga</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase">Anggota</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase">Dibuat Oleh</th>
                        <th class="px-8 py-4 font-adm-body text-adm-label-sm text-adm-outline uppercase">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-adm-outline-variant">
                    @forelse ($communities as $community)
                    <tr class="hover:bg-adm-surface-low/50 transition-colors">
                        <td class="px-8 py-4">
                            <p class="font-adm-body text-adm-label-md text-adm-primary">{{ $community->name }}</p>
                        </td>
                        <td class="px-8 py-4 font-adm-body text-adm-body-sm text-adm-on-surface-variant">{{ $community->city ?? '-' }}</td>
                        <td class="px-8 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-adm-surface-high text-adm-on-surface">{{ $community->sport_category ?? '-' }}</span>
                        </td>
                        <td class="px-8 py-4 font-adm-body text-adm-body-sm text-adm-primary font-semibold">{{ number_format($community->members_count) }}</td>
                        <td class="px-8 py-4 font-adm-body text-adm-body-sm text-adm-on-surface-variant">{{ $community->creator->name ?? '-' }}</td>
                        <td class="px-8 py-4 font-adm-body text-adm-body-sm text-adm-on-surface-variant">{{ $community->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-8 py-12 text-center font-adm-body text-adm-body-md text-adm-outline">Tidak ada komunitas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($communities->hasPages())
        <div class="px-8 py-4 border-t border-adm-outline-variant">{{ $communities->links() }}</div>
        @endif
    </div>
</div>
@endsection
