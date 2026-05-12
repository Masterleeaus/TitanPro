<?php

namespace Modules\TitanEchoAssist\Tests\Feature;

use PHPUnit\Framework\TestCase;
use Modules\TitanEchoAssist\Services\ChannelRouter;
use Modules\TitanEchoAssist\DTOs\MessagePayload;

/**
 * Cross-tenant isolation tests (Blueprint 19 + 22).
 *
 * Verifies that messages from different companies cannot be routed or observed
 * by each other, and that tenant boundaries are enforced throughout the
 * messaging pipeline.
 */
class CrossTenantIsolationTest extends TestCase
{
    public function test_message_payload_preserves_tenant_id(): void
    {
        $payload = MessagePayload::fromArray([
            'chatbot_id' => 10,
            'session_id' => 'sess-company-a',
            'channel'    => 'whatsapp',
            'message'    => 'Hello from company A',
            'tenant_id'  => 1001,
        ]);

        $this->assertSame(1001, $payload->tenantId);
    }

    public function test_message_payload_for_company_b_has_different_tenant_id(): void
    {
        $payloadA = MessagePayload::fromArray([
            'chatbot_id' => 10,
            'session_id' => 'sess-a',
            'channel'    => 'website',
            'message'    => 'Company A message',
            'tenant_id'  => 1001,
        ]);

        $payloadB = MessagePayload::fromArray([
            'chatbot_id' => 20,
            'session_id' => 'sess-b',
            'channel'    => 'website',
            'message'    => 'Company B message',
            'tenant_id'  => 2002,
        ]);

        $this->assertNotSame(
            $payloadA->tenantId,
            $payloadB->tenantId,
            'Different companies must have different tenant IDs'
        );
    }

    public function test_payload_to_array_preserves_tenant_id(): void
    {
        $payload = MessagePayload::fromArray([
            'chatbot_id' => 5,
            'session_id' => 'sess-t',
            'channel'    => 'telegram',
            'message'    => 'Test',
            'tenant_id'  => 3003,
        ]);

        $arr = $payload->toArray();
        $this->assertArrayHasKey('tenant_id', $arr);
        $this->assertSame(3003, $arr['tenant_id']);
    }

    public function test_channel_router_resolves_independently_per_tenant(): void
    {
        $router = new ChannelRouter();

        // Verify both companies resolve the same driver class (isolation is in payload, not resolver)
        $reflection = new \ReflectionClass($router);
        $prop       = $reflection->getProperty('channelMap');
        $prop->setAccessible(true);
        $map = $prop->getValue($router);

        // Both companies would resolve 'website' to the same driver class
        $this->assertArrayHasKey('website', $map);
        $this->assertTrue(
            in_array(
                \Modules\TitanEchoAssist\Contracts\ChannelDriver::class,
                class_implements($map['website']) ?: []
            ),
            'Website driver must implement ChannelDriver'
        );
    }

    public function test_idempotency_keys_are_isolated_per_session(): void
    {
        $keyA = \Modules\TitanEchoAssist\Jobs\SyncOfflineConversationJob::buildIdempotencyKey(
            'company-a-session',
            'Same message text',
            'whatsapp'
        );

        $keyB = \Modules\TitanEchoAssist\Jobs\SyncOfflineConversationJob::buildIdempotencyKey(
            'company-b-session',
            'Same message text',
            'whatsapp'
        );

        $this->assertNotSame($keyA, $keyB, 'Idempotency keys must differ across sessions/tenants');
    }

    public function test_billing_limits_isolated_per_company(): void
    {
        \TitanChatbotCacheStub::flush();

        $limit = new \Modules\TitanEchoAssist\Billing\Limits\ConversationLimit(2);

        $limit->increment(1001, '2025-06');
        $limit->increment(1001, '2025-06');

        // Company 1001 is at cap
        $this->assertTrue($limit->isExceeded(1001, '2025-06'));

        // Company 2002 must NOT be affected
        $this->assertFalse($limit->isExceeded(2002, '2025-06'));
    }

    public function test_usage_tracker_tokens_isolated_per_tenant(): void
    {
        \TitanChatbotCacheStub::flush();

        $tracker = new \Modules\TitanEchoAssist\Billing\Usage\UsageTracker();

        $tracker->record(\Modules\TitanEchoAssist\Billing\Usage\UsageRecord::fromArray([
            'prompt_tokens' => 100, 'completion_tokens' => 100, 'total_tokens' => 200,
            'tenant_id' => 1001,
        ]));

        $today = date('Y-m-d');

        // Company 1001 has 200 tokens; Company 2002 should have 0
        $this->assertSame(200, $tracker->getTotalTokens(1001, $today));
        $this->assertSame(0,   $tracker->getTotalTokens(2002, $today));
    }

    public function test_omni_manifest_requires_per_company_credential_scope(): void
    {
        $manifest = json_decode(
            file_get_contents(__DIR__ . '/../../manifests/omni_manifest.json'),
            true
        );

        foreach ($manifest['channels'] as $channel) {
            $this->assertSame(
                'company_id',
                $channel['credential_scope'],
                "Channel '{$channel['id']}' credentials must be scoped to company_id (cross-tenant isolation)"
            );
        }
    }
}
