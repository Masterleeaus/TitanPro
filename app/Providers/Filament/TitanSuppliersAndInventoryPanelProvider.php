<?php

namespace App\Providers\Filament;

use App\Filament\TitanSuppliersAndInventory\Pages\Dashboard;
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
 * TITAN SUPPLIERS & INVENTORY launcher panel scaffold.
 * Suppliers, Stock & Purchasing
 */
class TitanSuppliersAndInventoryPanelProvider extends PanelProvider
{
    use RegistersFilamentPlugins;

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('titaninventory')
            ->path('titaninventory')
            ->brandName(fn () => app(OrganizationBrandingResolver::class)->panelName('Titan Suppliers & Inventory'))
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
                    'Modules\SupplyChain\Filament\Plugin\SupplyChainPlugin',
                ]),
            ])
            ->discoverResources(in: app_path('Filament/TitanSuppliersAndInventory/Resources'), for: 'App\Filament\TitanSuppliersAndInventory\Resources')
            ->discoverPages(in: app_path('Filament/TitanSuppliersAndInventory/Pages'), for: 'App\Filament\TitanSuppliersAndInventory\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/TitanSuppliersAndInventory/Widgets'), for: 'App\Filament\TitanSuppliersAndInventory\Widgets')
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
