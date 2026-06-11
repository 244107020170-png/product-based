@extends('layouts.admin')
@section('title', 'Monitoring Lapangan - SPIES SPORT Admin')

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h2 class="font-adm-headline text-[22px] font-bold text-adm-primary tracking-tight">Monitoring Lapangan</h2>
            <p class="text-adm-on-surface-variant font-adm-body text-[13px] mt-0.5">Pantau seluruh lapangan yang terdaftar di platform.</p>
        </div>
        <div class="flex items-center gap-6">
            <div>
                <p class="text-[11px] font-medium text-adm-outline tracking-wide">Total Lapangan</p>
                <p class="text-[20px] font-bold text-adm-primary leading-tight">{{ number_format($totalFields) }}</p>
            </div>
            <div>
                <p class="text-[11px] font-medium text-adm-outline tracking-wide">Menunggu Verifikasi</p>
                <p class="text-[20px] font-bold text-adm-warning leading-tight">{{ number_format($pendingVerification) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-adm-surface-lowest rounded-[20px] soft-shadow border border-adm-outline-variant overflow-hidden">
        <div data-realtime-filter class="px-6 py-4 border-b border-adm-outline-variant">
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div class="w-[45%] min-w-[220px]">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 inset-y-0 flex items-center leading-none text-adm-outline text-[18px]">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" class="w-full h-10 pl-10 pr-4 bg-adm-surface-low border border-adm-outline-variant rounded-lg font-adm-body text-[13px] placeholder:text-adm-outline/50 focus:ring-2 focus:ring-adm-secondary-container outline-none" placeholder="Cari nama lapangan, pemilik...">
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="relative w-[180px] h-10 flex-shrink-0">
                        <select name="verification_status" class="w-full h-full appearance-none bg-adm-surface border border-adm-outline-variant rounded-lg font-adm-body text-[13px] pl-3 pr-8 focus:ring-2 outline-none cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('verification_status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="approved" {{ request('verification_status') === 'approved' ? 'selected' : '' }}>Terverifikasi</option>
                            <option value="rejected" {{ request('verification_status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-1.5 inset-y-0 flex items-center leading-none text-adm-outline text-[18px] pointer-events-none">expand_more</span>
                    </div>
                    <input type="hidden" name="sort" id="sort-input-fields" value="{{ request('sort', 'latest') }}">
                    <button type="button" data-sort="latest" class="sort-btn w-[100px] h-10 flex-shrink-0 rounded-lg font-adm-body text-[12px] font-semibold transition-all cursor-pointer border-none {{ request('sort', 'latest') === 'latest' ? 'bg-adm-dark text-adm-on-primary' : 'bg-adm-surface-low text-adm-on-surface-variant hover:bg-adm-surface-high' }}">Terbaru</button>
                    <button type="button" data-sort="oldest" class="sort-btn w-[100px] h-10 flex-shrink-0 rounded-lg font-adm-body text-[12px] font-semibold transition-all cursor-pointer border-none {{ request('sort') === 'oldest' ? 'bg-adm-dark text-adm-on-primary' : 'bg-adm-surface-low text-adm-on-surface-variant hover:bg-adm-surface-high' }}">Terlama</button>
                    @if(request('search') || request('verification_status'))
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
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase tracking-wider">Lapangan</th>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase tracking-wider">Pemilik</th>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase tracking-wider">Total Pesanan</th>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 font-adm-body text-[11px] font-semibold text-adm-outline uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-adm-outline-variant">
                    @forelse ($fields as $field)
                    <tr class="hover:bg-adm-surface-low/50 transition-colors">
                        <td class="px-6 py-3.5">
                            <p class="font-adm-body text-[13px] font-semibold text-adm-primary">{{ $field->name }}</p>
                        </td>
                        <td class="px-6 py-3.5 font-adm-body text-[13px] text-adm-on-surface-variant">{{ $field->owner->name ?? '-' }}</td>
                        <td class="px-6 py-3.5 font-adm-body text-[13px] text-adm-on-surface-variant">{{ $field->location }}</td>
                        <td class="px-6 py-3.5">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-adm-surface-high text-adm-on-surface">{{ $field->type }}</span>
                        </td>
                        <td class="px-6 py-3.5 font-adm-body text-[13px] text-adm-primary font-semibold">{{ number_format($field->bookings_count) }}</td>
                        <td class="px-6 py-3.5">
                            @php
                                $vMap = ['approved' => ['label' => 'Terverifikasi', 'class' => 'text-adm-success bg-adm-success/10'], 'pending' => ['label' => 'Menunggu', 'class' => 'text-adm-warning bg-adm-warning/10'], 'rejected' => ['label' => 'Ditolak', 'class' => 'text-adm-error bg-adm-error/10']];
                                $vInfo = $vMap[$field->verification_status] ?? ['label' => $field->verification_status, 'class' => 'text-adm-outline bg-adm-surface'];
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $vInfo['class'] }}">{{ $vInfo['label'] }}</span>
                        </td>
                        <td class="px-6 py-3.5">
                            <a href="{{ route('admin.fields.show', $field) }}" class="text-adm-dark font-medium font-adm-body text-[12px] hover:opacity-80">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center font-adm-body text-[13px] text-adm-outline">Tidak ada lapangan ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($fields->hasPages())
        <div class="px-6 py-3.5 border-t border-adm-outline-variant">{{ $fields->links() }}</div>
        @endif
        </div>
    </div>
</div>
@endsection
