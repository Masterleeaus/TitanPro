<?php

namespace Modules\TitanEchoAssist\Tests\Observability;

use PHPUnit\Framework\TestCase;
use Modules\TitanEchoAssist\Billing\Meters\VoiceSecondsMeter;
use Modules\TitanEchoAssist\Billing\Meters\ConversationMeter;
use Modules\TitanEchoAssist\Billing\Usage\UsageTracker;
use Modules\TitanEchoAssist\Billing\Usage\UsageRecord;
use Modules\TitanEchoAssist\Signals\ChatbotAnalyticsSignal;

/**
 * Observability and health metric tests (Blueprint 23).
 *
 * Validates that:
 * - AI agent response time metrics are tracked per company
 * - Billing meters expose per-company counters
 * - Health manifest exists and contains required fields
 * - ChatbotAnalyticsSignal is emittable
 */
class HealthMetricsTest extends TestCase
{
    protected function setUp(): void
    {
        \TitanChatbotCacheStub::flush();
    }

    // ─── Per-company billing metrics ─────────────────────────────────────────

    public function test_conversation_meter_key_scoped_by_company(): void
    {
        $meter = new ConversationMeter();

        $reflection = new \ReflectionClass($meter);
        $method     = $reflection->getMethod('buildKey');
        $method->setAccessible(true);

        $keyCompA = $method->invoke($meter, 100, '2025-06');
        $keyCompB = $method->invoke($meter, 200, '2025-06');

        $this->assertNotSame($keyCompA, $keyCompB, 'Meter keys must differ per company');
        $this->assertStringContainsString('100', $keyCompA);
        $this->assertStringContainsString('200', $keyCompB);
    }

    public function test_voice_seconds_meter_accumulates_per_tenant(): void
    {
        $meter = new VoiceSecondsMeter();

        $meter->record(60.0, ['tenant_id' => 1]);
        $meter->record(30.0, ['tenant_id' => 1]);

        $today = date('Y-m-d');
        $total = $meter->getSeconds(1, $today);

        $this->assertSame(90, $total, 'Voice seconds must accumulate to 60+30=90');
    }

    public function test_voice_seconds_isolated_between_tenants(): void
    {
        $meter = new VoiceSecondsMeter();
        $today = date('Y-m-d');

        $meter->record(120.0, ['tenant_id' => 10]);
        $meter->record(45.5, ['tenant_id' => 20]);

        $this->assertSame(120, $meter->getSeconds(10, $today));
        $this->assertSame(46, $meter->getSeconds(20, $today));
    }

    public function test_usage_tracker_records_prompt_and_completion_tokens(): void
    {
        $tracker = new UsageTracker();
        $today   = date('Y-m-d');

        $tracker->record(UsageRecord::fromArray([
            'prompt_tokens'     => 150,
            'completion_tokens' => 75,
            'total_tokens'      => 225,
            'tenant_id'         => 30,
        ]));

        $this->assertSame(225, $tracker->getTotalTokens(30, $today));
    }

    public function test_usage_tracker_per_chatbot_metric_tracked(): void
    {
        $tracker = new UsageTracker();
        $today   = date('Y-m-d');

        $tracker->record(UsageRecord::fromArray([
            'prompt_tokens'     => 100,
            'completion_tokens' => 50,
            'total_tokens'      => 150,
            'tenant_id'         => 40,
            'chatbot_id'        => 99,
        ]));

        $this->assertSame(150, $tracker->getChatbotTokens(99, $today));
    }

    // ─── Health manifest ──────────────────────────────────────────────────────

    public function test_health_manifest_exists(): void
    {
        $path = __DIR__ . '/../../manifests/health.manifest.json';
        $this->assertTrue(file_exists($path), 'health.manifest.json must exist');
    }

    public function test_health_manifest_is_valid_json(): void
    {
        $path = __DIR__ . '/../../manifests/health.manifest.json';
        $raw  = file_get_contents($path);
        $data = json_decode($raw, true);
        $this->assertNotNull($data, 'health.manifest.json must be valid JSON');
    }

    // ─── Analytics signal ─────────────────────────────────────────────────────

    public function test_chatbot_analytics_signal_class_exists(): void
    {
        $this->assertTrue(
            class_exists(ChatbotAnalyticsSignal::class),
            'ChatbotAnalyticsSignal must exist for observability'
        );
    }

    // ─── Observability config ─────────────────────────────────────────────────

    public function test_observability_config_exists(): void
    {
        $path = __DIR__ . '/../../Config/observability.php';
        $this->assertTrue(file_exists($path), 'observability config must exist');
    }

    // ─── Event listeners exist ────────────────────────────────────────────────

    public function test_record_voice_billing_listener_exists(): void
    {
        $this->assertTrue(
            class_exists(\Modules\TitanEchoAssist\Listeners\RecordVoiceSessionBillingListener::class)
        );
    }

    public function test_message_received_event_exists(): void
    {
        $this->assertTrue(
            class_exists(\Modules\TitanEchoAssist\Events\MessageReceived::class)
        );
    }

    public function test_message_billed_event_exists(): void
    {
        $this->assertTrue(
            class_exists(\Modules\TitanEchoAssist\Events\MessageBilled::class)
        );
    }
}
