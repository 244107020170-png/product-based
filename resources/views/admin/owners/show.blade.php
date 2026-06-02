@extends('layouts.admin')
@section('title', 'Detail Pemilik - SPIES SPORT Admin')

@section('content')
<div class="space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.owners') }}" class="flex items-center gap-2 text-adm-outline hover:text-adm-dark transition-colors no-underline">
            <span class="material-symbols-outlined">arrow_back</span>
            <span class="font-adm-body text-adm-label-md">Kembali</span>
        </a>
    </div>

    <div class="bg-adm-surface-lowest rounded-[20px] soft-shadow border border-adm-outline-variant p-8">
        <div class="flex items-start gap-6">
            <div class="w-20 h-20 rounded-full bg-adm-primary-container flex items-center justify-center font-bold text-white text-2xl flex-shrink-0">
                {{ strtoupper(substr($owner->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h2 class="font-adm-headline text-adm-headline-md text-adm-primary">{{ $owner->name }}</h2>
                <p class="text-adm-on-surface-variant font-adm-body text-adm-body-md mt-1">{{ $owner->email }}</p>
                <div class="flex items-center gap-4 mt-4">
                    <span class="px-3 py-1 bg-adm-primary-container/10 text-adm-primary-container rounded-full font-adm-body text-adm-label-sm">Pemilik</span>
                    <span class="text-adm-body-sm text-adm-outline">Bergabung {{ $owner->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
        <div class="mt-8 pt-8 border-t border-adm-outline-variant">
            <h3 class="font-adm-headline text-adm-headline-sm text-adm-primary mb-4">Daftar Lapangan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($owner->fields as $field)
                <div class="bg-adm-surface-low rounded-xl p-4 border border-adm-outline-variant">
                    <p class="font-adm-body text-adm-label-md text-adm-primary">{{ $field->name }}</p>
                    <p class="text-adm-body-sm text-adm-outline mt-1">{{ $field->type }} - {{ $field->location }}</p>
                    <p class="text-adm-body-sm text-adm-outline">{{ number_format($field->bookings_count) }} pesanan</p>
                </div>
                @empty
                <p class="text-adm-outline font-adm-body text-adm-body-md col-span-3">Belum ada lapangan</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
