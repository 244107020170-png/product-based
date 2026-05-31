@php
    use Carbon\Carbon;
    use App\Models\Field;
    use App\Models\User;

    $user = auth()->user();
    $userName = $user?->name ?: 'Pecinta Olahraga';
    $currentDate = Carbon::now()->locale('id')->translatedFormat('j F Y');
    $profileAvatar = $user?->avatarUrl();
    $sportOptions = $cards->pluck('sport')->unique()->values();
    $defaultSports = collect(['Futsal', 'Badminton', 'Basket', 'Voli', 'Tennis', 'Golf', 'Renang', 'Panahan', 'Lari', 'Sepeda', 'Tinju', 'Bela Diri', 'Yoga', 'Fitness', 'Hiking', 'Padel', 'Baseball', 'Rugby', 'Senam']);
    $sportOptions = $sportOptions->merge($defaultSports)->unique()->values();
    $sportEmojiMap = [
        'Futsal' => '⚽',
        'Badminton' => '🏸',
        'Basket' => '🏀',
        'Voli' => '🏐',
        'Tennis' => '🎾',
    ];



    $allFields = Field::with('owner')->get();
    $fullSportEmoji = [
        'Futsal'=>'⚽','Badminton'=>'🏸','Basket'=>'🏀','Voli'=>'🏐','Tennis'=>'🎾',
        'Golf'=>'🏌️','Renang'=>'🏊','Panahan'=>'🏹','Lari'=>'🏃','Sepeda'=>'🚴',
        'Tinju'=>'🥊','Bela Diri'=>'🥋','Yoga'=>'🧘','Fitness'=>'🏋️','Hiking'=>'🥾',
        'Padel'=>'🎾','Baseball'=>'⚾','Rugby'=>'🏉','Senam'=>'🤸',
    ];
    $fieldJson = json_encode($allFields->map(fn($f) => [
        'id' => $f->id, 'name' => $f->name, 'location' => $f->location,
        'type' => $f->type, 'rating' => $f->rating, 'image' => $f->image_url,
    ])->values()->toArray());
    $sportOptionsJson = json_encode($sportOptions->values()->toArray());
    $sportEmojiMapJson = json_encode($sportEmojiMap);

    $sidebarItems = [
        ['label' => 'Beranda', 'icon' => asset('assets/images/icons/dashboard.png'), 'href' => route('dashboard'), 'active' => false],
        ['label' => 'Aktivitas', 'icon' => asset('assets/images/icons/aktivitas.png'), 'href' => route('activity.index'), 'active' => false],
        ['label' => 'Favorit', 'icon' => asset('assets/images/icons/favoritmu.png'), 'href' => route('favorite.index'), 'active' => false],
        ['label' => 'Histori', 'icon' => asset('assets/images/icons/histori.png'), 'href' => route('history.index'), 'active' => false],
        ['label' => 'Cari tim', 'icon' => asset('assets/images/icons/caritim.png'), 'href' => route('matches.index'), 'active' => true],
        ['label' => 'Pemesanan', 'icon' => asset('assets/images/icons/booking.png'), 'href' => route('booking.index'), 'active' => false],
        ['label' => 'Keahlian', 'icon' => asset('assets/images/icons/keahlian.png'), 'href' => route('skill.index'), 'active' => false],
        ['label' => 'Profil', 'icon' => asset('assets/images/icons/profil.png'), 'href' => route('profile.show'), 'active' => false],
    ];
    $sidebarUtilities = [
        ['label' => 'Bantuan', 'icon' => asset('assets/images/icons/bantuan.png'), 'href' => route('preview.help')],
        ['label' => 'Pengaturan', 'icon' => asset('assets/images/icons/pengaturan.png'), 'href' => route('profile.edit')],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cari Tim – {{ config('app.name', 'Spies Sport') }}</title>
    @vite(['resources/js/app.js', 'resources/css/app.css', 'resources/css/player-dashboard.css', 'resources/js/player-dashboard.js'])
    <style>
        [x-cloak] { display: none !important; }
        /* Modern Swipe Engine styles with bounce spring */
        .swipe-card-modern {
            transform: translateX(0) rotate(0deg);
            transition: transform 0.45s cubic-bezier(0.175, 0.885, 0.32, 1.15), opacity 0.3s ease, box-shadow 0.3s ease;
            touch-action: none;
        }

        .swipe-card-modern.is-swiping-left {
            transform: translateX(-150%) rotate(-30deg) scale(0.9);
            opacity: 0;
            pointer-events: none;
        }

        .swipe-card-modern.is-swiping-right {
            transform: translateX(150%) rotate(30deg) scale(0.9);
            opacity: 0;
            pointer-events: none;
        }

        .swipe-card-modern--back {
            transition: transform 0.45s cubic-bezier(0.175, 0.885, 0.32, 1.15), opacity 0.3s ease;
        }

        /* Clean slim scrollbar for scroll areas */
        .upcoming-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .upcoming-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .upcoming-scroll::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 99px;
        }
        .upcoming-scroll::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

        /* Custom checkbox sizing */
        .w-4\.5 {
            width: 1.125rem;
        }
        .h-4\.5 {
            height: 1.125rem;
        }

        /* Swipe card image gradient overlay for better text contrast */
        .swipe-card-img-gradient::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 40%, rgba(0,0,0,0.06) 100%);
            pointer-events: none;
        }

        /* Ripple button effect */
        .btn-ripple {
            position: relative;
            overflow: hidden;
        }
        .btn-ripple::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at var(--x, 50%) var(--y, 50%), rgba(255,255,255,0.15) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .btn-ripple:hover::after {
            opacity: 1;
        }

        /* Smooth number font for stats */
        .numeral {
            font-variant-numeric: tabular-nums;
        }

        @media (max-width: 640px) {
            .swipe-card-modern.is-swiping-left {
                transform: translateX(-100%) rotate(-20deg) scale(0.9);
            }
            .swipe-card-modern.is-swiping-right {
                transform: translateX(100%) rotate(20deg) scale(0.9);
            }
        }
    </style>
    <script>
        window.__allFields = {!! $fieldJson !!};
    </script>
    <script>
    window.matchesIndexFilter = function () {
        return {
            openFilterModal: false,
            openPrivateSportModal: false,
            openFieldModal: false,
            selectedPrivateSport: '',
            selectedSports: [],
            selectedGender: '',
            allSports: {!! $sportOptionsJson !!},
            sportEmoji: {!! $sportEmojiMapJson !!},
                toggleSport(sport) {
                    if (!sport) return;

                    if (this.selectedSports.includes(sport)) {
                        this.selectedSports = this.selectedSports.filter(s => s !== sport);
                    } else {
                        this.selectedSports = [...this.selectedSports, sport];
                    }

                    this.$nextTick(() => {
                        if (window.buildDeck) window.buildDeck();
                    });
                },
                resetFilters() {
                    this.selectedSports = [];
                    this.selectedGender = '';
                    window.__genderFilter = '';

                    this.$nextTick(() => {
                        if (window.buildDeck) window.buildDeck();
                    });
                },
                prioritizedSports() {
                    const selected = this.allSports.filter(s => this.selectedSports.includes(s));
                    const unselected = this.allSports.filter(s => !this.selectedSports.includes(s));
                    return [...selected, ...unselected];
                },
                selectPrivateSport(sport) {
                    this.selectedPrivateSport = sport;
                    this.openPrivateSportModal = false;
                    this.openFieldModal = true;
                },
                filteredFields() {
                    if (!this.selectedPrivateSport) return [];
                    return (window.__allFields || []).filter(f => f.type === this.selectedPrivateSport);
                },
                fieldSearchQuery: '',
                get filteredFieldList() {
                    const q = this.fieldSearchQuery.toLowerCase().trim();
                    return this.filteredFields().filter(f => {
                        if (!q) return true;
                        return f.name.toLowerCase().includes(q) || (f.location && f.location.toLowerCase().includes(q));
                    });
                },
            };
        };
    </script>
</head>
<body class="player-dashboard-page" style="--player-dashboard-bg:url('{{ asset('assets/images/bg/bg-login.png') }}');">
<div class="player-dashboard-shell" x-data="matchesIndexFilter()">
    <aside class="player-sidebar" data-sidebar>
        <div class="player-sidebar__inner">
            <div class="player-sidebar__header">
                <a href="{{ route('dashboard') }}" class="player-sidebar__brand">
                    <img src="{{ asset('assets/images/logo/logodb.png') }}" alt="Spies Sport" class="player-sidebar__logo">
                </a>
                <button type="button" class="player-sidebar__close" data-sidebar-close><span></span><span></span></button>
            </div>
            <nav class="player-sidebar__nav">
                @foreach($sidebarItems as $item)
                    @php $cls = 'player-sidebar__item'.($item['active']?' is-active':'').($item['href']?'':' is-disabled'); @endphp
                    @if($item['href'])
                        <a href="{{ $item['href'] }}" class="{{ $cls }}">
                            <span class="player-sidebar__icon-wrap"><img src="{{ $item['icon'] }}" alt="" class="player-sidebar__icon"></span>
                            <span class="player-sidebar__label">{{ $item['label'] }}</span>
                        </a>
                    @else
                        <button type="button" class="{{ $cls }}" disabled>
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
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="player-sidebar__item player-sidebar__item--logout">
                        <span class="player-sidebar__icon-wrap"><img src="{{ asset('assets/images/icons/keluar.png') }}" alt="" class="player-sidebar__icon"></span>
                        <span class="player-sidebar__label">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>
    <button type="button" class="player-sidebar__backdrop" data-sidebar-backdrop></button>

    <main class="player-dashboard-main">
        <header class="player-dashboard-topbar">
            <div class="player-dashboard-topbar__left">
                <button type="button" class="player-dashboard-topbar__menu" data-sidebar-open><span></span><span></span><span></span></button>
                <label class="player-search" for="team-search">
                    <span class="player-search__icon">
                        <svg viewBox="0 0 20 20" fill="none"><circle cx="9" cy="9" r="5.75" stroke="currentColor" stroke-width="1.8"/><path d="M13.5 13.5L17 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </span>
                    <input id="team-search" type="search" placeholder="Cari pertandingan..." oninput="window._searchTitle = this.value; if(window.buildDeck) window.buildDeck()">
                </label>
            </div>
            <div class="player-dashboard-topbar__right">
                <div class="player-dashboard-topbar__date">
                    <span class="player-inline-icon">
                        <svg viewBox="0 0 24 24" fill="none"><rect x="3.5" y="5.5" width="17" height="15" rx="2.5" stroke="currentColor" stroke-width="1.8"/><path d="M7 3.5V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M17 3.5V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M3.5 9.5H20.5" stroke="currentColor" stroke-width="1.8"/></svg>
                    </span>
                    <span>{{ $currentDate }}</span>
                </div>
                <div style="position: relative;">
                    @include('partials.notification-bell')
                </div>
                <a href="{{ route('profile.show') }}" class="player-profile-pill">
                    <span class="player-profile-pill__avatar">
                        <img src="{{ $profileAvatar }}" alt="Profil" class="player-avatar-image player-avatar-image--profile" onerror="this.src='{{ asset('assets/images/characters/' . ($user->gender === 'perempuan' ? 'profil2.png' : 'profil1.png')) }}'">
                    </span>
                    <span class="player-profile-pill__name">{{ $userName }}</span>
                </a>
            </div>
        </header>

        {{-- ALERT MESSAGES --}}
        @if(session('success'))
        <div style="margin: 0 20px 20px; background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 12px 16px; border-radius: 10px; font-size: 14px; font-weight: 600;">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div style="margin: 0 20px 20px; background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 12px 16px; border-radius: 10px; font-size: 14px; font-weight: 600;">
            {{ session('error') }}
        </div>
        @endif

        {{-- HIGHLY RESPONSIVE 12-COLUMN LAYOUT --}}
        <section class="max-w-[1340px] mx-auto px-4 py-6 md:py-8 font-afacad">
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-extrabold font-archivo text-[#02025b] tracking-tight">Cari Lawan Main</h1>
                <p class="text-sm md:text-base font-semibold text-slate-400 mt-1">Geser kartu untuk menemukan pertandingan seru!</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 lg:grid-cols-12 gap-6 md:gap-8 items-stretch">
                
                {{-- LEFT COLUMN - FILTER & UPCOMING (md:col-span-4 / lg:col-span-3) --}}
                <div class="order-2 md:order-1 md:col-span-4 lg:col-span-3 space-y-6 flex flex-col w-full">
                    
                    {{-- Button Buat Pertandingan --}}
                    <div class="flex gap-3">
                        <a href="{{ route('matches.create') }}" class="flex-1 flex items-center justify-center gap-2.5 py-4 px-6 bg-[#11114b] hover:bg-[#02025b] text-white font-extrabold font-archivo rounded-2xl shadow-md shadow-slate-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 group active:translate-y-0">
                            <svg class="w-5 h-5 transition-transform group-hover:rotate-90 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            <span>Buat Publik</span>
                        </a>
                        <button @click="openPrivateSportModal = true" class="flex-1 flex items-center justify-center gap-2.5 py-4 px-6 bg-white hover:bg-slate-50 text-[#11114b] font-extrabold font-archivo rounded-2xl border-2 border-[#11114b] shadow-md shadow-slate-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 group active:translate-y-0">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                            <span>Buat Pribadi</span>
                        </button>
                    </div>

                    {{-- Filter Section --}}
                    <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-[0_10px_30px_-5px_rgba(0,0,77,0.03)] space-y-4 flex-1">
                        <h3 class="text-sm font-extrabold font-archivo text-[#02025b] uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" /></svg>
                            <span>Pilih Olahraga</span>
                        </h3>
                        <div class="flex flex-col gap-2.5" id="sport-filter-group">
                            <template x-for="(sport, index) in prioritizedSports()" :key="'v-' + sport">
                                <label x-show="index < 5" class="flex items-center justify-between p-3.5 border rounded-2xl cursor-pointer transition-all duration-200 select-none group hover:shadow-sm"
                                       :class="selectedSports.includes(sport) ? 'bg-indigo-50/80 border-indigo-200 text-indigo-700 font-bold shadow-sm' : 'bg-white border-slate-100 text-slate-700 hover:border-indigo-100'">
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg group-hover:scale-110 transition-transform duration-200" x-text="sportEmoji[sport] || '🏆'"></span>
                                        <span class="text-sm font-bold" x-text="sport"></span>
                                    </div>
                                    <input type="checkbox" :value="sport" class="w-5 h-5 rounded-md text-indigo-600 focus:ring-indigo-500 border-slate-300"
                                           :checked="selectedSports.includes(sport)"
                                           @change="toggleSport(sport)">
                                </label>
                            </template>
                        </div>

                        {{-- Hidden checkboxes — always in DOM for buildDeck --}}
                        <div class="hidden" aria-hidden="true">
                            @foreach($sportOptions as $sport)
                                <input type="checkbox" value="{{ $sport }}" class="sport-checkbox"
                                       :checked="selectedSports.includes($el.value)">
                            @endforeach
                        </div>

                        {{-- Gender Filter --}}
                        <div class="pt-1 space-y-2.5">
                            <h4 class="text-[10px] font-extrabold font-archivo text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                <span>Filter Gender</span>
                            </h4>
                            <div class="flex gap-2">
                                <button @click="selectedGender = ''; window.__genderFilter = ''; $nextTick(() => { if(window.buildDeck) window.buildDeck() })"
                                        :class="selectedGender === '' ? 'bg-indigo-50 border-indigo-200 text-indigo-700 font-bold shadow-sm' : 'bg-white border-slate-100 text-slate-500 hover:border-indigo-100'"
                                        class="flex-1 text-[11px] font-extrabold font-archivo py-2.5 rounded-xl border transition-all duration-200">
                                    Semua
                                </button>
                                <button @click="selectedGender = 'laki-laki'; window.__genderFilter = 'laki-laki'; $nextTick(() => { if(window.buildDeck) window.buildDeck() })"
                                        :class="selectedGender === 'laki-laki' ? 'bg-blue-50 border-blue-200 text-blue-700 font-bold shadow-sm' : 'bg-white border-slate-100 text-slate-500 hover:border-blue-100'"
                                        class="flex-1 text-[11px] font-extrabold font-archivo py-2.5 rounded-xl border transition-all duration-200 flex items-center justify-center gap-1">
                                    <span>♂</span> Pria
                                </button>
                                <button @click="selectedGender = 'perempuan'; window.__genderFilter = 'perempuan'; $nextTick(() => { if(window.buildDeck) window.buildDeck() })"
                                        :class="selectedGender === 'perempuan' ? 'bg-rose-50 border-rose-200 text-rose-700 font-bold shadow-sm' : 'bg-white border-slate-100 text-slate-500 hover:border-rose-100'"
                                        class="flex-1 text-[11px] font-extrabold font-archivo py-2.5 rounded-xl border transition-all duration-200 flex items-center justify-center gap-1">
                                    <span>♀</span> Wanita
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-1">
                            @if($sportOptions->count() > 5)
                                <button @click="openFilterModal = true" class="text-xs font-extrabold font-archivo text-indigo-600 hover:text-indigo-800 transition-colors select-none flex items-center gap-1 group">
                                    <span>Selengkapnya</span>
                                    <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                                </button>
                            @else
                                <span></span>
                            @endif
                            <button @click="resetFilters()" class="text-[10px] font-extrabold font-archivo text-slate-400 hover:text-rose-500 transition-colors select-none flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" /></svg>
                                <span>Reset Filter</span>
                            </button>
                        </div>
                    </div>

                </div>

                {{-- CENTER COLUMN - SWIPE CARD (md:col-span-8 / lg:col-span-6) --}}
                <div class="order-1 md:order-2 md:col-span-8 lg:col-span-6 flex flex-col items-center justify-center min-h-[580px] w-full self-start">
                    <div class="swipe-container relative w-full max-w-[440px] h-[550px] flex items-center justify-center">
                        
                        {{-- Back Card (Preview) --}}
                        <div class="swipe-card-modern swipe-card-modern--back absolute w-full h-[530px] bg-white rounded-[32px] border border-slate-100 shadow-md overflow-hidden opacity-60 scale-95 pointer-events-none z-10" data-swipe-card-back hidden>
                            <div class="relative w-full h-[250px] bg-slate-100 overflow-hidden">
                                <img data-card-back-image src="" alt="Tim berikutnya" class="w-full h-full object-cover">
                                <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-[#11114b]/80 via-[#11114b]/30 to-transparent p-5 pt-10">
                                    <span class="text-white text-base font-extrabold font-archivo leading-tight line-clamp-2 drop-shadow-lg" data-card-back-title-badge></span>
                                </div>
                            </div>
                            <div class="p-6">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg border text-[9px] font-extrabold font-archivo tracking-wider uppercase" data-card-back-sport-tag></span>
                            </div>
                        </div>

                        {{-- Front Card --}}
                        <div class="swipe-card-modern absolute w-full h-[530px] bg-white rounded-[32px] border border-slate-100 shadow-[0_20px_40px_-10px_rgba(17,17,75,0.12)] overflow-hidden z-20 cursor-grab active:cursor-grabbing select-none" data-swipe-card>
                            <div class="relative w-full h-[250px] bg-slate-100 overflow-hidden">
                                <img data-card-image src="" alt="Tim olahraga" class="w-full h-full object-cover pointer-events-none">
                                <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-[#11114b]/80 via-[#11114b]/30 to-transparent p-5 pt-10">
                                    <span class="text-white text-base font-extrabold font-archivo leading-tight line-clamp-2 drop-shadow-lg" data-card-title-badge></span>
                                </div>
                            </div>
                            <div class="p-6 flex flex-col justify-between h-[280px]">
                                <div>
                                    <div class="flex items-center gap-2 mb-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg border text-[9px] font-extrabold font-archivo tracking-wider uppercase" data-card-sport-tag></span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-x-4 gap-y-3.5">
                                        <!-- Schedule -->
                                        <div class="flex items-start gap-2.5">
                                            <div class="p-2 bg-blue-50/70 text-blue-600 rounded-xl flex-shrink-0 mt-0.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[9px] font-extrabold font-archivo text-slate-400 uppercase tracking-wider">Jadwal</span>
                                                <span class="text-xs font-bold text-slate-600 line-clamp-2 leading-tight" data-card-schedule></span>
                                            </div>
                                        </div>

                                        <!-- Venue -->
                                        <div class="flex items-start gap-2.5">
                                            <div class="p-2 bg-rose-50/70 text-rose-600 rounded-xl flex-shrink-0 mt-0.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[9px] font-extrabold font-archivo text-slate-400 uppercase tracking-wider">Lokasi</span>
                                                <span class="text-xs font-bold text-slate-700 line-clamp-2 leading-tight" data-card-venue></span>
                                            </div>
                                        </div>

                                        <!-- Needs (Slot Pemain) -->
                                        <div class="flex items-start gap-2.5">
                                            <div class="p-2 bg-amber-50/70 text-amber-600 rounded-xl flex-shrink-0 mt-0.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94-3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[9px] font-extrabold font-archivo text-slate-400 uppercase tracking-wider">Slot Pemain</span>
                                                <span class="text-xs font-bold text-slate-700 leading-tight" data-card-needs></span>
                                            </div>
                                        </div>

                                        <!-- Contribution -->
                                        <div class="flex items-start gap-2.5">
                                            <div class="p-2 bg-emerald-50/70 text-emerald-600 rounded-xl flex-shrink-0 mt-0.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.251.11a3.375 3.375 0 003.497 0L13 15.006m-.25-8.205A3.375 3.375 0 009.25 5.655L9 5.714m3 12.728a3.375 3.375 0 01-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[9px] font-extrabold font-archivo text-slate-400 uppercase tracking-wider">Kontribusi</span>
                                                <span class="text-xs font-bold text-slate-700 leading-tight" data-card-contribution></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Sporty Rounded Action Buttons --}}
                                <div class="grid grid-cols-2 gap-3 mt-auto">
                                    <button type="button" class="flex items-center justify-center gap-2.5 py-3.5 bg-gradient-to-r from-rose-50 to-pink-50 hover:from-rose-100 hover:to-pink-100 text-rose-600 border border-rose-100 hover:border-rose-200 rounded-full shadow-sm hover:shadow-md hover:shadow-rose-100/50 text-sm font-extrabold font-archivo transition-all duration-200 group active:scale-[0.97]" data-swipe-skip>
                                        <svg class="w-4.5 h-4.5 transition-transform group-hover:-rotate-12 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <span>Lewati</span>
                                    </button>
                                    <button type="button" class="flex items-center justify-center gap-2.5 py-3.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-full shadow-md shadow-emerald-200/60 hover:shadow-lg hover:shadow-emerald-200/80 hover:-translate-y-0.5 text-sm font-extrabold font-archivo transition-all duration-200 group active:scale-[0.97]" data-swipe-join>
                                        <svg class="w-4.5 h-4.5 transition-transform group-hover:scale-110 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <span>Bergabung</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Empty State with Interactive Reset CTA --}}
                        <div class="swipe-empty-state flex flex-col items-center justify-center text-center p-8 bg-white border border-slate-100 rounded-[32px] shadow-sm w-full h-[530px] z-30 transition-all duration-300" data-swipe-empty hidden>
                            <div class="w-20 h-20 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center text-3xl mb-4 border border-slate-100 shadow-sm animate-bounce">
                                🔍
                            </div>
                            <h4 class="text-lg font-extrabold font-archivo text-[#02025b] mb-1">Pertandingan Habis</h4>
                            <p class="text-sm font-semibold text-slate-400 max-w-[240px] mb-3">Tidak ada pertandingan aktif yang cocok dengan filter olahraga Anda.</p>
                            <button @click="resetFilters()" class="px-5 py-2.5 bg-[#11114b] hover:bg-[#02025b] text-white font-extrabold font-archivo text-xs rounded-xl shadow-md transition-all">
                                Reset Semua Filter
                            </button>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN - BADGE & TEAMS (md:col-span-12 / lg:col-span-3) --}}
                {{-- Responsive tablet split wrapper: md:flex-row on tablet, lg:flex-col on desktop --}}
                <div class="order-3 md:col-span-12 lg:col-span-3 flex flex-col md:flex-row lg:flex-col gap-6 md:gap-8 lg:gap-6 w-full">
                    
                    {{-- Badge / Level Card --}}
                    <div class="bg-gradient-to-br from-[#11114b] to-[#262680] rounded-3xl p-6 text-white shadow-xl shadow-slate-200 relative overflow-hidden group flex-1 w-full">
                        <div class="absolute -right-8 -top-8 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-125 transition-transform duration-500"></div>
                        <div class="absolute -left-12 -bottom-12 w-28 h-28 bg-white/5 rounded-full blur-lg group-hover:scale-110 transition-transform duration-500"></div>
                        
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-[10px] font-extrabold font-archivo tracking-wider uppercase opacity-80">Lencana Pemain</h3>
                            <span class="text-lg">🏆</span>
                        </div>
                        
                        <div class="flex flex-col items-center text-center py-0.5">
                            <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl mb-3 border border-white/20 shadow-inner group-hover:scale-105 transition-transform duration-300">
                                🎖️
                            </div>
                            
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-base font-extrabold font-archivo tracking-wide">{{ $userSkill['level'] }}</span>
                                <span class="px-2.5 py-0.5 bg-white/15 rounded-full text-[9px] font-bold font-archivo tracking-wider uppercase border border-white/10">Level</span>
                            </div>
                            
                            <div class="w-full mt-5">
                                <div class="flex justify-between items-center text-[9px] font-extrabold font-archivo tracking-wider uppercase opacity-75 mb-1.5">
                                    <span>Progress</span>
                                    <span>{{ $userSkill['progressPct'] }}%</span>
                                </div>
                                <div class="w-full h-2.5 bg-white/15 rounded-full overflow-hidden border border-white/10">
                                    <div class="h-full bg-gradient-to-r from-yellow-300 to-yellow-400 rounded-full transition-all duration-500 shadow-inner" style="width: {{ $userSkill['progressPct'] }}%"></div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-1.5 mt-4 text-[10px] font-semibold opacity-70 bg-white/5 px-3 py-1.5 rounded-full border border-white/10">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>{{ $userSkill['totalPoints'] }} poin</span>
                            </div>
                        </div>
                    </div>

                    {{-- My Teams - Compact Mini Cards --}}
                    <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-[0_10px_30px_-5px_rgba(0,0,77,0.03)] space-y-4 flex-1 w-full">
                        <h3 class="text-sm font-extrabold font-archivo text-[#02025b] uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                            Tim Anda
                        </h3>
                        
                        @if($myTeams->isNotEmpty())
                            <div class="flex flex-col gap-2.5">
                                @foreach($myTeams->take(2) as $team)
                                    @php
                                        $teamSport = match(true) {
                                            str_contains(strtolower($team->title . ' ' . ($team->field?->name ?? '')), 'futsal') => 'Futsal',
                                            str_contains(strtolower($team->title . ' ' . ($team->field?->name ?? '')), 'badminton') || str_contains(strtolower($team->title . ' ' . ($team->field?->name ?? '')), 'bulu') => 'Badminton',
                                            str_contains(strtolower($team->title . ' ' . ($team->field?->name ?? '')), 'basket') => 'Basket',
                                            str_contains(strtolower($team->title . ' ' . ($team->field?->name ?? '')), 'voli') || str_contains(strtolower($team->title . ' ' . ($team->field?->name ?? '')), 'volley') => 'Voli',
                                            str_contains(strtolower($team->title . ' ' . ($team->field?->name ?? '')), 'tenis') || str_contains(strtolower($team->title . ' ' . ($team->field?->name ?? '')), 'tennis') => 'Tennis',
                                            default => 'Olahraga'
                                        };

                                        $teamSportEmoji = match($teamSport) {
                                            'Futsal' => '⚽',
                                            'Badminton' => '🏸',
                                            'Basket' => '🏀',
                                            'Voli' => '🏐',
                                            'Tennis' => '🎾',
                                            default => '🏆',
                                        };
                                        
                                        $teamBadgeColor = match($teamSport) {
                                            'Futsal' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            'Badminton' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'Basket' => 'bg-orange-50 text-orange-600 border-orange-100',
                                            'Voli' => 'bg-purple-50 text-purple-600 border-purple-100',
                                            'Tennis' => 'bg-rose-50 text-rose-600 border-rose-100',
                                            default => 'bg-slate-50 text-slate-600 border-slate-100',
                                        };
                                    @endphp
                                    <a href="{{ route('matches.show', $team->id) }}" class="flex items-center gap-3 p-3 bg-white hover:bg-slate-50/80 border border-slate-100 hover:border-indigo-100/60 rounded-2xl transition-all duration-200 group shadow-sm hover:shadow-md">
                                        <div class="flex-shrink-0 w-9 h-9 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-base group-hover:scale-110 transition-transform duration-200">
                                            {{ $teamSportEmoji }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-xs font-extrabold font-archivo text-[#02025b] group-hover:text-indigo-600 transition-colors truncate">{{ $team->title }}</h4>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md border text-[8px] font-extrabold font-archivo tracking-wider uppercase {{ $teamBadgeColor }}">
                                                    {{ $teamSport }}
                                                </span>
                                                <span class="text-[9px] font-bold text-slate-400">{{ \Carbon\Carbon::parse($team->date)->locale('id')->format('d M') }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 text-center">
                                            <div class="text-[10px] font-extrabold font-archivo text-indigo-700 bg-indigo-50/80 border border-indigo-100 px-2.5 py-1 rounded-xl">
                                                {{ $team->players->count() }}/{{ $team->max_player }}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            <a href="{{ route('matches.myTeams') }}" class="flex items-center justify-center gap-1.5 w-full py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 hover:text-indigo-900 font-extrabold font-archivo text-xs rounded-xl transition-all border border-indigo-100 hover:border-indigo-200 group">
                                <span>Lihat Semua</span>
                                <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                            </a>
                        @else
                            <div class="flex flex-col items-center justify-center text-center p-6 bg-slate-50/50 border border-slate-100 rounded-2xl">
                                <span class="text-2xl mb-1.5">⚽</span>
                                <h4 class="text-xs font-extrabold font-archivo text-[#02025b] mb-0.5">Belum ada tim</h4>
                                <p class="text-[11px] font-semibold text-slate-400">Anda belum pernah mempublikasikan pertandingan Anda.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- CARI TEMAN MAIN / PARTNER FINDER --}}
            @php
                $partnerSportOptions = collect(\App\Models\User::where('open_partner', true)->whereNotNull('sport_preference')->where('sport_preference', '!=', '')->pluck('sport_preference')->unique()->values())->merge($defaultSports)->unique()->values();
            @endphp
            <div class="mt-8 bg-white rounded-3xl border border-slate-100 p-4 sm:p-6 shadow-[0_10px_30px_-5px_rgba(0,0,77,0.03)] w-full">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                    <h3 class="text-sm font-extrabold font-archivo text-[#02025b] uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                        <span>Cari Teman Main</span>
                    </h3>
                    <div class="flex flex-wrap items-center gap-2">
                        <select id="partnerSportFilter" onchange="filterPartners()" class="partner-filter-select">
                            <option value="">Semua Olahraga</option>
                            @foreach($partnerSportOptions as $ps)
                                <option value="{{ $ps }}">{{ $fullSportEmoji[$ps] ?? '🏆' }} {{ $ps }}</option>
                            @endforeach
                        </select>
                        <div class="partner-level-wrap" id="partnerLevelWrap">
                            <button type="button" class="partner-level-btn" id="partnerLevelBtn" onclick="toggleLevelDropdown()">
                                <span id="partnerLevelLabel">🌱 Semua Level</span>
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                            </button>
                            <div class="partner-level-dropdown" id="partnerLevelDropdown">
                                <button type="button" class="partner-level-option" data-value="" onclick="selectLevel('')">🌱 Semua Level</button>
                                <button type="button" class="partner-level-option" data-value="pemula" onclick="selectLevel('pemula')">🌱 Pemula</button>
                                <button type="button" class="partner-level-option" data-value="aktif" onclick="selectLevel('aktif')">⭐ Aktif</button>
                                <button type="button" class="partner-level-option" data-value="pro" onclick="selectLevel('pro')">🏆 Pro</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="partnerList" class="partner-grid">
                    <div class="partner-empty">Memuat data partner...</div>
                </div>
            </div>

            {{-- PARTNER MODAL --}}
            <div id="partnerModal" class="partner-modal-overlay" onclick="closePartnerModal(event)">
                <div class="partner-modal" onclick="event.stopPropagation()">
                    <div class="partner-modal__header">
                        <h3 class="partner-modal__title">Cari Teman Main</h3>
                        <button type="button" onclick="closePartnerModal()" class="partner-modal__close">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="partner-modal__filters">
                        <div class="partner-modal__search-wrap">
                            <svg class="partner-modal__search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                            <input type="text" id="partnerModalSearch" placeholder="Cari nama pemain..." class="partner-modal__search" oninput="filterPartnersModal()">
                        </div>
                        <select id="partnerModalSport" onchange="filterPartnersModal()" class="partner-filter-select">
                            <option value="">Semua Olahraga</option>
                            @foreach($partnerSportOptions as $ps)
                                <option value="{{ $ps }}">{{ $fullSportEmoji[$ps] ?? '🏆' }} {{ $ps }}</option>
                            @endforeach
                        </select>
                        <div class="partner-level-wrap" id="partnerModalLevelWrap">
                            <button type="button" class="partner-level-btn" id="partnerModalLevelBtn" onclick="document.getElementById('partnerModalLevelDropdown').classList.toggle('is-open')">
                                <span id="partnerModalLevelLabel">🌱 Semua Level</span>
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                            </button>
                            <div class="partner-level-dropdown" id="partnerModalLevelDropdown">
                                <button type="button" class="partner-level-option" data-value="" onclick="selectModalLevel('')">🌱 Semua Level</button>
                                <button type="button" class="partner-level-option" data-value="pemula" onclick="selectModalLevel('pemula')">🌱 Pemula</button>
                                <button type="button" class="partner-level-option" data-value="aktif" onclick="selectModalLevel('aktif')">⭐ Aktif</button>
                                <button type="button" class="partner-level-option" data-value="pro" onclick="selectModalLevel('pro')">🏆 Pro</button>
                            </div>
                        </div>
                    </div>
                    <div id="partnerModalList" class="partner-modal__list">
                        <div class="partner-empty">Memuat data partner...</div>
                    </div>
                </div>
            </div>

            <style>
            .partner-filter-select {
                padding: 7px 12px;
                border-radius: 10px;
                border: 1px solid rgba(0,0,77,.1);
                font-size: 12px;
                font-weight: 600;
                color: #02025b;
                outline: none;
                background: white;
                cursor: pointer;
                min-width: 130px;
            }
            .partner-filter-select:focus {
                border-color: #EB5436;
            }
            .partner-level-wrap {
                position: relative;
            }
            .partner-level-btn {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 7px 12px;
                border-radius: 10px;
                border: 1px solid rgba(0,0,77,.1);
                background: white;
                font-size: 12px;
                font-weight: 600;
                color: #02025b;
                cursor: pointer;
                font-family: inherit;
                transition: border-color .18s;
                white-space: nowrap;
            }
            .partner-level-btn:hover {
                border-color: #EB5436;
            }
            .partner-level-btn:focus {
                outline: none;
                border-color: #EB5436;
            }
            .partner-level-dropdown {
                position: absolute;
                top: calc(100% + 4px);
                right: 0;
                min-width: 180px;
                background: #fff;
                border: 1px solid rgba(0,0,77,.1);
                border-radius: 12px;
                box-shadow: 0 8px 24px rgba(0,0,0,.1);
                z-index: 50;
                padding: 6px;
                display: none;
                flex-direction: column;
                gap: 2px;
            }
            .partner-level-dropdown.is-open {
                display: flex;
            }
            .partner-level-option {
                padding: 8px 12px;
                border-radius: 8px;
                border: none;
                background: transparent;
                font-size: 12px;
                font-weight: 600;
                color: #02025b;
                cursor: pointer;
                text-align: left;
                font-family: inherit;
                transition: background .15s;
                white-space: nowrap;
            }
            .partner-level-option:hover {
                background: #f1f5f9;
            }
            .partner-level-option.is-active {
                background: #eef2ff;
                color: #4338ca;
            }
            .partner-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                gap: 12px;
            }
            .partner-card {
                background: #f8fafc;
                border-radius: 16px;
                padding: 14px;
                border: 1px solid rgba(0,0,77,.06);
                display: flex;
                align-items: center;
                gap: 12px;
                transition: all .2s;
            }
            .partner-card:hover {
                border-color: #EB5436;
                box-shadow: 0 4px 12px rgba(235,84,54,.08);
            }
            .partner-card__avatar {
                width: 44px;
                height: 44px;
                border-radius: 50%;
                object-fit: cover;
                flex-shrink: 0;
                background: #e2e8f0;
            }
            .partner-card__info {
                flex: 1;
                min-width: 0;
            }
            .partner-card__name {
                font-weight: 700;
                font-size: 13px;
                color: #02025b;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .partner-card__meta {
                font-size: 11px;
                color: #666;
                margin-top: 2px;
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 4px;
            }
            .partner-card__sport {
                color: #02025b;
            }
            .partner-card__skill {
                display: inline-block;
                background: #eef2ff;
                color: #4338ca;
                padding: 1px 8px;
                border-radius: 10px;
                font-size: 10px;
                font-weight: 600;
            }
            .partner-card__invite {
                flex-shrink: 0;
                display: inline-flex;
                align-items: center;
                gap: 5px;
                padding: 8px 14px;
                background: linear-gradient(135deg, #6366f1, #ec4899);
                color: #fff;
                border: none;
                border-radius: 10px;
                font-size: 11px;
                font-weight: 600;
                cursor: pointer;
                text-decoration: none;
                white-space: nowrap;
                transition: all .25s;
                box-shadow: 0 3px 10px rgba(99,102,241,.25);
            }
            .partner-card__invite:hover {
                transform: translateY(-1px);
                box-shadow: 0 5px 16px rgba(99,102,241,.35);
            }
            .partner-empty {
                text-align: center;
                padding: 24px;
                color: #94a3b8;
                font-size: 13px;
                grid-column: 1 / -1;
            }

            /* ── Tambah Card ── */
            .partner-tambah-card {
                background: linear-gradient(135deg, #02025b, #1e1b7a);
                border-radius: 16px;
                padding: 14px;
                border: none;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 6px;
                cursor: pointer;
                transition: all .25s;
                box-shadow: 0 4px 14px rgba(2,2,91,.15);
                min-height: 72px;
                color: #fff;
                font-family: inherit;
            }
            .partner-tambah-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(2,2,91,.25);
            }
            .partner-tambah-card svg {
                transition: transform .3s;
            }
            .partner-tambah-card:hover svg {
                transform: rotate(90deg);
            }
            .partner-tambah-card__label {
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .3px;
            }

            .partner-empty {
                text-align: center;
                padding: 24px;
                color: #94a3b8;
                font-size: 13px;
                grid-column: 1 / -1;
            }

            /* ── Modal ── */
            .partner-modal-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,.45);
                backdrop-filter: blur(4px);
                z-index: 9999;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            .partner-modal-overlay.is-open {
                display: flex;
            }
            .partner-modal {
                background: #fff;
                border-radius: 24px;
                width: 100%;
                max-width: 640px;
                max-height: 85vh;
                display: flex;
                flex-direction: column;
                box-shadow: 0 24px 60px rgba(0,0,0,.2);
                animation: modalIn .25s ease-out;
            }
            @keyframes modalIn {
                from { opacity: 0; transform: scale(.95) translateY(10px); }
                to { opacity: 1; transform: scale(1) translateY(0); }
            }
            .partner-modal__header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 20px 24px 0;
            }
            .partner-modal__title {
                font-size: 16px;
                font-weight: 700;
                color: #02025b;
                margin: 0;
            }
            .partner-modal__close {
                width: 32px;
                height: 32px;
                display: flex;
                align-items: center;
                justify-content: center;
                border: none;
                background: #f1f5f9;
                border-radius: 10px;
                cursor: pointer;
                color: #64748b;
                transition: all .2s;
            }
            .partner-modal__close:hover {
                background: #e2e8f0;
                color: #02025b;
            }
            .partner-modal__filters {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                padding: 16px 24px;
                border-bottom: 1px solid rgba(0,0,77,.06);
            }
            .partner-modal__search-wrap {
                flex: 1;
                min-width: 180px;
                position: relative;
            }
            .partner-modal__search-icon {
                position: absolute;
                left: 12px;
                top: 50%;
                transform: translateY(-50%);
                color: #94a3b8;
                pointer-events: none;
            }
            .partner-modal__search {
                width: 100%;
                padding: 9px 12px 9px 36px;
                border-radius: 10px;
                border: 1px solid rgba(0,0,77,.1);
                font-size: 13px;
                font-family: inherit;
                color: #02025b;
                outline: none;
                background: #f8fafc;
                transition: all .2s;
                box-sizing: border-box;
            }
            .partner-modal__search:focus {
                border-color: #EB5436;
                background: #fff;
                box-shadow: 0 0 0 3px rgba(235,84,54,.08);
            }
            .partner-modal__search::placeholder {
                color: #94a3b8;
            }
            .partner-modal__list {
                flex: 1;
                overflow-y: auto;
                padding: 16px 24px 24px;
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                gap: 10px;
            }
            .partner-modal__list .partner-empty {
                grid-column: 1 / -1;
            }

            /* ── Attractive invite button in modal ── */
            .partner-card__invite--modal {
                flex-shrink: 0;
                display: inline-flex;
                align-items: center;
                gap: 5px;
                padding: 8px 18px;
                background: linear-gradient(135deg, #f43f5e, #e11d48);
                color: #fff;
                border: none;
                border-radius: 10px;
                font-size: 12px;
                font-weight: 600;
                cursor: pointer;
                text-decoration: none;
                white-space: nowrap;
                transition: all .25s;
                box-shadow: 0 3px 10px rgba(244,63,94,.25);
            }
            .partner-card__invite--modal:hover {
                transform: translateY(-1px);
                box-shadow: 0 5px 16px rgba(244,63,94,.35);
            }

            @media (max-width: 500px) {
                .partner-grid {
                    grid-template-columns: 1fr;
                }
                .partner-filter-select {
                    min-width: 0;
                    flex: 1;
                }
                .partner-modal {
                    max-height: 90vh;
                    border-radius: 16px;
                }
                .partner-modal__filters {
                    flex-direction: column;
                }
                .partner-modal__list {
                    grid-template-columns: 1fr;
                }
            }
            </style>

            <script>
            /* ── Level dropdown (inline) ── */
            var _selectedLevel = '';
            function toggleLevelDropdown() {
                document.getElementById('partnerLevelDropdown').classList.toggle('is-open');
            }
            function selectLevel(val) {
                _selectedLevel = val;
                var labels = {'':'🌱 Semua Level','pemula':'🌱 Pemula','aktif':'⭐ Aktif','pro':'🏆 Pro'};
                document.getElementById('partnerLevelLabel').textContent = labels[val] || '🌱 Semua Level';
                document.getElementById('partnerLevelDropdown').classList.remove('is-open');
                document.querySelectorAll('.partner-level-option').forEach(function(b) {
                    b.classList.toggle('is-active', b.dataset.value === val);
                });
                filterPartners();
            }
            document.addEventListener('click', function(e) {
                var wrap = document.getElementById('partnerLevelWrap');
                if (wrap && !wrap.contains(e.target)) {
                    document.getElementById('partnerLevelDropdown').classList.remove('is-open');
                }
            });

            /* ── Modal level dropdown ── */
            var _modalLevel = '';
            function selectModalLevel(val) {
                _modalLevel = val;
                var labels = {'':'🌱 Semua Level','pemula':'🌱 Pemula','aktif':'⭐ Aktif','pro':'🏆 Pro'};
                document.getElementById('partnerModalLevelLabel').textContent = labels[val] || '🌱 Semua Level';
                document.getElementById('partnerModalLevelDropdown').classList.remove('is-open');
                document.querySelectorAll('#partnerModalLevelDropdown .partner-level-option').forEach(function(b) {
                    b.classList.toggle('is-active', b.dataset.value === val);
                });
                filterPartnersModal();
            }
            document.addEventListener('click', function(e) {
                var wrap = document.getElementById('partnerModalLevelWrap');
                if (wrap && !wrap.contains(e.target)) {
                    document.getElementById('partnerModalLevelDropdown').classList.remove('is-open');
                }
            });

            /* ── All partner data cache ── */
            var _allPartners = [];

            function filterPartners() {
                var sport = document.getElementById('partnerSportFilter').value;
                var skill = _selectedLevel;
                var container = document.getElementById('partnerList');
                container.innerHTML = '<div class="partner-empty">Memuat data partner...</div>';

                var url = '{{ route("partner.data") }}?sport=' + encodeURIComponent(sport) + '&skill=' + encodeURIComponent(skill);
                fetch(url).then(function(r) { return r.json(); }).then(function(data) {
                    _allPartners = data;
                    if (data.length === 0) {
                        container.innerHTML = '<div class="partner-empty">Belum ada partner yang tersedia dengan filter ini.</div>';
                        return;
                    }
                    var show = data.length > 7 ? data.slice(0, 6) : data.slice(0, 7);
                    var html = '';
                    show.forEach(function(p) {
                        html += partnerCardHtml(p);
                    });
                    if (data.length > 7) {
                        html += '<button type="button" onclick="openPartnerModal()" class="partner-tambah-card">' +
                            '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>' +
                            '<span class="partner-tambah-card__label">Tampilkan Semua</span>' +
                            '</button>';
                    }
                    container.innerHTML = html;
                }).catch(function() {
                    container.innerHTML = '<div class="partner-empty" style="color:#dc2626;">Gagal memuat data partner.</div>';
                });
            }

            function partnerCardHtml(p, isModal) {
                var phone = (p.phone || '').replace(/^0/, '62').replace(/[^0-9]/g, '');
                var waUrl = 'https://wa.me/' + phone + '?text=Halo%2C%20saya%20menemukan%20profil%20Anda%20di%20Spies%20Sport%20dan%20tertarik%20bermain%20bersama.';
                var inviteClass = isModal ? 'partner-card__invite--modal' : 'partner-card__invite';
                var inviteText = isModal ? 'Ajak' : '💬 Ajak';
                return '<div class="partner-card">' +
                    '<img src="' + p.avatar + '" alt="' + p.name + '" class="partner-card__avatar">' +
                    '<div class="partner-card__info">' +
                    '<div class="partner-card__name">' + p.name + '</div>' +
                    '<div class="partner-card__meta">' +
                    (p.sport_preference ? '<span class="partner-card__sport">' + p.sport_preference + '</span>' : '') +
                    (p.level ? '<span class="partner-card__skill">' + p.level + '</span>' : '') +
                    '</div></div>' +
                    '<a href="' + waUrl + '" target="_blank" class="' + inviteClass + '">' + inviteText + '</a>' +
                    '</div>';
            }

            /* ── Modal ── */
            function openPartnerModal() {
                document.getElementById('partnerModal').classList.add('is-open');
                document.body.style.overflow = 'hidden';
                renderModalList();
            }
            function closePartnerModal(e) {
                if (e && e.target !== e.currentTarget) return;
                document.getElementById('partnerModal').classList.remove('is-open');
                document.body.style.overflow = '';
            }
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    var modal = document.getElementById('partnerModal');
                    if (modal.classList.contains('is-open')) {
                        modal.classList.remove('is-open');
                        document.body.style.overflow = '';
                    }
                }
            });

            function filterPartnersModal() {
                renderModalList();
            }

            function renderModalList() {
                var search = (document.getElementById('partnerModalSearch').value || '').toLowerCase().trim();
                var sport = document.getElementById('partnerModalSport').value;
                var skill = _modalLevel;
                var container = document.getElementById('partnerModalList');

                var filtered = _allPartners.filter(function(p) {
                    if (search && p.name.toLowerCase().indexOf(search) === -1) return false;
                    if (sport && (!p.sport_preference || p.sport_preference.toLowerCase() !== sport.toLowerCase())) return false;
                    if (skill && (!p.level || p.level.toLowerCase() !== skill.toLowerCase())) return false;
                    return true;
                });

                if (filtered.length === 0) {
                    container.innerHTML = '<div class="partner-empty">Tidak ada pemain yang cocok.</div>';
                    return;
                }
                var html = '';
                filtered.forEach(function(p) {
                    html += partnerCardHtml(p, true);
                });
                container.innerHTML = html;
            }

            filterPartners();
            </script>

            {{-- PERTANDINGAN MENDATANG - Full Width Landscape Section --}}
            <div class="mt-8 bg-white rounded-3xl border border-slate-100 p-6 shadow-[0_10px_30px_-5px_rgba(0,0,77,0.03)] space-y-5 w-full">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-extrabold font-archivo text-[#02025b] uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                        <span>Pertandingan Mendatang</span>
                    </h3>
                    @if($upcomingBookings->isNotEmpty())
                    <span class="text-[11px] font-bold text-slate-400">{{ $upcomingBookings->count() }} pertandingan</span>
                    @endif
                </div>
                @if($upcomingBookings->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                    @foreach($upcomingBookings as $booking)
                        @php
                            $sport = match(true) {
                                str_contains(strtolower($booking->field->name), 'futsal') => 'Futsal',
                                str_contains(strtolower($booking->field->name), 'badminton') || str_contains(strtolower($booking->field->name), 'bulu') => 'Badminton',
                                str_contains(strtolower($booking->field->name), 'basket') => 'Basket',
                                str_contains(strtolower($booking->field->name), 'voli') || str_contains(strtolower($booking->field->name), 'volley') => 'Voli',
                                str_contains(strtolower($booking->field->name), 'tenis') || str_contains(strtolower($booking->field->name), 'tennis') => 'Tennis',
                                default => 'Olahraga'
                            };
                            
                            $sportEmoji = match($sport) {
                                'Futsal' => '⚽',
                                'Badminton' => '🏸',
                                'Basket' => '🏀',
                                'Voli' => '🏐',
                                'Tennis' => '🎾',
                                default => '🏆',
                            };

                            $sportBadgeColor = match($sport) {
                                'Futsal' => 'bg-blue-50 text-blue-600 border-blue-100',
                                'Badminton' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                'Basket' => 'bg-orange-50 text-orange-600 border-orange-100',
                                'Voli' => 'bg-purple-50 text-purple-600 border-purple-100',
                                'Tennis' => 'bg-rose-50 text-rose-600 border-rose-100',
                                default => 'bg-slate-50 text-slate-600 border-slate-100',
                            };
                        @endphp
                        <div class="group flex items-start gap-3 p-3.5 bg-white hover:bg-slate-50/80 border border-slate-100 hover:border-indigo-100/60 rounded-2xl transition-all duration-200 shadow-sm hover:shadow-md">
                            <div class="flex-shrink-0 w-9 h-9 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-base group-hover:scale-110 transition-transform duration-200">
                                {{ $sportEmoji }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5 mb-0.5">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-md border text-[8px] font-extrabold font-archivo tracking-wider uppercase {{ $sportBadgeColor }}">
                                        {{ $sport }}
                                    </span>
                                    <span class="text-[8px] font-extrabold text-emerald-600 bg-emerald-50/70 border border-emerald-100 px-1.5 py-0.5 rounded-md ml-auto flex-shrink-0">
                                        Confirmed
                                    </span>
                                </div>
                                <h4 class="text-xs font-extrabold font-archivo text-[#02025b] group-hover:text-indigo-600 transition-colors leading-snug truncate">{{ $booking->field->name }}</h4>
                                <div class="flex items-center gap-x-2.5 mt-1 text-[10px] font-bold text-slate-400">
                                    <span class="flex items-center gap-1 text-slate-500">
                                        <svg class="w-2.5 h-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                                        {{ \Carbon\Carbon::parse($booking->date)->locale('id')->translatedFormat('j M') }}
                                    </span>
                                    <span class="flex items-center gap-1 text-slate-500">
                                        <svg class="w-2.5 h-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $booking->start_time)->format('H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @else
                <div class="flex flex-col items-center justify-center text-center py-8 px-4 bg-slate-50/30 border border-slate-100 rounded-2xl">
                    <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-2xl mb-3">
                        📅
                    </div>
                    <h4 class="text-sm font-extrabold font-archivo text-[#02025b] mb-1">Belum ada pertandingan mendatang</h4>
                    <p class="text-[11px] font-semibold text-slate-400">Yuk buat pertandingan atau gabung dengan tim lain!</p>
                </div>
                @endif
            </div>
        </section>

        {{-- ALPINEJS MODAL POPUP - Glassmorphic Premium --}}
        <div x-show="openFilterModal" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-[#11114b]/50 backdrop-blur-md z-[999] flex items-center justify-center p-4" 
             @click.self="openFilterModal = false"
             @keydown.escape.window="openFilterModal = false">
            
            <div class="bg-white rounded-[32px] w-full max-w-[500px] p-8 shadow-2xl border border-slate-100 flex flex-col gap-6 relative"
                 x-show="openFilterModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="scale-90 translate-y-6 opacity-0"
                 x-transition:enter-end="scale-100 translate-y-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="scale-100 translate-y-0 opacity-100"
                 x-transition:leave-end="scale-90 translate-y-6 opacity-0">
                
                <button @click="openFilterModal = false" class="absolute top-6 right-6 p-2 bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 rounded-full transition-all hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <div class="space-y-1.5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-100 flex items-center justify-center text-lg">
                            🏅
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold font-archivo text-[#02025b]">Semua Olahraga</h3>
                            <p class="text-xs font-semibold text-slate-400">Pilih kategori untuk memfilter pertandingan</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2.5 max-h-[320px] overflow-y-auto pr-1 upcoming-scroll">
                    @foreach($sportOptions as $sport)
                        @php
                            $emoji = match($sport) {
                                'Futsal' => '⚽',
                                'Badminton' => '🏸',
                                'Basket' => '🏀',
                                'Voli' => '🏐',
                                'Tennis' => '🎾',
                                default => '🏆',
                            };
                        @endphp
                        <label class="flex items-center justify-between p-3.5 border rounded-2xl cursor-pointer transition-all duration-200 select-none group hover:shadow-sm"
                               data-sport="{{ $sport }}"
                               :class="selectedSports.includes($el.dataset.sport) ? 'bg-indigo-50/80 border-indigo-200 text-indigo-700 font-bold shadow-sm' : 'bg-white border-slate-100 text-slate-700 hover:border-indigo-100'">
                            <div class="flex items-center gap-2.5">
                                <span class="text-base group-hover:scale-110 transition-transform duration-200">{{ $emoji }}</span>
                                <span class="text-xs font-bold">{{ $sport }}</span>
                            </div>
                            <input type="checkbox" value="{{ $sport }}" 
                                   class="w-4.5 h-4.5 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300"
                                   :checked="selectedSports.includes($el.value)"
                                   @change="toggleSport($el.value)">
                        </label>
                    @endforeach
                </div>

                <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                    <button @click="resetFilters()" class="flex items-center gap-1.5 px-4 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-500 hover:text-slate-700 font-bold font-archivo text-xs rounded-xl transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" /></svg>
                        <span>Reset</span>
                    </button>
                    <button @click="openFilterModal = false" class="flex items-center gap-1.5 px-6 py-2.5 bg-gradient-to-r from-[#11114b] to-[#262680] hover:from-[#02025b] hover:to-[#1a1a6e] text-white font-bold font-archivo text-xs rounded-xl transition-all shadow-md hover:shadow-lg active:scale-[0.97]">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        <span>Terapkan</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Private Match: "Mau olahraga apa hari ini?" --}}
        <div x-show="openPrivateSportModal" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-[#11114b]/50 backdrop-blur-md z-[999] flex items-center justify-center p-4" 
             @click.self="openPrivateSportModal = false"
             @keydown.escape.window="openPrivateSportModal = false">
            
            <div class="bg-white rounded-[32px] w-full max-w-[420px] p-8 shadow-2xl border border-slate-100 flex flex-col gap-6 relative"
                 x-show="openPrivateSportModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="scale-90 translate-y-6 opacity-0"
                 x-transition:enter-end="scale-100 translate-y-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="scale-100 translate-y-0 opacity-100"
                 x-transition:leave-end="scale-90 translate-y-6 opacity-0">
                
                <button @click="openPrivateSportModal = false" class="absolute top-6 right-6 p-2 bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 rounded-full transition-all hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <div class="space-y-2 text-center">
                    <div class="mx-auto w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-100 flex items-center justify-center text-2xl">
                        🤔
                    </div>
                    <h3 class="text-xl font-extrabold font-archivo text-[#02025b]">Mau olahraga apa hari ini?</h3>
                    <p class="text-sm font-semibold text-slate-400">Pilih olahraga untuk mencari lapangan yang cocok</p>
                </div>

                <div class="grid grid-cols-3 gap-2.5 max-h-[280px] overflow-y-auto pr-1 upcoming-scroll">
                    @php
                        $privateSportEmoji = [
                            'Futsal' => '⚽', 'Badminton' => '🏸', 'Basket' => '🏀',
                            'Voli' => '🏐', 'Tennis' => '🎾', 'Golf' => '🏌️',
                            'Renang' => '🏊', 'Panahan' => '🏹', 'Lari' => '🏃',
                            'Sepeda' => '🚴', 'Tinju' => '🥊', 'Bela Diri' => '🥋',
                            'Yoga' => '🧘', 'Fitness' => '🏋️', 'Hiking' => '🥾',
                            'Padel' => '🎾', 'Baseball' => '⚾', 'Rugby' => '🏉',
                            'Senam' => '🤸',
                        ];
                    @endphp
                    @foreach($sportOptions as $sport)
                        <button @click="selectPrivateSport('{{ $sport }}')" 
                           class="flex flex-col items-center justify-center gap-1.5 p-3 border-2 border-slate-100 rounded-xl cursor-pointer transition-all duration-200 hover:border-indigo-200 hover:shadow-sm bg-white text-slate-700 hover:bg-indigo-50/30 w-full">
                            <span class="text-xl">{{ $privateSportEmoji[$sport] ?? '🏆' }}</span>
                            <span class="text-[9px] font-extrabold font-archivo uppercase tracking-wider text-center leading-tight">{{ $sport }}</span>
                        </button>
                    @endforeach
                </div>

                <div class="flex justify-center pt-2 border-t border-slate-100">
                    <button @click="openPrivateSportModal = false" class="flex items-center gap-1.5 px-6 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-500 hover:text-slate-700 font-bold font-archivo text-xs rounded-xl transition-all">
                        <span>Nanti dulu</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Private Match: Field List --}}
        <div x-show="openFieldModal" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-[#11114b]/50 backdrop-blur-md z-[999] flex items-center justify-center p-4"
             @click.self="openFieldModal = false"
             @keydown.escape.window="openFieldModal = false">

            <div class="bg-white rounded-[32px] w-full max-w-[600px] max-h-[85vh] shadow-2xl border border-slate-100 flex flex-col relative overflow-hidden"
                 x-show="openFieldModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="scale-90 translate-y-6 opacity-0"
                 x-transition:enter-end="scale-100 translate-y-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="scale-100 translate-y-0 opacity-100"
                 x-transition:leave-end="scale-90 translate-y-6 opacity-0"
                 @click.away="openFieldModal = false">

                <div class="p-6 pb-4 border-b border-slate-100 flex-shrink-0">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="text-lg font-extrabold font-archivo text-[#02025b]">Pilih Lapangan</h3>
                            <p class="text-sm text-slate-400 font-semibold mt-0.5">
                                <span x-text="'Cari lapangan ' + selectedPrivateSport"></span>
                            </p>
                        </div>
                        <button @click="openFieldModal = false" class="p-2 bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 rounded-full transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <input type="text" x-model="fieldSearchQuery" placeholder="Cari lapangan atau lokasi..."
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-semibold text-slate-700 outline-none focus:border-indigo-300 focus:bg-white transition-all">
                </div>

                <div class="p-4 px-6 overflow-y-auto flex-1">
                    <template x-if="filteredFieldList.length === 0">
                        <div class="text-center py-12 text-slate-400 text-sm font-semibold">
                            Tidak ada lapangan <span x-text="selectedPrivateSport"></span> yang tersedia
                        </div>
                    </template>
                    <div class="grid grid-cols-1 gap-3">
                        <template x-for="f in filteredFieldList" :key="f.id">
                            <a :href="'/booking/' + f.id + '?sport=' + encodeURIComponent(selectedPrivateSport)"
                               class="flex items-center gap-4 p-4 rounded-2xl border border-slate-100 bg-white hover:border-indigo-200 hover:shadow-sm transition-all no-underline text-inherit group">
                                <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0 bg-slate-100">
                                    <img :src="f.image" :alt="f.name" class="w-full h-full object-cover">
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm font-extrabold text-[#02025b] truncate" x-text="f.name"></h4>
                                    <p class="text-xs text-slate-500 mt-0.5 truncate" x-text="f.location || 'Lokasi tidak tersedia'"></p>
                                    <p class="text-xs text-slate-400 mt-1">
                                        <span class="text-amber-500">&#9733;</span>
                                        <span x-text="f.rating ?? '—'"></span>
                                        <span class="mx-1">&middot;</span>
                                        <span x-text="f.type"></span>
                                    </p>
                                </div>
                                <span class="text-slate-300 group-hover:text-indigo-400 transition-colors text-lg">&rarr;</span>
                            </a>
                        </template>
                    </div>
                </div>

                <div class="p-4 pt-3 border-t border-slate-100 flex justify-center flex-shrink-0">
                    <button @click="openFieldModal = false" class="px-6 py-2 bg-slate-50 hover:bg-slate-100 text-slate-500 hover:text-slate-700 font-bold font-archivo text-xs rounded-xl transition-all">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
(() => {
    const allCardsRaw = @json($cards);
    const cardEl = document.querySelector('[data-swipe-card]');
    const backCardEl = document.querySelector('[data-swipe-card-back]');
    const emptyEl = document.querySelector('[data-swipe-empty]');
    const skipBtn = document.querySelector('[data-swipe-skip]');
    const joinBtn = document.querySelector('[data-swipe-join]');
    const titleBadgeEl = document.querySelector('[data-card-title-badge]');
    const backTitleBadgeEl = document.querySelector('[data-card-back-title-badge]');
    const imageEl = document.querySelector('[data-card-image]');
    const backImageEl = document.querySelector('[data-card-back-image]');
    const venueEl = document.querySelector('[data-card-venue]');
    const sportTagEl = document.querySelector('[data-card-sport-tag]');
    const backSportTagEl = document.querySelector('[data-card-back-sport-tag]');
    const needsEl = document.querySelector('[data-card-needs]');
    const scheduleEl = document.querySelector('[data-card-schedule]');

    if (!cardEl || !emptyEl || !skipBtn || !joinBtn) {
        return;
    }

    const allCards = (Array.isArray(allCardsRaw) ? allCardsRaw : Object.values(allCardsRaw || {}))
        .filter((item) => item && typeof item === 'object')
        .map((item, index) => ({
            ...item,
            _swipeKey: String(item.id ?? `idx-${index}`),
        }));

    let deck = [];
    const swipedKeys = new Set();
    let pointerStartX = null;
    let dragShiftX = 0;
    let isAnimating = false;

    // Automatic local sports image mapper
    const getSportImage = (sport) => {
        const s = String(sport || '').toLowerCase();
        if (s.includes('badminton') || s.includes('bulu')) return "{{ asset('assets/images/sports/badminton.jpg') }}";
        if (s.includes('futsal')) return "{{ asset('assets/images/sports/futsal.jpg') }}";
        if (s.includes('basket')) return "{{ asset('assets/images/sports/basket.jpg') }}";
        if (s.includes('voli') || s.includes('volley')) return "{{ asset('assets/images/sports/volley.jpg') }}";
        return "{{ asset('assets/images/sports/default.jpg') }}";
    };

    // Format Contribution currency nicely
    const formatContribution = (val) => {
        const num = Number(val);
        if (!isNaN(num) && num > 0) {
            return 'Rp ' + num.toLocaleString('id-ID') + ' / Player';
        }
        return 'Gratis / Free';
    };

    // Sport tag color mapping
    const getSportTagClass = (sport) => {
        const s = String(sport || '').toLowerCase();
        if (s.includes('futsal')) return 'bg-blue-50 text-blue-600 border-blue-100';
        if (s.includes('badminton') || s.includes('bulu')) return 'bg-emerald-50 text-emerald-600 border-emerald-100';
        if (s.includes('basket')) return 'bg-orange-50 text-orange-600 border-orange-100';
        if (s.includes('voli') || s.includes('volley')) return 'bg-purple-50 text-purple-600 border-purple-100';
        if (s.includes('tenis') || s.includes('tennis')) return 'bg-rose-50 text-rose-600 border-rose-100';
        return 'bg-slate-50 text-slate-600 border-slate-100';
    };

    // Build deck based on selected sports & gender
    const buildDeck = () => {
        const selectedSports = Array.from(document.querySelectorAll('.sport-checkbox'))
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        const selectedGender = window.__genderFilter || '';
        const searchTitle = (window._searchTitle || '').toLowerCase().trim();

        let available = allCards.filter(item => {
            const matchesSport = selectedSports.length === 0 || selectedSports.includes(item.sport);
            const matchesGender = !selectedGender || item.creator_gender === selectedGender;
            const matchesTitle = !searchTitle || (item.title && item.title.toLowerCase().indexOf(searchTitle) !== -1);
            const notSwiped = !swipedKeys.has(item._swipeKey);
            return matchesSport && matchesGender && matchesTitle && notSwiped;
        });

        // Auto-recycle if all swiped
        if (!available.length && allCards.length > 0) {
            const validCards = allCards.filter(item => {
                const matchesSport = selectedSports.length === 0 || selectedSports.includes(item.sport);
                const matchesGender = !selectedGender || item.creator_gender === selectedGender;
                const matchesTitle = !searchTitle || (item.title && item.title.toLowerCase().indexOf(searchTitle) !== -1);
                return matchesSport && matchesGender && matchesTitle;
            });
            swipedKeys.forEach(key => {
                if (validCards.some(c => c._swipeKey === key)) {
                    swipedKeys.delete(key);
                }
            });
            available = validCards.filter(item => !swipedKeys.has(item._swipeKey));
        }

        deck = available;
        renderCard();
    };
    window.buildDeck = buildDeck;

    const resetCardTransform = () => {
        cardEl.classList.remove('is-swiping-left', 'is-swiping-right');
        cardEl.style.transition = '';
        cardEl.style.transform = '';
    };

    const renderCard = () => {
        if (!deck.length) {
            cardEl.style.display = 'none';
            if (backCardEl) backCardEl.hidden = true;
            emptyEl.classList.remove('hidden');
            skipBtn.disabled = true;
            joinBtn.disabled = true;
            isAnimating = false;
            return;
        }

        const current = deck[0];
        cardEl.style.display = 'block';
        resetCardTransform();
        emptyEl.classList.add('hidden');
        skipBtn.disabled = false;
        joinBtn.disabled = false;

        const currentImg = getSportImage(current.sport);
        imageEl.src = currentImg;
        imageEl.alt = `Tim ${current.sport}`;

        // Badge on image = match title, sport tag = small badge metadata
        if (titleBadgeEl) titleBadgeEl.textContent = current.title;
        if (sportTagEl) {
            sportTagEl.textContent = current.sport.toUpperCase();
            sportTagEl.className = `inline-flex items-center px-2.5 py-1 rounded-lg border text-[9px] font-extrabold font-archivo tracking-wider uppercase ${getSportTagClass(current.sport)}`;
        }

        venueEl.textContent = current.venue;
        needsEl.textContent = `Butuh ${current.neededPlayers} Pemain`;
        scheduleEl.textContent = current.schedule;

        // Active contribution mapping
        const contributionEl = document.querySelector('[data-card-contribution]');
        if (contributionEl) {
            contributionEl.textContent = formatContribution(current.contributionPerPlayer);
        }

        // Show back card preview
        if (backCardEl && backImageEl && backTitleBadgeEl) {
            const next = deck[1];
            if (next) {
                backCardEl.hidden = false;
                const nextImg = getSportImage(next.sport);
                backImageEl.src = nextImg;
                backImageEl.alt = `Tim ${next.sport}`;
                backTitleBadgeEl.textContent = next.title;

                if (backSportTagEl) {
                    backSportTagEl.textContent = next.sport.toUpperCase();
                    backSportTagEl.className = `inline-flex items-center px-2.5 py-1 rounded-lg border text-[9px] font-extrabold font-archivo tracking-wider uppercase ${getSportTagClass(next.sport)}`;
                }
            } else {
                backCardEl.hidden = true;
            }
        }
    };

    const swipe = (direction) => {
        if (!deck.length || isAnimating) return;
        isAnimating = true;
        resetCardTransform();
        cardEl.classList.add(direction === 'left' ? 'is-swiping-left' : 'is-swiping-right');

        const current = deck[0];
        if (current) {
            swipedKeys.add(current._swipeKey);
        }

        setTimeout(() => {
            isAnimating = false;
            if (direction === 'right' && current) {
                window.location.href = `/cari-tim/${current.id}`;
            } else {
                buildDeck();
            }
        }, 300);
    };

    // Event listeners
    skipBtn?.addEventListener('click', () => swipe('left'));
    joinBtn?.addEventListener('click', () => swipe('right'));

    // Sport filter - handled by Alpine toggleSport + buildDeck
    // (no redundant JS listener needed)

    // Touch/pointer swipe gesture
    cardEl?.addEventListener('pointerdown', (event) => {
        if (!deck.length || isAnimating) return;
        pointerStartX = event.clientX;
        dragShiftX = 0;
        cardEl.style.transition = 'none';
    });

    cardEl?.addEventListener('pointermove', (event) => {
        if (pointerStartX === null) return;
        dragShiftX = event.clientX - pointerStartX;
        const rotate = dragShiftX * 0.05;
        cardEl.style.transform = `translateX(${dragShiftX}px) rotate(${rotate}deg)`;
    });

    cardEl?.addEventListener('pointerup', () => {
        if (pointerStartX === null) return;
        cardEl.style.transition = '';
        pointerStartX = null;
        if (dragShiftX < -90) swipe('left');
        else if (dragShiftX > 90) swipe('right');
        else cardEl.style.transform = '';
    });

    cardEl?.addEventListener('pointerdown', (e) => {
        // Prevent pointer gesture conflicts on skip/join buttons
        if (e.target.closest('[data-swipe-skip]') || e.target.closest('[data-swipe-join]')) {
            pointerStartX = null;
        }
    });

    cardEl?.addEventListener('pointercancel', () => {
        pointerStartX = null;
        cardEl.style.transition = '';
        cardEl.style.transform = '';
    });

    // Initial render
    buildDeck();
})();
</script>
</body>
</html>
