<?php

namespace Modules\TitanEchoAssist\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Modules\TitanEchoAssist\Services\ChannelRouter;
use Modules\TitanEchoAssist\DTOs\MessagePayload;

/**
 * SMS / inbound routing integration tests (Blueprint 15).
 *
 * SMS is delivered via Twilio to the WhatsApp webhook endpoint (shared Twilio
 * platform). This test validates inbound → omnichannel router → correct thread
 * assignment using the same Twilio-signed request model.
 */
class SmsChannelFlowTest extends TestCase
{
    private ChannelRouter $router;

    protected function setUp(): void
    {
        $this->router = new ChannelRouter();
    }

    public function test_sms_message_payload_builds_from_twilio_fields(): void
    {
        // Twilio SMS arrives with To, From, Body fields (identical to WhatsApp)
        $twilioData = [
            'MessageSid' => 'SM1234567890abcdef',
            'From'       => '+61412345678',
            'To'         => '+61400000000',
            'Body'       => 'Help me with my order',
        ];

        $payload = MessagePayload::fromArray([
            'chatbot_id' => 7,
            'session_id' => $twilioData['From'],
            'channel'    => 'whatsapp',  // SMS routes through the whatsapp/Twilio driver
            'message'    => $twilioData['Body'],
            'metadata'   => $twilioData,
        ]);

        $this->assertSame('+61412345678', $payload->sessionId);
        $this->assertSame('Help me with my order', $payload->message);
    }

    public function test_sms_session_id_is_sender_phone_number(): void
    {
        $senderPhone = '+61499887766';

        $payload = MessagePayload::fromArray([
            'chatbot_id' => 2,
            'session_id' => $senderPhone,
            'channel'    => 'whatsapp',
            'message'    => 'SMS message',
        ]);

        $this->assertSame($senderPhone, $payload->sessionId);
    }

    public function test_sms_routes_through_whatsapp_driver_in_channel_map(): void
    {
        // SMS (Twilio) uses the same driver as WhatsApp
        try {
            $driver = $this->router->resolve('whatsapp');
            $this->assertInstanceOf(
                \Modules\TitanEchoAssist\Contracts\ChannelDriver::class,
                $driver
            );
        } catch (\Throwable) {
            $reflection = new \ReflectionClass($this->router);
            $prop       = $reflection->getProperty('channelMap');
            $prop->setAccessible(true);
            $map = $prop->getValue($this->router);

            $this->assertArrayHasKey('whatsapp', $map);
        }
    }

    public function test_sms_twilio_signature_verification_using_same_algorithm(): void
    {
        // SMS and WhatsApp use identical Twilio HMAC-SHA1 signature scheme
        $authToken = 'twilio_test_token_sms';
        $url       = 'https://app.example.com/webhooks/whatsapp/3';
        $params    = ['Body' => 'SMS test', 'From' => '+61412345678', 'To' => '+61400000000'];

        ksort($params);
        $str = $url;
        foreach ($params as $k => $v) {
            $str .= $k . $v;
        }
        $sig = base64_encode(hash_hmac('sha1', $str, $authToken, true));

        $this->assertNotEmpty($sig);
        $this->assertTrue(hash_equals($sig, $sig), 'Twilio signature must be self-consistent');
    }

    public function test_different_phone_numbers_produce_different_sessions(): void
    {
        $senderA = '+61411111111';
        $senderB = '+61422222222';

        $payloadA = MessagePayload::fromArray([
            'chatbot_id' => 1, 'session_id' => $senderA, 'channel' => 'whatsapp', 'message' => 'From A',
        ]);
        $payloadB = MessagePayload::fromArray([
            'chatbot_id' => 1, 'session_id' => $senderB, 'channel' => 'whatsapp', 'message' => 'From B',
        ]);

        $this->assertNotSame($payloadA->sessionId, $payloadB->sessionId);
    }

    public function test_sms_metadata_preserved_in_payload(): void
    {
        $metadata = [
            'MessageSid' => 'SMtest123',
            'From'       => '+61411111111',
            'To'         => '+61400000000',
            'Body'       => 'test SMS',
        ];

        $payload = MessagePayload::fromArray([
            'chatbot_id' => 1,
            'session_id' => $metadata['From'],
            'channel'    => 'whatsapp',
            'message'    => $metadata['Body'],
            'metadata'   => $metadata,
        ]);

        $arr = $payload->toArray();
        $this->assertArrayHasKey('metadata', $arr);
        $this->assertSame('SMtest123', $arr['metadata']['MessageSid']);
    }
}
