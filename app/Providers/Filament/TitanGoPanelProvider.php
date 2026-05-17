<?php

namespace App\Providers\Filament;

use App\Filament\TitanGo\Pages\Dashboard;
use App\Filament\TitanGo\Widgets\CleanerDashboardWidget;
use App\Support\OrganizationBrandingResolver;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

use App\Providers\Filament\Concerns\RegistersFilamentPlugins;

class TitanGoPanelProvider extends PanelProvider
{
    use RegistersFilamentPlugins;
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('titango')
            ->path('titango')
            ->brandName(fn () => app(OrganizationBrandingResolver::class)->panelName('TitanGo'))
            ->brandLogo(fn () => app(OrganizationBrandingResolver::class)->current()['logo_url'] ?? null)
            ->favicon(fn () => app(OrganizationBrandingResolver::class)->current()['favicon_url'] ?? null)
            ->colors(fn (): array => [
                'primary' => app(OrganizationBrandingResolver::class)->primaryColor('#f97316'),
            ])
            ->plugins([
                ...$this->breezyPlugin(),
                ...$this->availablePlugins([
                    'BezhanSalleh\FilamentShield\FilamentShieldPlugin',
                    'Modules\TitanGoField\Filament\Plugin\TitanGoFieldPlugin',
                ]),
            ])
            ->discoverPages(in: app_path('Filament/TitanGo/Pages'), for: 'App\\Filament\\TitanGo\\Pages')
            ->discoverPages(in: base_path('Modules/TitanGoField/Filament/Pages'), for: 'Modules\\TitanGoField\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/TitanGo/Widgets'), for: 'App\\Filament\\TitanGo\\Widgets')
            ->widgets([
                CleanerDashboardWidget::class,
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
            // Mount the UI Inspector and Titan OS shell hooks for this panel
            ->renderHook(...$this->uiInspectorHook())
            ->renderHook(...$this->titanOsShellHooks());
    }
}
