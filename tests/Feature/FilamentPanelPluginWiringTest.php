<?php

use App\Providers\Filament\Concerns\RegistersFilamentPlugins;
use Filament\Contracts\Plugin;

dataset('panel_plugin_wiring', [
    ['Providers/Filament/QualityAuditsPanelProvider.php', 'Modules\CleanQuality\Filament\Plugin\CleanQualityPlugin'],
    ['Providers/Filament/TitanSuppliersAndInventoryPanelProvider.php', 'Modules\SupplyChain\Filament\Plugin\SupplyChainPlugin'],
    ['Providers/Filament/ZeroFussPanelProvider.php', 'Modules\ZeroFussPortal\Filament\Plugin\ZeroFussPortalPlugin'],
    ['Providers/Filament/ZeroPayPanelProvider.php', 'Modules\ZeroPayModule\Filament\ZeroPayModulePlugin'],
    ['Providers/Filament/ZeroPayPanelProvider.php', 'Modules\EInvoice\Filament\Plugin\EInvoicePlugin'],
    ['Providers/Filament/DocsAndContractsPanelProvider.php', 'Modules\TitanDocs\Filament\Plugin\TitanDocsPlugin'],
    ['Providers/Filament/TitanEchoPanelProvider.php', 'Modules\TitanEchoAssist\Filament\Plugin\TitanEchoAssistPlugin'],
    ['Providers/Filament/TitanEchoPanelProvider.php', 'Modules\CallingAgent\Filament\Plugin\CallingAgentPlugin'],
    ['Providers/Filament/TitanEchoPanelProvider.php', 'Modules\TitanHello\Filament\Plugin\TitanHelloPlugin'],
    ['Providers/Filament/TitanGoPanelProvider.php', 'Modules\TitanGoField\Filament\Plugin\TitanGoFieldPlugin'],
    ['Providers/Filament/TitanSoloPanelProvider.php', 'Modules\TitanSoloDash\Filament\Plugin\TitanSoloDashPlugin'],
    ['Providers/Filament/TitanStudioPanelProvider.php', 'Modules\TitanStudioHub\Filament\Plugin\TitanStudioHubPlugin'],
    ['Providers/Filament/TitanProPanelProvider.php', 'Modules\TitanProAdmin\Filament\Plugin\TitanProAdminPlugin'],
    ['Providers/Filament/TitanProPanelProvider.php', 'Modules\TitanOperator\Filament\Plugin\TitanOperatorPlugin'],
    ['Providers/Filament/TitanQuotesPanelProvider.php', 'Modules\QuoteEngine\Filament\Plugin\QuoteEnginePlugin'],
    ['Providers/Filament/TitanMoneyPanelProvider.php', 'Modules\ZeroPayHub\Filament\Plugin\ZeroPayHubPlugin'],
    ['Providers/Filament/LeadScorerPanelProvider.php', 'Modules\TitanLeads\Filament\Plugin\TitanLeadsPlugin'],
    ['Providers/Filament/TitanNexusPanelProvider.php', 'Modules\NexusGrowth\Filament\Plugin\NexusGrowthPlugin'],
    ['Providers/Filament/TitanPixelPanelProvider.php', 'Modules\InstantAds\Filament\Plugin\InstantAdsPlugin'],
    ['Providers/Filament/TitanPixelPanelProvider.php', 'Modules\ProShots\Filament\Plugin\ProShotsPlugin'],
    ['Providers/Filament/ComplianceAndSafetyPanelProvider.php', 'Modules\Biometric\Filament\Plugin\BiometricPlugin'],
]);

test('target panels include required module plugins in available plugins lists', function (string $providerRelativePath, string $pluginClass) {
    $providerContent = file_get_contents(app_path($providerRelativePath));

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
