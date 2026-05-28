<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daftar</title>

    @vite(['resources/css/app.css', 'resources/css/register.css', 'resources/js/app.js', 'resources/js/register.js'])

    <style>
        .register-page .register-input {
            background: #FBE3DD;
        }
        .register-page .register-field:hover .register-input {
            border-color: #CF8A7E;
            background: #F9DCD5;
            box-shadow: 0 0 0 4px rgba(207, 138, 126, 0.2), inset 0 5px 8px rgba(143, 123, 82, 0.24), inset 0 -6px 8px rgba(255, 255, 255, 0.96);
        }
        .register-page .register-input:focus {
            background: #FEF0EC;
            border-color: #CF8A7E;
            box-shadow: 0 0 0 4px rgba(207, 138, 126, 0.2), var(--register-input-shadow);
        }
        .register-page .register-field:hover .register-input ~ .password-toggle {
            color: #DC494D;
        }
        .register-page .password-toggle:hover {
            color: #DC494D;
            background: rgba(220, 73, 77, 0.12);
        }
        .register-page .register-submit {
            background: #DC494D;
            color: #FFFFFF;
            box-shadow: 0 12px 20px rgba(220, 73, 77, 0.3);
        }
        .register-page .register-submit:hover {
            background: #C73A3E;
            box-shadow: 0 16px 22px rgba(220, 73, 77, 0.35);
        }
        .register-page .register-google:hover,
        .register-page .register-role-switch:hover {
            border-color: #DC494D;
            box-shadow: 0 10px 18px rgba(220, 73, 77, 0.15);
        }
        .register-page .register-login-text a:hover {
            color: #DC494D;
        }
        .register-page .register-mini-nav a:hover {
            color: #DC494D;
            background: rgba(220, 73, 77, 0.12);
        }
    </style>
</head>
<body
    class="register-page"
    style="--register-page-bg: url('{{ asset('assets/images/bg/reg-owner.png') }}');"
>
    <div class="register-scene">
        <aside class="register-hero" aria-hidden="true">
            <div class="register-hero-orb">
                
                <img
                    src="{{ asset('assets/images/characters/manager2.png') }}"
                    alt=""
                    class="register-hero-character"
                    style="width: min(82%, 370px);"
                >
            </div>
        </aside>

        <main class="register-panel">
            <section class="register-card" aria-labelledby="register-title" style="background: #FAEBE8;">
                <header class="register-header">
                    <a href="{{ route('home') }}">
                        <img
                            src="{{ asset('assets/images/logo/logo.png') }}"
                            alt="Spies Sport"
                            class="register-logo"
                        >
                    </a>

                    <nav class="register-mini-nav" aria-label="Menu cepat">
                        <a href="{{ route('explore') }}">Jelajahi</a>
                        <a href="{{ route('preview.help') }}">Bantuan</a>
                    </nav>
                </header>

                <h1 id="register-title" class="register-title">Daftar Pemilik</h1>

                <form method="POST" action="{{ route('register', ['role' => 'owner']) }}" class="register-form">
                    @csrf

                    <input type="hidden" name="role" value="owner">

                    <div class="register-grid">
                        <div class="register-field">
                            <label for="name" class="sr-only">Nama Lengkap</label>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Nama Lengkap"
                                class="register-input"
                                required
                                autofocus
                                autocomplete="name"
                            >
                            @error('name')
                                <span class="register-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="register-field register-field--password">
                            <label for="password" class="sr-only">Password</label>
                            <div class="register-password-wrap">
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    placeholder="Password"
                                    class="register-input register-input--password"
                                    required
                                    autocomplete="new-password"
                                >
                                <button
                                    type="button"
                                    class="password-toggle"
                                    data-target="password"
                                    aria-label="Tampilkan password"
                                    aria-pressed="false"
                                >
                                    <svg class="password-toggle__icon password-toggle__icon--hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M3 3l18 18"></path>
                                        <path d="M10.6 10.7a3 3 0 0 0 4.1 4.1"></path>
                                        <path d="M9.4 5.2A10.4 10.4 0 0 1 12 5c5.5 0 9.6 4.7 10 5-.6.5-2.2 2.4-4.6 3.8"></path>
                                        <path d="M6.2 6.3C3.8 7.8 2.4 9.5 2 10c.7.6 2.7 3 5.8 4.3"></path>
                                    </svg>
                                    <svg class="password-toggle__icon password-toggle__icon--visible" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M2 12s3.6-6.5 10-6.5S22 12 22 12s-3.6 6.5-10 6.5S2 12 2 12z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <span class="register-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="register-field">
                            <label for="username" class="sr-only">Username</label>
                            <input
                                id="username"
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                placeholder="Username"
                                class="register-input"
                                required
                                autocomplete="username"
                            >
                            @error('username')
                                <span class="register-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="register-field register-field--password">
                            <label for="password_confirmation" class="sr-only">Konfirmasi Password</label>
                            <div class="register-password-wrap">
                                <input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    placeholder="Konfirmasi Password"
                                    class="register-input register-input--password"
                                    required
                                    autocomplete="new-password"
                                >
                                <button
                                    type="button"
                                    class="password-toggle"
                                    data-target="password_confirmation"
                                    aria-label="Tampilkan konfirmasi password"
                                    aria-pressed="false"
                                >
                                    <svg class="password-toggle__icon password-toggle__icon--hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M3 3l18 18"></path>
                                        <path d="M10.6 10.7a3 3 0 0 0 4.1 4.1"></path>
                                        <path d="M9.4 5.2A10.4 10.4 0 0 1 12 5c5.5 0 9.6 4.7 10 5-.6.5-2.2 2.4-4.6 3.8"></path>
                                        <path d="M6.2 6.3C3.8 7.8 2.4 9.5 2 10c.7.6 2.7 3 5.8 4.3"></path>
                                    </svg>
                                    <svg class="password-toggle__icon password-toggle__icon--visible" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M2 12s3.6-6.5 10-6.5S22 12 22 12s-3.6 6.5-10 6.5S2 12 2 12z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <span class="register-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="register-field register-field--full">
                            <label for="email" class="sr-only">Email</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Email"
                                class="register-input"
                                required
                                autocomplete="email"
                            >
                            @error('email')
                                <span class="register-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @error('role')
                        <p class="register-meta-error">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="register-submit">Daftar</button>

                    <p class="register-login-text">
                        Sudah punya akun?
                        <a href="{{ route('login') }}">Masuk</a>
                    </p>

                    <p class="register-divider">Atau</p>

                    <div class="register-social">
                        <button
                            type="button"
                            class="register-google"
                            onclick="alert('Google login belum disetup')"
                        >
                            <img src="{{ asset('assets/images/icons/Google1.png') }}" alt="" class="register-google-icon">
                            <span>Daftar dengan Google</span>
                        </button>

                        <button
                            type="button"
                            class="register-role-switch"
                            onclick="window.location.href='{{ route('choose.role') }}'"
                            aria-label="Pilih role lain"
                        >
                            <img src="{{ asset('assets/images/icons/add-reg.png') }}" alt="" class="register-role-switch-icon">
                        </button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
