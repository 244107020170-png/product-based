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
            ->brandLogo(fn () => request()->routeIs('filament.admin.auth.login') || str_contains(request()->path(), 'admin/login') ? asset('assets/images/logo/logo.png') : null)
            ->brandLogoHeight('60px')
            ->login(AdminLogin::class)
            ->colors([
                'primary' => '#00004D',
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): \Illuminate\Support\HtmlString => new \Illuminate\Support\HtmlString('
                    <style>
                        .fi-simple-layout {
                            background-image: url("' . asset('assets/images/bg/login admin bg.png') . '") !important;
                            background-size: cover !important;
                            background-position: center !important;
                            background-repeat: no-repeat !important;
                            min-height: 100vh !important;
                        }
                        
                        .fi-simple-main,
                        .fi-simple-layout main,
                        .fi-simple-main-card {
                            background-color: rgba(255, 255, 255, 0.95) !important;
                            backdrop-filter: blur(10px) !important;
                            border-radius: 1.5rem !important;
                            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
                            border: 1px solid rgba(0, 0, 77, 0.08) !important;
                            padding: 2.5rem !important;
                        }

                        .fi-btn.fi-color-primary,
                        .fi-btn-color-primary,
                        button[type="submit"].fi-btn {
                            background-color: #00004D !important;
                            color: #ffffff !important;
                        }
                        .fi-btn.fi-color-primary:hover,
                        .fi-btn-color-primary:hover,
                        button[type="submit"].fi-btn:hover {
                            background-color: #000022 !important;
                            color: #ffffff !important;
                        }
                    </style>
                ')
            )
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
