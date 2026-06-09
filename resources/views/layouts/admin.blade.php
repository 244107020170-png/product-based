<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SPIES SPORT Admin Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@600;700;800&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    @stack('styles')
</head>
<body class="bg-adm-bg text-adm-on-surface font-adm-body min-h-screen">

@php
    use Illuminate\Support\Facades\DB;

    $adminMenus = [
        ['label' => 'Beranda', 'icon' => 'dashboard', 'route' => 'admin.dashboard', 'active' => request()->routeIs('admin.dashboard')],
        ['label' => 'Monitoring Pengguna', 'icon' => 'person', 'route' => 'admin.users', 'active' => request()->routeIs('admin.users*')],
        ['label' => 'Monitoring Pemilik', 'icon' => 'storefront', 'route' => 'admin.owners', 'active' => request()->routeIs('admin.owners*')],
        ['label' => 'Monitoring Lapangan', 'icon' => 'stadium', 'route' => 'admin.fields', 'active' => request()->routeIs('admin.fields*')],
        ['label' => 'Monitoring Pesanan', 'icon' => 'event_note', 'route' => 'admin.bookings', 'active' => request()->routeIs('admin.bookings*')],
        ['label' => 'Monitoring Pembayaran', 'icon' => 'payments', 'route' => 'admin.payments', 'active' => request()->routeIs('admin.payments*')],
        ['label' => 'Monitoring Komunitas', 'icon' => 'groups', 'route' => 'admin.communities', 'active' => request()->routeIs('admin.communities*')],
        ['label' => 'Monitoring Sistem', 'icon' => 'settings_suggest', 'route' => 'admin.system', 'active' => request()->routeIs('admin.system*')],
        ['label' => 'Laporan & Analitik', 'icon' => 'analytics', 'route' => 'admin.reports', 'active' => request()->routeIs('admin.reports*')],
        ['label' => 'Pengaturan Platform', 'icon' => 'settings', 'route' => 'admin.settings', 'active' => request()->routeIs('admin.settings*')],
    ];
    $currentUser = Auth::user();

    // ── Dynamic Server Status ──
    try {
        DB::connection()->getPdo();
        $dbConnected = true;
    } catch (\Exception $e) {
        $dbConnected = false;
    }
    $appRunning = true;
    $serverStatusClass = ($dbConnected && $appRunning) ? 'success' : 'error';
    $serverStatusText = ($dbConnected && $appRunning) ? 'Optimal' : 'Offline';
    $serverStatusIcon = ($dbConnected && $appRunning) ? 'check_circle' : 'error';

    // ── Notifications ──
    $notifications = $currentUser->notifications()->orderBy('created_at', 'desc')->take(10)->get();
    $unreadCount = $currentUser->unreadNotifications()->count();
    $showTopbar = request()->routeIs('admin.dashboard');
@endphp

<!-- SideNavBar -->
<aside id="adminSidebar" class="fixed left-0 top-0 h-full z-50 pt-1 pb-5 px-3 w-[220px] bg-adm-surface-lowest border-r border-adm-outline-variant shadow-sm flex flex-col admin-sidebar">
    <div class="flex items-center gap-2 mb-3 px-2">
        <div>
            <h1 class="font-adm-headline text-[18px] font-bold text-adm-primary leading-tight">SPIES SPORT</h1>
            <p class="text-[9px] font-adm-body text-adm-outline uppercase tracking-widest leading-tight">Pusat Kontrol Admin</p>
        </div>
    </div>
    <nav class="max-h-[calc(100vh-140px)] space-y-0.5 custom-scrollbar overflow-y-auto min-h-0">
        @foreach ($adminMenus as $menu)
        <a href="{{ route($menu['route']) }}"
           class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-adm-body text-[13px] font-medium no-underline transition-all relative
                  @if ($menu['active'])
                      bg-adm-dark text-white shadow-sm
                  @else
                      text-adm-on-surface-variant hover:bg-adm-surface-high hover:text-adm-primary
                  @endif">
            <span class="material-symbols-outlined text-[20px] {{ $menu['active'] ? 'text-white' : '' }}" style="font-variation-settings: 'FILL' {{ $menu['active'] ? 1 : 0 }};">{{ $menu['icon'] }}</span>
            {{ $menu['label'] }}
        </a>
        @endforeach
    </nav>

    <!-- Admin Profile Footer -->
    <div class="pt-4 border-t border-adm-outline-variant">
        <div class="flex items-center gap-2.5 px-1">
            <div class="w-9 h-9 rounded-full bg-adm-primary-container text-white flex items-center justify-center font-bold text-xs flex-shrink-0">
                {{ strtoupper(substr($currentUser->name ?? 'A', 0, 1)) }}
            </div>
            <div class="flex-1 overflow-hidden">
                <p class="font-adm-body text-[13px] font-semibold text-adm-primary truncate leading-tight">{{ $currentUser->name ?? 'Admin' }}</p>
                <p class="text-[11px] text-adm-outline truncate leading-tight">Super Administrator</p>
            </div>
            <a href="#" onclick="event.preventDefault(); document.getElementById('sidebar-logout-btn').click();" class="cursor-pointer">
                <span class="material-symbols-outlined text-[20px] text-adm-error hover:opacity-80">logout</span>
            </a>
            <button id="sidebar-logout-btn" onclick="openLogoutModal()" class="hidden"></button>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar Overlay -->
<div id="adminSidebarOverlay" class="admin-sidebar-overlay" onclick="toggleAdminSidebar()"></div>

<!-- Mobile Sidebar Toggle -->
<button id="adminSidebarToggle" class="admin-sidebar-toggle" onclick="toggleAdminSidebar()" aria-label="Toggle sidebar">
    <span></span>
    <span></span>
    <span></span>
</button>

@if($showTopbar)
<!-- TopAppBar -->
<header class="fixed top-5 left-[236px] right-0 h-[60px] flex items-center justify-between px-6 z-40 bg-adm-surface border-b border-adm-outline-variant">
    <div class="relative w-[360px] ml-20">
        <span class="material-symbols-outlined text-[16px] absolute left-3 inset-y-0 flex items-center text-adm-outline/40">search</span>
        <input id="admin-search-input" class="w-full h-9 pl-9 pr-3 bg-adm-surface-low border border-adm-outline-variant rounded-lg font-adm-body text-[13px] placeholder:text-adm-outline/40 focus:ring-2 focus:ring-adm-secondary-container focus:border-adm-secondary outline-none transition-all" placeholder="Cari data, laporan, atau transaksi..." type="text">
    </div>
    <div class="flex items-center gap-4">
        <!-- Notification -->
        <div class="relative" id="admin-notif-wrapper">
            <button id="admin-notif-bell" class="relative w-9 h-9 flex items-center justify-center rounded-lg hover:bg-adm-surface-low cursor-pointer transition-colors" type="button">
                <span class="material-symbols-outlined text-[20px] text-adm-on-surface-variant">notifications</span>
                @if($unreadCount > 0)
                    <span id="admin-notif-badge" class="absolute -top-0.5 -right-0.5 min-w-[16px] h-[16px] flex items-center justify-center bg-adm-error text-white text-[9px] font-bold rounded-full px-1 ring-2 ring-adm-surface">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                @else
                    <span id="admin-notif-badge-empty" class="absolute top-1.5 right-1.5 w-[6px] h-[6px] bg-adm-error rounded-full ring-2 ring-adm-surface" style="display:none;"></span>
                @endif
            </button>
            <div id="admin-notif-dropdown" class="absolute top-full right-0 z-[9999] w-[380px] max-w-[calc(100vw-24px)] mt-2 bg-white rounded-xl shadow-xl border border-adm-outline-variant overflow-hidden" style="display:none;">
                <div class="px-4 py-3 border-b border-adm-outline-variant/50 flex items-center justify-between">
                    <span class="text-[14px] font-bold text-adm-primary">Notifikasi</span>
                    @if($unreadCount > 0)
                        <span id="admin-notif-badge-header" class="text-[10px] font-semibold bg-adm-error text-white px-2 py-0.5 rounded-full">{{ $unreadCount }} baru</span>
                    @endif
                </div>
                <div class="max-h-[360px] overflow-y-auto" id="admin-notif-list">
                    @forelse($notifications as $notif)
                        @php
                            $d = $notif->data;
                            $isUnread = is_null($notif->read_at);
                            $_bookingId = $d['booking_id'] ?? null;
                            $_notifType = $d['type'] ?? '';
                        @endphp
                        <div class="notif-item px-4 py-3 border-b border-adm-outline-variant/30 flex gap-3 items-start {{ $isUnread ? 'bg-blue-50/70' : '' }}" data-notif-id="{{ $notif->id }}" data-unread="{{ $isUnread ? '1' : '0' }}">
                            @if($isUnread)
                                <span class="notif-dot w-2 h-2 rounded-full bg-blue-500 flex-shrink-0 mt-1.5"></span>
                            @else
                                <span class="w-2 h-2 flex-shrink-0 mt-1.5"></span>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="text-[12.5px] text-gray-800 leading-relaxed">
                                    @if(($_notifType) === 'payment_claimed')
                                        <strong>{{ $d['user_name'] ?? 'Pemain' }}</strong> mengklaim sudah bayar <strong>{{ $d['match_title'] ?? '' }}</strong>
                                    @elseif(($_notifType) === 'payment_confirmed')
                                        Pembayaran untuk <strong>{{ $d['match_title'] ?? '' }}</strong> dikonfirmasi
                                    @elseif(($_notifType) === 'booking_payment_received')
                                        Pembayaran untuk <strong>{{ $d['field_name'] ?? '' }}</strong> diterima, menunggu konfirmasi owner
                                    @elseif(($_notifType) === 'booking_confirmed')
                                        Booking <strong>{{ $d['field_name'] ?? '' }}</strong> telah dikonfirmasi
                                    @elseif(($_notifType) === 'community_joined')
                                        <strong>{{ $d['user_name'] ?? 'Anggota baru' }}</strong> bergabung ke komunitas <strong>{{ $d['community_name'] ?? '' }}</strong>
                                    @else
                                        {{ $d['message'] ?? '' }}
                                    @endif
                                </div>
                                <div class="text-[10.5px] text-gray-400 mt-0.5 {{ $isUnread ? 'font-semibold' : '' }}">{{ \Carbon\Carbon::parse($notif->created_at)->locale('id')->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-10 text-center text-gray-400 text-[13px]">Belum ada notifikasi.</div>
                    @endforelse
                </div>
                @if($notifications->isNotEmpty())
                    <button id="admin-notif-mark-read" type="button" class="w-full py-2.5 text-center text-[12px] font-bold {{ $unreadCount > 0 ? 'text-adm-primary hover:bg-adm-surface-low cursor-pointer' : 'text-gray-300 cursor-default' }} border-t border-adm-outline-variant/50 bg-white transition-colors" {{ $unreadCount > 0 ? '' : 'disabled' }}>
                        {{ $unreadCount > 0 ? 'Tandai Sudah Dibaca' : 'Sudah Dibaca' }}
                    </button>
                @endif
            </div>
        </div>

        <!-- Help -->
        <button id="admin-help-btn" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-adm-surface-low cursor-pointer transition-colors" type="button">
            <span class="material-symbols-outlined text-[20px] text-adm-on-surface-variant">help_outline</span>
        </button>

        <!-- Server Status -->
        <div class="flex items-center gap-2 h-9 px-3 bg-adm-surface-low rounded-lg" id="admin-server-status">
            @if($serverStatusClass === 'success')
                <span class="material-symbols-outlined text-[16px] text-adm-success">check_circle</span>
                <span class="text-[11px] font-semibold text-adm-success">Optimal</span>
            @else
                <span class="material-symbols-outlined text-[16px] text-adm-error">error</span>
                <span class="text-[11px] font-semibold text-adm-error">Offline</span>
            @endif
        </div>

        <!-- Logout -->
        <button id="admin-logout-btn" onclick="openLogoutModal()"
           class="flex items-center gap-1.5 h-9 px-3 bg-adm-error text-adm-on-primary rounded-lg font-adm-body text-[12px] font-semibold no-underline hover:bg-adm-error/90 transition-all active:scale-95 whitespace-nowrap cursor-pointer border-none">
            <span class="material-symbols-outlined text-[15px]">logout</span>
            Keluar
        </button>
    </div>
</header>
@endif

<!-- Help Modal -->
<div id="admin-help-modal" class="fixed inset-0 z-[9999] bg-black/40 flex items-center justify-center" style="display:none;">
    <div class="bg-white rounded-2xl shadow-2xl w-[520px] max-w-[calc(100vw-32px)] max-h-[80vh] flex flex-col overflow-hidden">
        <div class="shrink-0 px-6 py-4 border-b border-adm-outline-variant/50 flex items-center justify-between">
            <div>
                <h2 class="text-[16px] font-bold text-adm-primary">Bantuan Dashboard Admin</h2>
                <p class="text-[11px] text-adm-outline mt-0.5">Panduan singkat fungsi setiap menu</p>
            </div>
            <button onclick="closeHelpModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-adm-surface-low cursor-pointer border-none bg-transparent">
                <span class="material-symbols-outlined text-[18px] text-adm-outline">close</span>
            </button>
        </div>
        <div class="overflow-y-auto p-5 space-y-3 flex-1 min-h-0">
            @php
                $helpItems = [
                    ['icon' => 'dashboard', 'title' => 'Beranda', 'desc' => 'Halaman utama dashboard dengan ringkasan statistik global, grafik pemesanan, dan data transaksi terbaru. Cocok untuk memantau kondisi platform secara cepat.'],
                    ['icon' => 'person', 'title' => 'Monitoring Pengguna', 'desc' => 'Melihat daftar seluruh pengguna (player) yang terdaftar. Admin dapat melihat detail profil, riwayat aktivitas, dan status akun pengguna.'],
                    ['icon' => 'storefront', 'title' => 'Monitoring Pemilik', 'desc' => 'Mengelola data pemilik lapangan. Admin dapat melihat daftar pemilik, detail lapangan yang dikelola, serta status verifikasi pemilik.'],
                    ['icon' => 'stadium', 'title' => 'Monitoring Lapangan', 'desc' => 'Memantau seluruh lapangan yang terdaftar di platform. Termasuk informasi harga, lokasi, jadwal, dan status ketersediaan lapangan.'],
                    ['icon' => 'event_note', 'title' => 'Monitoring Pesanan', 'desc' => 'Melihat dan mengelola seluruh pemesanan (booking) yang terjadi. Admin dapat memonitor status pembayaran, jadwal, dan konfirmasi pemesanan.'],
                    ['icon' => 'payments', 'title' => 'Monitoring Pembayaran', 'desc' => 'Memantau transaksi pembayaran dari seluruh pemesanan. Meliputi status pembayaran, metode pembayaran, dan riwayat transaksi.'],
                    ['icon' => 'groups', 'title' => 'Monitoring Komunitas', 'desc' => 'Mengelola komunitas olahraga yang ada di platform. Admin dapat melihat anggota, aktivitas, dan status komunitas.'],
                    ['icon' => 'settings_suggest', 'title' => 'Monitoring Sistem', 'desc' => 'Memantau kesehatan sistem secara keseluruhan. Mengecek status layanan seperti database, booking service, autentikasi, dan lainnya.'],
                ];
            @endphp
            @foreach($helpItems as $item)
            <div class="flex items-start gap-3 p-3 rounded-xl bg-adm-surface-low/50 hover:bg-adm-surface-low transition-colors">
                <span class="material-symbols-outlined text-[22px] text-adm-primary flex-shrink-0 mt-0.5">{{ $item['icon'] }}</span>
                <div>
                    <h3 class="text-[13px] font-bold text-adm-primary">{{ $item['title'] }}</h3>
                    <p class="text-[11.5px] text-gray-500 leading-relaxed mt-0.5">{{ $item['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Logout Confirmation Modal -->
<div id="admin-logout-modal" class="fixed inset-0 z-[9999] bg-black/40 flex items-center justify-center" style="display:none;">
    <div class="bg-white rounded-2xl shadow-2xl w-[380px] max-w-[calc(100vw-32px)] p-6 text-center">
        <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-[28px] text-adm-error">logout</span>
        </div>
        <h3 class="text-[16px] font-bold text-adm-primary mb-2">Keluar Dashboard</h3>
        <p class="text-[13px] text-gray-500 mb-6">Apakah Anda yakin ingin keluar dari dashboard admin?</p>
        <div class="flex gap-3 justify-center">
            <button onclick="closeLogoutModal()" class="px-5 py-2.5 rounded-lg border border-adm-outline-variant text-[13px] font-semibold text-gray-600 hover:bg-adm-surface-low transition-colors cursor-pointer bg-white">
                Batal
            </button>
            <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-adm-error text-white text-[13px] font-semibold hover:bg-adm-error/90 transition-colors cursor-pointer border-none">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Main Content Canvas -->
<main class="ml-[236px] {{ $showTopbar ? 'pt-[60px]' : 'pt-5' }} p-6">
    <div class="max-w-[1600px] mx-auto">
        {{ $slot ?? '' }}
        @yield('content')
    </div>
</main>

@stack('scripts')
<script>
// ── Mobile Sidebar Toggle ──
function toggleAdminSidebar() {
    document.getElementById('adminSidebar').classList.toggle('is-open');
    document.getElementById('adminSidebarOverlay').classList.toggle('is-visible');
    document.getElementById('adminSidebarToggle').classList.toggle('is-active');
}

// ── Notification Dropdown ──
(function() {
    var bell = document.getElementById('admin-notif-bell');
    var dd = document.getElementById('admin-notif-dropdown');

    function closeNotifDropdown() {
        if (dd) dd.style.display = 'none';
        document.removeEventListener('click', closeNotifHandler);
    }

    function closeNotifHandler() { closeNotifDropdown(); }

    if (bell && dd) {
        bell.addEventListener('click', function(e) {
            e.stopPropagation();
            if (dd.style.display === 'block') {
                closeNotifDropdown();
            } else {
                dd.style.display = 'block';
                setTimeout(function() { document.addEventListener('click', closeNotifHandler); }, 10);
            }
        });
    }

    // Mark all read
    var markBtn = document.getElementById('admin-notif-mark-read');
    if (markBtn) {
        markBtn.addEventListener('click', function() {
            if (markBtn.disabled) return;
            markBtn.disabled = true;
            var csrf = document.querySelector('meta[name="csrf-token"]');
            if (!csrf) { markBtn.disabled = false; return; }
            var fd = new FormData();
            fd.append('_token', csrf.getAttribute('content'));
            fetch('{{ route("notifications.markAllRead") }}', { method: 'POST', body: fd })
                .then(function() {
                    dd.querySelectorAll('.notif-item[data-unread="1"]').forEach(function(el) {
                        el.style.background = '';
                        el.setAttribute('data-unread', '0');
                        var dot = el.querySelector('.notif-dot');
                        if (dot) dot.style.background = 'transparent';
                        var time = el.querySelector('.text-gray-400');
                        if (time) time.style.fontWeight = '';
                    });
                    var badge = document.getElementById('admin-notif-badge');
                    if (badge) badge.remove();
                    var badgeHdr = document.getElementById('admin-notif-badge-header');
                    if (badgeHdr) badgeHdr.remove();
                    markBtn.textContent = 'Sudah Dibaca';
                    markBtn.style.color = '#d1d5db';
                    markBtn.style.cursor = 'default';
                })
                .catch(function() { markBtn.disabled = false; });
        });
    }

    // Prevent dropdown close on scroll
    var list = document.getElementById('admin-notif-list');
    if (list) {
        list.addEventListener('click', function(e) { e.stopPropagation(); });
    }
})();

// ── Help Modal ──
function openHelpModal() {
    document.getElementById('admin-help-modal').style.display = 'flex';
}

function closeHelpModal() {
    document.getElementById('admin-help-modal').style.display = 'none';
}

(function() {
    var helpBtn = document.getElementById('admin-help-btn');
    var helpModal = document.getElementById('admin-help-modal');
    if (helpBtn && helpModal) {
        helpBtn.addEventListener('click', openHelpModal);
        helpModal.addEventListener('click', function(e) {
            if (e.target === helpModal) closeHelpModal();
        });
    }
})();

// ── Logout Modal ──
function openLogoutModal() {
    document.getElementById('admin-logout-modal').style.display = 'flex';
}

function closeLogoutModal() {
    document.getElementById('admin-logout-modal').style.display = 'none';
}

(function() {
    var logoutModal = document.getElementById('admin-logout-modal');
    if (logoutModal) {
        logoutModal.addEventListener('click', function(e) {
            if (e.target === logoutModal) closeLogoutModal();
        });
    }
})();

// ── Realtime Filter ──
function resetFilters(e) {
    if (e) e.preventDefault();
    var container = document.querySelector('[data-realtime-filter]');
    if (!container) return;
    container.querySelectorAll('[name]').forEach(function(el) {
        if (el.tagName.toLowerCase() === 'select') el.selectedIndex = 0;
        else el.value = '';
    });
    var url = window.location.pathname;
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function(res) { return res.text(); })
        .then(function(html) {
            var results = document.querySelector('[data-realtime-results]');
            if (!results) return;
            var parser = new DOMParser();
            var doc = parser.parseFromString(html, 'text/html');
            var newResults = doc.querySelector('[data-realtime-results]');
            if (newResults && newResults.innerHTML.trim()) {
                results.innerHTML = newResults.innerHTML;
                history.replaceState(null, '', url);
                attachPaginationHandlers(results);
            }
        })
        .catch(function() {});
}

function attachPaginationHandlers(ctx) {
    if (!ctx) ctx = document;
    ctx.querySelectorAll('a[href*="page="]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            realtimeFetch(this.href);
        });
    });
}

function realtimeFetch(url) {
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function(res) { return res.text(); })
        .then(function(html) {
            var results = document.querySelector('[data-realtime-results]');
            if (!results) return;
            var parser = new DOMParser();
            var doc = parser.parseFromString(html, 'text/html');
            var newResults = doc.querySelector('[data-realtime-results]');
            if (newResults && newResults.innerHTML.trim()) {
                results.innerHTML = newResults.innerHTML;
                history.replaceState(null, '', url);
                attachPaginationHandlers(results);
            }
        })
        .catch(function() {});
}

(function() {
    var filterContainer = document.querySelector('[data-realtime-filter]');
    var resultsContainer = document.querySelector('[data-realtime-results]');
    if (!filterContainer || !resultsContainer) return;

    var debounceTimer;

    function collectParams() {
        var inputs = filterContainer.querySelectorAll('[name]');
        var pairs = [];
        for (var i = 0; i < inputs.length; i++) {
            var el = inputs[i];
            if (el.value) {
                pairs.push(encodeURIComponent(el.name) + '=' + encodeURIComponent(el.value));
            }
        }
        return pairs.join('&');
    }

    function triggerFetch() {
        var params = collectParams();
        var url = window.location.pathname + (params ? '?' + params : '');
        realtimeFetch(url);
    }

    var inputs = filterContainer.querySelectorAll('[name]');
    for (var i = 0; i < inputs.length; i++) {
        var input = inputs[i];
        var tag = input.tagName.toLowerCase();
        var type = (input.getAttribute('type') || '').toLowerCase();
        if (tag === 'input' && (type === 'text' || type === 'search')) {
            input.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(triggerFetch, 400);
            });
        } else {
            input.addEventListener('change', triggerFetch);
        }
    }

    attachPaginationHandlers(resultsContainer);
})();

// ── Sort Buttons ──
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.sort-btn');
    if (!btn) return;
    var sort = btn.getAttribute('data-sort');
    var container = btn.closest('[data-realtime-filter]');
    if (!container) return;
    var hidden = container.querySelector('input[name="sort"]');
    if (!hidden) return;
    hidden.value = sort;
    container.querySelectorAll('.sort-btn').forEach(function(b) {
        var isActive = b.getAttribute('data-sort') === sort;
        b.className = b.className
            .replace(/\bbg-adm-dark\b/g, '')
            .replace(/\btext-adm-on-primary\b/g, '')
            .replace(/\bbg-adm-surface-low\b/g, '')
            .replace(/\btext-adm-on-surface-variant\b/g, '')
            .replace(/\bhover:bg-adm-surface-high\b/g, '');
        if (isActive) {
            b.classList.add('bg-adm-dark', 'text-adm-on-primary');
        } else {
            b.classList.add('bg-adm-surface-low', 'text-adm-on-surface-variant', 'hover:bg-adm-surface-high');
        }
    });
    var params = [];
    container.querySelectorAll('[name]').forEach(function(el) {
        if (el.value) {
            params.push(encodeURIComponent(el.name) + '=' + encodeURIComponent(el.value));
        }
    });
    var url = window.location.pathname + (params.length ? '?' + params.join('&') : '');
    realtimeFetch(url);
});

// ── Search Functionality ──
(function() {
    var searchInput = document.getElementById('admin-search-input');
    if (!searchInput) return;

    function performSearch(term) {
        term = term.trim().toLowerCase();
        if (!term) return;

        var map = [
            { keywords: ['pengguna', 'user', 'player', 'orang'], url: '{{ route("admin.users") }}' },
            { keywords: ['pemilik', 'owner'], url: '{{ route("admin.owners") }}' },
            { keywords: ['lapangan', 'field', 'stadion', 'stadium'], url: '{{ route("admin.fields") }}' },
            { keywords: ['pesanan', 'booking', 'order', 'sewa'], url: '{{ route("admin.bookings") }}' },
            { keywords: ['pembayaran', 'payment', 'bayar', 'transaksi'], url: '{{ route("admin.payments") }}' },
            { keywords: ['komunitas', 'community', 'group', 'grup'], url: '{{ route("admin.communities") }}' },
            { keywords: ['sistem', 'system', 'server', 'status'], url: '{{ route("admin.system") }}' },
            { keywords: ['laporan', 'report', 'analitik', 'analytics'], url: '{{ route("admin.reports") }}' },
            { keywords: ['pengaturan', 'setting', 'konfigurasi'], url: '{{ route("admin.settings") }}' },
        ];

        for (var i = 0; i < map.length; i++) {
            for (var j = 0; j < map[i].keywords.length; j++) {
                if (map[i].keywords[j].includes(term)) {
                    window.location.href = map[i].url;
                    return;
                }
            }
        }

        window.location.href = '{{ route("admin.dashboard") }}?q=' + encodeURIComponent(term);
    }

    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            performSearch(searchInput.value);
        }
    });
})();
</script>
</body>
</html>
