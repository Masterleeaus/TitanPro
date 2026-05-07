<?php

namespace App\Providers\Filament;

use App\Providers\Filament\Concerns\RegistersFilamentPlugins;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
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
use Modules\CRMCore\Filament\Plugin\CRMCorePlugin;

class AdminPanelProvider extends PanelProvider
{
    use RegistersFilamentPlugins;

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
            ->path('titanpro')
            ->brandName('TitanPro — Super Admin')
            ->colors([
                'primary' => $this->primaryColor(),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->plugins([
                FilamentShieldPlugin::make(),
                CRMCorePlugin::make(),
                ...$this->breezyPlugin(),
                ...$this->availablePlugins([
                    // Media / activity / dashboard surfaces
                    'Awcodes\\Curator\\CuratorPlugin',
                    'Alizharb\\FilamentActivitylog\\FilamentActivitylogPlugin',
                    'Eightynine\\FilamentAdvancedWidgets\\AdvancedWidgetsPlugin',
                    'Shreejan\\DashArrange\\DashArrangePlugin',
                    'Leandrocfe\\FilamentApexCharts\\FilamentApexChartsPlugin',
                    'LaraZeus\\DynamicDashboard\\DynamicDashboardPlugin',

                    // Navigation / panel shell
                    'Pxlrbt\\FilamentSpotlight\\SpotlightPlugin',
                    'Andreia\\FilamentUiSwitcher\\FilamentUiSwitcherPlugin',
                    'Biostate\\FilamentMenuBuilder\\FilamentMenuBuilderPlugin',
                    'NoteBrainsLab\\FilamentMenuManager\\FilamentMenuManagerPlugin',
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
                \App\Filament\Widgets\CleaningOperationsOverview::class,
                \App\Filament\Widgets\DispatchOverviewWidget::class,
                \App\Filament\Widgets\RevenueReportingSnapshot::class,
                \App\Filament\Widgets\InvoiceVisibilityWidget::class,
                \App\Filament\Widgets\PwaLaunchWidget::class,
                \App\Filament\Widgets\CleanerLiveMap::class,
                \App\Filament\Widgets\RevenueChartWidget::class,
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
}
