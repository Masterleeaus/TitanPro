<?php

namespace Modules\TitanEchoAssist\Tests\Unit;

use PHPUnit\Framework\TestCase;

class PortalLifecycleAutomationContractTest extends TestCase
{
    private string $moduleRoot;

    protected function setUp(): void
    {
        $this->moduleRoot = dirname(__DIR__, 2);
    }

    public function test_portal_automation_files_exist(): void
    {
        $files = [
            'Services/ChatbotPortalAutomationService.php',
            'Jobs/SendQuoteFollowupNotification.php',
            'Listeners/PortalAutomation/HandleJobCompleted.php',
            'Listeners/PortalAutomation/HandleInvoiceOverdue.php',
            'Listeners/PortalAutomation/HandleQuoteSent.php',
            'Listeners/PortalAutomation/HandleVisitTomorrow.php',
            'Console/Commands/CheckOverdueInvoicesCommand.php',
            'Console/Commands/SendVisitRemindersCommand.php',
            'Routes/console.php',
        ];

        foreach ($files as $file) {
            $this->assertFileExists($this->moduleRoot.'/'.$file);
        }
    }

    public function test_automation_service_contains_trigger_templates_and_idempotency_check(): void
    {
        $content = file_get_contents($this->moduleRoot.'/Services/ChatbotPortalAutomationService.php');

        $this->assertStringContainsString("'job_completed' => \$this->requestFeedback(\$payload)", $content);
        $this->assertStringContainsString("'invoice_overdue' => \$this->sendPaymentReminder(\$payload)", $content);
        $this->assertStringContainsString("'quote_sent' => \$this->scheduleQuoteFollowup(\$payload)", $content);
        $this->assertStringContainsString("'visit_tomorrow' => \$this->sendAccessCheckPrompt(\$payload)", $content);
        $this->assertStringContainsString('How did your clean go? Tap to leave a review ⭐', $content);
        $this->assertStringContainsString('is overdue. Tap to pay.', $content);
        $this->assertStringContainsString('Just checking in — have you had a chance to review your quote?', $content);
        $this->assertStringContainsString('Any access instructions to update?', $content);
        $this->assertStringContainsString('hasProcessedAutomation', $content);
        $this->assertStringContainsString("whereJsonContains('trigger_payload->idempotency_key'", $content);
    }

    public function test_wiring_for_events_commands_and_schedule_is_present(): void
    {
        $eventProvider = file_get_contents($this->moduleRoot.'/Providers/EventServiceProvider.php');
        $moduleProvider = file_get_contents($this->moduleRoot.'/Providers/ModuleServiceProvider.php');
        $consoleRoutes = file_get_contents($this->moduleRoot.'/Routes/console.php');
        $estimateController = file_get_contents(dirname($this->moduleRoot, 2).'/app/Http/Controllers/Owner/EstimateController.php');

        $this->assertStringContainsString('JobStatusChanged::class', $eventProvider);
        $this->assertStringContainsString('HandleJobCompleted::class', $eventProvider);
        $this->assertStringContainsString('EstimateSent::class', $eventProvider);
        $this->assertStringContainsString('HandleQuoteSent::class', $eventProvider);
        $this->assertStringContainsString("chatbot:portal:check-overdue-invoices", $consoleRoutes);
        $this->assertStringContainsString("chatbot:portal:send-visit-reminders", $consoleRoutes);
        $this->assertStringContainsString("['api', 'web', 'admin', 'tenant', 'channels', 'console']", $moduleProvider);
        $this->assertStringContainsString('EstimateSent::dispatch($estimate->fresh());', $estimateController);
    }
}

