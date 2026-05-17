<?php

use App\Providers\Filament\Concerns\RegistersFilamentPlugins;
use Filament\Contracts\Plugin;

dataset('panel_plugin_wiring', [
    [app_path('Providers/Filament/QualityAuditsPanelProvider.php'), 'Modules\CleanQuality\Filament\Plugin\CleanQualityPlugin'],
    [app_path('Providers/Filament/TitanSuppliersAndInventoryPanelProvider.php'), 'Modules\SupplyChain\Filament\Plugin\SupplyChainPlugin'],
    [app_path('Providers/Filament/ZeroFussPanelProvider.php'), 'Modules\ZeroFussPortal\Filament\Plugin\ZeroFussPortalPlugin'],
    [app_path('Providers/Filament/ZeroPayPanelProvider.php'), 'Modules\ZeroPayModule\Filament\ZeroPayModulePlugin'],
    [app_path('Providers/Filament/ZeroPayPanelProvider.php'), 'Modules\EInvoice\Filament\Plugin\EInvoicePlugin'],
    [app_path('Providers/Filament/DocsAndContractsPanelProvider.php'), 'Modules\TitanDocs\Filament\Plugin\TitanDocsPlugin'],
    [app_path('Providers/Filament/TitanEchoPanelProvider.php'), 'Modules\TitanEchoAssist\Filament\Plugin\TitanEchoAssistPlugin'],
    [app_path('Providers/Filament/TitanEchoPanelProvider.php'), 'Modules\CallingAgent\Filament\Plugin\CallingAgentPlugin'],
    [app_path('Providers/Filament/TitanEchoPanelProvider.php'), 'Modules\TitanHello\Filament\Plugin\TitanHelloPlugin'],
    [app_path('Providers/Filament/TitanGoPanelProvider.php'), 'Modules\TitanGoField\Filament\Plugin\TitanGoFieldPlugin'],
    [app_path('Providers/Filament/TitanSoloPanelProvider.php'), 'Modules\TitanSoloDash\Filament\Plugin\TitanSoloDashPlugin'],
    [app_path('Providers/Filament/TitanStudioPanelProvider.php'), 'Modules\TitanStudioHub\Filament\Plugin\TitanStudioHubPlugin'],
    [app_path('Providers/Filament/TitanProPanelProvider.php'), 'Modules\TitanProAdmin\Filament\Plugin\TitanProAdminPlugin'],
    [app_path('Providers/Filament/TitanProPanelProvider.php'), 'Modules\TitanOperator\Filament\Plugin\TitanOperatorPlugin'],
    [app_path('Providers/Filament/TitanQuotesPanelProvider.php'), 'Modules\QuoteEngine\Filament\Plugin\QuoteEnginePlugin'],
    [app_path('Providers/Filament/TitanMoneyPanelProvider.php'), 'Modules\ZeroPayHub\Filament\Plugin\ZeroPayHubPlugin'],
    [app_path('Providers/Filament/LeadScorerPanelProvider.php'), 'Modules\TitanLeads\Filament\Plugin\TitanLeadsPlugin'],
    [app_path('Providers/Filament/TitanNexusPanelProvider.php'), 'Modules\NexusGrowth\Filament\Plugin\NexusGrowthPlugin'],
    [app_path('Providers/Filament/TitanPixelPanelProvider.php'), 'Modules\InstantAds\Filament\Plugin\InstantAdsPlugin'],
    [app_path('Providers/Filament/TitanPixelPanelProvider.php'), 'Modules\ProShots\Filament\Plugin\ProShotsPlugin'],
    [app_path('Providers/Filament/ComplianceAndSafetyPanelProvider.php'), 'Modules\Biometric\Filament\Plugin\BiometricPlugin'],
]);

test('target panels include required module plugins in available plugins lists', function (string $providerPath, string $pluginClass) {
    $providerContent = file_get_contents($providerPath);

    expect($providerContent)->toContain($pluginClass);
})->with('panel_plugin_wiring');

test('new module plugins are class discoverable', function () {
    expect(class_exists(\Modules\TitanLeads\Filament\Plugin\TitanLeadsPlugin::class))->toBeTrue()
        ->and(class_exists(\Modules\EInvoice\Filament\Plugin\EInvoicePlugin::class))->toBeTrue()
        ->and(class_exists(\Modules\ProShots\Filament\Plugin\ProShotsPlugin::class))->toBeTrue();
});

test('available plugins helper skips unknown plugin classes', function () {
    $plugin = new class implements Plugin
    {
        public static function make(): static
        {
            return new static();
        }

        public function getId(): string
        {
            return 'wiring-test-plugin';
        }

        public function register(\Filament\Panel $panel): void {}

        public function boot(\Filament\Panel $panel): void {}
    };

    $helper = new class
    {
        use RegistersFilamentPlugins;

        public function resolveAvailable(array $plugins): array
        {
            $method = new ReflectionMethod($this, 'availablePlugins');
            $method->setAccessible(true);

            /** @var array<int, object> $resolved */
            $resolved = $method->invoke($this, $plugins);

            return $resolved;
        }
    };

    $resolved = $helper->resolveAvailable([
        $plugin::class,
        'Modules\Nope\Filament\Plugin\MissingPlugin',
    ]);

    expect($resolved)->toHaveCount(1)
        ->and($resolved[0]->getId())->toBe('wiring-test-plugin');
});
