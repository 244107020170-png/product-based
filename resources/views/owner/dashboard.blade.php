<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Pemilik</title>
    @vite(['resources/css/app.css', 'resources/css/owner-dashboard.css'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
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
                <h1>Selamat datang kembali, {{ auth()->user()->name }}!</h1>
                <p>Berikut ringkasan performa fasilitas olahraga Anda.</p>
            </div>
            <a href="{{ route('owner.tambahLapangan') }}" class="add-btn">
                <i class="fa-solid fa-plus"></i> Tambah Lapangan
            </a>
        </div>

        <div class="stats-grid">
            <div class="stats-card">
                <div>
                    <p>Total Lapangan</p>
                    <h2 class="blue-text">{{ $fieldCount ?? 0 }}</h2>
                </div>
                <div class="stats-icon blue"><i class="fa-regular fa-futbol"></i></div>
            </div>
            <div class="stats-card">
                <div>
                    <p>Total Pesanan</p>
                    <h2 class="green-text">{{ $bookingCount ?? 0 }}</h2>
                </div>
                <div class="stats-icon green"><i class="fa-solid fa-circle-check"></i></div>
            </div>
            <div class="stats-card">
                <div>
                    <p>Rating & Review</p>
                    <h2 style="display:flex;align-items:center;gap:6px;color:#c2410c;font-size:1.9rem;font-weight:400;">
                        <span style="color:#f59e0b;">★</span> {{ $avgRating ?? 0 }}
                        <span style="font-size:13px;color:#94a3b8;font-weight:400;">({{ $totalReviews ?? 0 }})</span>
                    </h2>
                </div>
                <div class="stats-icon" style="background:#fef3c7;color:#f59e0b;"><i class="fa-solid fa-star"></i></div>
            </div>
            <div class="stats-card">
                <div>
                    <p>Pendapatan Bulan Ini</p>
                    <h2 class="red-text">Rp {{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }}</h2>
                </div>
                <div class="stats-icon red"><i class="fa-solid fa-dollar-sign"></i></div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 mt-8">

            <div class="flex-1 min-w-0 space-y-6">

                {{-- Recent Bookings --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Pesanan Terbaru</h3>
                        <a href="{{ route('owner.kelolaBooking') }}" class="text-sm font-semibold text-red-500 hover:underline">Kelola Semua</a>
                    </div>
                    @php
                        $recentBookings = \App\Models\Booking::whereHas('field', fn($q) => $q->where('owner_id', auth()->id()))
                            ->with(['user', 'field'])
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
                    @endphp
                    @if($recentBookings->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-gray-500 text-xs font-semibold uppercase border-b border-gray-100">
                                    <th class="pb-3 pr-4">Penyewa</th>
                                    <th class="pb-3 pr-4">Lapangan</th>
                                    <th class="pb-3 pr-4">Tanggal</th>
                                    <th class="pb-3 pr-4">Waktu</th>
                                    <th class="pb-3 pr-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($recentBookings as $b)
                                @php
                                    $statusClass = match($b->status) {
                                        'confirmed', 'completed', 'paid' => 'text-green-700 bg-green-50',
                                        'pending', 'waiting_confirmation', 'waiting_payment' => 'text-yellow-700 bg-yellow-50',
                                        'cancelled', 'rejected', 'expired' => 'text-red-700 bg-red-50',
                                        default => 'text-gray-700 bg-gray-50',
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 pr-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-xs font-bold text-red-600">
                                                {{ strtoupper(substr($b->user?->name ?? '?', 0, 1)) }}
                                            </div>
                                            <span class="text-sm font-medium text-gray-800">{{ $b->user?->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 pr-4 text-sm text-gray-600">{{ $b->field?->name ?? 'N/A' }}</td>
                                    <td class="py-3 pr-4 text-sm text-gray-600">{{ $b->date?->format('d M Y') }}</td>
                                    <td class="py-3 pr-4 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($b->start_time)->format('H.i') }} - {{ \Carbon\Carbon::parse($b->end_time)->format('H.i') }}
                                    </td>
                                    <td class="py-3 pr-4">
                                        @php
                                            $statusLabel = match($b->status) {
                                                'confirmed' => 'Terkonfirmasi',
                                                'completed' => 'Selesai',
                                                'paid' => 'Dibayar',
                                                'pending' => 'Menunggu',
                                                'waiting_confirmation' => 'Menunggu Konfirmasi',
                                                'waiting_payment' => 'Menunggu Pembayaran',
                                                'cancelled' => 'Dibatalkan',
                                                'rejected' => 'Ditolak',
                                                'expired' => 'Kedaluwarsa',
                                                default => ucfirst($b->status),
                                            };
                                        @endphp
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-10 text-gray-400">
                        <i class="fa-solid fa-inbox text-4xl mb-4 block"></i>
                        <p>Belum ada pesanan</p>
                    </div>
                    @endif
                </div>

                {{-- Field Status --}}
                @php $fields = \App\Models\Field::where('owner_id', auth()->id())->get(); @endphp
                @if($fields->count())
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Status Lapangan</h3>
                        <a href="{{ route('owner.kelolaLapangan') }}" class="text-sm font-semibold text-red-500 hover:underline">Kelola Semua</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                        @foreach($fields as $f)
                        <div class="bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-md transition-shadow">
                            <div class="h-32 relative bg-gray-200">
                                <img src="{{ $f->image_url }}" alt="{{ $f->name }}" class="w-full h-full object-cover" onerror="this.style.display='none'">
                                <span class="absolute top-3 right-3 text-xs font-bold px-3 py-1 rounded-full
                                    {{ $f->is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $f->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                                </span>
                            </div>
                            <div class="p-4">
                                <h4 class="font-semibold text-gray-800">{{ $f->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $f->type ?? 'Olahraga' }}</p>
                                <div class="flex items-center justify-between mt-3 text-sm">
                                    <span class="font-semibold text-red-500">Rp{{ number_format($f->price_per_hour ?? 0, 0, ',', '.') }}</span>
                                    <span class="text-gray-400 text-xs"><i class="fa-regular fa-clock mr-1"></i>{{ substr($f->open_time ?? '08:00', 0, 5) }} - {{ substr($f->close_time ?? '22:00', 0, 5) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            {{-- Right Sidebar --}}
            <div class="w-full lg:w-80 space-y-6">

                {{-- Quick Actions --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 mb-6">Akses Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('owner.tambahLapangan') }}" class="flex items-center p-4 rounded-xl border border-gray-100 hover:border-red-200 hover:bg-red-50/50 transition-all group">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-4 group-hover:bg-red-100">
                                <i class="fa-solid fa-plus text-gray-500 group-hover:text-red-500"></i>
                            </div>
                            <span class="font-semibold text-sm text-gray-700 group-hover:text-red-600">Tambah Lapangan</span>
                        </a>
                        <a href="{{ route('owner.jadwalDanSlot') }}" class="flex items-center p-4 rounded-xl border border-gray-100 hover:border-red-200 hover:bg-red-50/50 transition-all group">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-4 group-hover:bg-red-100">
                                <i class="fa-regular fa-calendar text-gray-500 group-hover:text-red-500"></i>
                            </div>
                            <span class="font-semibold text-sm text-gray-700 group-hover:text-red-600">Atur Jadwal</span>
                        </a>
                        <a href="{{ route('owner.kelolaBooking') }}" class="flex items-center p-4 rounded-xl border border-gray-100 hover:border-red-200 hover:bg-red-50/50 transition-all group">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-4 group-hover:bg-red-100">
                                <i class="fa-solid fa-list text-gray-500 group-hover:text-red-500"></i>
                            </div>
                            <span class="font-semibold text-sm text-gray-700 group-hover:text-red-600">Lihat Pesanan</span>
                        </a>
                        <a href="{{ route('owner.promosiDiskon') }}" class="flex items-center p-4 rounded-xl border border-gray-100 hover:border-red-200 hover:bg-red-50/50 transition-all group">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-4 group-hover:bg-red-100">
                                <i class="fa-solid fa-tag text-gray-500 group-hover:text-red-500"></i>
                            </div>
                            <span class="font-semibold text-sm text-gray-700 group-hover:text-red-600">Buat Promo</span>
                        </a>
                    </div>
                </div>

                {{-- Recent Reviews --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">⭐ Review Terbaru</h3>
                    @if($recentReviews->count())
                    <div class="space-y-4">
                        @foreach($recentReviews as $rv)
                        <div style="padding:12px;border-radius:12px;background:#f8fafc;">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <div style="width:30px;height:30px;border-radius:50%;background:#EB5436;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:white;">
                                        {{ strtoupper(substr($rv->user?->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p style="margin:0;font-size:13px;font-weight:600;color:#1e293b;">{{ $rv->user?->name ?? 'Anonim' }}</p>
                                        <p style="margin:0;font-size:11px;color:#94a3b8;">{{ $rv->field?->name ?? '-' }}</p>
                                    </div>
                                </div>
                                <div style="display:flex;gap:2px;">
                                    @for($i = 1; $i <= 5; $i++)
                                    <span style="font-size:14px;color:{{ $i <= $rv->rating ? '#f59e0b' : '#e2e8f0' }};">★</span>
                                    @endfor
                                </div>
                            </div>
                            @if($rv->review)
                            <p style="margin:0;font-size:12px;color:#64748b;line-height:1.4;font-style:italic;">"{{ \Illuminate\Support\Str::limit($rv->review, 80) }}"</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-400">
                        <i class="fa-regular fa-star text-3xl mb-3 block"></i>
                        <p class="text-sm">Belum ada review</p>
                    </div>
                    @endif
                </div>

                {{-- Activity Timeline --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 mb-6">Aktivitas Terkini</h3>
                    @php
                        $recentActivities = \App\Models\Booking::whereHas('field', fn($q) => $q->where('owner_id', auth()->id()))
                            ->with(['user', 'field'])
                            ->orderBy('updated_at', 'desc')
                            ->take(5)
                            ->get();
                    @endphp
                    @if($recentActivities->count())
                    <div class="relative space-y-6 pl-6">
                        <div class="absolute left-[7px] top-2 bottom-2 w-0.5 bg-gray-200"></div>
                        @foreach($recentActivities as $act)
                        @php
                            $colors = ['bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-red-500', 'bg-purple-500'];
                            $color = $colors[$loop->index % count($colors)];
                        @endphp
                        <div class="relative">
                            <div class="absolute -left-[23px] top-1 w-3.5 h-3.5 rounded-full {{ $color }} border-4 border-white shadow-sm"></div>
                            <p class="text-sm font-semibold text-gray-700">{{ $act->user?->name ?? 'Seseorang' }} memesan {{ $act->field?->name ?? 'lapangan' }}</p>
                            <p class="text-xs text-gray-400">{{ $act->updated_at->diffForHumans() }}</p>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-400">
                        <i class="fa-solid fa-clock-rotate-left text-3xl mb-3 block"></i>
                        <p class="text-sm">Belum ada aktivitas</p>
                    </div>
                    @endif
                </div>

            </div>

        </div>

    </main>

</div>

@include('owner.faq-popup')
</body>
</html>
