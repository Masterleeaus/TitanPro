<?php

namespace App\Providers\Filament;

use App\Filament\TitanNexus\Pages\LeadPipeline;
use App\Filament\TitanNexus\Pages\MarketingCampaigns;
use App\Filament\TitanNexus\Pages\TrainingContent;
use App\Filament\TitanNexus\Pages\Verticals;
use App\Providers\Filament\Concerns\RegistersFilamentPlugins;
use App\Support\OrganizationBrandingResolver;
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
 * TitanNexus — vertical pack management, lead generation,
 * niche training content, and marketing automation panel.
 */
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
                    'Relaticle\\Flowforge\\FilamentFlowforgePlugin',
                    'LaraZeus\\DynamicDashboard\\DynamicDashboardPlugin',
                ]),
            ])
            ->discoverResources(in: app_path('Filament/TitanNexus/Resources'), for: 'App\\Filament\\TitanNexus\\Resources')
            ->discoverPages(in: app_path('Filament/TitanNexus/Pages'), for: 'App\\Filament\\TitanNexus\\Pages')
            ->pages([
                Pages\Dashboard::class,
                Verticals::class,
                LeadPipeline::class,
                TrainingContent::class,
                MarketingCampaigns::class,
            ])
            ->discoverWidgets(in: app_path('Filament/TitanNexus/Widgets'), for: 'App\\Filament\\TitanNexus\\Widgets')
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
