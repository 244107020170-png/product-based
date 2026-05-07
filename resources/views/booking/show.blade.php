@php
    use Carbon\Carbon;
    $userName = Auth::user()->name ?? 'Player';
    $userAvatar = Auth::user()->avatarUrl();
    $currentDate = Carbon::now()->locale('id')->translatedFormat('j F Y');
    $field = $field;
    $allFields = $allFields;
    $availableTimes = $availableTimes;
    
    // Sidebar
    $sidebarItems = [
        ['label'=>'Dashboard',  'icon'=>asset('assets/images/icons/dashboard.png'), 'href'=>route('dashboard'),    'active'=>false],
        ['label'=>'Aktivitas',  'icon'=>asset('assets/images/icons/aktivitas.png'), 'href'=>url('/matches'),       'active'=>false],
        ['label'=>'Favoritmu',  'icon'=>asset('assets/images/icons/favoritmu.png'), 'href'=>null,                  'active'=>false],
        ['label'=>'Histori',    'icon'=>asset('assets/images/icons/histori.png'),   'href'=>null,                  'active'=>false],
        ['label'=>'Cari tim',   'icon'=>asset('assets/images/icons/caritim.png'),   'href'=>route('matches.index'),'active'=>false],
        ['label'=>'Booking',    'icon'=>asset('assets/images/icons/booking.png'),   'href'=>route('booking.show', $field->id),       'active'=>true],
        ['label'=>'Keahlianmu', 'icon'=>asset('assets/images/icons/keahlian.png'),  'href'=>null,                  'active'=>false],
        ['label'=>'Profil',     'icon'=>asset('assets/images/icons/profil.png'),    'href'=>route('profile.show'), 'active'=>false],
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
    <title>Booking – {{ config('app.name', 'Spies Sport') }}</title>
    @vite([
        'resources/css/app.css',
        'resources/css/player-dashboard.css',
    ])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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

    {{-- Topbar (same as Skill page) --}}
    <header class="player-dashboard-topbar">
        <div class="player-dashboard-topbar__left">
            <button type="button" class="player-dashboard-topbar__menu" data-sidebar-open><span></span><span></span><span></span></button>
            <label class="player-search" for="booking-search">
                <span class="player-search__icon">
                    <svg viewBox="0 0 20 20" fill="none"><circle cx="9" cy="9" r="5.75" stroke="currentColor" stroke-width="1.8"/><path d="M13.5 13.5L17 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </span>
                <input id="booking-search" type="search" placeholder="Cari lapangan...">
            </label>
        </div>
        <div class="player-dashboard-topbar__right">
            <div class="player-dashboard-topbar__date">
                <span class="player-inline-icon">
                    <svg viewBox="0 0 24 24" fill="none"><rect x="3.5" y="5.5" width="17" height="15" rx="2.5" stroke="currentColor" stroke-width="1.8"/><path d="M7 3.5V7M17 3.5V7M3.5 9.5H20.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </span>
                <span>{{ $currentDate }}</span>
            </div>
            <button type="button" class="player-dashboard-topbar__icon">
                <span class="player-inline-icon">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M9 18H15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M6.5 17.5H17.5L16.3 15.6C15.9 15 15.7 14.3 15.7 13.6V10.8C15.7 8.49 14.04 6.54 11.8 6.16V5.5C11.8 4.67 11.13 4 10.3 4C9.47 4 8.8 4.67 8.8 5.5V6.16C6.56 6.54 4.9 8.49 4.9 10.8V13.6C4.9 14.3 4.7 15 4.3 15.6L3.1 17.5H6.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                </span>
            </button>
            <a href="{{ route('profile.show') }}" class="player-profile-pill">
                <span class="player-profile-pill__avatar">
                    <img src="{{ $userAvatar }}" alt="Profil" class="player-avatar-image player-avatar-image--profile">
                </span>
                <span class="player-profile-pill__name">{{ $userName }}</span>
            </a>
        </div>
    </header>

    <section style="padding: 20px; max-width: 1400px; margin: 0 auto;">
        <div x-data="bookingApp()" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            {{-- ===== KIRI: Field Card ===== --}}
            <div>
                <!-- Display yang dipilih -->
                <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px;">
                    <div style="position: relative; overflow: hidden; height: 250px;">
                        <img :src="selectedField.image || '{{ asset('assets/images/bg/Explore.png') }}'" 
                             alt="Field" style="width: 100%; height: 100%; object-fit: cover;">
                        <div style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.7); color: white; padding: 8px 16px; border-radius: 50px; font-size: 14px;">
                            ⭐ <span x-text="selectedField.rating || '4.8'"></span>
                        </div>
                    </div>
                    <div style="padding: 20px;">
                        <h3 x-text="selectedField.name" style="font-size: 20px; font-weight: bold; margin: 0 0 10px 0; color: #001a4d;"></h3>
                        <div style="display: flex; gap: 15px; margin-bottom: 15px; font-size: 14px; color: #666;">
                            <span>📍 <span x-text="selectedField.location"></span></span>
                            <span>💰 <span x-text="selectedField.formattedPrice || 'Rp120.000/jam'"></span></span>
                        </div>
                    </div>
                </div>

                {{-- Fasilitas --}}
                <div style="background: white; border-radius: 16px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px;">
                    <h4 style="margin: 0 0 15px 0; font-size: 16px; font-weight: bold; color: #001a4d;">⭐ Fasilitas</h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 12px;">
                        <template x-for="facility in selectedFieldFacilities" :key="facility.name">
                            <div style="background: #f0f4ff; border: 1px solid #d4e0f5; border-radius: 12px; padding: 12px; text-align: center;">
                                <div style="font-size: 24px; margin-bottom: 5px;" x-text="facility.icon"></div>
                                <div style="font-size: 12px; color: #001a4d; font-weight: 500;" x-text="facility.name"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- ===== KANAN: Form Booking ===== --}}
            <div>
                <form @submit.prevent="submitBooking()" style="background: white; border-radius: 16px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px;">

                    {{-- Pilih Tanggal --}}
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #001a4d; font-size: 14px;">Pilih Tanggal</label>
                        <div style="position: relative;">
                            <input type="text" 
                                   x-ref="dateInput"
                                   @change="updateDate"
                                   placeholder="Pilih tanggal" 
                                   style="width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; cursor: pointer; appearance: none;">
                            <div style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #666;">📅</div>
                        </div>
                    </div>

                    {{-- Pilih Jam --}}
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #001a4d; font-size: 14px;">Pilih Jam</label>
                        <select x-model="selectedTime" @change="updateTime()"
                                style="width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; background: white; cursor: pointer;">
                            <option value="">Pilih waktu</option>
                            <template x-for="time in availableTimes" :key="time.start">
                                <option :value="time.start" x-text="time.display"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Pilih Lapangan --}}
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #001a4d; font-size: 14px;">Pilih Lapangan</label>
                        <div @click="toggleFieldDropdown()" 
                             style="padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 8px; background: white; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                            <span x-text="selectedField.name" style="color: #333;"></span>
                            <span x-text="showFieldDropdown ? '▲' : '▼'" style="color: #666;"></span>
                        </div>

                        {{-- Field Dropdown --}}
                        <div x-show="showFieldDropdown" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 2px solid #e0e0e0; border-top: none; border-radius: 0 0 8px 8px; max-height: 300px; overflow-y: auto; z-index: 10; margin-top: -8px; padding-top: 8px;">
                            <template x-for="f in allFields" :key="f.id">
                                <div @click="selectField(f)" 
                                     style="padding: 12px 15px; cursor: pointer; border-bottom: 1px solid #f0f0f0; display: flex; gap: 12px; align-items: center;"
                                     :style="{ 'background-color': selectedField.id === f.id ? '#f0f4ff' : 'white' }">
                                    <img :src="f.image || '{{ asset('assets/images/bg/Explore.png') }}'" 
                                         style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover;">
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: #001a4d;" x-text="f.name"></div>
                                        <div style="font-size: 12px; color: #666;" x-text="f.location"></div>
                                        <div style="font-size: 12px; color: #1d6fcf; font-weight: 600;" x-text="formatPrice(f.price_per_hour)"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Ringkasan Pesanan --}}
                    <div style="background: #f9fafb; border-radius: 12px; padding: 16px; margin-top: 24px; border: 1px solid #e0e0e0;">
                        <h4 style="margin: 0 0 12px 0; font-size: 14px; font-weight: 700; color: #001a4d;">📍 Ringkasan Pesanan</h4>
                        <div style="font-size: 13px; color: #666; line-height: 1.8;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <span>Harga/jam:</span>
                                <span style="font-weight: 600;" x-text="formatPrice(selectedField.price_per_hour)"></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <span>Durasi:</span>
                                <span style="font-weight: 600;" x-text="bookingDuration + ' jam'"></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e0e0e0;">
                                <span>Biaya Admin:</span>
                                <span style="font-weight: 600;">Rp2.000</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-weight: 700; color: #001a4d; font-size: 16px;">
                                <span>Total</span>
                                <span x-text="formatPrice(totalPrice)"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Book Now Button --}}
                    <button type="submit" style="width: 100%; margin-top: 16px; padding: 12px; background: #00004d; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 700; cursor: pointer; transition: all 0.3s ease;">
                        Book Now
                    </button>
                </form>
            </div>
        </div>

        {{-- ===== BROWSING FIELDS ===== --}}
        <div style="margin-top: 40px;">
            <h2 style="font-size: 24px; font-weight: bold; color: #001a4d; margin-bottom: 20px;">🏐 Lapangan Lainnya</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                <template x-for="f in allFields" :key="f.id">
                    <div @click="selectField(f)"
                         style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); cursor: pointer; transition: all 0.3s ease;"
                         @mouseenter="$el.style.transform = 'translateY(-8px)'; $el.style.boxShadow = '0 8px 16px rgba(0,0,0,0.15)';"
                         @mouseleave="$el.style.transform = 'translateY(0)'; $el.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)';
                         :style="{ 'border': selectedField.id === f.id ? '2px solid #003d99' : '1px solid transparent' }">
                        <div style="position: relative; height: 180px; overflow: hidden;">
                            <img :src="f.image || '{{ asset('assets/images/bg/Explore.png') }}'" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                            <div style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.7); color: white; padding: 6px 12px; border-radius: 50px; font-size: 12px;">
                                ⭐ <span x-text="f.rating || '4.8'"></span>
                            </div>
                        </div>
                        <div style="padding: 16px;">
                            <h3 x-text="f.name" style="font-size: 16px; font-weight: bold; margin: 0 0 8px 0; color: #001a4d;"></h3>
                            <div style="font-size: 12px; color: #666; margin-bottom: 10px;">📍 <span x-text="f.location"></span></div>
                            <div style="font-size: 14px; color: #1d6fcf; font-weight: 700;" x-text="formatPrice(f.price_per_hour)"></div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>

</main>
</div>

document.addEventListener('DOMContentLoaded', function() {
{{-- Alpine.js Data --}}
<script>
function bookingApp() {
    return {
        selectedField: @json($field->makeVisible(['id', 'name', 'location', 'price_per_hour', 'image', 'facilities', 'rating'])),
        allFields: @json($allFields->map(fn($f) => $f->makeVisible(['id', 'name', 'location', 'price_per_hour', 'image', 'facilities', 'rating']))->toArray()),
        availableTimes: @json($availableTimes),
        selectedDate: '',
        selectedTime: '',
        showFieldDropdown: false,
        bookingDuration: 1,
        adminFee: 2000,

        get selectedFieldFacilities() {
            const facilities = this.selectedField.facilities || [];
            const facilityIcons = {
                'Rumput Premium': '🌱',
                'Mushala': '🕌',
                'Toilet Bersih': '🚽',
                'Kursi': '🪑',
                'Parkir Luas': '🅿️',
                'LED Tuning': '💡',
                'Kantin': '🍜',
                'Ruang Ganti': '👕',
                'AC': '❄️',
                'WiFi': '📡',
            };
            
            return facilities.map(f => ({ name: f, icon: facilityIcons[f] || '✓' }));
        },

        get totalPrice() {
            return (this.selectedField.price_per_hour * this.bookingDuration) + this.adminFee;
        },

        init() {
            this.$nextTick(() => {
                flatpickr(this.$refs.dateInput, {
                    minDate: 'today',
                    onChange: (selectedDates) => this.updateDate(selectedDates[0])
                });
            });
        },

        updateDate(date) {
            if (date) {
                this.selectedDate = date.toISOString().split('T')[0];
            }
        },

        updateTime() {
            if (this.selectedTime) {
                const time = this.availableTimes.find(t => t.start === this.selectedTime);
                if (time) {
                    this.bookingDuration = 1;
                }
            }
        },

        selectField(field) {
            this.selectedField = field;
            this.showFieldDropdown = false;
        },

        toggleFieldDropdown() {
            this.showFieldDropdown = !this.showFieldDropdown;
        },

        formatPrice(price) {
            return 'Rp' + price.toLocaleString('id-ID');
        },

        submitBooking() {
            if (!this.selectedDate || !this.selectedTime) {
                alert('Pilih tanggal dan jam terlebih dahulu');
                return;
            }

            fetch('{{ route("booking.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    field_id: this.selectedField.id,
                    date: this.selectedDate,
                    start_time: this.selectedTime,
                    end_time: this.availableTimes.find(t => t.start === this.selectedTime).end,
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert('Booking berhasil!');
                    window.location.href = '{{ route("booking.index") }}';
                }
            })
            .catch(e => alert('Error: ' + e));
        }
    }
}
</script>

</body>
</html>
