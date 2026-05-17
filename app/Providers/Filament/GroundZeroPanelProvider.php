<?php

namespace App\Providers\Filament;

use App\Filament\GroundZero\Pages\Actions;
use App\Filament\GroundZero\Pages\Agents;
use App\Filament\GroundZero\Pages\Dashboard;
use App\Filament\GroundZero\Pages\Memory;
use App\Filament\GroundZero\Pages\Timeline;
use App\Filament\GroundZero\Pages\TitanWorkControlPanel;
use App\Http\Middleware\CheckSubscription;
use App\Providers\Filament\Concerns\RegistersFilamentPlugins;
use App\Support\OrganizationBrandingResolver;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
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

                    'BezhanSalleh\FilamentShield\FilamentShieldPlugin',
                    'AlizHarb\ActivityLog\ActivityLogPlugin',
                    'Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin',
                    'LaraZeus\DynamicDashboard\DynamicDashboardPlugin',
                    'Shreejan\DashArrange\DashArrangePlugin',
                    'Pxlrbt\FilamentSpotlight\SpotlightPlugin',
                ]),
            ])
            ->pages([
                Dashboard::class,
                TitanWorkControlPanel::class,
                Timeline::class,
                Agents::class,
                Memory::class,
                Actions::class,
            ])
            ->widgets([])
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
            ->renderHook(...$this->uiInspectorHook())
            ->renderHook(...$this->titanOsShellHooks());
    }
}
