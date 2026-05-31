{{-- NAVBAR / SIDEBAR OWNER --}}

{{-- MOBILE SIDEBAR TOGGLE --}}
<button id="sidebarToggle" class="sidebar-toggle-btn" onclick="toggleOwnerSidebar()" aria-label="Toggle sidebar">
    <i class="fa-solid fa-bars"></i>
</button>

{{-- SIDEBAR OVERLAY --}}
<div id="sidebarOverlay" class="sidebar-overlay" onclick="toggleOwnerSidebar()"></div>

<aside class="sidebar">
    
    {{-- LOGO --}}
    <div>
        <a href="/owner/dashboard" class="sidebar-logo" style="text-decoration:none;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:12px 0;">
            <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo" style="display:block;">
            <p style="text-align:center;margin:5px 0 0 0;">Portal Pemilik</p>
        </a>

        <nav class="sidebar-menu">

            <a href="/owner/dashboard" class="menu-item {{ Request::is('owner/dashboard*') ? 'active' : '' }}">
                <i class="fa-solid fa-table-columns"></i>
                <span>Beranda</span>
            </a>

            <a href="/owner/kelolaLapangan" class="menu-item {{ Request::is('owner/kelolaLapangan*') ? 'active' : '' }}">
                <i class="fa-solid fa-person-running"></i>
                <span>Kelola Lapangan</span>
            </a>

            <a href="/owner/jadwalDanSlot" class="menu-item {{ Request::is('owner/jadwalDanSlot*') ? 'active' : '' }}">
                <i class="fa-regular fa-calendar"></i>
                <span>Jadwal dan Slot</span>
            </a>

            <a href="/owner/kelolaBooking" class="menu-item {{ Request::is('owner/kelolaBooking*') ? 'active' : '' }}">
                <i class="fa-solid fa-ticket"></i>
                <span>Kelola Pesanan</span>
            </a>

            <a href="/owner/promosiDiskon" class="menu-item {{ Request::is('owner/promosiDiskon*') ? 'active' : '' }}">
                <i class="fa-solid fa-percent"></i>
                <span>Promosi dan Diskon</span>
            </a>

            <a href="/owner/pemeliharaanKontrol" class="menu-item {{ Request::is('owner/pemeliharaanKontrol*') ? 'active' : '' }}">
                <i class="fa-solid fa-screwdriver-wrench"></i>
                <span>Pemeliharaan Kontrol</span>
            </a>



        </nav>
    </div>


    {{-- BOTTOM MENU --}}
    <div class="sidebar-bottom">

        <a href="#" class="menu-item">
            <i class="fa-solid fa-gear"></i>
            <span>Pengaturan</span>
        </a>

        <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
            @csrf
        </form>
        <a href="#" class="menu-item logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Keluar</span>
        </a>

    </div>

</aside>

<script>
function toggleOwnerSidebar() {
    document.querySelector('.sidebar').classList.toggle('is-open');
    document.getElementById('sidebarOverlay').classList.toggle('is-visible');
}
</script>
