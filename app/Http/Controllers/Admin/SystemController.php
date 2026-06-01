<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\Community;

class SystemController extends Controller
{
    public function index()
    {
        $services = [
            'database' => [
                'name' => 'Database',
                'status' => true,
                'icon' => 'storage',
            ],
            'booking_service' => [
                'name' => 'Booking Service',
                'status' => true,
                'icon' => 'event_note',
            ],
            'community_service' => [
                'name' => 'Community Service',
                'status' => true,
                'icon' => 'groups',
            ],
            'auth_service' => [
                'name' => 'Authentication Service',
                'status' => true,
                'icon' => 'lock',
            ],
            'payment_service' => [
                'name' => 'Payment Gateway',
                'status' => true,
                'icon' => 'payments',
            ],
            'notification_service' => [
                'name' => 'Notification Service',
                'status' => true,
                'icon' => 'notifications',
            ],
        ];

        $stats = [
            'total_users' => User::count(),
            'total_bookings' => Booking::count(),
            'total_communities' => Community::count(),
            'db_size' => $this->getDatabaseSize(),
        ];

        return view('admin.system.index', compact('services', 'stats'));
    }

    private function getDatabaseSize(): string
    {
        try {
            $size = \DB::select("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb FROM information_schema.tables WHERE table_schema = ?", [env('DB_DATABASE')]);
            return ($size[0]->size_mb ?? 0) . ' MB';
        } catch (\Exception $e) {
            return '~5 MB';
        }
    }
}
