<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Field;
use App\Models\Matchs;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Ringkasan Sistem';

    protected ?string $description = 'Monitoring cepat untuk user, lapangan, booking, dan aktivitas match.';

    protected function getStats(): array
    {
        return [
            Stat::make('Total User', User::count())
                ->description('Admin, owner, dan player')
                ->icon('heroicon-o-users')
                ->color('info'),
            Stat::make('Lapangan Menunggu', Field::where('verification_status', 'pending')->count())
                ->description('Perlu diverifikasi admin')
                ->icon('heroicon-o-clipboard-document-check')
                ->color('warning'),
            Stat::make('Booking Aktif', Booking::whereIn('status', ['pending', 'confirmed'])->count())
                ->description('Pending dan confirmed')
                ->icon('heroicon-o-calendar-days')
                ->color('success'),
            Stat::make('Match Terjadwal', Matchs::where('date', '>=', now()->toDateString())->count())
                ->description('Aktivitas cari tim mendatang')
                ->icon('heroicon-o-user-group')
                ->color('danger'),
        ];
    }
}
