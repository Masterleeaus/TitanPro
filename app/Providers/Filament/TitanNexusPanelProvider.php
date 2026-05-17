<?php

namespace App\Providers\Filament;

use App\Providers\Filament\Concerns\RegistersFilamentPlugins;
use App\Support\OrganizationBrandingResolver;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class TitanNexusPanelProvider extends PanelProvider
{
    use RegistersFilamentPlugins;

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('titannexus')
            ->path('titannexus')
            ->brandName(fn () => app(OrganizationBrandingResolver::class)->panelName('TitanNexus'))
            ->brandLogo(fn () => app(OrganizationBrandingResolver::class)->current()['logo_url'] ?? null)
            ->favicon(fn () => app(OrganizationBrandingResolver::class)->current()['favicon_url'] ?? null)
            ->colors(fn (): array => [
                'primary' => app(OrganizationBrandingResolver::class)->primaryColor('#6366f1'),
            ])
            ->plugins([
                ...$this->breezyPlugin(),
                ...$this->availablePlugins([
                    'BezhanSalleh\\FilamentShield\\FilamentShieldPlugin',
                    'AlizHarb\\ActivityLog\\ActivityLogPlugin',
                    'Relaticle\\Flowforge\\FilamentFlowforgePlugin',
                    'LaraZeus\\DynamicDashboard\\DynamicDashboardPlugin',
                    'Leandrocfe\\FilamentApexCharts\\FilamentApexChartsPlugin',
                    'Shreejan\\DashArrange\\DashArrangePlugin',
                    'Pxlrbt\\FilamentSpotlight\\SpotlightPlugin',
                ]),
            ])

            // Resource-only panel surface.
            // Do not auto-discover custom Pages here. Old Page navigation was producing
            // missing route names such as filament.titannexus.pages.booking-handoffs.
            ->discoverResources(
                in: app_path('Filament/TitanNexus/Resources'),
                for: 'App\\Filament\\TitanNexus\\Resources'
            )
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(
                in: app_path('Filament/TitanNexus/Widgets'),
                for: 'App\\Filament\\TitanNexus\\Widgets'
            )
            ->widgets([
                Widgets\AccountWidget::class,
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
            ])
            ->renderHook(...$this->uiInspectorHook())
            ->renderHook(...$this->titanOsShellHooks());
    }
}
