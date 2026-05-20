<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\AdminLogin;
use App\Filament\Widgets\AdminOverview;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('Spies Sport')
            ->login(AdminLogin::class)
            ->colors([
                'primary' => Color::Red,
            ])
            ->renderHook(PanelsRenderHook::STYLES_AFTER, fn (): string => <<<'HTML'
                <style>
                    body.fi-panel-admin:not(:has(.fi-simple-layout)) {
                        background: #FFF6D7;
                    }

                    body.fi-panel-admin:not(:has(.fi-simple-layout)) .fi-layout,
                    body.fi-panel-admin:not(:has(.fi-simple-layout)) .fi-main,
                    body.fi-panel-admin:not(:has(.fi-simple-layout)) .fi-main-ctn,
                    body.fi-panel-admin:not(:has(.fi-simple-layout)) .fi-page,
                    body.fi-panel-admin:not(:has(.fi-simple-layout)) .fi-topbar,
                    body.fi-panel-admin:not(:has(.fi-simple-layout)) .fi-sidebar,
                    body.fi-panel-admin:not(:has(.fi-simple-layout)) .fi-sidebar-header {
                        background-color: #FFF6D7 !important;
                    }

                    body.fi-panel-admin:not(:has(.fi-simple-layout)) .fi-section,
                    body.fi-panel-admin:not(:has(.fi-simple-layout)) .fi-ta,
                    body.fi-panel-admin:not(:has(.fi-simple-layout)) .fi-wi-stats-overview-stat,
                    body.fi-panel-admin:not(:has(.fi-simple-layout)) .fi-dropdown-panel,
                    body.fi-panel-admin:not(:has(.fi-simple-layout)) .fi-modal-window {
                        background-color: #FFF6D7 !important;
                    }
                </style>
            HTML)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AdminOverview::class,
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
