<?php

namespace App\Providers\Filament;

use App\Filament\Pages\UiStudio;
use App\Filament\GroundZero\Widgets\JobsByStatusChartWidget;
use App\Http\Middleware\CheckSubscription;
use App\Providers\Filament\Concerns\RegistersFilamentPlugins;
use App\Support\OrganizationBrandingResolver;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
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
 * GroundZero — Primary owner/admin panel for day-to-day cleaning business operations.
 *
 * Accessible to: owner, admin, dispatcher, bookkeeper.
 * Subscription-gated: CheckSubscription middleware enforces active subscription for owners/admins.
 */
class GroundZeroPanelProvider extends PanelProvider
{
    use RegistersFilamentPlugins;

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('groundzero')
            ->path('groundzero')
            ->brandName(fn () => app(OrganizationBrandingResolver::class)->panelName('GroundZero'))
            ->brandLogo(fn () => app(OrganizationBrandingResolver::class)->current()['logo_url'] ?? null)
            ->favicon(fn () => app(OrganizationBrandingResolver::class)->current()['favicon_url'] ?? null)
            ->colors(fn (): array => [
                'primary' => app(OrganizationBrandingResolver::class)->primaryColor('#06b6d4'),
            ])
            ->plugins([
                ...$this->breezyPlugin(),
                ...$this->availablePlugins([
                    'BezhanSalleh\\FilamentShield\\FilamentShieldPlugin',
                    'Leandrocfe\\FilamentApexCharts\\FilamentApexChartsPlugin',
                    'LaraZeus\\DynamicDashboard\\DynamicDashboardPlugin',
                ]),
            ])
            ->discoverResources(in: app_path('Filament/GroundZero/Resources'), for: 'App\\Filament\\GroundZero\\Resources')
            ->discoverPages(in: app_path('Filament/GroundZero/Pages'), for: 'App\\Filament\\GroundZero\\Pages')
            ->pages([
                Pages\Dashboard::class,
                UiStudio::class,
            ])
            ->discoverWidgets(in: app_path('Filament/GroundZero/Widgets'), for: 'App\\Filament\\GroundZero\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                JobsByStatusChartWidget::class,
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
                CheckSubscription::class,
            ])
            ->renderHook(...$this->uiInspectorHook());
    }
}
