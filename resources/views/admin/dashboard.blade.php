@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FFFEF0] to-[#FFF6D7] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-[#00004D] mb-2">Admin Dashboard</h1>
            <p class="text-gray-600">Selamat datang di panel admin Spies Sport</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Total Users Card -->
            <div class="bg-white rounded-lg border-l-4 border-[#FED56F] shadow-md hover:shadow-lg transition-shadow duration-300 p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Users</p>
                        <p class="text-3xl font-bold text-[#EB5436] mt-2">{{ $totalUsers }}</p>
                    </div>
                    <div class="bg-[#FED56F] rounded-full p-3">
                        <svg class="w-6 h-6 text-[#00004D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Owners Card -->
            <div class="bg-white rounded-lg border-l-4 border-[#FED56F] shadow-md hover:shadow-lg transition-shadow duration-300 p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Owners</p>
                        <p class="text-3xl font-bold text-[#EB5436] mt-2">{{ $totalOwners }}</p>
                    </div>
                    <div class="bg-[#FED56F] rounded-full p-3">
                        <svg class="w-6 h-6 text-[#00004D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Players Card -->
            <div class="bg-white rounded-lg border-l-4 border-[#FED56F] shadow-md hover:shadow-lg transition-shadow duration-300 p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Players</p>
                        <p class="text-3xl font-bold text-[#EB5436] mt-2">{{ $totalPlayers }}</p>
                    </div>
                    <div class="bg-[#FED56F] rounded-full p-3">
                        <svg class="w-6 h-6 text-[#00004D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Fields Card -->
            <div class="bg-white rounded-lg border-l-4 border-[#FED56F] shadow-md hover:shadow-lg transition-shadow duration-300 p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Fields</p>
                        <p class="text-3xl font-bold text-[#EB5436] mt-2">{{ $totalFields }}</p>
                    </div>
                    <div class="bg-[#FED56F] rounded-full p-3">
                        <svg class="w-6 h-6 text-[#00004D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Bookings Card -->
            <div class="bg-white rounded-lg border-l-4 border-[#FED56F] shadow-md hover:shadow-lg transition-shadow duration-300 p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Bookings</p>
                        <p class="text-3xl font-bold text-[#EB5436] mt-2">{{ $totalBookings }}</p>
                    </div>
                    <div class="bg-[#FED56F] rounded-full p-3">
                        <svg class="w-6 h-6 text-[#00004D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Matches Card -->
            <div class="bg-white rounded-lg border-l-4 border-[#FED56F] shadow-md hover:shadow-lg transition-shadow duration-300 p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Matches</p>
                        <p class="text-3xl font-bold text-[#EB5436] mt-2">{{ $totalMatches }}</p>
                    </div>
                    <div class="bg-[#FED56F] rounded-full p-3">
                        <svg class="w-6 h-6 text-[#00004D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Alert -->
        <div class="bg-[#FFF6D7] border-l-4 border-[#EB5436] rounded-lg p-6 mb-8">
            <p class="text-[#00004D] font-medium">
                ℹ️ Admin panel ini dilindungi dengan role-based middleware. Hanya user dengan role 'admin' yang dapat mengakses area ini.
            </p>
        </div>
    </div>
</div>
@endsection
