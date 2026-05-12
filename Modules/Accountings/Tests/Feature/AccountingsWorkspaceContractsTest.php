<?php

namespace Modules\Accountings\Tests\Feature;

use Modules\Accountings\UI\Tabs\ControlPanelTabs;
use Tests\TestCase;

class AccountingsWorkspaceContractsTest extends TestCase
{
    public function test_workspace_tabs_define_all_eight_expected_tabs(): void
    {
        $tabs = ControlPanelTabs::make();
        $keys = array_column($tabs, 'key');

        $this->assertCount(8, $tabs);
        $this->assertSame(
            ['overview', 'invoices', 'payments', 'collections', 'bank_matching', 'accounting', 'compliance', 'ai_control'],
            $keys
        );
    }

    public function test_workspace_filament_artifacts_exist(): void
    {
        $base = dirname(__DIR__, 2);

        $this->assertFileExists($base . '/Filament/Widgets/AccountingOverviewWidget.php');
        $this->assertFileExists($base . '/Filament/Resources/InvoiceResource.php');
        $this->assertFileExists($base . '/Filament/Resources/PaymentSessionResource.php');
        $this->assertFileExists($base . '/Filament/Resources/JournalResource.php');
        $this->assertFileExists($base . '/Filament/Pages/CollectionsPage.php');
        $this->assertFileExists($base . '/Filament/Pages/BankMatchingPage.php');
        $this->assertFileExists($base . '/Filament/Pages/CompliancePage.php');
        $this->assertFileExists($base . '/Filament/Pages/AiControlPage.php');
    }

    public function test_required_manifest_contract_files_exist_and_are_valid(): void
    {
        $base = dirname(__DIR__, 2) . '/manifests';

        foreach (['ai_tools.json', 'signals_manifest.json', 'lifecycle_manifest.json'] as $file) {
            $path = $base . '/' . $file;
            $this->assertFileExists($path);
            $this->assertIsArray(
                json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR),
                "{$file} must decode to an object or array."
            );
        }

        $this->assertFileExists($base . '/zeropay.php');
        $this->assertFileExists($base . '/titanzero.php');
    }

    public function test_ai_tools_manifest_classes_resolve(): void
    {
        $path = dirname(__DIR__, 2) . '/manifests/ai_tools.json';
        $data = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        foreach ($data['tools'] as $tool) {
            $this->assertArrayHasKey('class', $tool);
            $this->assertTrue(class_exists($tool['class']), "Tool class [{$tool['class']}] does not resolve.");
        }
    }

    public function test_signals_manifest_declares_wired_accountings_events(): void
    {
        $path = dirname(__DIR__, 2) . '/manifests/signals_manifest.json';
        $data = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        $this->assertArrayHasKey('signals', $data);
        $events = array_column($data['signals'], 'event');

        $expected = [
            'Modules\\Accountings\\Events\\InvoiceJournalPosted',
            'Modules\\Accountings\\Events\\GstPosted',
            'Modules\\Accountings\\Events\\ReceivablesAged',
            'Modules\\Accountings\\Events\\StatementGenerated',
            'Modules\\Accountings\\Events\\WriteOffPosted',
            'Modules\\Accountings\\Events\\LedgerAdjustmentSuggested',
            'Modules\\Accountings\\Events\\ZeroPayPaymentConfirmed',
        ];

        foreach ($expected as $eventClass) {
            $this->assertContains($eventClass, $events);
        }
    }
}
