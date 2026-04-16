<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>

<body class="h-screen bg-login flex overflow-hidden">

    <!-- LEFT -->
    <div class="card auth w-[371px] h-[464px] p-6 flex flex-col justify-between">

        <div class="card-auth w-full p-6">

            <!-- TOP -->
            <div class="flex justify items-center">
                <img src="{{ asset('assets/images/logo/logo.png') }}" class="w-28">

                <div class="flex gap-3 text-[10px] text-indigo-950">
                    <span>Jelajahi</span>
                    <span>Bantuan</span>
                </div>
            </div>

            <!-- TITLE -->
            <h1 class="text-4xl font-bold text-indigo-950 mt-2">Sign in</h1>

            <!-- INPUT -->
            <div class="space-y-3 mt-4">

                <input 
                    type="text"
                    placeholder="Username atau Email"
                    class="w-full px-4 py-2 input-neu outline-none text-sm"
                >

                <div class="relative">
                    <input 
                        type="password"
                        placeholder="Password"
                        class="password-input w-full px-4 py-2 input-neu outline-none text-sm"
                    >
                    <span class="eye absolute right-4 top-2 cursor-pointer text-sm">👁</span>
                </div>

            </div>

            <!-- FORGOT -->
            <div class="text-right text-[10px] text-indigo-950 mt-1">
                Lupa password?
            </div>

            <!-- BUTTON -->
            <button class="btn-main w-full text-white text-sm font-bold py-2 rounded-lg mt-3">
                Masuk
            </button>

            <!-- GOOGLE -->
            <div class="flex items-center gap-2 mt-2">
                <button class="btn-main flex-1 text-white py-2 rounded-lg text-xs font-bold flex items-center justify-center gap-2">
                    <img src="{{ asset('assets/images/icons/google.png') }}" class="w-4">
                    Masuk dengan Google
                </button>

                <div class="btn-main p-2 rounded-lg">
                    <img src="{{ asset('assets/images/icons/user-add.png') }}" class="w-4">
                </div>
            </div>

            <!-- REGISTER -->
            <p class="text-[10px] mt-2">
                Belum punya akun? 
                <a href="#register.blade.php" class="font-bold underline">Daftar</a>
            </p>

        </div>

    </div>

    <!-- CHARACTER ONLY (NO BG HERE!) -->
    <div class="flex-1 relative">

        <img 
            src="{{ asset('assets/images/characters/char.png') }}"
            class="absolute bottom-0 right-0 h-full max-h-screen object-contain floating"
        >

    </div>

</body>
</html>