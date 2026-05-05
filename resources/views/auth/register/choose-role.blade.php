@extends('layouts.app')

@section('title', 'Pilih Role')

@push('styles')
    @vite(['resources/css/choose-role.css'])
@endpush

@push('scripts')
    @vite(['resources/js/choose-role.js'])
@endpush

@section('content')
<div class="choose-role-page">

    <!-- TITLE -->
    <h1 class="title">
        Kamu daftar sebagai apa nih?
    </h1>

    <!-- ROLE CONTAINER -->
    <div class="role-container">

        <!-- PLAYER -->
        <a href="{{ route('register', ['role' => 'player']) }}" class="role-card">
            
            <div class="role-box">
                Pemain
            </div>

            <img 
                src="{{ asset('assets/images/characters/player.png') }}" {{-- 🔁 GANTI GAMBAR --}}
                alt="Pemain"
                class="role-image"
            >
        </a>

        <!-- OWNER -->
        <a href="{{ route('owner.register') }}" class="role-card">
            
            <div class="role-box">
                Pemilik
            </div>

            <img 
                src="{{ asset('assets/images/characters/owner.png') }}" {{-- 🔁 GANTI GAMBAR --}}
                alt="Pemilik"
                class="role-image"
            >
        </a>

    </div>

</div>
@endsection
