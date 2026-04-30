<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body
        class="min-h-screen bg-cover bg-center bg-no-repeat px-4 py-10 sm:px-6"
        style="background-image: url('{{ asset('assets/images/bg/bg-daftar.png') }}');"
    >
        <div class="mx-auto flex min-h-[calc(100vh-5rem)] w-full max-w-md items-center justify-center">
            <div class="w-full">
                <div class="mb-6 flex justify-center">
                    <img
                        src="{{ asset('assets/images/logo/logo.png') }}"
                        alt="Logo"
                        class="h-auto w-36 sm:w-40"
                    >
                </div>

                <div class="rounded-[28px] border border-stone-300 p-6 shadow-[0_10px_30px_rgba(0,0,0,0.12)] sm:p-8" style="background-color: #FFF6D7;">
                    <div class="mb-4 text-sm leading-6 text-gray-700">
                        {{ __('Lupa kata sandi Anda? Tidak masalah. Cukup beri tahu kami alamat email Anda dan kami akan mengirimkan tautan pengaturan ulang kata sandi melalui email yang memungkinkan Anda memilih kata sandi baru.') }}
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mt-4 flex items-center justify-end">
                            <x-primary-button>
                                {{ __('Email Password Reset Link') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
