<?php

namespace App\Providers\Filament;

use App\Filament\ZeroPay\Pages\Dashboard;
use App\Filament\ZeroPay\Pages\StripeSettings;
use App\Filament\ZeroPay\Widgets\FinanceOverviewWidget;
use App\Providers\Filament\Concerns\RegistersFilamentPlugins;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * ZeroPay — Payments and invoicing panel.
 *
 * Accessible to users with the owner, admin, or bookkeeper role.
 * Provides invoice management, payment recording, and Stripe integration settings.
 */
class ZeroPayPanelProvider extends PanelProvider
{
    use RegistersFilamentPlugins;

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('zeropay')
            ->path('zeropay')
            ->brandName('ZeroPay')
            ->colors([
                'primary' => Color::Violet,
            ])
            ->plugins([
                ...$this->breezyPlugin(),
                ...$this->availablePlugins([
                    'BezhanSalleh\\FilamentShield\\FilamentShieldPlugin',
                ]),
            ])
            ->discoverResources(in: app_path('Filament/ZeroPay/Resources'), for: 'App\\Filament\\ZeroPay\\Resources')
            ->discoverPages(in: app_path('Filament/ZeroPay/Pages'), for: 'App\\Filament\\ZeroPay\\Pages')
            ->pages([
                Dashboard::class,
                StripeSettings::class,
            ])
            ->discoverWidgets(in: app_path('Filament/ZeroPay/Widgets'), for: 'App\\Filament\\ZeroPay\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                FinanceOverviewWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
