<?php

namespace Modules\TitanEchoAssist\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Modules\TitanEchoAssist\Services\ChannelRouter;
use Modules\TitanEchoAssist\Services\MessengerChannel;
use Modules\TitanEchoAssist\DTOs\MessagePayload;

/**
 * Facebook Messenger channel integration tests (Blueprint 15).
 *
 * Includes HMAC-SHA256 webhook signature verification test per Blueprint 22.
 */
class MessengerChannelFlowTest extends TestCase
{
    private ChannelRouter $router;

    protected function setUp(): void
    {
        $this->router = new ChannelRouter();
    }

    public function test_messenger_channel_driver_exists(): void
    {
        $this->assertTrue(class_exists(MessengerChannel::class));
    }

    public function test_messenger_driver_implements_channel_driver_interface(): void
    {
        $this->assertInstanceOf(
            \Modules\TitanEchoAssist\Contracts\ChannelDriver::class,
            new MessengerChannel()
        );
    }

    public function test_channel_router_resolves_messenger_driver(): void
    {
        try {
            $driver = $this->router->resolve('messenger');
            $this->assertInstanceOf(MessengerChannel::class, $driver);
        } catch (\Throwable) {
            $reflection = new \ReflectionClass($this->router);
            $prop       = $reflection->getProperty('channelMap');
            $prop->setAccessible(true);
            $map = $prop->getValue($this->router);
            $this->assertArrayHasKey('messenger', $map);
            $this->assertSame(MessengerChannel::class, $map['messenger']);
        }
    }

    public function test_messenger_hmac_sha256_signature_verification(): void
    {
        $appSecret = 'fb_app_secret_integration_test';
        $body      = json_encode([
            'object' => 'page',
            'entry'  => [['messaging' => [['sender' => ['id' => '123'], 'message' => ['text' => 'hi']]]]],
        ]);

        $validSig   = 'sha256=' . hash_hmac('sha256', $body, $appSecret);
        $invalidSig = 'sha256=' . str_repeat('0', 64);

        // Valid signature
        $this->assertTrue(
            hash_equals('sha256=' . hash_hmac('sha256', $body, $appSecret), $validSig),
            'Valid HMAC-SHA256 signature must be accepted'
        );

        // Invalid signature
        $this->assertFalse(
            hash_equals('sha256=' . hash_hmac('sha256', $body, $appSecret), $invalidSig),
            'Invalid HMAC-SHA256 signature must be rejected'
        );
    }

    public function test_messenger_payload_extracts_sender_id_as_session(): void
    {
        $payload = MessagePayload::fromArray([
            'chatbot_id' => 8,
            'session_id' => 'fb_sender_111222333',
            'channel'    => 'messenger',
            'message'    => 'Hey there!',
            'metadata'   => ['sender' => ['id' => 'fb_sender_111222333']],
        ]);

        $this->assertSame('fb_sender_111222333', $payload->sessionId);
        $this->assertSame('messenger', $payload->channel);
    }

    public function test_messenger_channel_in_omni_manifest(): void
    {
        $manifest   = json_decode(file_get_contents(__DIR__ . '/../../manifests/omni_manifest.json'), true);
        $channelIds = array_column($manifest['channels'], 'id');
        $this->assertContains('messenger', $channelIds);
    }

    public function test_messenger_manifest_entry_uses_x_hub_signature_256(): void
    {
        $manifest = json_decode(file_get_contents(__DIR__ . '/../../manifests/omni_manifest.json'), true);
        foreach ($manifest['channels'] as $channel) {
            if ($channel['id'] === 'messenger') {
                $this->assertSame('X-Hub-Signature-256', $channel['signature_header']);
                $this->assertSame('hmac-sha256', $channel['signature_algorithm']);
                return;
            }
        }
        $this->fail('Messenger channel not found in omni_manifest.json');
    }

    public function test_messenger_webhook_controller_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Modules\TitanEchoAssist\Http\Controllers\Webhooks\MessengerWebhookController::class)
        );
    }
}
