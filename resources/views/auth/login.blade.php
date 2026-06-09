<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .login-character {
            position: absolute;
            bottom: 0;
            right: 0;
            height: 100%;
            width: 1000px;
            object-fit: contain;
            object-position: right bottom;
            pointer-events: none;
        }

        .login-card-wrap {
            position: absolute;
            top: 50%;
            left: 128px;
            transform: translateY(-50%);
            width: 500px;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        @media (max-width: 1100px) {
            .login-character { width: 700px; }
            .login-card-wrap { left: 2rem; width: 420px; }
        }

        @media (max-width: 768px) {
            body.bg-login {
                overflow: auto;
                flex-direction: column;
                min-height: 100vh;
                height: auto;
                background-position: top center;
                padding: 0;
            }

            .login-character {
                position: relative;
                width: 100%;
                height: 260px;
                object-position: center 60%;
                margin-top: 1rem;
            }

            .login-card-wrap {
                position: relative;
                top: auto;
                left: auto;
                transform: none;
                width: 100%;
                height: auto;
                padding: 0 1rem 2rem;
            }

            .card-auth { padding: 1.5rem !important; }
        }

        @media (max-width: 480px) {
            .login-character { height: 180px; }

            .login-card-wrap { padding: 0 0.75rem 1.5rem; }

            .card-auth { padding: 1rem !important; }

            .login-title { font-size: 2.5rem !important; }

            .login-nav a { font-size: 11px; padding: 2px 6px; }
        }
    </style>
</head>

<body class="h-screen bg-login flex overflow-hidden relative">

    <!-- CHARACTER BACKGROUND -->
    <img 
        src="{{ asset('assets/images/characters/char.png') }}"
        class="login-character floating"
    >

    <!-- LEFT -->
    <div class="login-card-wrap card auth">

        <div class="card-auth w-full p-12">

            <!-- TOP -->
            <div class="flex justify items-center">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" class="w-33">
                </a>

                <div class="login-nav flex gap-3 text-[12px] text-indigo-950 text-allign-right ml-auto">
                    <a href="{{ route('explore') }}" class="transition-all duration-200 hover:text-orange-500 hover:shadow-lg hover:shadow-orange-200 px-2 py-1 rounded">Jelajahi</a>
                    <a href="{{ route('preview.help') }}" class="transition-all duration-200 hover:text-orange-500 hover:shadow-lg hover:shadow-orange-200 px-2 py-1 rounded">Bantuan</a>
                </div>
            </div>

            <!-- TITLE -->
            <h1 class="login-title text-5xl font-bold text-indigo-950 mt-6 mb-8">Masuk</h1>

            <!-- FORM LOGIN -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- INPUT -->
                <div class="space-y-5 mt-6">

                    <div>
                        <input 
                            type="text"
                            id="login"
                            name="login"
                            placeholder="Username atau Email"
                            class="w-full px-4 py-2 input-neu outline-none text-sm"
                            value="{{ old('login', old('email')) }}"
                            required
                        >
                        @error('login')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="relative">
                        <input 
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Kata Sandi"
                            class="password-input w-full px-4 py-2 input-neu outline-none text-sm pr-10"
                            required
                        >

                        <span 
                            id="togglePassword"
                            class="eye absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer select-none text-sm"
                        >
                        </span>

                        @error('password')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <!-- FORGOT -->
                <div class="text-right text-[12px] text-indigo-950 mt-4 mb-4">
                    <a href="{{ route('password.request') }}" class="transition-colors duration-200 hover:text-orange-500">Lupa password?</a>
                </div>

                <!-- BUTTON MASUK -->
                <button type="submit" class="w-full text-white text-sm font-bold py-2 rounded-lg mt-6 mb-2 bg-[#00004D] hover:bg-[#000033] transition-colors duration-200">
                    Masuk
                </button>

            </form>

            <!-- GOOGLE -->
            <div class="flex items-center gap-2 mt-2 mb-3">
                <button type="button" x-data @click="$dispatch('open-modal-google-info')" class="w-full text-white py-2 rounded-lg text-xs font-bold flex items-center justify-center gap-2 bg-[#00004D] hover:bg-[#000033] transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-4">
                        <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                        <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                        <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                        <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
                    </svg>
                    Masuk dengan Google
                </button>

                <button type="button" onclick="window.location.href='{{ route('register') }}'" class="bg-[#00004D] hover:bg-[#000033] py-2 px-4 rounded-lg cursor-pointer flex items-center justify-center transition-colors duration-200">
                    <img src="{{ asset('assets/images/icons/user-add.png') }}" class="w-5 h-5">
                </button>
            </div>

            <!-- REGISTER -->
            <p class="text-[12px] mt-3">
                Belum punya akun? 
                <a href="{{ route('choose.role') }}" class="font-bold transition-colors duration-200 hover:text-orange-500">
                Daftar
                </a>
            </p>

        </div>

    </div>

<x-custom-modal name="google-info"
                 type="info"
                 title="Fitur Segera Hadir"
                 message="Login dengan Google belum tersedia. Silakan gunakan email dan password untuk masuk."
                 cancelText="Tutup" />

</body>
</html>
