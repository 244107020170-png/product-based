<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($field) ? 'Ubah Lapangan' : 'Tambah Lapangan' }}</title>

@php
    $referer = request()->headers->get('referer');
    $previousUrl = url()->previous();
    $currentUrl = url()->current();
    $isInternalReferer = $referer && parse_url($referer, PHP_URL_HOST) === request()->getHost();
    $backUrl = $isInternalReferer && $previousUrl !== $currentUrl ? $previousUrl : '/owner/kelolaLapangan';

    function facilityIcon($name) {
        $map = [
            'wifi' => 'fa-wifi', 'wi-fi' => 'fa-wifi',
            'parkir' => 'fa-car', 'parkir luas' => 'fa-car', 'parkiran' => 'fa-car',
            'ac' => 'fa-fan', 'kipas' => 'fa-fan', 'kipas angin' => 'fa-fan', 'air conditioner' => 'fa-fan',
            'toilet' => 'fa-shower', 'kamar mandi' => 'fa-shower', 'wc' => 'fa-shower',
            'mushala' => 'fa-mosque', 'musholla' => 'fa-mosque', 'masjid' => 'fa-mosque', 'musala' => 'fa-mosque',
            'kantin' => 'fa-utensils', 'restoran' => 'fa-utensils', 'warung' => 'fa-utensils',
            'kafe' => 'fa-mug-saucer', 'cafe' => 'fa-mug-saucer', 'kopi' => 'fa-mug-saucer',
            'kursi' => 'fa-chair', 'tempat duduk' => 'fa-chair', 'bangku' => 'fa-chair',
            'ruang ganti' => 'fa-door-open', 'ganti' => 'fa-door-open', 'locker room' => 'fa-door-open',
            'rumput' => 'fa-leaf', 'rumput premium' => 'fa-leaf', 'sintetis' => 'fa-leaf',
            'lampu' => 'fa-lightbulb', 'led' => 'fa-lightbulb', 'pencahayaan' => 'fa-lightbulb', 'lighting' => 'fa-lightbulb',
            'kolam renang' => 'fa-water', 'renang' => 'fa-water', 'swimming pool' => 'fa-water', 'kolam' => 'fa-water',
            'gym' => 'fa-dumbbell', 'fitnes' => 'fa-dumbbell', 'fitness' => 'fa-dumbbell', 'olahraga' => 'fa-dumbbell',
            'basket' => 'fa-basketball', 'bola basket' => 'fa-basketball',
            'futsal' => 'fa-futbol', 'bola' => 'fa-futbol', 'sepak bola' => 'fa-futbol',
            'badminton' => 'fa-table-tennis-paddle-ball', 'bulutangkis' => 'fa-table-tennis-paddle-ball',
            'loker' => 'fa-cabinet-filing', 'lemari' => 'fa-cabinet-filing',
            'tv' => 'fa-tv', 'televisi' => 'fa-tv', 'layar' => 'fa-tv',
            'proyektor' => 'fa-projector', 'projector' => 'fa-projector',
            'sound system' => 'fa-music', 'speaker' => 'fa-music', 'musik' => 'fa-music', 'audio' => 'fa-music',
            'cctv' => 'fa-video', 'kamera' => 'fa-video', 'keamanan' => 'fa-shield-halved', 'security' => 'fa-shield-halved',
            'meja' => 'fa-table', 'tabel' => 'fa-table',
            'payung' => 'fa-umbrella', 'tenda' => 'fa-campground', 'gazebo' => 'fa-campground',
            'taman' => 'fa-tree', 'pohon' => 'fa-tree', 'hijau' => 'fa-tree',
            'sepeda' => 'fa-bicycle', 'bike' => 'fa-bicycle', 'parkir sepeda' => 'fa-bicycle',
            'air minum' => 'fa-bottle-water', 'dispenser' => 'fa-bottle-water', 'minum' => 'fa-bottle-water',
            'minuman' => 'fa-mug-saucer', 'snack' => 'fa-cookie', 'makanan' => 'fa-cookie',
            'trampolin' => 'fa-children', 'anak' => 'fa-children', 'kids' => 'fa-children',
        ];
        $key = strtolower(trim($name));
        return $map[$key] ?? 'fa-circle-check';
    }
@endphp

    <!-- Panggil file CSS baru di sini -->
    @vite([
        'resources/css/app.css',
        'resources/css/owner-dashboard.css', 
        'resources/css/owner-bookings.css',
        'resources/css/owner-form-field.css'
    ])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>
<body>

<div class="dashboard-layout">

    {{-- SIDEBAR --}}
    @include('owner.navbar')

    {{-- MAIN CONTENT --}}
    <main class="main-content">

        {{-- TOPBAR --}}
        <div class="topbar">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Cari pemesanan, pelanggan...">
            </div>

            <div class="topbar-right">
                <a href="{{ route('owner.notifikasi') }}" class="notif-btn" style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;position:relative;">
                    <i class="fa-solid fa-bell"></i>
                    @if(auth()->user()->unreadNotifications()->count() > 0)
                        <span style="position:absolute;top:2px;right:2px;width:10px;height:10px;background:#ef4444;border:2px solid #fff;border-radius:50%;"></span>
                    @endif
                </a>
                <button class="notif-btn" onclick="toggleFaqPopup()"><i class="fa-solid fa-headset"></i></button>
                <a href="{{ route('owner.bantuan') }}" class="notif-btn question" style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;"><i class="fa-solid fa-circle-question"></i></a>
                <a href="#" class="profile-box" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="text-decoration:none;color:inherit;">
                    <div>
                        <h5>{{ auth()->user()->name }}</h5>
                        <p>Profil Pemilik</p>
                    </div>
                    <img src="https://i.pravatar.cc/100" alt="Profil">
                </a>
            </div>
        </div>

        {{-- HEADER FORM --}}
        <div class="welcome-section">
            <div>
                <h1>{{ isset($field) ? 'Ubah Data Lapangan' : 'Tambah Lapangan Baru' }}</h1>
                <p>Isi informasi detail mengenai lapangan olahraga di bawah ini.</p>
            </div>
            
            <a href="{{ $backUrl }}" class="add-btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        {{-- CONTAINER FORM --}}
        <div class="form-container">
            
            <form action="{{ isset($field) ? '/owner/fields/'.$field->id.'/update' : '/owner/fields/store' }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($field))
                    @method('PUT')
                @endif

                {{-- Baris 1: Nama & Jenis --}}
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Lapangan</label>
                        <input type="text" name="name" class="form-control" value="{{ isset($field) ? $field->name : '' }}" placeholder="Contoh: Lapangan A" required>
                    </div>

                    <div class="form-group">
                        <label>Jenis Olahraga</label>
                        <select name="type" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            @php
                                $sportList = ['Futsal','Badminton','Basket','Voli','Tennis','Golf','Renang','Panahan','Lari','Sepeda','Tinju','Bela Diri','Yoga','Fitness','Hiking','Padel','Baseball','Rugby','Senam'];
                            @endphp
                            @foreach($sportList as $sport)
                            <option value="{{ $sport }}" {{ (isset($field) && $field->type == $sport) ? 'selected' : '' }}>{{ $sport }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Baris 2: Harga & Jumlah Lapangan --}}
                <div class="form-row">
                    <div class="form-group">
                        <label>Harga per Jam</label>
                        <input type="number" name="price" class="form-control" value="{{ isset($field) ? $field->price_per_hour : '' }}" placeholder="Contoh: 120000" required>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Lapangan</label>
                        <select name="number_of_courts" class="form-control" required>
                            @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}" {{ (isset($field) && $field->number_of_courts == $i) ? 'selected' : '' }}>{{ $i }} Lapangan</option>
                            @endfor
                        </select>
                        <small class="form-help">Maksimal 6 lapangan per tempat</small>
                    </div>
                </div>

                {{-- Baris 3: Foto --}}
                <div class="form-row">
                    <div class="form-group">
                        <label>Foto Lapangan</label>
                        <input type="file" name="image" class="form-control">
                        @if(isset($field))
                            <small class="form-help">*Abaikan jika tidak ingin mengubah foto lama</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div></div>
                    </div>
                </div>

                {{-- Baris 4: Lokasi & Maps Link --}}
                <div class="form-row">
                    <div class="form-group">
                        <label>Lokasi / Alamat</label>
                        <input type="text" name="location" class="form-control" value="{{ isset($field) ? $field->location : '' }}" placeholder="Contoh: Jl. Merdeka No. 123, Malang" required>
                    </div>

                    <div class="form-group">
                        <label>Tautan Google Maps <span style="color:#dc2626;font-weight:400;">*</span></label>
                        <input type="url" name="maps_link" class="form-control" value="{{ isset($field) ? $field->maps_link : '' }}" placeholder="https://maps.app.goo.gl/..." required>
                        <small class="form-help">Tempel link Google Maps untuk arah ke lapangan</small>
                    </div>
                </div>

                {{-- Baris 5: Jam Operasional --}}
                <div class="time-picker-section">
                    <label class="section-label">Jam Operasional</label>
                    <div class="time-picker-row">
                        @php $open = isset($field) ? $field->open_time : '08:00'; @endphp
                        @php $close = isset($field) ? $field->close_time : '22:00'; @endphp

                        <div class="time-picker-group">
                            <span class="time-picker-label">Buka</span>
                            <div class="time-dropdown">
                                <button type="button" class="time-trigger" data-target="open_time">
                                    <span class="time-trigger-text">{{ sprintf('%02d.00', (int)substr($open, 0, 2)) }}</span>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </button>
                                <div class="time-grid" data-target="open_time">
                                    @for ($h = 05; $h <= 23; $h++)
                                        @php $val = sprintf('%02d:00', $h); @endphp
                                        <div class="time-option {{ $open == $val ? 'active' : '' }}" data-value="{{ $val }}">
                                            {{ sprintf('%02d:00', $h) }}
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            <input type="hidden" name="open_time" value="{{ $open }}">
                        </div>

                        <div class="time-picker-sep">
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>

                        <div class="time-picker-group">
                            <span class="time-picker-label">Tutup</span>
                            <div class="time-dropdown">
                                <button type="button" class="time-trigger" data-target="close_time">
                                    <span class="time-trigger-text">{{ substr($close, 0, 5) }}</span>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </button>
                                <div class="time-grid" data-target="close_time">
                                    @for ($h = 05; $h <= 23; $h++)
                                        @php $val = sprintf('%02d:00', $h); @endphp
                                        <div class="time-option {{ $close == $val ? 'active' : '' }}" data-value="{{ $val }}">
                                            {{ sprintf('%02d:00', $h) }}
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            <input type="hidden" name="close_time" value="{{ $close }}">
                        </div>
                    </div>
                </div>

                {{-- Fasilitas (Checkbox + Custom) --}}
                <div class="facilities-section">
                    @php $facilitiesArray = isset($field) && $field->facilities ? (is_array($field->facilities) ? $field->facilities : json_decode($field->facilities, true) ?? []) : []; @endphp
                    <label class="section-label">Fasilitas Lapangan</label>
                    <div class="checkbox-group" id="facility-checkboxes">
                        <label class="checkbox-label">
                            <input type="checkbox" name="facilities[]" value="WiFi" {{ in_array('WiFi', $facilitiesArray) ? 'checked' : '' }}> 
                            <i class="fa-solid fa-wifi"></i> Wi-Fi
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="facilities[]" value="Toilet" {{ in_array('Toilet', $facilitiesArray) ? 'checked' : '' }}> 
                            <i class="fa-solid fa-shower"></i> Kamar Mandi
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="facilities[]" value="Parkir" {{ in_array('Parkir', $facilitiesArray) ? 'checked' : '' }}> 
                            <i class="fa-solid fa-car"></i> Parkir Luas
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="facilities[]" value="AC" {{ in_array('AC', $facilitiesArray) ? 'checked' : '' }}> 
                            <i class="fa-solid fa-fan"></i> Kipas / AC
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="facilities[]" value="Mushala" {{ in_array('Mushala', $facilitiesArray) ? 'checked' : '' }}> 
                            <i class="fa-solid fa-mosque"></i> Mushala
                        </label>

                        {{-- Custom facilities from DB --}}
                        @php
                            $builtin = ['WiFi','Toilet','Parkir','AC','Mushala'];
                            $customFacilities = array_values(array_filter($facilitiesArray, fn($f) => !in_array($f, $builtin)));
                        @endphp
                        @foreach($customFacilities as $cf)
                        <div class="custom-facility-tag" data-facility="{{ $cf }}">
                            <input type="hidden" name="facilities[]" value="{{ $cf }}">
                            <span class="cf-icon"><i class="fa-solid {{ facilityIcon($cf) }}"></i></span>
                            <span class="cf-name">{{ $cf }}</span>
                            <button type="button" class="cf-remove" onclick="this.closest('.custom-facility-tag').remove()">&times;</button>
                        </div>
                        @endforeach
                    </div>

                    {{-- Custom facility input --}}
                    <div class="custom-facility-input-wrap">
                        <label class="custom-facility-label">Lainnya:</label>
                        <div class="custom-facility-row">
                            <input type="text" id="custom-facility-input" placeholder="Tulis fasilitas..." maxlength="50">
                            <button type="button" id="custom-facility-add" class="add-btn" style="padding:8px 16px;font-size:13px;">Tambah</button>
                        </div>
                        <div id="custom-facility-suggestions" class="cf-suggestions"></div>
                    </div>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="form-actions">
                    <button type="reset" class="add-btn">Atur Ulang</button>
                    <button type="submit" class="add-btn">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

    </main>
</div>

<style>
.custom-facility-tag {
    display:inline-flex;
    align-items:center;
    gap:6px;
    background:#f0fdf4;
    border:1px solid #bbf7d0;
    border-radius:999px;
    padding:4px 12px 4px 8px;
    font-size:13px;
    font-weight:500;
    color:#166534;
}
.custom-facility-tag .cf-icon {
    display:flex;
    align-items:center;
    font-size:14px;
}
.custom-facility-tag .cf-remove {
    background:none;
    border:none;
    color:#dc2626;
    cursor:pointer;
    font-size:16px;
    line-height:1;
    padding:0 2px;
}
.custom-facility-input-wrap {
    margin-top:12px;
}
.custom-facility-label {
    font-size:13px;
    font-weight:600;
    color:#334155;
    margin-bottom:6px;
    display:block;
}
.custom-facility-row {
    display:flex;
    gap:8px;
    align-items:center;
}
.custom-facility-row input {
    flex:1;
    min-width:140px;
    padding:8px 12px;
    border:1px solid #cbd5e1;
    border-radius:10px;
    font-size:13px;
    outline:none;
    font-family:inherit;
}
.custom-facility-row input:focus {
    border-color:#dc2626;
    box-shadow:0 0 0 2px rgba(220,38,38,.1);
}
.cf-suggestions {
    display:flex;
    gap:6px;
    flex-wrap:wrap;
    margin-top:8px;
}
.cf-suggestion {
    padding:4px 12px;
    background:#f1f5f9;
    border-radius:999px;
    font-size:12px;
    cursor:pointer;
    border:1px solid #e2e8f0;
    transition:all .15s;
    color:#334155;
}
.cf-suggestion:hover {
    background:#dc2626;
    color:#fff;
    border-color:#dc2626;
}
</style>

<script>
    const facilityIconMap = {
        'wifi': 'fa-wifi', 'wi-fi': 'fa-wifi',
        'parkir': 'fa-car', 'parkir luas': 'fa-car', 'parkiran': 'fa-car',
        'ac': 'fa-fan', 'kipas': 'fa-fan', 'kipas angin': 'fa-fan',
        'toilet': 'fa-shower', 'kamar mandi': 'fa-shower', 'wc': 'fa-shower',
        'mushala': 'fa-mosque', 'musholla': 'fa-mosque', 'masjid': 'fa-mosque',
        'kantin': 'fa-utensils', 'restoran': 'fa-utensils',
        'kafe': 'fa-mug-saucer', 'cafe': 'fa-mug-saucer',
        'kursi': 'fa-chair', 'bangku': 'fa-chair',
        'ruang ganti': 'fa-door-open', 'ganti': 'fa-door-open',
        'rumput': 'fa-leaf', 'rumput premium': 'fa-leaf', 'sintetis': 'fa-leaf',
        'lampu': 'fa-lightbulb', 'led': 'fa-lightbulb', 'lighting': 'fa-lightbulb',
        'kolam renang': 'fa-water', 'renang': 'fa-water', 'kolam': 'fa-water',
        'gym': 'fa-dumbbell', 'fitnes': 'fa-dumbbell', 'fitness': 'fa-dumbbell',
        'basket': 'fa-basketball', 'badminton': 'fa-table-tennis-paddle-ball',
        'futsal': 'fa-futbol', 'sepak bola': 'fa-futbol',
        'loker': 'fa-cabinet-filing',
        'tv': 'fa-tv', 'proyektor': 'fa-projector',
        'sound system': 'fa-music', 'speaker': 'fa-music', 'musik': 'fa-music',
        'cctv': 'fa-video', 'keamanan': 'fa-shield-halved', 'security': 'fa-shield-halved',
        'meja': 'fa-table', 'payung': 'fa-umbrella', 'tenda': 'fa-campground', 'gazebo': 'fa-campground',
        'taman': 'fa-tree', 'sepeda': 'fa-bicycle',
        'dispenser': 'fa-bottle-water', 'air minum': 'fa-bottle-water',
        'mushola': 'fa-mosque',
    };

    function getFacilityIcon(name) {
        const key = name.toLowerCase().trim();
        return facilityIconMap[key] || 'fa-circle-check';
    }

    const builtinFacilities = ['WiFi','Toilet','Parkir','AC','Mushala'];

    document.addEventListener('DOMContentLoaded', function () {

        document.querySelectorAll('.time-trigger').forEach(trigger => {
            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                const dropdown = this.closest('.time-dropdown');
                const wasOpen = dropdown.classList.contains('open');

                document.querySelectorAll('.time-dropdown.open').forEach(d => d.classList.remove('open'));

                if (!wasOpen) dropdown.classList.add('open');
            });
        });

        document.querySelectorAll('.time-grid').forEach(grid => {
            const target = grid.dataset.target;
            const input = document.querySelector('input[name="' + target + '"]');
            const triggerText = document.querySelector('.time-trigger[data-target="' + target + '"] .time-trigger-text');

            grid.querySelectorAll('.time-option').forEach(opt => {
                opt.addEventListener('click', function () {
                    grid.querySelectorAll('.time-option').forEach(o => o.classList.remove('active'));
                    this.classList.add('active');
                    input.value = this.dataset.value;

                    const h = this.dataset.value.substring(0, 2);
                    triggerText.textContent = h + '.00';

                    this.closest('.time-dropdown').classList.remove('open');
                });
            });
        });

        document.addEventListener('click', function () {
            document.querySelectorAll('.time-dropdown.open').forEach(d => d.classList.remove('open'));
        });

        // Custom facility input
        var facilityInput = document.getElementById('custom-facility-input');
        var facilityAdd = document.getElementById('custom-facility-add');
        var facilityCheckboxes = document.getElementById('facility-checkboxes');
        var suggestionsBox = document.getElementById('custom-facility-suggestions');

        var suggestionPool = ['Kantin','Kursi','Ruang Ganti','Rumput Premium','Lampu LED','Taman','Loker','TV','Proyektor','Sound System','CCTV','Meja','Gazebo','Kolam Renang','Gym','Trampolin','Payung','Tenda','Dispenser','Air Minum','Kafe','Tempat Parkir Sepeda','Free WiFi'];

        function showSuggestions(filter) {
            suggestionsBox.innerHTML = '';
            var f = filter.toLowerCase().trim();
            if (!f || f.length < 1) { suggestionsBox.style.display = 'none'; return; }
            var matches = suggestionPool.filter(function(s) {
                return s.toLowerCase().indexOf(f) !== -1;
            });
            if (matches.length === 0) { suggestionsBox.style.display = 'none'; return; }
            suggestionsBox.style.display = 'flex';
            matches.forEach(function(s) {
                var el = document.createElement('span');
                el.className = 'cf-suggestion';
                el.textContent = s;
                el.addEventListener('click', function() {
                    facilityInput.value = s;
                    suggestionsBox.style.display = 'none';
                    facilityInput.focus();
                });
                suggestionsBox.appendChild(el);
            });
        }

        function addCustomFacility(name) {
            name = name.trim();
            if (!name) return;
            if (builtinFacilities.indexOf(name) !== -1) {
                var cb = facilityCheckboxes.querySelector('input[value="' + name + '"]');
                if (cb) { cb.checked = true; }
                return;
            }
            var existing = facilityCheckboxes.querySelector('.custom-facility-tag[data-facility="' + name.replace(/"/g, '&quot;') + '"]');
            if (existing) return;
            var div = document.createElement('div');
            div.className = 'custom-facility-tag';
            div.setAttribute('data-facility', name);
            div.innerHTML = '<input type="hidden" name="facilities[]" value="' + name.replace(/"/g, '&quot;') + '">' +
                '<span class="cf-icon"><i class="fa-solid ' + getFacilityIcon(name) + '"></i></span>' +
                '<span class="cf-name">' + name + '</span>' +
                '<button type="button" class="cf-remove" onclick="this.closest(\'.custom-facility-tag\').remove()">&times;</button>';
            facilityCheckboxes.appendChild(div);
        }

        if (facilityAdd) {
            facilityAdd.addEventListener('click', function() {
                addCustomFacility(facilityInput.value);
                facilityInput.value = '';
                suggestionsBox.style.display = 'none';
            });
        }

        if (facilityInput) {
            facilityInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addCustomFacility(this.value);
                    this.value = '';
                    suggestionsBox.style.display = 'none';
                }
            });
            facilityInput.addEventListener('input', function() {
                showSuggestions(this.value);
            });
            facilityInput.addEventListener('blur', function() {
                setTimeout(function() { suggestionsBox.style.display = 'none'; }, 200);
            });
            facilityInput.addEventListener('focus', function() {
                if (this.value.trim()) showSuggestions(this.value);
            });
        }

    });
</script>

@include('owner.faq-popup')
</body>
</html>