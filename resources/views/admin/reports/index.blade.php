@extends('layouts.admin')
@section('title', 'Laporan & Analitik - SPIES SPORT Admin')

@section('content')
<div class="space-y-8">
    <div class="flex items-end justify-between">
        <div>
            <h2 class="font-adm-headline text-adm-headline-lg text-adm-primary tracking-tight">Laporan & Analitik</h2>
            <p class="text-adm-on-surface-variant font-adm-body text-adm-body-md mt-1">Analisis pertumbuhan dan performa platform.</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET">
                <select name="year" class="bg-adm-surface border border-adm-outline-variant rounded-lg font-adm-body text-adm-body-sm px-3 py-2 focus:ring-2 outline-none" onchange="this.form.submit()">
                    @foreach (range(now()->year, now()->year - 2) as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </form>
            <button class="flex items-center gap-2 bg-adm-dark text-adm-on-primary px-4 py-2 rounded-lg font-adm-body text-adm-label-sm hover:opacity-90">
                <span class="material-symbols-outlined text-[18px]">download</span> PDF
            </button>
            <button class="flex items-center gap-2 bg-adm-dark text-adm-on-primary px-4 py-2 rounded-lg font-adm-body text-adm-label-sm hover:opacity-90">
                <span class="material-symbols-outlined text-[18px]">table</span> Excel
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-adm-surface-lowest p-6 rounded-[20px] soft-shadow border border-adm-outline-variant">
            <h3 class="font-adm-headline text-adm-headline-sm text-adm-primary mb-4">Pertumbuhan Pengguna {{ $year }}</h3>
            <div class="h-48 flex items-end justify-between gap-2 px-2">
                @php
                    $maxU = max($userGrowth->max(), 1);
                @endphp
                @foreach (['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'] as $i => $m)
                    @php $val = $userGrowth[$i + 1] ?? 0; @endphp
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full bg-adm-dark rounded-t-lg" style="height: {{ max(4, ($val / $maxU) * 160) }}px"></div>
                        <span class="text-[8px] font-bold text-adm-outline">{{ $m }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-adm-surface-lowest p-6 rounded-[20px] soft-shadow border border-adm-outline-variant">
            <h3 class="font-adm-headline text-adm-headline-sm text-adm-primary mb-4">Pertumbuhan Pemilik {{ $year }}</h3>
            <div class="h-48 flex items-end justify-between gap-2 px-2">
                @php $maxO = max($ownerGrowth->max(), 1); @endphp
                @foreach (['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'] as $i => $m)
                    @php $val = $ownerGrowth[$i + 1] ?? 0; @endphp
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full bg-adm-secondary-container rounded-t-lg" style="height: {{ max(4, ($val / $maxO) * 160) }}px"></div>
                        <span class="text-[8px] font-bold text-adm-outline">{{ $m }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-adm-surface-lowest rounded-[20px] soft-shadow border border-adm-outline-variant overflow-hidden">
            <div class="px-6 py-4 border-b border-adm-outline-variant">
                <h3 class="font-adm-headline text-adm-headline-sm text-adm-primary">Booking per Kota</h3>
            </div>
            <div class="p-6">
                @forelse ($bookingsPerCity as $city)
                <div class="flex items-center justify-between py-2 border-b border-adm-outline-variant/50 last:border-0">
                    <span class="font-adm-body text-adm-body-sm text-adm-primary">{{ $city->location ? explode(',', $city->location)[0] : '-' }}</span>
                    <span class="font-bold text-adm-dark">{{ number_format($city->total) }}</span>
                </div>
                @empty
                <p class="text-adm-body-sm text-adm-outline">Belum ada data</p>
                @endforelse
            </div>
        </div>

        <div class="bg-adm-surface-lowest rounded-[20px] soft-shadow border border-adm-outline-variant overflow-hidden">
            <div class="px-6 py-4 border-b border-adm-outline-variant">
                <h3 class="font-adm-headline text-adm-headline-sm text-adm-primary">Booking per Olahraga</h3>
            </div>
            <div class="p-6">
                @forelse ($bookingsPerSport as $sport => $count)
                <div class="flex items-center justify-between py-2 border-b border-adm-outline-variant/50 last:border-0">
                    <span class="font-adm-body text-adm-body-sm text-adm-primary">{{ $sport ?: 'Tidak diketahui' }}</span>
                    <span class="font-bold text-adm-dark">{{ number_format($count) }}</span>
                </div>
                @empty
                <p class="text-adm-body-sm text-adm-outline">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-adm-surface-lowest p-6 rounded-[20px] soft-shadow border border-adm-outline-variant">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-adm-headline text-adm-headline-sm text-adm-primary">Ringkasan Pendapatan {{ $year }}</h3>
            <p class="text-adm-headline-sm font-bold text-adm-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="h-48 flex items-end justify-between gap-2 px-2">
            @php $maxR = max($revenuePerMonth->max(), 1); @endphp
            @foreach (['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'] as $i => $m)
                @php $val = $revenuePerMonth[$i + 1] ?? 0; @endphp
                <div class="flex-1 flex flex-col items-center gap-1">
                    <div class="w-full bg-adm-success/60 rounded-t-lg" style="height: {{ max(4, ($val / $maxR) * 160) }}px"></div>
                    <span class="text-[8px] font-bold text-adm-outline">{{ $m }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
