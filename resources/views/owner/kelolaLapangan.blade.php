<!DOCTYPE html>
<html lang="en">
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
                <button class="notif-btn">
                    <i class="fa-solid fa-bell"></i>
                </button>

                <button class="notif-btn">
                    <i class="fa-solid fa-headset"></i>
                </button>
                <button class="notif-btn question">
                    <i class="fa-solid fa-circle-question"></i>
                </button>

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
                    <h2 class="green-text">{{ count($fields) }}</h2>
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
                    <p>Total Review</p>
                    <h2 class="red-text">{{ $fields->sum('reviews_count') }}</h2>
                </div>

                <div class="stats-icon red">
                    <i class="fa-regular fa-message"></i>
                </div>
            </div>

        </div>


        {{-- FILTER --}}
        <div class="filter-section">
            <select>
                <option>Filter</option>
                <option>Futsal</option>
                <option>Basket</option>
                <option>Badminton</option>
            </select>

            <button>
                <i class="fa-solid fa-rotate-right"></i>
                Reset Filter
            </button>
        </div>


        {{-- FIELD CARD --}}
        <div class="field-grid">

            @forelse ($fields as $field)
            <div class="field-card">

                <div class="field-image">
                    <img src="{{ $field->image_url }}" alt="{{ $field->name }}" onerror="this.style.display='none'">

                    <span class="badge">{{ $field->type ?? 'Olahraga' }}</span>
                    @if($field->featured)
                    <span style="position:absolute;top:10px;right:10px;background:#02025b;color:white;padding:4px 10px;border-radius:20px;font-size:10px;font-weight:700;display:flex;align-items:center;gap:4px;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="#fbbf24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        Featured
                    </span>
                    @endif
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
                        <span><i class="fa-solid fa-wifi"></i></span>
                        <span><i class="fa-solid fa-shower"></i></span>
                        <span><i class="fa-solid fa-car"></i></span>
                        <span><i class="fa-solid fa-fan"></i></span>
                    </div>

                    <div class="field-info">
                        <span>
                            <i class="fa-regular fa-clock"></i>
                            {{ $field->open_time ?? '08:00' }} - {{ $field->close_time ?? '22:00' }}
                        </span>

                        <span>
                            ⭐ {{ $field->rating ?? '0' }}
                            <span style="font-size:11px;color:#94a3b8;">({{ $field->reviews_count ?? 0 }})</span>
                        </span>
                    </div>

                    <div class="field-actions">
                        <button class="edit-btn">Ubah</button>
                        <button class="schedule-btn">Jadwal</button>
                        <button class="featured-btn" onclick="event.preventDefault();toggleFeatured({{ $field->id }}, this)" style="background:none;border:1px solid #ddd;border-radius:6px;padding:6px 10px;cursor:pointer;font-size:12px;color:{{ $field->featured ? '#fbbf24' : '#999' }};">
                            <i class="fa-solid {{ $field->featured ? 'fa-star' : 'fa-regular fa-star' }}"></i>
                        </button>
                        <button class="more-btn">
                            <i class="fa-solid fa-ellipsis"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                <i class="fa-solid fa-inbox" style="font-size: 48px; color: #ccc; margin-bottom: 16px; display: block;"></i>
                <h3 style="color: #888; font-size: 18px; margin-bottom: 8px;">Belum ada lapangan</h3>
                <p style="color: #aaa; margin-bottom: 20px;">Mulai tambahkan lapangan untuk memulai bisnis Anda</p>
                <a href="{{ route('owner.tambahLapangan') }}" style="display: inline-block; background: linear-gradient(135deg, #ff4d4d, #ff2e63); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                    <i class="fa-solid fa-plus"></i> Tambah Lapangan
                </a>
            </div>
            @endforelse

        </div>

        {{-- Review List --}}
        @if($allReviews->count())
        <div style="margin-top:32px;">
            <h3 style="font-size:18px;font-weight:700;color:#1e293b;margin-bottom:16px;">📝 Semua Review</h3>
            <div style="background:white;border-radius:16px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.04);">
                <div class="overflow-x-auto">
                    <table style="width:100%;border-collapse:collapse;">
                        <thead>
                            <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                                <th style="text-align:left;padding:14px 16px;font-size:12px;font-weight:700;color:#64748b;">Pengguna</th>
                                <th style="text-align:left;padding:14px 16px;font-size:12px;font-weight:700;color:#64748b;">Lapangan</th>
                                <th style="text-align:left;padding:14px 16px;font-size:12px;font-weight:700;color:#64748b;">Penilaian</th>
                                <th style="text-align:left;padding:14px 16px;font-size:12px;font-weight:700;color:#64748b;">Ulasan</th>
                                <th style="text-align:left;padding:14px 16px;font-size:12px;font-weight:700;color:#64748b;">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody style="border-bottom:1px solid #e2e8f0;">
                            @foreach($allReviews as $rv)
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:14px 16px;">
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div style="width:34px;height:34px;border-radius:50%;background:#EB5436;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:white;flex-shrink:0;">
                                            {{ strtoupper(substr($rv->user?->name ?? '?', 0, 1)) }}
                                        </div>
                                        <span style="font-size:13px;font-weight:600;color:#1e293b;">{{ $rv->user?->name ?? 'Anonim' }}</span>
                                    </div>
                                </td>
                                <td style="padding:14px 16px;font-size:13px;color:#475569;">{{ $rv->field?->name ?? '-' }}</td>
                                <td style="padding:14px 16px;">
                                    <div style="display:flex;gap:2px;">
                                        @for($i = 1; $i <= 5; $i++)
                                        <span style="font-size:14px;color:{{ $i <= $rv->rating ? '#f59e0b' : '#e2e8f0' }};">★</span>
                                        @endfor
                                    </div>
                                </td>
                                <td style="padding:14px 16px;font-size:13px;color:#64748b;max-width:250px;">
                                    @if($rv->review)
                                    <span style="font-style:italic;">"{{ \Illuminate\Support\Str::limit($rv->review, 60) }}"</span>
                                    @else
                                    <span style="color:#94a3b8;">-</span>
                                    @endif
                                </td>
                                <td style="padding:14px 16px;font-size:12px;color:#94a3b8;">{{ \Carbon\Carbon::parse($rv->created_at)->locale('id')->translatedFormat('j M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </main>

</div>

@include('owner.faq-popup')
<script>
function toggleFeatured(fieldId, btn) {
    fetch('{{ url("owner/fields") }}/' + fieldId + '/toggle-featured', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    }).then(function(r) { return r.json(); }).then(function(data) {
        if (data.success) {
            var icon = btn.querySelector('i');
            if (data.featured) {
                icon.className = 'fa-solid fa-star';
                btn.style.color = '#fbbf24';
            } else {
                icon.className = 'fa-regular fa-star';
                btn.style.color = '#999';
            }
            location.reload();
        }
    });
}
</script>
</body>
</html>