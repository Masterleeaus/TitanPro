<?php

namespace App\Providers\Filament;

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
 * TitanSolo — Solo operator dashboard panel.
 */
class TitanSoloPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('titansolo')
            ->path('titansolo')
            ->brandName('TitanSolo — Solo Ops')
            ->colors([
                'primary' => Color::Sky,
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
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/TitanSolo/Widgets'), for: 'App\\Filament\\TitanSolo\\Widgets')
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
            ]);
    }

    /**
     * Return a configured BreezyCore plugin array (empty array when package is absent).
     *
     * @return array<int, object>
     */
    private function breezyPlugin(): array
    {
        if (! class_exists(\Jeffgreco13\FilamentBreezy\BreezyCore::class)) {
            return [];
        }

        return [
            \Jeffgreco13\FilamentBreezy\BreezyCore::make()
                ->myProfile(
                    shouldRegisterUserMenu: true,
                    shouldRegisterNavigation: false,
                    hasAvatars: false,
                    slug: 'my-profile',
                )
                ->enableTwoFactorAuthentication(),
        ];
    }

    /**
     * Register optional plugins without breaking the panel if a package is absent.
     *
     * @param  array<int, class-string>  $pluginClasses
     * @return array<int, object>
     */
    private function availablePlugins(array $pluginClasses): array
    {
        $plugins = [];

        foreach ($pluginClasses as $pluginClass) {
            if (! class_exists($pluginClass) || ! method_exists($pluginClass, 'make')) {
                continue;
            }

            $plugins[] = $pluginClass::make();
        }

        return $plugins;
    }
}
