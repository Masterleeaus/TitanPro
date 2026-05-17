<?php

namespace App\Providers\Filament;

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
 * Titan Pro panel.
 *
 * Business operations command centre for owners and managers.
 * Super Admin governance, roles, module repair, platform settings, and theme
 * administration remain in the Admin panel. Titan Pro focuses on customers,
 * jobs, estimates, invoices, payments, CRM, field visibility, and reporting.
 *
 * Panel path: /pro
 * Panel ID:   pro
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
            ->id('pro')
            ->path('pro')
            ->brandName('Titan Pro')
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
                    'Modules\BookingModule\Filament\BookingModulePlugin',
                    'Modules\CleaningJobs\Filament\Plugin\CleaningJobsPlugin',
                    'Modules\TitanRewind\Filament\Plugin\TitanRewindPlugin',

                    'Awcodes\Curator\CuratorPlugin',
                    'AlizHarb\ActivityLog\ActivityLogPlugin',
                    'Shreejan\DashArrange\DashArrangePlugin',
                    'Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin',
                    'LaraZeus\DynamicDashboard\DynamicDashboardPlugin',
                    'Pxlrbt\FilamentSpotlight\SpotlightPlugin',
                    'Andreia\FilamentUiSwitcher\FilamentUiSwitcherPlugin',
                    'Biostate\FilamentMenuBuilder\FilamentMenuBuilderPlugin',
                    'NoteBrainsLab\FilamentMenuManager\FilamentMenuManagerPlugin',
                    'BezhanSalleh\PanelSwitch\PanelSwitchPlugin',
                    'JeffersonGoncalves\FilamentTopbar\FilamentTopbarPlugin',
                    'OsamaAtef\FilamentDrilldownSidebar\FilamentDrilldownSidebarPlugin',
                    'Savannabits\FilamentModules\FilamentModulesPlugin',
                    'TomatoPHP\FilamentSettingsHub\FilamentSettingsHubPlugin',
                    'TomatoPHP\FilamentIcons\FilamentIconsPlugin',
                    'TomatoPHP\FilamentTranslationComponent\FilamentTranslationComponentPlugin',
                    'Devonab\FilamentEasyFooter\EasyFooterPlugin',
                ]),
            ])
            ->resources([
                \App\Filament\Resources\CustomerResource::class,
                \App\Filament\Resources\PropertyResource::class,
                \App\Filament\Resources\JobResource::class,
                \App\Filament\Resources\JobTypeResource::class,
                \App\Filament\Resources\JobTypeChecklistItemResource::class,
                \App\Filament\Resources\JobChecklistItemResource::class,
                \App\Filament\Resources\JobMessageResource::class,
                \App\Filament\Resources\EstimateResource::class,
                \App\Filament\Resources\EstimatePackageResource::class,
                \App\Filament\Resources\InvoiceResource::class,
                \App\Filament\Resources\PaymentResource::class,
                \App\Filament\Resources\ItemResource::class,
                \App\Filament\Resources\AttachmentResource::class,
                \App\Filament\Resources\MessageTemplateResource::class,
                \App\Filament\Resources\DriverLocationResource::class,
            ])
            ->pages([
                Pages\Dashboard::class,
                \App\Filament\TitanPro\Pages\CommandCenter::class,
                \App\Filament\TitanPro\Pages\FeatureMap::class,
                \App\Filament\Pages\OperationsReports::class,
                \App\Filament\Pages\Reports::class,
            ])
            ->widgets([
                Widgets\AccountWidget::class,
                \App\Filament\TitanPro\Widgets\TitanProBusinessStats::class,
                \App\Filament\TitanPro\Widgets\TitanProFinancialStats::class,
                \App\Filament\TitanPro\Widgets\TitanProWorkflowStats::class,
                \App\Filament\TitanPro\Widgets\TitanProPanelHealthWidget::class,
                \App\Filament\Widgets\CleaningOperationsOverview::class,
                \App\Filament\Widgets\DispatchOverviewWidget::class,
                \App\Filament\Widgets\RevenueReportingSnapshot::class,
                \App\Filament\Widgets\InvoiceVisibilityWidget::class,
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
            ->renderHook(...$this->uiInspectorHook())
            ->renderHook(...$this->titanOsShellHooks())
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
