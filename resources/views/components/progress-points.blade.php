@props(['user' => auth()->user()])

@php
    $currentPoints = $user->points ?? 0;
    $nextTierTarget = $user->nextTierTarget();
    $pointsNeeded = $user->pointsNeeded();
    $tierName = $user->tierName();
    $tierColor = $user->tierColor();
    $progressPercentage = $user->progressPercentage();
@endphp

<div class="w-full">
    <!-- Progress Card Container -->
    <div class="bg-gradient-to-r from-slate-50 to-slate-100 rounded-2xl p-6 md:p-8 shadow-sm border border-slate-200">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <!-- Points Display -->
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full" 
                         style="background-color: {{ $tierColor }}20; border: 2px solid {{ $tierColor }};">
                        <span class="text-2xl font-bold" style="color: {{ $tierColor }};">
                            {{ $currentPoints }}
                        </span>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-600">Total Points</p>
                    <p class="text-lg md:text-xl font-bold text-slate-900">{{ $tierName }}</p>
                </div>
            </div>

            <!-- Tier Badge -->
            <div class="flex items-center justify-center md:justify-end">
                <div class="px-4 py-2 rounded-full text-sm font-semibold"
                     style="background-color: {{ $tierColor }}20; color: {{ $tierColor }}; border: 1px solid {{ $tierColor }};">
                    {{ $tierName }} Tier
                </div>
            </div>
        </div>

        <!-- Progress Bar Section -->
        <div class="space-y-3">
            <!-- Progress Labels -->
            <div class="flex justify-between items-center">
                <p class="text-xs md:text-sm font-medium text-slate-700">
                    Butuh <span class="font-bold" style="color: {{ $tierColor }};">{{ $pointsNeeded }} poin</span> lagi
                </p>
                <p class="text-xs md:text-sm font-medium text-slate-600">
                    Target: <span class="font-bold">{{ $nextTierTarget }}</span> pts
                </p>
            </div>

            <!-- Progress Bar -->
            <div class="w-full h-3 md:h-4 bg-slate-300 rounded-full overflow-hidden shadow-inner">
                <div class="h-full rounded-full transition-all duration-500 ease-out"
                     style="width: {{ $progressPercentage }}%; background: linear-gradient(90deg, {{ $tierColor }}, {{ $tierColor }}dd);">
                </div>
            </div>

            <!-- Motivational Message -->
            <p class="text-sm md:text-base font-medium text-slate-700 text-center md:text-left">
                <span class="text-slate-600">Semangat! </span>
                <span style="color: {{ $tierColor }};">{{ $pointsNeeded > 0 ? 'Kamu hampir mencapai ' . $nextTierTarget . ' poin!' : 'Kamu sudah di tier maksimal!' }}</span>
            </p>
        </div>

        <!-- Tier Progress Indicators (Mobile Responsive) -->
        <div class="mt-6 grid grid-cols-4 gap-2 md:gap-3">
            <!-- Beginner (0-20) -->
            <div class="flex flex-col items-center">
                <div class="w-full aspect-square rounded-lg mb-2 transition-all"
                     style="background-color: {{ $currentPoints >= 20 ? '#6b7280' : '#d1d5db' }}; opacity: {{ $currentPoints >= 0 ? 1 : 0.5 }};">
                    <div class="flex items-center justify-center h-full">
                        <span class="text-xs md:text-sm font-bold text-white">1</span>
                    </div>
                </div>
                <p class="text-xs text-center text-slate-700 font-semibold">Beginner</p>
                <p class="text-xs text-center text-slate-500">0-20</p>
            </div>

            <!-- Pro (20-50) -->
            <div class="flex flex-col items-center">
                <div class="w-full aspect-square rounded-lg mb-2 transition-all"
                     style="background-color: {{ $currentPoints >= 50 ? '#1d6fcf' : '#bfdbfe' }}; opacity: {{ $currentPoints >= 20 ? 1 : 0.5 }};">
                    <div class="flex items-center justify-center h-full">
                        <span class="text-xs md:text-sm font-bold text-white">2</span>
                    </div>
                </div>
                <p class="text-xs text-center text-slate-700 font-semibold">Pro</p>
                <p class="text-xs text-center text-slate-500">20-50</p>
            </div>

            <!-- Master (50-100) -->
            <div class="flex flex-col items-center">
                <div class="w-full aspect-square rounded-lg mb-2 transition-all"
                     style="background-color: {{ $currentPoints >= 100 ? '#7c3aed' : '#e9d5ff' }}; opacity: {{ $currentPoints >= 50 ? 1 : 0.5 }};">
                    <div class="flex items-center justify-center h-full">
                        <span class="text-xs md:text-sm font-bold text-white">3</span>
                    </div>
                </div>
                <p class="text-xs text-center text-slate-700 font-semibold">Master</p>
                <p class="text-xs text-center text-slate-500">50-100</p>
            </div>

            <!-- Champion (100+) -->
            <div class="flex flex-col items-center">
                <div class="w-full aspect-square rounded-lg mb-2 transition-all"
                     style="background-color: {{ $currentPoints >= 100 ? '#fbbf24' : '#fef3c7' }}; opacity: {{ $currentPoints >= 100 ? 1 : 0.5 }};">
                    <div class="flex items-center justify-center h-full">
                        <span class="text-xs md:text-sm font-bold {{ $currentPoints >= 100 ? 'text-slate-900' : 'text-slate-400' }}">★</span>
                    </div>
                </div>
                <p class="text-xs text-center text-slate-700 font-semibold">Champion</p>
                <p class="text-xs text-center text-slate-500">100+</p>
            </div>
        </div>
    </div>
</div>
