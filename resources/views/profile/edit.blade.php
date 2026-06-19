@php
    $userName  = $user->name ?? '';
    $gender    = $user->gender ?? '';
    $sidebarItems = [
        ['label'=>'Beranda',  'icon'=>asset('assets/images/icons/dashboard.png'), 'href'=>route('dashboard'),    'active'=>false],
        ['label'=>'Aktivitas',  'icon'=>asset('assets/images/icons/aktivitas.png'), 'href'=>route('activity.index'),       'active'=>false],
        ['label'=>'Favorit',  'icon'=>asset('assets/images/icons/favoritmu.png'), 'href'=>route('favorite.index'),                  'active'=>false],
        ['label'=>'Histori',    'icon'=>asset('assets/images/icons/histori.png'),   'href'=>route('history.index'),                  'active'=>false],
        ['label'=>'Cari tim',   'icon'=>asset('assets/images/icons/caritim.png'),   'href'=>route('matches.index'),'active'=>false],
        ['label'=>'Pemesanan',    'icon'=>asset('assets/images/icons/booking.png'),   'href'=>route('booking.index'),        'active'=>false],
        ['label'=>'Keahlian', 'icon'=>asset('assets/images/icons/keahlian.png'),  'href'=>route('skill.index'),                  'active'=>false],
        ['label'=>'Profil',     'icon'=>asset('assets/images/icons/profil.png'),    'href'=>route('profile.show'), 'active'=>true],
    ];
    $sidebarUtilities = [
        ['label'=>'Bantuan',    'icon'=>asset('assets/images/icons/bantuan.png'),    'href'=>route('preview.help')],
        ['label'=>'Pengaturan', 'icon'=>asset('assets/images/icons/pengaturan.png'), 'href'=>route('profile.edit')],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Profil – {{ config('app.name', 'Spies Sport') }}</title>
    @vite([
        'resources/css/app.css',
        'resources/css/player-dashboard.css',
        'resources/css/player-profile.css',
        'resources/js/player-dashboard.js',
    ])
</head>
<body class="player-dashboard-page"
      style="--player-dashboard-bg:url('{{ asset('assets/images/bg/bg-login.png') }}');">
<div class="player-dashboard-shell">

{{-- ============ SIDEBAR ============ --}}
<aside class="player-sidebar" data-sidebar>
    <div class="player-sidebar__inner">
        <div class="player-sidebar__header">
            <a href="{{ route('dashboard') }}" class="player-sidebar__brand">
                <img src="{{ asset('assets/images/logo/logodb.png') }}" alt="Spies Sport" class="player-sidebar__logo">
            </a>
            <button type="button" class="player-sidebar__close" data-sidebar-close aria-label="Tutup"><span></span><span></span></button>
        </div>

        <nav class="player-sidebar__nav" aria-label="Menu utama">
            @foreach($sidebarItems as $item)
            @php $cls='player-sidebar__item'.($item['active']?' is-active':'').($item['href']?'':' is-disabled'); @endphp
            @if($item['href'])
            <a href="{{ $item['href'] }}" class="{{ $cls }}">
                <span class="player-sidebar__icon-wrap"><img src="{{ $item['icon'] }}" alt="" class="player-sidebar__icon"></span>
                <span class="player-sidebar__label">{{ $item['label'] }}</span>
            </a>
            @else
            <button type="button" class="{{ $cls }}" disabled aria-disabled="true">
                <span class="player-sidebar__icon-wrap"><img src="{{ $item['icon'] }}" alt="" class="player-sidebar__icon"></span>
                <span class="player-sidebar__label">{{ $item['label'] }}</span>
            </button>
            @endif
            @endforeach
        </nav>

        <div class="player-sidebar__footer">
            @foreach($sidebarUtilities as $item)
            <a href="{{ $item['href'] }}" class="player-sidebar__item">
                <span class="player-sidebar__icon-wrap"><img src="{{ $item['icon'] }}" alt="" class="player-sidebar__icon"></span>
                <span class="player-sidebar__label">{{ $item['label'] }}</span>
            </a>
            @endforeach
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="player-sidebar__item player-sidebar__item--logout">
                    <span class="player-sidebar__icon-wrap"><img src="{{ asset('assets/images/icons/keluar.png') }}" alt="" class="player-sidebar__icon"></span>
                    <span class="player-sidebar__label">Keluar</span>
                </button>
            </form>
        </div>
    </div>
</aside>
<button type="button" class="player-sidebar__backdrop" data-sidebar-backdrop aria-label="Tutup sidebar"></button>

{{-- ============ MAIN ============ --}}
<main class="player-dashboard-main">

    {{-- Topbar --}}
    <header class="profile-topbar">
        <button type="button" class="player-dashboard-topbar__menu" data-sidebar-open aria-label="Menu"><span></span><span></span><span></span></button>

        <div style="position: relative;">
            @include('partials.notification-bell')
        </div>

        <button type="button" class="player-profile-pill" aria-label="Profil">
            <span class="player-profile-pill__avatar">
                <img src="{{ $user->avatarUrl() }}" alt="{{ $userName }}" class="player-avatar-image player-avatar-image--profile" id="topbar-avatar">
            </span>
            <span class="player-profile-pill__name">{{ $userName }}</span>
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" style="opacity:.45;margin-left:2px"><path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
    </header>

    {{-- Page title --}}
    <section class="profile-page-title">
        <h1>Edit Profil</h1>
    </section>

    <section class="profile-content">

        {{-- Flash: success --}}
        @if(session('status') === 'profile-updated')
        <div class="profile-alert profile-alert--success" id="profile-flash" role="alert">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 12L10 17L19 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Profil berhasil diperbarui!
        </div>
        @endif

        {{-- Flash: validation errors --}}
        @if($errors->any())
        <div class="profile-alert profile-alert--error" role="alert">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/><path d="M12 8V12M12 16H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            <div>
                @foreach($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Cover --}}
        <div class="profile-cover" id="profile-cover-btn" role="button" tabindex="0" aria-label="Ganti foto sampul">
            <img src="{{ $user->cover_photo ? (str_starts_with($user->cover_photo, 'covers/') ? asset('storage/' . $user->cover_photo) : $user->cover_photo) : asset('assets/images/bg/Explore.png') }}" alt="Cover" class="profile-cover__img" id="cover-img-preview">
            <div class="profile-cover__edit-overlay">
                <span class="profile-cover__edit-icon">
                    <svg viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="18" height="14" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M3 17L7.5 12.5L10.5 15.5L14 11L21 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="7.5" cy="8" r="1.5" fill="currentColor"/></svg>
                </span>
            </div>
            {{-- cover upload input moved into the form to ensure it is submitted --}}
        </div>

        {{-- FORM CARD --}}
        <div class="profile-card">

            {{-- Avatar --}}
            <div class="profile-avatar-block">
                <div class="profile-avatar-wrap">
                    <img src="{{ $user->avatarUrl() }}" alt="{{ $userName }}"
                         class="profile-avatar-wrap__img" id="avatar-preview">
                    <label for="avatar-upload" class="profile-avatar-wrap__edit" title="Ganti foto">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </label>
                </div>
            </div>

            {{-- THE FORM --}}
            <form id="profile-edit-form"
                  method="POST"
                  action="{{ route('profile.update') }}"
                  enctype="multipart/form-data"
                  class="profile-form"
                  novalidate>
                @csrf
                @method('PATCH')

                {{-- Hidden: avatar file --}}
                <input type="file" id="avatar-upload" name="avatar" accept="image/*" style="display:none;" aria-hidden="true">

                {{-- Hidden: cover file --}}
                <input type="file" id="cover-upload" name="cover" accept="image/*" style="display:none;" aria-hidden="true">

                <div class="profile-form__main-grid">

                    {{-- LEFT COLUMN --}}
                    <div class="profile-form__left-col">

                        <div class="profile-form__group">
                            <label for="pf-name">Nama Lengkap</label>
                            <input id="pf-name" name="name" type="text"
                                   class="profile-form__input @error('name') is-error @enderror"
                                   value="{{ old('name', $user->name) }}"
                                   placeholder="Nama lengkap" autocomplete="name" required>
                            @error('name')<span class="profile-form__error">{{ $message }}</span>@enderror
                        </div>

                        <div class="profile-form__group">
                            <label for="pf-username">Nama Pengguna</label>
                            <div class="profile-form__input-prefix-wrap">
                                <span class="profile-form__prefix">@</span>
                                <input id="pf-username" name="username" type="text"
                                       class="profile-form__input profile-form__input--prefix @error('username') is-error @enderror"
                                       value="{{ old('username', $user->username) }}"
                                       placeholder="username" autocomplete="username" required>
                            </div>
                            @error('username')<span class="profile-form__error">{{ $message }}</span>@enderror
                        </div>

                        <div class="profile-form__group">
                            <label>Level Keahlian</label>
                            <div style="padding:13px 16px; border-radius:14px; background:rgba(0,0,77,.04); border:1.5px solid rgba(0,0,77,.08); font-size:.93rem; font-weight:700; color:rgba(0,0,77,.5); display:flex; align-items:center; gap:8px;">
                                @php
                                    $_pBookings = \App\Models\Booking::where('user_id', $user->id)->whereNotIn('status', ['cancelled','expired','rejected'])->count();
                                    $_pMatches = \Illuminate\Support\Facades\DB::table('match_players')->where('user_id', $user->id)->count();
                                    $_pReviews = \App\Models\Review::where('user_id', $user->id)->count();
                                    $_pPoints = ($_pBookings * 1) + ($_pMatches * 2) + ($_pReviews * 3);
                                    if ($_pPoints >= 21) { $_pLevel = 'Pro'; $_pEmoji = '🏆'; }
                                    elseif ($_pPoints >= 6) { $_pLevel = 'Aktif'; $_pEmoji = '⭐'; }
                                    else { $_pLevel = 'Pemula'; $_pEmoji = '🌱'; }
                                @endphp
                                <span>{{ $_pEmoji }}</span>
                                <span>{{ $_pLevel }}</span>
                            </div>
                            <p style="font-size:0.78rem;color:rgba(0,0,77,.5);margin:4px 0 0;">Level ditentukan otomatis berdasarkan aktivitas dan pencapaianmu ({{ $_pPoints }} poin).</p>
                        </div>

                        <div class="profile-form__group">
                            <label class="profile-form__checkbox-label">
                                <input type="checkbox" name="open_partner" value="1"
                                       {{ old('open_partner', $user->open_partner) ? 'checked' : '' }}>
                                <span class="profile-form__checkbox-text">Tampilkan profil untuk Cari Teman Main</span>
                            </label>
                            <p style="font-size:0.78rem;color:rgba(0,0,77,.5);margin:4px 0 0;">Aktifkan agar pemain lain dapat menemukan dan mengajak Anda bermain.</p>
                        </div>

                    </div>{{-- /left-col --}}

                    {{-- RIGHT COLUMN --}}
                    <div class="profile-form__right-col">

                        <div class="profile-form__group">
                            <label for="pf-email">Email</label>
                            <input id="pf-email" name="email" type="email"
                                   class="profile-form__input @error('email') is-error @enderror"
                                   value="{{ old('email', $user->email) }}"
                                   placeholder="email@example.com" autocomplete="email" required>
                            @error('email')<span class="profile-form__error">{{ $message }}</span>@enderror
                        </div>

                        <div class="profile-form__group">
                            <label for="pf-phone">Nomor Telepon</label>
                            <input id="pf-phone" name="phone" type="tel"
                                   class="profile-form__input @error('phone') is-error @enderror"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="Cth: 081234567890" autocomplete="tel">
                            @error('phone')<span class="profile-form__error">{{ $message }}</span>@enderror
                        </div>

                        <div class="profile-form__group">
                            <label for="pf-city">Kota</label>
                            <input id="pf-city" name="city" type="text"
                                   class="profile-form__input @error('city') is-error @enderror"
                                   value="{{ old('city', $user->city) }}"
                                   placeholder="Cth: Jakarta, Surabaya, Bandung..." autocomplete="address-level2">
                            @error('city')<span class="profile-form__error">{{ $message }}</span>@enderror
                            <p style="font-size:0.78rem;color:rgba(0,0,77,.5);margin:4px 0 0;">Digunakan untuk mencari lapangan terdekat.</p>
                        </div>

                        <div class="profile-form__group" style="flex:1;">
                            <label for="pf-bio">Bio</label>
                            <textarea id="pf-bio" name="bio"
                                      class="profile-form__textarea profile-form__textarea--tall @error('bio') is-error @enderror"
                                      placeholder="Ceritakan sedikit tentang diri kamu..."
                                      rows="6" maxlength="500">{{ old('bio', $user->bio) }}</textarea>
                            <span class="profile-form__char-count" id="bio-count">{{ strlen(old('bio', $user->bio ?? '')) }}/500</span>
                        </div>

                    </div>{{-- /right-col --}}

                </div>{{-- /grid --}}

                {{-- Actions --}}
                <div class="profile-actions">
                    <a href="{{ route('profile.show') }}" class="profile-btn profile-btn--discard">Batal</a>
                    <button type="submit" class="profile-btn profile-btn--save" id="profile-save-btn">
                        <span class="profile-btn__label">Simpan Perubahan</span>
                        <span class="profile-btn__spinner" aria-hidden="true" style="display:none;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="spinning"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2.5" stroke-dasharray="40" stroke-dashoffset="20" stroke-linecap="round"/></svg>
                        </span>
                    </button>
                </div>

            </form>
        </div>{{-- /card --}}
    </section>
</main>
</div>

<script>
(function(){
    /* ---- Avatar preview ---- */
    const avatarInput   = document.getElementById('avatar-upload');
    const avatarPreview = document.getElementById('avatar-preview');
    const topbarAvatar  = document.getElementById('topbar-avatar');

    avatarInput?.addEventListener('change', function(){
        const file = this.files[0];
        if(!file) return;
        if (window._avatarUrl) URL.revokeObjectURL(window._avatarUrl);
        const url = URL.createObjectURL(file);
        window._avatarUrl = url;
        if(avatarPreview) avatarPreview.src = url;
        if(topbarAvatar)  topbarAvatar.src  = url;
    });

    /* ---- Cover preview ---- */
    const coverBtn   = document.getElementById('profile-cover-btn');
    const coverInput = document.getElementById('cover-upload');
    const coverImg   = document.getElementById('cover-img-preview');

    coverBtn?.addEventListener('click', ()=> coverInput.click());
    coverBtn?.addEventListener('keydown', e=>{ if(e.key==='Enter'||e.key===' ') coverInput.click(); });
    coverInput?.addEventListener('change', function(){
        const file = this.files[0];
        if(file && coverImg){ coverImg.src = URL.createObjectURL(file); }
    });

    /* ---- Bio char count ---- */
    const bioArea  = document.getElementById('pf-bio');
    const bioCount = document.getElementById('bio-count');
    bioArea?.addEventListener('input', ()=>{
        const n = bioArea.value.length;
        bioCount.textContent = n+'/500';
        bioCount.style.color = n > 450 ? '#eb5436' : '';
    });

    /* ---- Submit: loading state ---- */
    const form    = document.getElementById('profile-edit-form');
    const saveBtn = document.getElementById('profile-save-btn');
    form?.addEventListener('submit', ()=>{
        if(!saveBtn) return;
        saveBtn.disabled = true;
        saveBtn.querySelector('.profile-btn__label').textContent = 'Menyimpan...';
        saveBtn.querySelector('.profile-btn__spinner').style.display = 'inline-flex';
    });

    /* ---- Auto-dismiss flash ---- */
    const flash = document.getElementById('profile-flash');
    if(flash) setTimeout(()=>{ flash.style.opacity='0'; flash.style.transition='opacity .5s'; setTimeout(()=>flash.remove(), 500); }, 3500);

    /* ---- Username @ prefix auto-strip ---- */
    const usernameInput = document.getElementById('pf-username');
    usernameInput?.addEventListener('input', ()=>{
        if(usernameInput.value.startsWith('@')){
            usernameInput.value = usernameInput.value.slice(1);
        }
    });
})();
</script>

<style>
/* Spinner anim */
@keyframes spin { to { transform: rotate(360deg); } }
.spinning { animation: spin .8s linear infinite; }

/* Error state */
.profile-form__input.is-error,
.profile-form__select.is-error,
.profile-form__textarea.is-error {
    border-color: #eb5436;
    background: rgba(235,84,54,0.04);
}
.profile-form__error {
    display: block;
    font-size: .78rem;
    font-weight: 600;
    color: #eb5436;
    margin-top: 2px;
}
.profile-form__char-count {
    display: block;
    text-align: right;
    font-size: .75rem;
    color: rgba(0,0,77,.42);
    margin-top: 3px;
}
.profile-form__hint {
    font-size: .75rem;
    font-weight: 500;
    color: rgba(0,0,77,.45);
}
/* Username prefix wrapper */
.profile-form__input-prefix-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.profile-form__prefix {
    position: absolute;
    left: 16px;
    font-size: .93rem;
    font-weight: 700;
    color: rgba(0,0,77,.5);
    pointer-events: none;
    z-index: 1;
}
.profile-form__input--prefix {
    padding-left: 28px !important;
}
/* Save spinner */
.profile-btn__spinner {
    display: inline-flex;
    align-items: center;
    margin-left: 6px;
}
.profile-form__checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    padding: 10px 14px;
    border-radius: 12px;
    border: 1px solid rgba(0,0,77,.08);
    background: rgba(255,255,255,.6);
    transition: all .2s;
}
.profile-form__checkbox-label:hover {
    border-color: rgba(0,0,77,.2);
    background: rgba(255,255,255,.9);
}
.profile-form__checkbox-label input[type="checkbox"] {
    width: 20px;
    height: 20px;
    accent-color: #eb5436;
}
.profile-form__checkbox-text {
    font-size: .88rem;
    font-weight: 600;
    color: rgba(0,0,77,.85);
}
</style>
</body>
</html>
