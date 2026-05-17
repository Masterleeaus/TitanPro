<?php

namespace App\Providers\Filament;

use App\Filament\SiteInspectorAssistant\Pages\Dashboard;
use App\Providers\Filament\Concerns\RegistersFilamentPlugins;
use App\Support\OrganizationBrandingResolver;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * SITE INSPECTOR ASSISTANT launcher panel scaffold.
 * Camera-Based Job Completion Checks
 */
class SiteInspectorAssistantPanelProvider extends PanelProvider
{
    use RegistersFilamentPlugins;

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('siteinspectorassistant')
            ->path('siteinspectorassistant')
            ->brandName(fn () => app(OrganizationBrandingResolver::class)->panelName('Site Inspector Assistant'))
            ->brandLogo(fn () => app(OrganizationBrandingResolver::class)->current()['logo_url'] ?? null)
            ->favicon(fn () => app(OrganizationBrandingResolver::class)->current()['favicon_url'] ?? null)
            ->colors(fn (): array => [
                'primary' => app(OrganizationBrandingResolver::class)->primaryColor('#2563eb'),
            ])
            ->plugins([
                ...$this->breezyPlugin(),
                ...$this->availablePlugins([
                    'BezhanSalleh\FilamentShield\FilamentShieldPlugin',
                    'Pxlrbt\FilamentSpotlight\SpotlightPlugin',
                    'BezhanSalleh\PanelSwitch\PanelSwitchPlugin',
                ]),
            ])
            ->discoverResources(in: app_path('Filament/SiteInspectorAssistant/Resources'), for: 'App\Filament\SiteInspectorAssistant\Resources')
            ->discoverPages(in: app_path('Filament/SiteInspectorAssistant/Pages'), for: 'App\Filament\SiteInspectorAssistant\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/SiteInspectorAssistant/Widgets'), for: 'App\Filament\SiteInspectorAssistant\Widgets')
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
