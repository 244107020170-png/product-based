import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // CSS
                'resources/css/admin.css',
                'resources/css/app.css',
                'resources/css/choose-role.css',
                'resources/css/owner-bookings.css',
                'resources/css/owner-dashboard.css',
                'resources/css/owner-form-field.css',
                'resources/css/owner-jadwal-slot.css',
                'resources/css/owner-kelola-booking.css',
                'resources/css/owner-pengaturan.css',
                'resources/css/pages.css',
                'resources/css/pemeliharaanDanKontrol.css',
                'resources/css/player-dashboard.css',
                'resources/css/player-history.css',
                'resources/css/player-profile-view.css',
                'resources/css/player-profile.css',
                'resources/css/register.css',

                // JS
                'resources/js/admin.js',
                'resources/js/app.js',
                'resources/js/auth.js',
                'resources/js/bootstrap.js',
                'resources/js/choose-role.js',
                'resources/js/owner-kelola-booking.js',
                'resources/js/player-dashboard.js',
                'resources/js/player-history.js',
                'resources/js/register.js',
            ],
            refresh: true,
        }),
    ],
});