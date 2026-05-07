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
 * ZeroFuss — Customer self-service portal panel.
 */
class ZeroFussPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('zerofuss')
            ->path('zerofuss')
            ->brandName('ZeroFuss — Customer Portal')
            ->colors([
                'primary' => Color::Teal,
            ])
            ->plugins([
                ...$this->breezyPlugin(),
            ])
            ->discoverResources(in: app_path('Filament/ZeroFuss/Resources'), for: 'App\\Filament\\ZeroFuss\\Resources')
            ->discoverPages(in: app_path('Filament/ZeroFuss/Pages'), for: 'App\\Filament\\ZeroFuss\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/ZeroFuss/Widgets'), for: 'App\\Filament\\ZeroFuss\\Widgets')
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
     * Customers can manage their profile and password via the self-service portal.
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
                ),
        ];
    }
}
