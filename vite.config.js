import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/player-dashboard.css',
                'resources/css/player-profile.css',
                'resources/css/player-profile-view.css',
                'resources/css/player-history.css',
                'resources/js/app.js',
                'resources/js/player-dashboard.js',
                'resources/js/player-history.js',
            ],
            refresh: true,
        }),
    ],
});
