@extends('layouts.admin')
@section('title', 'SPIES SPORT Admin Dashboard')

@section('content')
<div class="space-y-5">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="font-adm-headline text-[22px] font-bold text-adm-primary tracking-tight">Ringkasan Eksekutif</h2>
            <p class="text-adm-on-surface-variant font-adm-body text-[13px] mt-0.5">Selamat datang kembali. Berikut adalah performa platform Anda hari ini.</p>
        </div>
        <button class="flex items-center gap-1.5 bg-adm-dark text-adm-on-primary px-4 py-2 rounded-lg font-adm-body text-[12px] font-semibold hover:opacity-90 transition-all active:scale-95">
            <span class="material-symbols-outlined text-[16px]">download</span>
            Unduh Laporan Bulanan
        </button>
    </div>

    <!-- Global Stats Grid (Bento Style) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Player -->
        <div class="bg-adm-surface-lowest p-4 rounded-[16px] soft-shadow border border-adm-outline-variant group hover:border-adm-secondary transition-all">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 bg-adm-secondary-container/20 text-adm-secondary rounded-[10px] flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">group</span>
                </div>
                <span class="text-adm-success font-adm-body text-[11px] font-semibold flex items-center gap-0.5">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span> +{{ $growthUsers }}
                </span>
            </div>
            <div class="mt-3">
                <p class="text-adm-outline font-adm-body text-[12px] font-medium">Total Pengguna</p>
                <h3 class="text-[22px] font-bold text-adm-primary mt-0.5">{{ number_format($totalPlayers) }}</h3>
            </div>
        </div>

        <!-- Total Owner -->
        <div class="bg-adm-surface-lowest p-4 rounded-[16px] soft-shadow border border-adm-outline-variant group hover:border-adm-secondary transition-all">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 bg-adm-primary-container/10 text-adm-primary-container rounded-[10px] flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">storefront</span>
                </div>
                <span class="text-adm-success font-adm-body text-[11px] font-semibold flex items-center gap-0.5">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span> +{{ $growthOwners }}
                </span>
            </div>
            <div class="mt-3">
                <p class="text-adm-outline font-adm-body text-[12px] font-medium">Total Pemilik</p>
                <h3 class="text-[22px] font-bold text-adm-primary mt-0.5">{{ number_format($totalOwners) }}</h3>
            </div>
        </div>

        <!-- Total Lapangan -->
        <div class="bg-adm-surface-lowest p-4 rounded-[16px] soft-shadow border border-adm-outline-variant group hover:border-adm-secondary transition-all">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 bg-adm-surface-high text-adm-on-secondary-container rounded-[10px] flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">stadium</span>
                </div>
                <span class="text-adm-warning font-adm-body text-[11px] font-semibold flex items-center gap-0.5">
                    <span class="material-symbols-outlined text-[14px]">remove</span> {{ $totalFields }}
                </span>
            </div>
            <div class="mt-3">
                <p class="text-adm-outline font-adm-body text-[12px] font-medium">Total Lapangan</p>
                <h3 class="text-[22px] font-bold text-adm-primary mt-0.5">{{ number_format($totalFields) }}</h3>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="bg-adm-dark text-adm-on-primary p-4 rounded-[16px] soft-shadow border border-adm-dark group hover:scale-[1.02] transition-all relative overflow-hidden">
            <div class="absolute -right-3 -bottom-3 opacity-10">
                <span class="material-symbols-outlined text-[90px]">payments</span>
            </div>
            <div class="relative z-10">
                <div class="flex items-start justify-between">
                    <div class="w-10 h-10 bg-white/20 text-white rounded-[10px] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">payments</span>
                    </div>
                    <span class="bg-white/20 px-2 py-0.5 rounded text-[9px] font-adm-body font-bold uppercase tracking-widest">Pendapatan Platform</span>
                </div>
                <div class="mt-3">
                    <p class="text-white/70 font-adm-body text-[12px] font-medium">Pendapatan Platform</p>
                    <h3 class="text-[22px] font-bold mt-0.5">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <!-- Row 2 -->
        <div class="bg-adm-surface-lowest p-4 rounded-[16px] soft-shadow border border-adm-outline-variant">
            <p class="text-adm-outline font-adm-body text-[12px] font-medium">Total Pesanan</p>
            <h3 class="text-[20px] font-bold text-adm-primary mt-0.5">{{ number_format($totalBookings) }}</h3>
            <div class="mt-3 pt-3 border-t border-adm-outline-variant flex items-center justify-between">
                <span class="font-adm-body text-[12px] text-adm-on-surface-variant">Tingkat keberhasilan</span>
                <span class="font-bold text-[13px] text-adm-success">{{ $bookingSuccessRate }}%</span>
            </div>
        </div>

        <div class="bg-adm-surface-lowest p-4 rounded-[16px] soft-shadow border border-adm-outline-variant">
            <p class="text-adm-outline font-adm-body text-[12px] font-medium">Total Komunitas</p>
            <h3 class="text-[20px] font-bold text-adm-primary mt-0.5">{{ number_format($totalCommunities) }}</h3>
            <div class="mt-3 pt-3 border-t border-adm-outline-variant flex items-center justify-between">
                <span class="font-adm-body text-[12px] text-adm-on-surface-variant">Grup Aktif</span>
                <span class="font-bold text-[13px] text-adm-secondary">{{ $activeCommunities }}</span>
            </div>
        </div>

        <div class="bg-adm-surface-lowest p-4 rounded-[16px] soft-shadow border border-adm-outline-variant">
            <p class="text-adm-outline font-adm-body text-[12px] font-medium">Pesanan Hari Ini</p>
            <h3 class="text-[20px] font-bold text-adm-primary mt-0.5">{{ $todayBookings }}</h3>
            <div class="mt-3 h-1 bg-adm-surface-container rounded-full overflow-hidden">
                <div class="h-full bg-adm-secondary" style="width: {{ $dailyProgress }}%"></div>
            </div>
            <p class="text-[9px] text-adm-outline mt-1.5">{{ $dailyProgress }}% dari target harian</p>
        </div>

        <div class="bg-adm-surface-lowest p-4 rounded-[16px] soft-shadow border border-adm-outline-variant">
            <p class="text-adm-outline font-adm-body text-[12px] font-medium">Pesanan Bulan Ini</p>
            <h3 class="text-[20px] font-bold text-adm-primary mt-0.5">{{ number_format($monthBookings) }}</h3>
            <div class="mt-3 pt-3 border-t border-adm-outline-variant flex items-center justify-between">
                <span class="font-adm-body text-[12px] text-adm-on-surface-variant">Pertumbuhan MoM</span>
                <span class="font-bold text-[13px] {{ $monthGrowth !== null && $monthGrowth >= 0 ? 'text-adm-success' : 'text-adm-error' }}">
                    {{ $monthGrowth !== null ? ($monthGrowth >= 0 ? '+' : '') . $monthGrowth . '%' : 'N/A' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Bar Chart: Pesanan per Bulan -->
        <div class="lg:col-span-2 bg-adm-surface-lowest p-4 rounded-[16px] soft-shadow border border-adm-outline-variant">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="font-adm-headline text-[16px] font-bold text-adm-primary">Pesanan per Bulan</h3>
                    <p class="font-adm-body text-[11px] text-adm-outline">Tren penyewaan lapangan tahun {{ now()->year }}</p>
                </div>
                <span class="bg-adm-surface border border-adm-outline-variant rounded-lg font-adm-body text-[11px] px-2.5 py-1 text-adm-on-surface-variant">{{ now()->year }}</span>
            </div>
            <div class="h-[130px] w-full flex items-end justify-between gap-1.5">
                @foreach ($chartData as $data)
                    @php
                        $heightPercent = $maxVal > 0 ? max(4, ($data['total'] / $maxVal) * 100) : 4;
                        $isMax = $data['total'] >= $maxVal && $maxVal > 1;
                        $barColor = $isMax ? 'bg-adm-dark' : 'bg-adm-secondary-container/40';
                    @endphp
                    <div class="flex-1 flex flex-col items-center justify-end h-full">
                        <span class="text-[9px] font-bold text-adm-primary mb-0.5 {{ $data['total'] > 0 ? '' : 'opacity-0' }}">{{ $data['total'] > 0 ? number_format($data['total']) : '0' }}</span>
                        <div class="w-full min-h-[4px] rounded-t-sm transition-all duration-300 hover:opacity-80 {{ $barColor }}" style="height: {{ $heightPercent }}%"></div>
                        <span class="text-[8px] font-semibold text-adm-outline mt-1">{{ $data['month'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Growth Visualization Card -->
        <div class="bg-adm-surface-lowest p-5 rounded-[16px] soft-shadow border border-adm-outline-variant flex flex-col">
            <h3 class="font-adm-headline text-[16px] font-bold text-adm-primary mb-0.5">Pertumbuhan</h3>
            <p class="font-adm-body text-[12px] text-adm-outline mb-4">Pengguna vs Pemilik (Tren Garis)</p>
            <div class="flex-1 flex items-center justify-center relative">
                <svg class="w-full h-28" viewBox="0 0 400 120" preserveAspectRatio="none">
                    @php
                        $maxGrowth = max(max($playerGrowthData), max($ownerGrowthData), 1);
                        $playerD = '';
                        $ownerD = '';
                        foreach (range(0, 11) as $idx) {
                            $px = ($idx / 11) * 400;
                            $py = 110 - (($playerGrowthData[$idx] ?? 0) / $maxGrowth) * 95;
                            $playerD .= ($idx === 0 ? "M {$px},{$py}" : " L {$px},{$py}");
                            $oy = 110 - (($ownerGrowthData[$idx] ?? 0) / $maxGrowth) * 95;
                            $ownerD .= ($idx === 0 ? "M {$px},{$oy}" : " L {$px},{$oy}");
                        }
                    @endphp
                    <path d="{{ $playerD }}" fill="none" stroke="#00004D" stroke-width="2.5" vector-effect="non-scaling-stroke"></path>
                    <path d="{{ $ownerD }}" fill="none" stroke="#4059AA" stroke-dasharray="4,4" stroke-width="2.5" vector-effect="non-scaling-stroke"></path>
                </svg>
            </div>
            <div class="mt-auto space-y-2">
                <div class="flex items-center justify-between p-2.5 bg-adm-surface rounded-[10px]">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-adm-dark rounded-full"></span>
                        <span class="font-adm-body text-[12px] font-semibold">Pengguna Baru</span>
                    </div>
                    <span class="font-bold text-[13px]">+{{ number_format(array_sum($playerGrowthData)) }}</span>
                </div>
                <div class="flex items-center justify-between p-2.5 bg-adm-surface rounded-[10px]">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-adm-secondary-container rounded-full"></span>
                        <span class="font-adm-body text-[12px] font-semibold">Pemilik Baru</span>
                    </div>
                    <span class="font-bold text-[13px]">+{{ number_format(array_sum($ownerGrowthData)) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <!-- User Aktif -->
        <div class="bg-adm-surface-lowest p-4 rounded-[16px] soft-shadow border border-adm-outline-variant flex flex-col">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-8 h-8 rounded-full bg-adm-success/10 text-adm-success flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                </div>
                <h4 class="font-adm-body text-[13px] font-semibold text-adm-primary">Pengguna Aktif Hari Ini</h4>
            </div>
            <div class="flex items-end justify-between">
                <h5 class="text-[24px] font-bold text-adm-primary">{{ number_format($activeUsersToday) }}</h5>
                <div class="text-right">
                    <p class="text-[9px] text-adm-outline font-bold uppercase">Waktu Puncak</p>
                    <p class="font-adm-body text-[12px] font-semibold text-adm-secondary">{{ $peakTime }}</p>
                </div>
            </div>
        </div>

        <!-- Owner Aktif -->
        <div class="bg-adm-surface-lowest p-4 rounded-[16px] soft-shadow border border-adm-outline-variant flex flex-col">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-8 h-8 rounded-full bg-adm-secondary-container/20 text-adm-on-secondary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">store</span>
                </div>
                <h4 class="font-adm-body text-[13px] font-semibold text-adm-primary">Pemilik Aktif Hari Ini</h4>
            </div>
            <div class="flex items-end justify-between">
                <h5 class="text-[24px] font-bold text-adm-primary">{{ number_format($activeOwnersToday) }}</h5>
                <div class="text-right">
                    <p class="text-[9px] text-adm-outline font-bold uppercase">Waktu Respon</p>
                    <p class="font-adm-body text-[12px] font-semibold text-adm-success">{{ $avgResponseTime }}</p>
                </div>
            </div>
        </div>

        <!-- Lapangan Terpopuler -->
        <div class="bg-adm-surface-lowest p-4 rounded-[16px] soft-shadow border border-adm-outline-variant flex flex-col overflow-hidden relative">
            <div class="flex items-center gap-2.5 mb-3 relative z-10">
                <div class="w-8 h-8 rounded-full bg-adm-primary-container text-white flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
                </div>
                <h4 class="font-adm-body text-[13px] font-semibold text-adm-primary">Lapangan Terpopuler</h4>
            </div>
            <div class="relative z-10">
                <h5 class="font-adm-body text-[15px] font-bold text-adm-primary truncate">{{ $popularFieldName }}</h5>
                <p class="font-adm-body text-[12px] text-adm-outline mt-0.5">{{ number_format($popularFieldBookings) }} Pesanan</p>
            </div>
        </div>

        <!-- Kota Teraktif -->
        <div class="bg-adm-surface-lowest p-4 rounded-[16px] soft-shadow border border-adm-outline-variant flex flex-col">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-8 h-8 rounded-full bg-adm-surface-high text-adm-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">location_on</span>
                </div>
                <h4 class="font-adm-body text-[13px] font-semibold text-adm-primary">Kota Teraktif</h4>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="text-[18px] font-bold text-adm-primary">{{ $topCityName }}</h5>
                    <p class="text-[9px] text-adm-outline uppercase font-bold">Pertumbuhan: +15.2%</p>
                </div>
                <div class="w-10 h-10 rounded bg-adm-surface border border-adm-outline-variant flex items-center justify-center overflow-hidden">
                    <span class="material-symbols-outlined text-[18px] text-adm-dark">location_city</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity / Transactions Section -->
    <div class="bg-adm-surface-lowest rounded-[16px] soft-shadow border border-adm-outline-variant overflow-hidden">
        <div class="px-5 py-3.5 border-b border-adm-outline-variant flex items-center justify-between">
            <div>
                <h3 class="font-adm-headline text-[16px] font-bold text-adm-primary">Transaksi Terbaru</h3>
                <p class="font-adm-body text-[12px] text-adm-outline">Update real-time pembayaran dari pengguna</p>
            </div>
            <a href="{{ route('admin.bookings') }}" class="text-adm-dark font-adm-body text-[12px] font-semibold hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-adm-surface-low">
                    <tr>
                        <th class="px-5 py-3 font-adm-body text-[11px] font-bold text-adm-outline uppercase tracking-wider">Pengguna</th>
                        <th class="px-5 py-3 font-adm-body text-[11px] font-bold text-adm-outline uppercase tracking-wider">Lapangan / Pemilik</th>
                        <th class="px-5 py-3 font-adm-body text-[11px] font-bold text-adm-outline uppercase tracking-wider">Waktu</th>
                        <th class="px-5 py-3 font-adm-body text-[11px] font-bold text-adm-outline uppercase tracking-wider">Jumlah</th>
                        <th class="px-5 py-3 font-adm-body text-[11px] font-bold text-adm-outline uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-adm-outline-variant">
                    @forelse ($recentBookings as $tx)
                    <tr class="hover:bg-adm-surface-low/50 transition-colors">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full {{ $tx['color'] }} flex items-center justify-center font-bold text-xs">{{ $tx['initial'] }}</div>
                                <div>
                                    <p class="font-adm-body text-[13px] font-semibold text-adm-primary leading-tight">{{ $tx['user_name'] }}</p>
                                    <p class="text-[11px] text-adm-outline leading-tight">{{ $tx['user_email'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            <p class="font-adm-body text-[13px] font-semibold text-adm-primary leading-tight">{{ $tx['field_name'] }}</p>
                            <p class="text-[11px] text-adm-outline leading-tight">Pemilik: {{ $tx['owner_name'] }}</p>
                        </td>
                        <td class="px-5 py-3 font-adm-body text-[12px] text-adm-on-surface-variant">{{ $tx['date'] }}, {{ $tx['time'] }}</td>
                        <td class="px-5 py-3 font-bold text-[14px] text-adm-primary">Rp {{ number_format($tx['amount'], 0, ',', '.') }}</td>
                        <td class="px-5 py-3">
                            @php
                                $statusMap = [
                                    'pending' => ['label' => 'Pending', 'class' => 'bg-adm-warning/10 text-adm-warning'],
                                    'waiting_payment' => ['label' => 'Menunggu Bayar', 'class' => 'bg-adm-warning/10 text-adm-warning'],
                                    'paid' => ['label' => 'Dibayar', 'class' => 'bg-adm-accent/10 text-adm-accent'],
                                    'confirmed' => ['label' => 'Dikonfirmasi', 'class' => 'bg-adm-success/10 text-adm-success'],
                                    'completed' => ['label' => 'Selesai', 'class' => 'bg-adm-success/10 text-adm-success'],
                                    'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-adm-error/10 text-adm-error'],
                                    'expired' => ['label' => 'Kedaluwarsa', 'class' => 'bg-adm-error/10 text-adm-error'],
                                    'rejected' => ['label' => 'Ditolak', 'class' => 'bg-adm-error/10 text-adm-error'],
                                ];
                                $statusInfo = $statusMap[$tx['status']] ?? ['label' => $tx['status'], 'class' => 'bg-gray-100 text-gray-600'];
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $statusInfo['class'] }}">
                                {{ $statusInfo['label'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center font-adm-body text-[13px] text-adm-outline">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
