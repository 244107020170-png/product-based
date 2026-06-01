<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SPIES SPORT Admin Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@600;700;800&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    @stack('styles')
</head>
<body class="bg-adm-bg text-adm-on-surface font-adm-body min-h-screen">

@php
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
@endphp

<!-- SideNavBar -->
<aside class="fixed left-0 top-0 h-full z-50 py-5 px-3 w-[240px] bg-adm-surface-lowest border-r border-adm-outline-variant shadow-sm flex flex-col">
    <div class="flex items-center gap-2 mb-6 px-2">
        <div>
            <h1 class="font-adm-headline text-[18px] font-bold text-adm-primary leading-tight">SPIES SPORT</h1>
            <p class="text-[9px] font-adm-body text-adm-outline uppercase tracking-widest leading-tight">Pusat Kontrol Admin</p>
        </div>
    </div>
    <nav class="flex-1 space-y-0.5 custom-scrollbar overflow-y-auto">
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
    <div class="mt-auto pt-4 border-t border-adm-outline-variant">
        <div class="flex items-center gap-2.5 px-1">
            <div class="w-9 h-9 rounded-full bg-adm-primary-container text-white flex items-center justify-center font-bold text-xs flex-shrink-0">
                {{ strtoupper(substr($currentUser->name ?? 'A', 0, 1)) }}
            </div>
            <div class="flex-1 overflow-hidden">
                <p class="font-adm-body text-[13px] font-semibold text-adm-primary truncate leading-tight">{{ $currentUser->name ?? 'Admin' }}</p>
                <p class="text-[11px] text-adm-outline truncate leading-tight">Super Administrator</p>
            </div>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="cursor-pointer">
                <span class="material-symbols-outlined text-[20px] text-adm-outline hover:text-adm-dark">logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        </div>
    </div>
</aside>

<!-- TopAppBar -->
<header class="fixed top-1 left-[240px] right-0 h-[60px] flex items-center justify-between px-6 z-40 bg-adm-surface border-b border-adm-outline-variant">
    <div class="relative w-[360px] ml-20">
        <span class="material-symbols-outlined text-[16px] absolute left-3 inset-y-0 flex items-center text-adm-outline/40">search</span>
        <input class="w-full h-9 pl-9 pr-3 bg-adm-surface-low border border-adm-outline-variant rounded-lg font-adm-body text-[13px] placeholder:text-adm-outline/40 focus:ring-2 focus:ring-adm-secondary-container focus:border-adm-secondary outline-none transition-all" placeholder="Cari data, laporan, atau transaksi..." type="text">
    </div>
    <div class="flex items-center gap-4">
        <div class="relative w-9 h-9 flex items-center justify-center rounded-lg hover:bg-adm-surface-low cursor-pointer transition-colors">
            <span class="material-symbols-outlined text-[20px] text-adm-on-surface-variant">notifications</span>
            <span class="absolute top-1.5 right-1.5 w-[6px] h-[6px] bg-adm-error rounded-full ring-2 ring-adm-surface"></span>
        </div>
        <div class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-adm-surface-low cursor-pointer transition-colors">
            <span class="material-symbols-outlined text-[20px] text-adm-on-surface-variant">help_outline</span>
        </div>
        <div class="flex items-center gap-2 h-9 px-3 bg-adm-surface-low rounded-lg">
            <span class="w-[5px] h-[5px] bg-adm-success rounded-full animate-pulse"></span>
            <span class="text-[11px] font-semibold text-adm-success">Optimal</span>
        </div>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="flex items-center gap-1.5 h-9 px-3 bg-adm-error text-adm-on-primary rounded-lg font-adm-body text-[12px] font-semibold no-underline hover:bg-adm-error/90 transition-all active:scale-95 whitespace-nowrap">
            <span class="material-symbols-outlined text-[15px]">logout</span>
            Keluar
        </a>
    </div>
</header>

<!-- Main Content Canvas -->
<main class="ml-[240px] pt-[60px] p-6">
    <div class="max-w-[1600px] mx-auto">
        {{ $slot ?? '' }}
        @yield('content')
    </div>
</main>

@stack('scripts')
</body>
</html>
