<?php

namespace App\Providers\Filament;

use App\Filament\TitanSolo\Pages\Dashboard;
use App\Filament\TitanSolo\Widgets\SoloOverviewWidget;
use App\Providers\Filament\Concerns\RegistersFilamentPlugins;
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
 * TitanSolo — Solo operator dashboard panel.
 */
class TitanSoloPanelProvider extends PanelProvider
{
    use RegistersFilamentPlugins;

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('titansolo')
            ->path('titansolo')
            ->brandName(fn () => app(OrganizationBrandingResolver::class)->panelName('TitanSolo'))
            ->brandLogo(fn () => app(OrganizationBrandingResolver::class)->current()['logo_url'] ?? null)
            ->favicon(fn () => app(OrganizationBrandingResolver::class)->current()['favicon_url'] ?? null)
            ->colors(fn (): array => [
                'primary' => app(OrganizationBrandingResolver::class)->primaryColor('#0ea5e9'),
            ])
            ->plugins([
                ...$this->breezyPlugin(),
                ...$this->availablePlugins([
                    'BezhanSalleh\\FilamentShield\\FilamentShieldPlugin',
                    'LaraZeus\\DynamicDashboard\\DynamicDashboardPlugin',
                ]),
            ])
            ->discoverResources(in: app_path('Filament/TitanSolo/Resources'), for: 'App\\Filament\\TitanSolo\\Resources')
            ->discoverPages(in: app_path('Filament/TitanSolo/Pages'), for: 'App\\Filament\\TitanSolo\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/TitanSolo/Widgets'), for: 'App\\Filament\\TitanSolo\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                SoloOverviewWidget::class,
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
            ->renderHook(...$this->uiOverrideSsrHook())
            ->renderHook(...$this->uiInspectorHook());
    }
}
