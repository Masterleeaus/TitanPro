<?php

namespace App\Providers\Filament;

use App\Filament\ZeroFuss\Pages\Dashboard;
use App\Providers\Filament\Concerns\RegistersFilamentPlugins;
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
 * ZeroFuss — Customer self-service portal panel.
 */
class ZeroFussPanelProvider extends PanelProvider
{
    use RegistersFilamentPlugins;

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('zerofuss')
            ->path('zerofuss')
            ->brandName('ZeroFuss')
            ->colors([
                'primary' => Color::Teal,
            ])
            ->plugins([
                ...$this->breezyPlugin(),
            ])
            ->discoverResources(in: app_path('Filament/ZeroFuss/Resources'), for: 'App\\Filament\\ZeroFuss\\Resources')
            ->discoverPages(in: app_path('Filament/ZeroFuss/Pages'), for: 'App\\Filament\\ZeroFuss\\Pages')
            ->pages([
                Dashboard::class,
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
}
