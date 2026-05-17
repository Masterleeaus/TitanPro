<?php

namespace Modules\TitanEchoAssist\Tests\Unit;

use PHPUnit\Framework\TestCase;

class ChatbotPortalModelsContractTest extends TestCase
{
    private string $moduleRoot;

    protected function setUp(): void
    {
        $this->moduleRoot = dirname(__DIR__, 2);
    }

    public function test_portal_model_files_exist(): void
    {
        $models = [
            'ChatbotPortalBookingRequest.php',
            'ChatbotPortalRecurringService.php',
            'ChatbotPortalSiteProfile.php',
            'ChatbotPortalDocumentLink.php',
            'ChatbotPortalFeedback.php',
            'ChatbotPortalNotification.php',
            'ChatbotPortalAction.php',
            'ChatbotAvatar.php',
            'ChatbotCannedResponse.php',
            'ChatbotChannelWebhook.php',
            'ChatbotPageVisit.php',
            'ChatbotPortalAutomationLog.php',
        ];

        foreach ($models as $model) {
            $this->assertFileExists($this->moduleRoot.'/Models/'.$model);
        }
    }

    public function test_portal_migrations_exist(): void
    {
        $migrations = [
            'Database/migrations/2026_05_17_160000_create_chatbot_portal_models_tables.php',
            'Database/migrations/2026_05_17_160100_update_chatbot_conversations_for_portal_fields.php',
            'Database/migrations/2026_05_17_160200_update_chatbot_support_tables_for_portal.php',
        ];

        foreach ($migrations as $migration) {
            $this->assertFileExists($this->moduleRoot.'/'.$migration);
        }
    }

    public function test_site_profile_alarm_code_uses_encrypted_cast(): void
    {
        $content = file_get_contents($this->moduleRoot.'/Models/ChatbotPortalSiteProfile.php');
        $this->assertStringContainsString("'alarm_code' => 'encrypted'", $content);
    }

    public function test_conversation_contains_portal_fields(): void
    {
        $content = file_get_contents($this->moduleRoot.'/Models/Conversation.php');
        $this->assertStringContainsString("'email'", $content);
        $this->assertStringContainsString("'rating'", $content);
        $this->assertStringContainsString("'reviewed_at'", $content);
        $this->assertStringContainsString("'internal_notes'", $content);
        $this->assertStringContainsString("'is_pinned'", $content);
        $this->assertStringContainsString("'source_channel'", $content);
        $this->assertStringContainsString("'voice_call_duration'", $content);
        $this->assertStringContainsString("'voice_recording_url'", $content);
    }
}
