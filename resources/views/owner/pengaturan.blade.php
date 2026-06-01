<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun - Spiessport Portal Pemilik</title>

    @vite(['resources/css/app.css', 'resources/css/owner-pengaturan.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

</head>
<body>

<div class="dashboard-layout">

    @include('owner.navbar')

    <main class="main-content">

        <div class="welcome-section">
            <div>
                <h1>Pengaturan Akun</h1>
                <p>Kelola profil dan keamanan akun Anda.</p>
            </div>
        </div>

        @if(session('success'))
        <div style="background:linear-gradient(135deg,#16a34a,#15803d);color:white;padding:16px 22px;border-radius:20px;margin-bottom:24px;font-weight:500;font-size:14px;box-shadow:0 4px 20px rgba(22,163,74,0.25);display:flex;align-items:center;gap:12px;">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;background:rgba(255,255,255,0.2);border-radius:50%;font-size:16px;">✓</span>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div style="background:linear-gradient(135deg,#dc2626,#b91c1c);color:white;padding:16px 22px;border-radius:20px;margin-bottom:24px;font-weight:500;font-size:14px;box-shadow:0 4px 20px rgba(220,38,38,0.25);display:flex;align-items:center;gap:12px;">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;background:rgba(255,255,255,0.2);border-radius:50%;font-size:16px;">✕</span>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        {{-- Tabs Navigation --}}
        <div class="settings-tabs">
            <button class="settings-tab active" id="tab-profil" onclick="switchTab('profil')">Profil</button>
            <button class="settings-tab" id="tab-keamanan" onclick="switchTab('keamanan')">Keamanan</button>

        </div>

        <div class="grid grid-cols-12 gap-gutter">

            {{-- Profile Panel --}}
            <div class="col-span-12 lg:col-span-8 bg-surface-container-lowest p-8 rounded-[20px] shadow-[0px_4px_4px_rgba(0,0,0,0.08)] border border-outline-variant/30" id="panel-profil">
                <form method="POST" action="{{ route('owner.pengaturan.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col md:flex-row items-start md:items-center gap-stack-lg pb-stack-lg border-b border-outline-variant/50">
                        <div class="relative group">
                            <img alt="Foto Profil" class="w-24 h-24 rounded-full border-4 border-primary-fixed-dim object-cover" id="profile-preview"
                                src="https://i.pravatar.cc/100">
                            <button class="absolute bottom-0 right-0 bg-primary text-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform" type="button">
                                <span class="material-symbols-outlined text-sm" data-icon="edit">edit</span>
                            </button>
                        </div>
                        <div>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface">Foto Profil</h3>
                            <p class="text-body-md text-on-surface-variant">PNG atau JPG maksimal 5MB. Gunakan foto terbaru untuk profesionalisme.</p>
                            <div class="mt-2 flex gap-3">
                                <button class="px-4 py-1.5 border border-outline text-on-surface font-label-caps rounded-[20px] hover:bg-surface-container transition-colors" type="button">Ganti Foto</button>
                                <button class="px-4 py-1.5 text-error font-label-caps rounded-[20px] hover:bg-error-container/20 transition-colors" type="button">Hapus</button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter mt-6">
                        <div class="space-y-2">
                            <label class="font-label-caps text-on-surface-variant">NAMA LENGKAP</label>
                            <input class="w-full px-4 py-3 rounded-[20px] border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md"
                                name="name" type="text" value="{{ old('name', auth()->user()->name) }}" placeholder="Masukkan nama lengkap">
                        </div>
                        <div class="space-y-2">
                            <label class="font-label-caps text-on-surface-variant">ALAMAT EMAIL</label>
                            <input class="w-full px-4 py-3 rounded-[20px] border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md"
                                name="email" type="email" value="{{ old('email', auth()->user()->email) }}" placeholder="email@contoh.com">
                        </div>
                        <div class="space-y-2">
                            <label class="font-label-caps text-on-surface-variant">NOMOR TELEPON</label>
                            <input class="w-full px-4 py-3 rounded-[20px] border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md"
                                name="phone" type="tel" value="{{ old('phone', auth()->user()->phone) }}" placeholder="+62">
                        </div>
                        <div class="col-span-full space-y-2">
                            <label class="font-label-caps text-on-surface-variant">ALAMAT</label>
                            <textarea class="w-full px-4 py-3 rounded-[20px] border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md"
                                name="address" placeholder="Alamat lengkap fasilitas atau kantor" rows="3">{{ old('address', auth()->user()->address ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-gutter pt-gutter border-t border-outline-variant flex justify-end gap-4">
                        <button type="reset" class="px-8 py-3 border border-outline text-on-surface font-headline-sm rounded-[20px] hover:bg-surface-container-high transition-all">Batalkan</button>
                        <button type="submit" class="px-8 py-3 bg-primary text-white font-headline-sm rounded-[20px] hover:bg-primary-container shadow-md hover:shadow-lg transition-all transform active:scale-95">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            {{-- Security Panel --}}
            <div class="hidden col-span-12 lg:col-span-8 bg-surface-container-lowest p-8 rounded-[20px] shadow-[0px_4px_4px_rgba(0,0,0,0.08)] border border-outline-variant/30" id="panel-keamanan">
                <div class="mb-stack-lg">
                    <h3 class="font-headline-md text-headline-md text-on-surface">Ubah Kata Sandi</h3>
                    <p class="text-body-md text-on-surface-variant">Pastikan kata sandi Anda kuat untuk menjaga keamanan data bisnis.</p>
                </div>
                <form class="space-y-stack-lg max-w-md" method="POST" action="{{ route('owner.pengaturan.password') }}">
                    @csrf
                    @method('PUT')
                    <div class="space-y-2">
                        <label class="font-label-caps text-on-surface-variant">KATA SANDI BARU</label>
                        <input class="w-full px-4 py-3 rounded-[20px] border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                            name="password" type="password" placeholder="Masukkan kata sandi baru">
                    </div>
                    <div class="space-y-2">
                        <label class="font-label-caps text-on-surface-variant">KONFIRMASI KATA SANDI BARU</label>
                        <input class="w-full px-4 py-3 rounded-[20px] border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                            name="password_confirmation" type="password" placeholder="Ulangi kata sandi baru">
                    </div>
                    <div class="flex justify-end gap-4 mt-6">
                        <button type="submit" class="px-8 py-3 bg-primary text-white font-headline-sm rounded-[20px] hover:bg-primary-container shadow-md hover:shadow-lg transition-all transform active:scale-95">Perbarui Kata Sandi</button>
                    </div>
                </form>
            </div>

            {{-- Info Sidebar --}}
            <div class="col-span-12 lg:col-span-4 space-y-stack-lg">
                <div class="bg-primary-container text-on-primary-container p-6 rounded-[20px] shadow-sm">
                    <div class="flex justify-between items-start mb-stack-md">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fa-solid fa-chart-bar font-headline-md"></i>
                                <h4 class="font-label-caps">Statistik Pesanan</h4>
                            </div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-stat-lg font-stat-lg">{{ $totalBookings ?? 0 }}</span>
                                <span class="text-body-md">Pesanan</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 bg-white/20 px-2 py-1 rounded text-xs font-bold">
                            <i class="fa-solid fa-arrow-{{ $trend >= 0 ? 'up' : 'down' }}"></i> {{ $trend >= 0 ? '+' : '' }}{{ $trend }}%
                        </div>
                    </div>
                    <div class="flex items-end justify-between gap-1 h-20 mb-4">
                        @foreach($dailyBookings as $i => $count)
                        <div class="flex-1 rounded-t-sm" style="height: {{ max($count / $maxDaily * 100, 5) }}%; background: {{ $i === 6 ? '#fff' : 'rgba(255,255,255,0.3)' }}"></div>
                        @endforeach
                    </div>
                    <p class="text-xs opacity-80">Jumlah pesanan masuk dalam 7 hari terakhir</p>
                </div>
                <div class="bg-surface-container p-6 rounded-[20px] border border-outline-variant/30">
                    <h4 class="font-headline-sm text-headline-sm text-on-surface mb-stack-md">Tips Keamanan</h4>
                    <ul class="space-y-3 text-body-md text-on-surface-variant">
                        <li class="flex gap-2">
                            <i class="fa-solid fa-circle-info text-primary shrink-0"></i>
                            Gunakan minimal 8 karakter dengan kombinasi angka dan simbol.
                        </li>
                        <li class="flex gap-2">
                            <i class="fa-solid fa-circle-info text-primary shrink-0"></i>
                            Jangan bagikan kode OTP atau kata sandi kepada siapapun.
                        </li>
                        <li class="flex gap-2">
                            <i class="fa-solid fa-circle-info text-primary shrink-0"></i>
                            Perbarui kata sandi Anda secara berkala setiap 3 bulan.
                        </li>
                    </ul>
                </div>
            </div>

        </div>

    </main>

</div>

@include('owner.faq-popup')

<script>
    function switchTab(tabName) {
        const tabs = ['profil', 'keamanan'];
        tabs.forEach(t => {
            const tabBtn = document.getElementById(`tab-${t}`);
            const panel = document.getElementById(`panel-${t}`);
            if (t === tabName) {
                tabBtn?.classList.add('active');
                panel?.classList.remove('hidden');
            } else {
                tabBtn?.classList.remove('active');
                panel?.classList.add('hidden');
            }
        });
    }

    document.querySelectorAll('button[type="reset"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            this.closest('form').reset();
        });
    });

    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            if (btn && !btn.dataset.submitted) {
                btn.dataset.submitted = 'true';
                btn.disabled = true;
                btn.innerHTML = '<span class="flex items-center gap-2"><span class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span> Menyimpan...</span>';
            }
        });
    });
</script>

</body>
</html>