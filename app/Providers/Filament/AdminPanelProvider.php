<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Modules\CRMCore\Filament\Plugin\CRMCorePlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $crmCoreAutoloaderPath = base_path('Modules/CRMCore/Support/CRMCoreAutoloader.php');

        if (file_exists($crmCoreAutoloaderPath)) {
            require_once $crmCoreAutoloaderPath;
            \Modules\CRMCore\Support\CRMCoreAutoloader::register();
        }

        return $panel
            ->default()
            ->id('titanpro')
            ->path('admin')
            ->brandName('TitanPro — Super Admin')
            ->colors([
                'primary' => $this->primaryColor(),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->plugins([
                FilamentShieldPlugin::make(),
                CRMCorePlugin::make(),
                ...$this->availablePlugins([
                    // Account / auth / security
                    'Jeffgreco13\\FilamentBreezy\\BreezyCore',
                    'Pxlrbt\\FilamentSpotlight\\SpotlightPlugin',

                    // Media / activity / dashboard surfaces
                    'Awcodes\\Curator\\CuratorPlugin',
                    'Alizharb\\FilamentActivitylog\\FilamentActivitylogPlugin',
                    'Eightynine\\FilamentAdvancedWidgets\\AdvancedWidgetsPlugin',
                    'Shreejan\\DashArrange\\DashArrangePlugin',
                    'Leandrocfe\\FilamentApexCharts\\FilamentApexChartsPlugin',
                    'LaraZeus\\DynamicDashboard\\DynamicDashboardPlugin',

                    // Navigation / panel shell
                    'Andreia\\FilamentUiSwitcher\\FilamentUiSwitcherPlugin',
                    'Biostate\\FilamentMenuBuilder\\FilamentMenuBuilderPlugin',
                    'Notebrainslab\\FilamentMenuManager\\FilamentMenuManagerPlugin',
                    'BezhanSalleh\\PanelSwitch\\PanelSwitchPlugin',
                    'JeffersonGoncalves\\FilamentTopbar\\FilamentTopbarPlugin',
                    'OsamaAtef\\FilamentDrilldownSidebar\\FilamentDrilldownSidebarPlugin',
                    'Savannabits\\FilamentModules\\FilamentModulesPlugin',

                    // TomatoPHP platform surfaces
                    'TomatoPHP\\FilamentCms\\FilamentCMSPlugin',
                    'TomatoPHP\\FilamentSettingsHub\\FilamentSettingsHubPlugin',
                    'TomatoPHP\\FilamentIcons\\FilamentIconsPlugin',
                    'TomatoPHP\\FilamentTranslationComponent\\FilamentTranslationComponentPlugin',
                ]),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
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
     * Use a Filament-native palette to avoid invisible button text caused by
     * third-party theme foreground-token conflicts.
     */
    private function primaryColor(): mixed
    {
        return Color::Blue;
    }

    /**
     * Register optional Composer-installed Filament plugins without breaking the panel
     * if a package is removed, renamed, or only provides component classes.
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
