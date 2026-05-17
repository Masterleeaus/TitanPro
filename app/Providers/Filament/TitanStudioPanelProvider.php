<?php

namespace App\Providers\Filament;

use App\Providers\Filament\Concerns\RegistersFilamentPlugins;
use App\Filament\Pages\ThemeManager;
use App\Support\OrganizationBrandingResolver;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
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
 * TitanStudio — Workflow builder, automation, and CMS studio panel.
 */
class TitanStudioPanelProvider extends PanelProvider
{
    use RegistersFilamentPlugins;

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('titanstudio')
            ->path('titanstudio')
            ->brandName(fn () => app(OrganizationBrandingResolver::class)->panelName('TitanStudio'))
            ->brandLogo(fn () => app(OrganizationBrandingResolver::class)->current()['logo_url'] ?? null)
            ->favicon(fn () => app(OrganizationBrandingResolver::class)->current()['favicon_url'] ?? null)
            ->colors(fn (): array => [
                'primary' => app(OrganizationBrandingResolver::class)->primaryColor('#ec4899'),
            ])
            ->plugins([
                ...$this->breezyPlugin(),
                ...$this->availablePlugins([

                    'BezhanSalleh\FilamentShield\FilamentShieldPlugin',
                    'AlizHarb\ActivityLog\ActivityLogPlugin',
                    'Awcodes\Curator\CuratorPlugin',
                    'Pxlrbt\FilamentSpotlight\SpotlightPlugin',
                    'Andreia\FilamentUiSwitcher\FilamentUiSwitcherPlugin',
                    'JeffersonGoncalves\FilamentTopbar\FilamentTopbarPlugin',
                    'TomatoPHP\FilamentCms\FilamentCMSPlugin',
                    'TomatoPHP\FilamentSettingsHub\FilamentSettingsHubPlugin',
                    'TomatoPHP\FilamentIcons\FilamentIconsPlugin',
                    'TomatoPHP\FilamentTranslationComponent\FilamentTranslationComponentPlugin',
                    'Devonab\FilamentEasyFooter\EasyFooterPlugin',
                    'Modules\TitanStudioHub\Filament\Plugin\TitanStudioHubPlugin',
                ]),
            ])
            ->resources([
                \App\Filament\Resources\MessageTemplateResource::class,
                \App\Filament\Resources\CmsPageResource::class,
                \App\Filament\Resources\JobTypeChecklistItemResource::class,
                \App\Filament\Resources\JobChecklistItemResource::class,
            ])
            ->discoverResources(in: app_path('Filament/TitanStudio/Resources'), for: 'App\\Filament\\TitanStudio\\Resources')
            ->discoverPages(in: app_path('Filament/TitanStudio/Pages'), for: 'App\\Filament\\TitanStudio\\Pages')
            ->pages([
                Pages\Dashboard::class,
                ThemeManager::class,
            ])
            ->discoverWidgets(in: app_path('Filament/TitanStudio/Widgets'), for: 'App\\Filament\\TitanStudio\\Widgets')
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
