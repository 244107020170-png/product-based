@extends('layouts.admin')
@section('title', 'Detail Komunitas - SPIES SPORT Admin')

@section('content')
<div class="space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.communities') }}" class="flex items-center gap-2 text-adm-outline hover:text-adm-dark transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
            <span class="font-adm-body text-adm-label-md">Kembali</span>
        </a>
    </div>

    <div class="bg-adm-surface-lowest rounded-[20px] soft-shadow border border-adm-outline-variant p-8">
        <div class="flex items-start gap-6">
            <div class="w-16 h-16 rounded-full bg-adm-secondary-container flex items-center justify-center font-bold text-adm-on-secondary-container text-xl flex-shrink-0">
                {{ strtoupper(substr($community->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h2 class="font-adm-headline text-adm-headline-md text-adm-primary">{{ $community->name }}</h2>
                <p class="text-adm-on-surface-variant font-adm-body text-adm-body-md mt-1">
                    {{ $community->sport_category }} {{ $community->city ? '• ' . $community->city : '' }}
                </p>
                <p class="text-adm-body-sm text-adm-outline mt-2">Dibuat oleh {{ $community->creator->name ?? '-' }}</p>
                @if ($community->description)
                <p class="text-adm-body-sm text-adm-on-surface-variant mt-4">{{ $community->description }}</p>
                @endif
            </div>
            <div class="text-center">
                <p class="text-adm-headline-sm font-bold text-adm-primary">{{ number_format($community->members_count) }}</p>
                <p class="text-adm-label-sm text-adm-outline">Anggota</p>
            </div>
        </div>
    </div>
</div>
@endsection
