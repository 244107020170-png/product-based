@php
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
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Lapangan</title>

    @vite(['resources/css/app.css', 'resources/css/owner-dashboard.css'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
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

                <button class="notif-btn">
                    <i class="fa-solid fa-headset"></i>
                </button>
                <a href="{{ route('owner.bantuan') }}" class="notif-btn question" style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                    <i class="fa-solid fa-circle-question"></i>
                </a>

                <div class="profile-box">
                    <div>
                        <h5>{{ auth()->user()->name }}</h5>
                        <p>Profil Pemilik</p>
                    </div>

                    <img src="https://i.pravatar.cc/100" alt="Profil">
                </div>
            </div>
        </div>


        {{-- WELCOME SECTION --}}
        <div class="welcome-section">
            <div>
                <h1>Ayo tambahkan lapangan mu!</h1>
                <p>Kelola lapangan olahraga di sini.</p>
            </div>

            <a href="{{ route('owner.tambahLapangan') }}" class="add-btn">
                <i class="fa-solid fa-plus"></i>
                Tambah Lapangan
            </a>
        </div>


        {{-- STATISTIC CARD --}}
        <div class="stats-grid">

            <div class="stats-card">
                <div>
                    <p>Total Lapangan</p>
                    <h2 class="blue-text">{{ count($fields) }}</h2>
                </div>

                <div class="stats-icon blue">
                    <i class="fa-regular fa-futbol"></i>
                </div>
            </div>

            <div class="stats-card">
                <div>
                    <p>Tersedia</p>
                    <h2 class="green-text">{{ $fields->where('is_available', true)->count() }}</h2>
                </div>

                <div class="stats-icon green">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>

            <div class="stats-card">
                <div>
                    <p>Rating & Ulasan</p>
                    <h2 class="blue-text" style="display:flex;align-items:center;gap:6px;">
                        <span style="color:#f59e0b;">★</span> {{ round($totalRating ?? 0, 1) }}
                    </h2>
                </div>

                <div class="stats-icon" style="background:#fef3c7;color:#f59e0b;">
                    <i class="fa-solid fa-star"></i>
                </div>
            </div>

            <div class="stats-card">
                <div>
                    <p>Total Ulasan</p>
                    <h2 class="red-text">{{ $fields->sum('reviews_count') }}</h2>
                </div>

                <div class="stats-icon red">
                    <i class="fa-regular fa-message"></i>
                </div>
            </div>

        </div>


        {{-- Tabs --}}
        <div style="display:flex;margin-top:24px;margin-bottom:24px;position:relative;">
            <button class="kl-tab is-active" data-kltab="lapangan" style="flex:1;padding:12px 0;border:none;background:transparent;font-weight:700;font-size:14px;color:#dc2626;cursor:pointer;transition:color .2s;text-align:center;position:relative;z-index:1;">Lapangan</button>
            <button class="kl-tab" data-kltab="ulasan" style="flex:1;padding:12px 0;border:none;background:transparent;font-weight:700;font-size:14px;color:#94a3b8;cursor:pointer;transition:color .2s;text-align:center;position:relative;z-index:1;">Ulasan</button>
            {{-- baseline --}}
            <div style="position:absolute;left:0;right:0;bottom:0;height:2px;background:#e2e8f0;"></div>
            {{-- active indicator --}}
            <div id="kl-active-indicator" style="position:absolute;bottom:0;left:0;width:50%;height:2px;background:#dc2626;transition:left .25s ease;z-index:2;"></div>
        </div>

        {{-- TAB LAPANGAN --}}
        <div class="kl-tabpanel is-active" data-klpanel="lapangan">
            {{-- FILTER --}}
            @php
            $sportList = ['Futsal','Badminton','Basket','Voli','Tennis','Golf','Renang','Panahan','Lari','Sepeda','Tinju','Bela Diri','Yoga','Fitness','Hiking','Padel','Baseball','Rugby','Senam'];
            $sportColors = [
                'Futsal'    => ['bg'=>'#eff6ff','text'=>'#2563eb'],
                'Badminton' => ['bg'=>'#ecfdf5','text'=>'#059669'],
                'Basket'    => ['bg'=>'#fff7ed','text'=>'#ea580c'],
                'Voli'      => ['bg'=>'#f5f3ff','text'=>'#7c3aed'],
                'Tennis'    => ['bg'=>'#fefce8','text'=>'#ca8a04'],
                'Golf'      => ['bg'=>'#f0fdf4','text'=>'#16a34a'],
                'Renang'    => ['bg'=>'#ecfeff','text'=>'#0891b2'],
                'Panahan'   => ['bg'=>'#fff1f2','text'=>'#e11d48'],
                'Lari'      => ['bg'=>'#eef2ff','text'=>'#4f46e5'],
                'Sepeda'    => ['bg'=>'#fdf2f8','text'=>'#db2777'],
                'Tinju'     => ['bg'=>'#fef2f2','text'=>'#dc2626'],
                'Bela Diri' => ['bg'=>'#f8fafc','text'=>'#475569'],
                'Yoga'      => ['bg'=>'#f5f3ff','text'=>'#8b5cf6'],
                'Fitness'   => ['bg'=>'#f7fee7','text'=>'#65a30d'],
                'Hiking'    => ['bg'=>'#fafaf9','text'=>'#78716c'],
                'Padel'     => ['bg'=>'#f0fdfa','text'=>'#0d9488'],
                'Baseball'  => ['bg'=>'#fffbeb','text'=>'#d97706'],
                'Rugby'     => ['bg'=>'#f5f5f5','text'=>'#525252'],
                'Senam'     => ['bg'=>'#f0f9ff','text'=>'#0284c7'],
            ];
            @endphp
            <div class="filter-section">
                <select id="filter-type">
                    <option value="">Semua Kategori</option>
                    @foreach($sportList as $s)
                    <option value="{{ $s }}">{{ $s }}</option>
                    @endforeach
                </select>

                <button id="filter-reset">
                    <i class="fa-solid fa-rotate-right"></i>
                    Atur Ulang Filter
                </button>
            </div>

            {{-- FIELD CARD --}}
            <div class="field-grid" id="field-grid">
                @forelse ($fields as $field)
                <div class="field-card" data-type="{{ $field->type ?? '' }}">
                    <div class="field-image">
                        <img src="{{ $field->image_url }}" alt="{{ $field->name }}" onerror="this.style.display='none'">
                        <span class="badge" style="background:{{ ($sportColors[$field->type] ?? null) ? $sportColors[$field->type]['bg'] : '#fee2e2' }};color:{{ ($sportColors[$field->type] ?? null) ? $sportColors[$field->type]['text'] : '#b91c1c' }};">{{ $field->type ?? 'Olahraga' }}</span>
                    </div>
                    <div class="field-content">
                        <div class="field-top">
                            <div>
                                <h3>{{ $field->name }}</h3>
                                <p>{{ $field->type ?? 'Olahraga' }}</p>
                            </div>
                            <h4>Rp{{ number_format($field->price_per_hour ?? 0, 0, ',', '.') }}</h4>
                        </div>
                        <div class="facility-icons">
                            @php
                                $facArr = $field->facilities ? (is_array($field->facilities) ? $field->facilities : json_decode($field->facilities, true) ?? []) : [];
                            @endphp
                            @foreach($facArr as $fac)
                            <span title="{{ $fac }}"><i class="fa-solid {{ facilityIcon($fac) }}"></i></span>
                            @endforeach
                        </div>
                        <div class="field-info">
                            <span><i class="fa-regular fa-clock"></i> {{ $field->open_time ?? '08:00' }} - {{ $field->close_time ?? '22:00' }}</span>
                            <span><i class="fa-solid fa-layer-group"></i> {{ $field->number_of_courts ?? 1 }} Lapangan</span>
                            <span>⭐ {{ $field->rating ?? '0' }} <span style="font-size:11px;color:#94a3b8;">({{ $field->reviews_count ?? 0 }})</span></span>
                        </div>
                        <div class="field-actions">
                            <button class="edit-btn" onclick="location.href='{{ route('owner.field.edit', $field->id) }}'">Ubah</button>
                            <button class="schedule-btn" onclick="location.href='{{ url('owner/jadwalDanSlot?field_id=' . $field->id) }}'">Jadwal</button>
                        </div>
                    </div>
                </div>
                @empty
                <div style="grid-column:1/-1;text-align:center;padding:40px;">
                    <i class="fa-solid fa-inbox" style="font-size:48px;color:#ccc;margin-bottom:16px;display:block;"></i>
                    <h3 style="color:#888;font-size:18px;margin-bottom:8px;">Belum ada lapangan</h3>
                    <p style="color:#aaa;margin-bottom:20px;">Mulai tambahkan lapangan untuk memulai bisnis Anda</p>
                    <a href="{{ route('owner.tambahLapangan') }}" style="display:inline-block;background:linear-gradient(135deg,#ff4d4d,#ff2e63);color:white;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600;">
                        <i class="fa-solid fa-plus"></i> Tambah Lapangan
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- TAB ULASAN --}}
        <div class="kl-tabpanel" data-klpanel="ulasan" style="display:none;">
            {{-- Summary stats --}}
            <div style="display:flex;gap:16px;margin-bottom:24px;flex-wrap:wrap;">
                <div style="flex:1;min-width:180px;background:white;border-radius:16px;padding:20px;box-shadow:0 2px 12px rgba(0,0,0,.04);display:flex;align-items:center;gap:14px;">
                    <div style="width:48px;height:48px;border-radius:14px;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;">⭐</div>
                    <div>
                        <p style="margin:0 0 2px;font-size:12px;font-weight:600;color:#94a3b8;">Rata-rata Penilaian</p>
                        <p style="margin:0;font-size:22px;font-weight:800;color:#1e293b;">{{ $avgRating }}</p>
                    </div>
                </div>
                <div style="flex:1;min-width:180px;background:white;border-radius:16px;padding:20px;box-shadow:0 2px 12px rgba(0,0,0,.04);display:flex;align-items:center;gap:14px;">
                    <div style="width:48px;height:48px;border-radius:14px;background:#dbeafe;display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;">
                        <i class="fa-regular fa-message" style="font-size:22px;color:#2563eb;"></i>
                    </div>
                    <div>
                        <p style="margin:0 0 2px;font-size:12px;font-weight:600;color:#94a3b8;">Total Ulasan</p>
                        <p style="margin:0;font-size:22px;font-weight:800;color:#1e293b;">{{ $totalReviews }}</p>
                    </div>
                </div>
            </div>

            @if($allReviews->count())
            <div style="display:flex;flex-direction:column;gap:14px;">
                @foreach($allReviews as $rv)
                <div style="background:white;border-radius:16px;padding:20px;box-shadow:0 2px 12px rgba(0,0,0,.04);border:1px solid rgba(0,0,0,.04);">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:8px;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;border-radius:50%;background:#EB5436;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:white;flex-shrink:0;">
                                {{ strtoupper(substr($rv->user?->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <p style="margin:0;font-size:13px;font-weight:600;color:#1e293b;">{{ $rv->user?->name ?? 'Anonim' }}</p>
                                <p style="margin:0;font-size:12px;color:#94a3b8;">{{ $rv->field?->name ?? '-' }}</p>
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <div style="display:flex;gap:2px;justify-content:flex-end;">
                                @for($i = 1; $i <= 5; $i++)
                                <span style="font-size:16px;color:{{ $i <= $rv->rating ? '#f59e0b' : '#e2e8f0' }};">★</span>
                                @endfor
                            </div>
                            <span style="font-size:11px;color:#94a3b8;">{{ \Carbon\Carbon::parse($rv->created_at)->locale('id')->translatedFormat('j M Y') }}</span>
                        </div>
                    </div>
                    @if($rv->review)
                    <p style="margin:10px 0 0;font-size:13px;color:#475569;line-height:1.5;font-style:italic;">"{{ $rv->review }}"</p>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div style="text-align:center;padding:60px 20px;color:#94a3b8;">
                <i class="fa-regular fa-star" style="font-size:48px;margin-bottom:16px;display:block;"></i>
                <p style="font-weight:700;margin:0 0 6px;">Belum ada ulasan</p>
                <p style="font-size:13px;margin:0;">Ulasan dari pelanggan akan muncul di sini.</p>
            </div>
            @endif
        </div>

    </main>

</div>

@include('owner.faq-popup')

<style>
.kl-tabpanel { display: none; }
.kl-tabpanel.is-active { display: block; }
.filter-section select,
.filter-section button { height:42px; padding:0 16px; font-size:13px; border-radius:10px; }
.field-card { border-radius:25px; }
.stats-card { border-radius:25px; }
.field-actions { display:flex; gap:8px; flex-wrap:wrap; }
.field-actions .edit-btn,
.field-actions .schedule-btn { flex:1; min-width:80px; padding:10px; font-size:13px; border-radius:10px; }
@media (max-width:768px) {
    .filter-section { flex-wrap:wrap; }
    .filter-section select,
    .filter-section button { flex:1; min-width:120px; }
    .field-actions .edit-btn,
    .field-actions .schedule-btn { padding:8px; font-size:12px; }
}
@media (max-width:480px) {
    .field-actions { flex-direction:column; }
    .field-actions .edit-btn,
    .field-actions .schedule-btn { width:100%; }
}
</style>
<script>
(function(){
    // tabs
    var tabs = document.querySelectorAll('.kl-tab');
    var indicator = document.getElementById('kl-active-indicator');
    var panels = {};
    document.querySelectorAll('.kl-tabpanel').forEach(function(p) {
        panels[p.getAttribute('data-klpanel')] = p;
    });
    tabs.forEach(function(t) {
        t.addEventListener('click', function() {
            tabs.forEach(function(x) {
                x.classList.remove('is-active');
                x.style.color = '#94a3b8';
            });
            Object.values(panels).forEach(function(p) { if (p) p.classList.remove('is-active'); p.style.display = 'none'; });
            t.classList.add('is-active');
            t.style.color = '#dc2626';
            var target = panels[t.getAttribute('data-kltab')];
            if (target) { target.classList.add('is-active'); target.style.display = 'block'; }
            if (indicator) {
                indicator.style.left = t === tabs[0] ? '0' : '50%';
            }
        });
    });

    // filter by type
    var filterSelect = document.getElementById('filter-type');
    var resetBtn = document.getElementById('filter-reset');
    var cards = document.querySelectorAll('#field-grid .field-card');

    function applyFilter(val) {
        cards.forEach(function(c) {
            if (!val || c.getAttribute('data-type') === val) {
                c.style.display = '';
            } else {
                c.style.display = 'none';
            }
        });
    }

    if (filterSelect) {
        filterSelect.addEventListener('change', function() {
            applyFilter(this.value);
        });
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            filterSelect.value = '';
            applyFilter('');
        });
    }
})();
</script>
</body>
</html>