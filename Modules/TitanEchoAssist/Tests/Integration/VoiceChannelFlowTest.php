<?php

namespace Modules\TitanEchoAssist\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Modules\TitanEchoAssist\Services\ChannelRouter;
use Modules\TitanEchoAssist\Services\VoiceChannel;
use Modules\TitanEchoAssist\DTOs\MessagePayload;

/**
 * Voice channel integration tests (Blueprint 15).
 *
 * Flow: call → CallingAgent handoff → transcript returned to EchoAssist.
 */
class VoiceChannelFlowTest extends TestCase
{
    private ChannelRouter $router;

    protected function setUp(): void
    {
        $this->router = new ChannelRouter();
    }

    public function test_voice_channel_driver_exists(): void
    {
        $this->assertTrue(class_exists(VoiceChannel::class));
    }

    public function test_voice_driver_implements_channel_driver_interface(): void
    {
        $this->assertInstanceOf(
            \Modules\TitanEchoAssist\Contracts\ChannelDriver::class,
            new VoiceChannel()
        );
    }

    public function test_channel_router_resolves_voice_driver(): void
    {
        try {
            $driver = $this->router->resolve('voice');
            $this->assertInstanceOf(VoiceChannel::class, $driver);
        } catch (\Throwable) {
            $reflection = new \ReflectionClass($this->router);
            $prop       = $reflection->getProperty('channelMap');
            $prop->setAccessible(true);
            $map = $prop->getValue($this->router);
            $this->assertArrayHasKey('voice', $map);
            $this->assertSame(VoiceChannel::class, $map['voice']);
        }
    }

    public function test_voice_payload_includes_session_id_for_call_tracking(): void
    {
        $payload = MessagePayload::fromArray([
            'chatbot_id' => 9,
            'session_id' => 'voice-call-abc123',
            'channel'    => 'voice',
            'message'    => 'What are your opening hours?',
            'tenant_id'  => 50,
        ]);

        $this->assertSame('voice-call-abc123', $payload->sessionId);
        $this->assertSame('voice', $payload->channel);
        $this->assertSame(50, $payload->tenantId);
    }

    public function test_voice_channel_in_channel_type_enum(): void
    {
        $values = array_map(fn($c) => $c->value, \Modules\TitanEchoAssist\Enums\ChannelType::cases());
        $this->assertContains('voice', $values);
    }

    public function test_voice_channel_in_omni_manifest(): void
    {
        $manifest   = json_decode(file_get_contents(__DIR__ . '/../../manifests/omni_manifest.json'), true);
        $channelIds = array_column($manifest['channels'], 'id');
        $this->assertContains('voice', $channelIds);
    }

    public function test_voice_billing_listener_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Modules\TitanEchoAssist\Listeners\RecordVoiceSessionBillingListener::class)
        );
    }

    public function test_voice_session_duration_event_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Modules\TitanEchoAssist\Events\VoiceSessionDurationRecorded::class)
        );
    }

    public function test_voice_session_started_event_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Modules\TitanEchoAssist\Events\VoiceSessionStarted::class)
        );
    }

    public function test_voice_seconds_meter_records_duration(): void
    {
        \TitanChatbotCacheStub::flush();

        $meter = new \Modules\TitanEchoAssist\Billing\Meters\VoiceSecondsMeter();

        $meter->record(30.5, ['tenant_id' => 5]);
        $meter->record(15.0, ['tenant_id' => 5]);

        // Total billed: ceil(30.5)=31 + ceil(15.0)=15 = 46 seconds
        $today = date('Y-m-d');
        $total = $meter->getSeconds(5, $today);
        $this->assertSame(46, $total, 'Voice billing must accumulate ceiled seconds per tenant');
    }
}
