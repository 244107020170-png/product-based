<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite('resources/css/app.css', 'resources/js/app.js')
</head>

<body class="h-screen bg-login flex overflow-hidden relative">

    <!-- CHARACTER BACKGROUND -->
    <img 
        src="{{ asset('assets/images/characters/char.png') }}"
        class="absolute bottom-0 right-0 h-full w-[1000px] object-contain pointer-events-none floating"
    >

    <!-- LEFT -->
    <div class="absolute top-1/2 left-32 transform -translate-y-1/2 card auth w-[500px] h-screen flex items-center justify-center relative z-10">

        <div class="card-auth w-full p-12">

            <!-- TOP -->
            <div class="flex justify items-center">
                <img src="{{ asset('assets/images/logo/logo.png') }}" class="w-33">

                <div class="flex gap-3 text-[12px] text-indigo-950 text-allign-right ml-auto">
                    <a href="{{ route('explore') }}" class="transition-all duration-200 hover:text-orange-500 hover:shadow-lg hover:shadow-orange-200 px-2 py-1 rounded">Jelajahi</a>
                    <a href="" class="transition-all duration-200 hover:text-orange-500 hover:shadow-lg hover:shadow-orange-200 px-2 py-1 rounded">Bantuan</a>
                </div>
            </div>

            <!-- TITLE -->
            <h1 class="text-5xl font-bold text-indigo-950 mt-6 mb-8">Sign in</h1>

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
                            placeholder="Password"
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
                <button type="submit" class="btn-main w-full text-white text-sm font-bold py-2 rounded-lg mt-6 mb-2">
                    Masuk
                </button>

            </form>

            <!-- GOOGLE -->
            <div class="flex items-center gap-2 mt-2 mb-3">
                <button type="button" onclick="alert('Google login belum disetup')" class="btn-main w-full text-white py-2 rounded-lg text-xs font-bold flex items-center justify-center gap-2">
                    <img src="{{ asset('assets/images/icons/google.png') }}" class="w-4">
                    Masuk dengan Google
                </button>

                <button type="button" onclick="window.location.href='{{ route('register') }}'" class="btn-main py-2 px-4 rounded-lg cursor-pointer flex items-center justify-center">
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

</body>
</html>
