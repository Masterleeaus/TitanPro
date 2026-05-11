<?php

namespace App\Providers\Filament;

use App\Providers\Filament\Concerns\RegistersFilamentPlugins;
use App\Filament\TitanGo\Pages\Dashboard;
use App\Filament\TitanGo\Widgets\ActiveJobsWidget;
use App\Filament\TitanGo\Widgets\PwaPreviewBridgeWidget;
use App\Filament\TitanGo\Widgets\SyncHealthWidget;
use App\Filament\TitanGo\Widgets\TechnicianActivityChartWidget;
use App\Filament\TitanGo\Widgets\TechnicianActivityWidget;
use App\Filament\TitanGo\Widgets\TitanGoDashboardWidget;
use App\Filament\Widgets\CleanerLiveMap;
use App\Support\OrganizationBrandingResolver;
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
 * TitanGo — Technician PWA bridge panel.
 *
 * Provides a lightweight Filament panel for field technicians and admin preview
 * users who need access to the technician PWA experience at /titango.
 */
class TitanGoPanelProvider extends PanelProvider
{
    use RegistersFilamentPlugins;

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('titango')
            ->path('titango')
            ->brandName(fn () => app(OrganizationBrandingResolver::class)->panelName('TitanGo — Field Ops'))
            ->brandLogo(fn () => app(OrganizationBrandingResolver::class)->current()['logo_url'] ?? null)
            ->favicon(fn () => app(OrganizationBrandingResolver::class)->current()['favicon_url'] ?? null)
            ->colors(fn (): array => [
                'primary' => app(OrganizationBrandingResolver::class)->primaryColor('#f97316'),
            ])
            ->plugins([
                ...$this->breezyPlugin(),
                ...$this->availablePlugins([
                    'BezhanSalleh\\FilamentShield\\FilamentShieldPlugin',
                    'Leandrocfe\\FilamentApexCharts\\FilamentApexChartsPlugin',
                    'LaraZeus\\DynamicDashboard\\DynamicDashboardPlugin',
                ]),
            ])
            ->discoverResources(in: app_path('Filament/TitanGo/Resources'), for: 'App\\Filament\\TitanGo\\Resources')
            ->discoverPages(in: app_path('Filament/TitanGo/Pages'), for: 'App\\Filament\\TitanGo\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/TitanGo/Widgets'), for: 'App\\Filament\\TitanGo\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                TitanGoDashboardWidget::class,
                TechnicianActivityWidget::class,
                TechnicianActivityChartWidget::class,
                ActiveJobsWidget::class,
                CleanerLiveMap::class,
                SyncHealthWidget::class,
                PwaPreviewBridgeWidget::class,
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
            ->renderHook(...$this->uiInspectorHook());
    }
}
