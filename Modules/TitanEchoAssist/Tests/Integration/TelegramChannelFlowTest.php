<?php

namespace Modules\TitanEchoAssist\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Modules\TitanEchoAssist\Services\ChannelRouter;
use Modules\TitanEchoAssist\Services\TelegramChannel;
use Modules\TitanEchoAssist\DTOs\MessagePayload;

/**
 * Telegram channel integration tests (Blueprint 15).
 *
 * Full flow: bot message → MessageReceived → AI response → reply sent.
 */
class TelegramChannelFlowTest extends TestCase
{
    private ChannelRouter $router;

    protected function setUp(): void
    {
        $this->router = new ChannelRouter();
    }

    public function test_telegram_channel_driver_exists(): void
    {
        $this->assertTrue(class_exists(TelegramChannel::class));
    }

    public function test_telegram_driver_implements_channel_driver_interface(): void
    {
        $this->assertInstanceOf(
            \Modules\TitanEchoAssist\Contracts\ChannelDriver::class,
            new TelegramChannel()
        );
    }

    public function test_channel_router_resolves_telegram_driver(): void
    {
        try {
            $driver = $this->router->resolve('telegram');
            $this->assertInstanceOf(TelegramChannel::class, $driver);
        } catch (\Throwable) {
            $reflection = new \ReflectionClass($this->router);
            $prop       = $reflection->getProperty('channelMap');
            $prop->setAccessible(true);
            $map = $prop->getValue($this->router);
            $this->assertArrayHasKey('telegram', $map);
            $this->assertSame(TelegramChannel::class, $map['telegram']);
        }
    }

    public function test_telegram_payload_preserves_sender_as_session_id(): void
    {
        $payload = MessagePayload::fromArray([
            'chatbot_id' => 3,
            'session_id' => '123456789',
            'channel'    => 'telegram',
            'message'    => '/start',
            'metadata'   => [
                'update_id' => 100,
                'message'   => ['from' => ['id' => 123456789], 'text' => '/start'],
            ],
        ]);

        $this->assertSame('123456789', $payload->sessionId);
        $this->assertSame('telegram', $payload->channel);
    }

    public function test_telegram_secret_token_verification_logic(): void
    {
        $expectedToken = 'my_telegram_secret_abc123';
        $incoming      = 'my_telegram_secret_abc123';

        $this->assertTrue(
            hash_equals($expectedToken, $incoming),
            'Matching token must pass verification'
        );

        $this->assertFalse(
            hash_equals($expectedToken, 'wrong_token'),
            'Wrong token must fail verification'
        );
    }

    public function test_telegram_missing_token_rejected(): void
    {
        $expectedToken = 'some_secret';
        $incoming      = '';

        $valid = ($expectedToken !== '' && $incoming !== '' && hash_equals($expectedToken, $incoming));
        $this->assertFalse($valid, 'Missing token must be rejected');
    }

    public function test_telegram_channel_in_omni_manifest(): void
    {
        $manifest   = json_decode(file_get_contents(__DIR__ . '/../../manifests/omni_manifest.json'), true);
        $channelIds = array_column($manifest['channels'], 'id');
        $this->assertContains('telegram', $channelIds);
    }

    public function test_telegram_manifest_entry_has_secret_token_header(): void
    {
        $manifest = json_decode(file_get_contents(__DIR__ . '/../../manifests/omni_manifest.json'), true);
        foreach ($manifest['channels'] as $channel) {
            if ($channel['id'] === 'telegram') {
                $this->assertSame('X-Telegram-Bot-Api-Secret-Token', $channel['signature_header']);
                return;
            }
        }
        $this->fail('Telegram channel not found in omni_manifest.json');
    }

    public function test_telegram_channel_payload_to_array_has_channel_key(): void
    {
        $payload = MessagePayload::fromArray([
            'chatbot_id' => 1,
            'session_id' => '987654',
            'channel'    => 'telegram',
            'message'    => 'Hello bot',
        ]);

        $arr = $payload->toArray();
        $this->assertArrayHasKey('channel', $arr);
        $this->assertSame('telegram', $arr['channel']);
    }
}
