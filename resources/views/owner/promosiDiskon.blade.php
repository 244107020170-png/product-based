<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promosi dan Diskon</title>
    @vite(['resources/css/app.css', 'resources/css/owner-dashboard.css', 'resources/js/app.js'])
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
                <input type="text" placeholder="Cari promo, pelanggan...">
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

        <div class="welcome-section">
            <div>
                <h1>Promosi dan Diskon</h1>
                <p>Kelola penawaran khusus dan tingkatkan pemesanan lapangan Anda.</p>
            </div>
            <button class="add-btn" onclick="document.getElementById('createPromoModal').style.display='flex'">
                <i class="fa-solid fa-plus"></i> Buat Promo Baru
            </button>
        </div>

        {{-- Stats --}}
        <div class="stats-grid">
            <div class="stats-card">
                <div>
                    <p>Promosi Aktif</p>
                    <h2 class="green-text">{{ $activePromos->count() }}</h2>
                </div>
                <div class="stats-icon green"><i class="fa-solid fa-bullhorn"></i></div>
            </div>
            <div class="stats-card">
                <div>
                    <p>Estimasi Pendapatan</p>
                    <h2 class="yellow-text">Rp {{ number_format($estimatedRevenue ?? 0, 0, ',', '.') }}</h2>
                </div>
                <div class="stats-icon yellow"><i class="fa-solid fa-coins"></i></div>
            </div>
            <div class="stats-card">
                <div>
                    <p>Total Kampanye</p>
                    <h2 class="red-text">{{ $discounts->count() }}</h2>
                </div>
                <div class="stats-icon red"><i class="fa-solid fa-chart-simple"></i></div>
            </div>
        </div>

        {{-- Content --}}
        <div x-data="{
            targetRef: null,
            openConfirm(title, message, ref) {
                this.targetRef = ref;
                this.$dispatch('open-modal-promo-confirm', { title, message });
            },
            executeConfirm() {
                if (this.targetRef && this.$refs[this.targetRef]) {
                    this.$refs[this.targetRef].submit();
                }
            }
        }" x-on:modal-confirmed.window="if ($event.detail.name === 'promo-confirm') executeConfirm()" class="flex flex-col lg:flex-row gap-6 mt-8">

            <div class="flex-1 min-w-0 space-y-6">

                {{-- Active Promotions --}}
                @if($activePromos->count())
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Promosi Berjalan</h3>
                        <span class="text-sm text-gray-500">{{ $activePromos->count() }} aktif</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($activePromos as $p)
                        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 relative">
                            <div class="absolute top-3 right-3">
                                <span class="text-xs font-bold px-3 py-1 rounded-full bg-green-50 text-green-700">
                                    <i class="fa-solid fa-circle text-[6px] mr-1 align-middle"></i> Aktif
                                </span>
                            </div>
                            <div class="mb-3">
                                <h4 class="font-bold text-gray-800">{{ $p->name }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ $p->description ?? 'Tidak ada deskripsi' }}</p>
                            </div>
                            <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-semibold tracking-wider">Potongan</p>
                                    <p class="text-red-600 font-extrabold text-lg">
                                        @if($p->type === 'percentage') {{ $p->value }}% DISKON
                                        @else Rp{{ number_format($p->value, 0, ',', '.') }} @endif
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-gray-400 uppercase font-semibold tracking-wider">Berakhir</p>
                                    <p class="text-gray-700 font-semibold text-sm">{{ $p->end_date->diffForHumans() }}</p>
                                </div>
                            </div>
                            @php $pf = $p->fields->first(); @endphp
                            @if($pf)
                            <p class="mt-2 text-xs text-gray-400"><i class="fa-regular fa-futbol mr-1"></i>{{ $pf->name }}</p>
                            @endif
                            <div class="flex gap-2 mt-3">
                                <button class="flex-1 py-2 bg-gray-50 rounded-lg text-gray-600 font-semibold text-sm hover:bg-gray-100 transition-colors"
                                    onclick="editPromo({{ $p->id }})">
                                    <i class="fa-solid fa-pen mr-1"></i> Ubah
                                </button>
                                <form action="{{ route('owner.discounts.toggle', $p) }}" method="POST" class="inline" x-ref="toggle-{{ $p->id }}">
                                    @csrf
                                    <button type="button" class="py-2 px-3 bg-yellow-50 rounded-lg text-yellow-700 font-semibold text-sm hover:bg-yellow-100 transition-colors"
                                        @click="openConfirm('Nonaktifkan Promo', 'Nonaktifkan promo ini?', 'toggle-{{ $p->id }}')">
                                        <i class="fa-solid fa-pause"></i>
                                    </button>
                                </form>
                                <form action="{{ route('owner.discounts.destroy', $p) }}" method="POST" class="inline" x-ref="delete-card-{{ $p->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" class="py-2 px-3 bg-red-50 rounded-lg text-red-600 font-semibold text-sm hover:bg-red-100 transition-colors"
                                        @click="openConfirm('Hapus Promo', 'Hapus promo ini?', 'delete-card-{{ $p->id }}')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- All Campaigns Table --}}
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Riwayat Kampanye</h3>
                        <button class="p-2 rounded-lg hover:bg-gray-50 transition-colors text-gray-400">
                            <i class="fa-solid fa-filter"></i>
                        </button>
                    </div>
                    @if($discounts->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50">
                                <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="px-6 py-4">Nama Promo</th>
                                    <th class="px-6 py-4">Lapangan</th>
                                    <th class="px-6 py-4">Potongan</th>
                                    <th class="px-6 py-4 text-right">Status</th>
                                    <th class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($discounts as $d)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-gray-800">{{ $d->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $d->start_date->format('d M Y') }} • {{ $d->type === 'percentage' ? 'Persentase' : 'Nominal' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">@php $fc = $d->fields->count(); @endphp @if($fc > 1) {{ $fc }} Lapangan @elseif($fc === 1) {{ $d->fields->first()->name }} @else Semua Lapangan @endif</td>
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-red-600">
                                            @if($d->type === 'percentage') {{ $d->value }}%
                                            @else Rp{{ number_format($d->value, 0, ',', '.') }} @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                            {{ $d->is_active && $d->end_date >= now() ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $d->is_active && $d->end_date >= now() ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <button class="p-2 rounded-lg hover:bg-gray-100 transition-colors text-gray-400" onclick="editPromo({{ $d->id }})">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <form action="{{ route('owner.discounts.destroy', $d) }}" method="POST" class="inline" x-ref="delete-table-{{ $d->id }}">
                                                @csrf @method('DELETE')
                                                <button type="button" class="p-2 rounded-lg hover:bg-red-50 transition-colors text-gray-400 hover:text-red-500"
                                                    @click="openConfirm('Hapus Promo', 'Hapus promo ini?', 'delete-table-{{ $d->id }}')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-16 text-gray-400">
                        <i class="fa-solid fa-tags text-5xl mb-4 block"></i>
                        <h3 class="text-lg font-semibold text-gray-500 mb-2">Belum Ada Promo</h3>
                        <p class="text-sm">Buat promo pertama Anda untuk menarik lebih banyak pelanggan.</p>
                    </div>
                    @endif
                </div>

            </div>

            {{-- Right Sidebar --}}
            <div class="w-full lg:w-80 space-y-6">

                {{-- Quick Create Card --}}
                <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 opacity-10">
                        <i class="fa-solid fa-gift text-[120px]"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Tingkatkan Reservasi!</h3>
                    <p class="text-white/80 text-sm mb-6">Buat kampanye kilat untuk mengisi slot lapangan yang masih kosong minggu ini.</p>
                    <button class="w-full py-3 bg-white text-red-600 font-bold rounded-xl hover:bg-red-50 transition-colors flex items-center justify-center gap-2"
                        onclick="document.getElementById('createPromoModal').style.display='flex'">
                        <i class="fa-solid fa-bolt"></i> Mulai Promosi Kilat
                    </button>
                </div>

                {{-- Recent Activity --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 mb-6">Aktivitas Terkini</h3>
                    @php
                        $recentActivities = \App\Models\Discount::where('owner_id', auth()->id())
                            ->orderBy('updated_at', 'desc')->take(5)->get();
                    @endphp
                    @if($recentActivities->count())
                    <div class="relative space-y-6 pl-6">
                        <div class="absolute left-[7px] top-2 bottom-2 w-0.5 bg-gray-200"></div>
                        @foreach($recentActivities as $act)
                        @php
                            $colors = ['bg-green-500', 'bg-blue-500', 'bg-yellow-500', 'bg-red-500', 'bg-purple-500'];
                            $color = $colors[$loop->index % count($colors)];
                            $msg = $act->wasRecentlyCreated ? 'Promo dibuat' : 'Promo diperbarui';
                        @endphp
                        <div class="relative">
                            <div class="absolute -left-[23px] top-1 w-3.5 h-3.5 rounded-full {{ $color }} border-4 border-white shadow-sm"></div>
                            <p class="text-sm font-semibold text-gray-700">{{ $msg }}: {{ $act->name }}</p>
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

                {{-- Tips Card --}}
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-4">
                        <i class="fa-solid fa-lightbulb text-yellow-600 text-xl"></i>
                        <h4 class="font-bold text-gray-800">Tips Promosi</h4>
                    </div>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Berdasarkan data, memberikan diskon <strong class="text-gray-700">15% pada hari kerja</strong> dapat meningkatkan okupansi hingga 40%. Coba gunakan kampanye berulang untuk hasil maksimal.
                    </p>
                </div>

            </div>

            <x-custom-modal name="promo-confirm"
                             type="confirm"
                             confirmText="Ya"
                             cancelText="Kembali" />

        </div>

    </main>

</div>

{{-- CREATE PROMO MODAL --}}
<div id="createPromoModal" style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,.5);justify-content:center;align-items:center;" onclick="if(event.target===this)this.style.display='none'">
    <div style="background:white;border-radius:20px;padding:28px 24px;max-width:520px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.25);max-height:90vh;overflow-y:auto;">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-800">Buat Promo Baru</h3>
            <span onclick="document.getElementById('createPromoModal').style.display='none'" style="cursor:pointer;font-size:24px;color:#999;line-height:1;">&times;</span>
        </div>
        <form action="{{ route('owner.discounts.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Promo</label>
                    <input type="text" name="name" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-200 focus:border-red-400 outline-none" placeholder="Contoh: Promo Akhir Pekan">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-200 focus:border-red-400 outline-none" placeholder="Jelaskan promo Anda..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe</label>
                        <select name="type" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-200 focus:border-red-400 outline-none">
                            <option value="percentage">Persentase (%)</option>
                            <option value="fixed">Nominal (Rp)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nilai</label>
                        <input type="number" name="value" required min="0" step="0.01" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-200 focus:border-red-400 outline-none" placeholder="25">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" required value="{{ now()->format('Y-m-d') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-200 focus:border-red-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Berakhir</label>
                        <input type="date" name="end_date" required value="{{ now()->addDays(7)->format('Y-m-d') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-200 focus:border-red-400 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Lapangan (opsional)</label>
                    <select name="field_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-200 focus:border-red-400 outline-none">
                        <option value="">Semua Lapangan</option>
                        @foreach($fields as $f)
                        <option value="{{ $f->id }}">{{ $f->name }} ({{ $f->type }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" class="flex-1 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-colors" onclick="document.getElementById('createPromoModal').style.display='none'">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-red-600 text-white rounded-xl font-semibold text-sm hover:bg-red-700 transition-colors"><i class="fa-solid fa-floppy-disk mr-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- EDIT PROMO MODAL --}}
<div id="editPromoModal" style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,.5);justify-content:center;align-items:center;" onclick="if(event.target===this)this.style.display='none'">
    <div style="background:white;border-radius:20px;padding:28px 24px;max-width:520px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.25);max-height:90vh;overflow-y:auto;">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-800" id="editModalTitle">Ubah Promo</h3>
            <span onclick="document.getElementById('editPromoModal').style.display='none'" style="cursor:pointer;font-size:24px;color:#999;line-height:1;">&times;</span>
        </div>
        <form id="editPromoForm" method="POST">
            @csrf @method('PUT')
            <div class="space-y-4" id="editFormFields">
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" class="flex-1 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-colors" onclick="document.getElementById('editPromoModal').style.display='none'">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-red-600 text-white rounded-xl font-semibold text-sm hover:bg-red-700 transition-colors"><i class="fa-solid fa-floppy-disk mr-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function editPromo(id) {
    fetch('/owner/discounts/' + id + '/edit')
        .then(r => r.json())
        .then(d => {
            const form = document.getElementById('editPromoForm');
            form.action = '/owner/discounts/' + id + '/update';

            let fieldsHtml = `
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Promo</label>
                    <input type="text" name="name" value="${d.name}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm outline-none">${d.description || ''}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe</label>
                        <select name="type" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm outline-none">
                            <option value="percentage" ${d.type === 'percentage' ? 'selected' : ''}>Persentase (%)</option>
                            <option value="fixed" ${d.type === 'fixed' ? 'selected' : ''}>Nominal (Rp)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nilai</label>
                        <input type="number" name="value" value="${d.value}" required min="0" step="0.01" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="${d.start_date}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Berakhir</label>
                        <input type="date" name="end_date" value="${d.end_date}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Lapangan</label>
                    <select name="field_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm outline-none">
                        <option value="">Semua Lapangan</option>
                        @foreach($fields as $f)
                        <option value="{{ $f->id }}" ${d.field_id == {{ $f->id }} ? 'selected' : ''}>{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>
            `;
            document.getElementById('editFormFields').innerHTML = fieldsHtml;
            document.getElementById('editModalTitle').textContent = 'Ubah: ' + d.name;
            document.getElementById('editPromoModal').style.display = 'flex';
        });
}

document.querySelectorAll('[onclick*="createPromoModal"]').forEach(el => {
    el.addEventListener('click', function(e) {
        if (this.getAttribute('onclick').includes('event.target===this')) return;
    });
});
</script>

@include('owner.faq-popup')
</body>
</html>
