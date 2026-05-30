<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori - Pemilik</title>
    @vite(['resources/css/app.css', 'resources/css/owner-dashboard.css'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <style>
    .histab-lapangan,
    .histab-review { display: none; }
    .histab-lapangan.is-active,
    .histab-review.is-active { display: block; }
    .histab-lapangan table,
    .histab-review table { width: 100%; border-collapse: collapse; }
    @media (max-width: 768px) {
        .owner-tab { flex:1; text-align:center; padding:12px 12px !important; font-size:13px !important; }
        .histab-lapangan .stats-grid { grid-template-columns: repeat(2,1fr); }
    }
    @media (max-width: 480px) {
        .histab-lapangan .stats-grid { grid-template-columns: 1fr; }
    }
    </style>
</head>
<body>

<div class="dashboard-layout">

    @include('owner.navbar')

    <main class="main-content">

        <div class="topbar">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Search bookings, customers...">
            </div>
            <div class="topbar-right">
                <button class="notif-btn"><i class="fa-solid fa-bell"></i></button>
                <button class="notif-btn" onclick="toggleFaqPopup()"><i class="fa-solid fa-headset"></i></button>
                <button class="notif-btn question"><i class="fa-solid fa-circle-question"></i></button>
                <div class="profile-box">
                    <div>
                        <h5>{{ auth()->user()->name }}</h5>
                        <p>Profil Pemilik</p>
                    </div>
                    <img src="https://i.pravatar.cc/100" alt="Profile">
                </div>
            </div>
        </div>

        <div class="welcome-section">
            <div>
                <h1>Histori</h1>
                <p>Ringkasan lapangan dan ulasan dari pelanggan.</p>
            </div>
        </div>

        {{-- Tabs --}}
        <div style="display:flex;gap:0;border-bottom:2px solid #e2e8f0;margin-bottom:24px;">
            <button class="owner-tab is-active" data-owntab="lapangan" style="padding:12px 24px;border:none;border-bottom:3px solid #EB5436;background:transparent;font-weight:700;font-size:14px;color:#02025b;cursor:pointer;transition:all .2s;">Lapangan</button>
            <button class="owner-tab" data-owntab="review" style="padding:12px 24px;border:none;border-bottom:3px solid transparent;background:transparent;font-weight:700;font-size:14px;color:#94a3b8;cursor:pointer;transition:all .2s;">Ulasan</button>
        </div>

        {{-- TAB LAPANGAN --}}
        <div class="histab-lapangan is-active">
            @if($fields->count())
            <div class="stats-grid" style="margin-bottom:24px;">
                <div class="stats-card">
                    <div>
                        <p>Total Lapangan</p>
                        <h2 class="blue-text">{{ $fields->count() }}</h2>
                    </div>
                    <div class="stats-icon blue"><i class="fa-regular fa-futbol"></i></div>
                </div>
                <div class="stats-card">
                    <div>
                        <p>Tersedia</p>
                        <h2 class="green-text">{{ $fields->where('is_available', true)->count() }}</h2>
                    </div>
                    <div class="stats-icon green"><i class="fa-solid fa-circle-check"></i></div>
                </div>
                <div class="stats-card">
                    <div>
                        <p>Total Booking</p>
                        <h2 class="yellow-text">{{ $fields->sum('bookings_count') }}</h2>
                    </div>
                    <div class="stats-icon yellow"><i class="fa-solid fa-calendar-days"></i></div>
                </div>
                <div class="stats-card">
                    <div>
                        <p>Rating Rata-rata</p>
                        <h2 class="red-text" style="display:flex;align-items:center;gap:4px;"><span style="color:#f59e0b;">★</span> {{ $avgRating }}</h2>
                    </div>
                    <div class="stats-icon" style="background:#fef3c7;color:#f59e0b;"><i class="fa-solid fa-star"></i></div>
                </div>
            </div>

            <div style="background:white;border-radius:16px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.04);">
                <div class="overflow-x-auto">
                    <table>
                        <thead>
                            <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                                <th style="text-align:left;padding:14px 16px;font-size:12px;font-weight:700;color:#64748b;">Lapangan</th>
                                <th style="text-align:left;padding:14px 16px;font-size:12px;font-weight:700;color:#64748b;">Lokasi</th>
                                <th style="text-align:center;padding:14px 16px;font-size:12px;font-weight:700;color:#64748b;">Rating</th>
                                <th style="text-align:center;padding:14px 16px;font-size:12px;font-weight:700;color:#64748b;">Total Booking</th>
                                <th style="text-align:center;padding:14px 16px;font-size:12px;font-weight:700;color:#64748b;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fields as $f)
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:14px 16px;">
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div style="width:40px;height:40px;border-radius:10px;overflow:hidden;flex-shrink:0;background:#e2e8f0;">
                                            <img src="{{ $f->image_url }}" alt="" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'">
                                        </div>
                                        <span style="font-size:13px;font-weight:600;color:#1e293b;">{{ $f->name }}</span>
                                    </div>
                                </td>
                                <td style="padding:14px 16px;font-size:13px;color:#64748b;">{{ $f->location ?? '-' }}</td>
                                <td style="text-align:center;padding:14px 16px;">
                                    <span style="font-size:13px;font-weight:600;color:#1e293b;">★ {{ $f->rating ?? '0' }}</span>
                                </td>
                                <td style="text-align:center;padding:14px 16px;font-size:13px;color:#64748b;">{{ $f->bookings_count ?? 0 }}</td>
                                <td style="text-align:center;padding:14px 16px;">
                                    <span style="display:inline-block;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;{{ $f->is_available ? 'background:#e6f7e6;color:#166534;' : 'background:#fee2e2;color:#991b1b;' }}">
                                        {{ $f->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div style="text-align:center;padding:60px 20px;color:#94a3b8;">
                <i class="fa-regular fa-futbol" style="font-size:48px;margin-bottom:16px;display:block;"></i>
                <p style="font-weight:700;margin:0 0 6px;">Belum ada lapangan</p>
                <p style="font-size:13px;margin:0;">Tambahkan lapanganmu terlebih dahulu.</p>
            </div>
            @endif
        </div>

        {{-- TAB ULASAN --}}
        <div class="histab-review">

            {{-- Summary stats --}}
            <div style="display:flex;gap:16px;margin-bottom:24px;flex-wrap:wrap;">
                <div style="flex:1;min-width:180px;background:white;border-radius:16px;padding:20px;box-shadow:0 2px 12px rgba(0,0,0,.04);display:flex;align-items:center;gap:14px;">
                    <div style="width:48px;height:48px;border-radius:14px;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;">⭐</div>
                    <div>
                        <p style="margin:0 0 2px;font-size:12px;font-weight:600;color:#94a3b8;">Rating Rata-rata</p>
                        <p style="margin:0;font-size:22px;font-weight:800;color:#1e293b;">{{ $avgRating }}</p>
                    </div>
                </div>
                <div style="flex:1;min-width:180px;background:white;border-radius:16px;padding:20px;box-shadow:0 2px 12px rgba(0,0,0,.04);display:flex;align-items:center;gap:14px;">
                    <div style="width:48px;height:48px;border-radius:14px;background:#dbeafe;display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;">📝</div>
                    <div>
                        <p style="margin:0 0 2px;font-size:12px;font-weight:600;color:#94a3b8;">Total Review</p>
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
                <p style="font-weight:700;margin:0 0 6px;">Belum ada review</p>
                <p style="font-size:13px;margin:0;">Review dari pelanggan akan muncul di sini.</p>
            </div>
            @endif
        </div>

    </main>

</div>

@include('owner.faq-popup')

<script>
(function(){
    var tabs = document.querySelectorAll('[data-owntab]');
    var panels = {
        lapangan: document.querySelector('.histab-lapangan'),
        review: document.querySelector('.histab-review'),
    };
    tabs.forEach(function(t) {
        t.addEventListener('click', function() {
            tabs.forEach(function(x) {
                x.classList.remove('is-active');
                x.style.color = '#94a3b8';
                x.style.borderBottomColor = 'transparent';
            });
            Object.values(panels).forEach(function(p) { if (p) p.classList.remove('is-active'); });
            t.classList.add('is-active');
            t.style.color = '#02025b';
            t.style.borderBottomColor = '#EB5436';
            var target = panels[t.getAttribute('data-owntab')];
            if (target) target.classList.add('is-active');
        });
    });
})();
</script>
</body>
</html>