<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'platform_name' => config('app.name', 'SPIES SPORT'),
            'email_support' => 'support@spiessport.com',
            'whatsapp_support' => '6281234567890',
            'address' => 'Jakarta, Indonesia',
            'maintenance_mode' => false,
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'platform_name' => 'required|string|max:255',
            'email_support' => 'required|email|max:255',
            'whatsapp_support' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // Update .env or settings table in production
        // For now, store in session flash
        return back()->with('success', 'Pengaturan platform berhasil diperbarui.');
    }
}
