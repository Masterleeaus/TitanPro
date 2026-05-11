<?php

namespace App\Providers\Filament;

use App\Filament\TitanPro\Widgets\ActiveOrganizationsWidget;
use App\Filament\TitanPro\Widgets\FailedJobsWidget;
use App\Filament\TitanPro\Widgets\PlatformRevenueWidget;
use App\Filament\TitanPro\Widgets\UsageMetricsWidget;
use App\Providers\Filament\Concerns\RegistersFilamentPlugins;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
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

/**
 * TitanPro — Super Admin SaaS control panel.
 *
 * Accessible only by users with the `super_admin` role.
 * Provides cross-org organisation management, user management,
 * subscription oversight, module management, and platform health.
 *
 * Panel path: /titanpro
 * Panel ID:   titanpro
 */
class TitanProPanelProvider extends PanelProvider
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
                'primary' => Color::Blue,
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
            ->discoverResources(in: app_path('Filament/TitanPro/Resources'), for: 'App\\Filament\\TitanPro\\Resources')
            ->discoverPages(in: app_path('Filament/TitanPro/Pages'), for: 'App\\Filament\\TitanPro\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                ActiveOrganizationsWidget::class,
                PlatformRevenueWidget::class,
                UsageMetricsWidget::class,
                FailedJobsWidget::class,
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
            ])
            ->renderHook(...$this->uiOverrideSsrHook())
            ->renderHook(...$this->uiInspectorHook())
            ->navigationGroups([
                NavigationGroup::make('Panels')
                    ->collapsible(false),
            ])
            ->navigationItems([
                NavigationItem::make('Ground Zero')
                    ->url('/groundzero')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->group('Panels')
                    ->sort(1),
                NavigationItem::make('Titan Quotes')
                    ->url('/titanquotes')
                    ->icon('heroicon-o-document-text')
                    ->group('Panels')
                    ->sort(2),
                NavigationItem::make('Zero Pay')
                    ->url('/zeropay')
                    ->icon('heroicon-o-credit-card')
                    ->group('Panels')
                    ->sort(3),
                NavigationItem::make('Titan Go')
                    ->url('/titango')
                    ->icon('heroicon-o-truck')
                    ->group('Panels')
                    ->sort(4),
                NavigationItem::make('Zero Fuss')
                    ->url('/zerofuss')
                    ->icon('heroicon-o-sparkles')
                    ->group('Panels')
                    ->sort(5),
                NavigationItem::make('Titan Solo')
                    ->url('/titansolo')
                    ->icon('heroicon-o-user')
                    ->group('Panels')
                    ->sort(6),
                NavigationItem::make('Titan Studio')
                    ->url('/titanstudio')
                    ->icon('heroicon-o-paint-brush')
                    ->group('Panels')
                    ->sort(7),
                NavigationItem::make('Titan Nexus')
                    ->url('/titannexus')
                    ->icon('heroicon-o-building-office-2')
                    ->group('Panels')
                    ->sort(8),
            ]);
    }
}
