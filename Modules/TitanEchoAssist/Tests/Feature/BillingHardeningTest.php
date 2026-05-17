<?php

namespace Modules\TitanEchoAssist\Tests\Feature;

use PHPUnit\Framework\TestCase;
use Modules\TitanEchoAssist\Billing\Limits\ConversationLimit;
use Modules\TitanEchoAssist\Billing\UsageCapEnforcer;
use Modules\TitanEchoAssist\Billing\CapExceededException;
use Modules\TitanEchoAssist\Events\MessageBilled;
use Modules\TitanEchoAssist\Billing\Usage\UsageTracker;
use Modules\TitanEchoAssist\Billing\Usage\UsageRecord;

/**
 * Billing hardening tests (Blueprint 3 + 22).
 *
 * Verifies:
 * - All billing records include company_id
 * - Usage cap enforcement blocks unbounded AI calls per company
 * - MessageBilled event is dispatched for chargeable messages
 * - Billing reconciliation: billed tokens match usage tracker
 */
class BillingHardeningTest extends TestCase
{
    protected function setUp(): void
    {
        \TitanChatbotCacheStub::flush();
    }

    // ─── company_id scoping ───────────────────────────────────────────────────

    public function test_message_billed_event_always_has_company_id(): void
    {
        foreach ([1, 42, 999] as $companyId) {
            $event = MessageBilled::fromUsage($companyId, 'website', 'sess', 1, 100);
            $this->assertSame($companyId, $event->companyId, "company_id must be {$companyId}");
        }
    }

    public function test_usage_record_to_array_has_tenant_id_key(): void
    {
        $record = UsageRecord::fromArray([
            'provider'          => 'openai',
            'model'             => 'gpt-4o-mini',
            'prompt_tokens'     => 200,
            'completion_tokens' => 100,
            'total_tokens'      => 300,
            'tenant_id'         => 55,
        ]);

        $arr = $record->toArray();
        $this->assertArrayHasKey('tenant_id', $arr);
        $this->assertSame(55, $arr['tenant_id']);
    }

    // ─── Usage cap enforcement ────────────────────────────────────────────────

    public function test_usage_cap_blocks_at_limit(): void
    {
        $limit    = new ConversationLimit(3);
        $enforcer = new UsageCapEnforcer($limit);

        $companyId = 100;

        $enforcer->record($companyId);
        $enforcer->record($companyId);
        $enforcer->record($companyId);

        $this->assertFalse($enforcer->isAllowed($companyId), 'Company at cap must be blocked');
    }

    public function test_usage_cap_different_companies_independent(): void
    {
        $limit    = new ConversationLimit(2);
        $enforcer = new UsageCapEnforcer($limit);

        $enforcer->record(200); // company 200 uses 1
        $enforcer->record(200); // company 200 uses 2 → at cap
        $enforcer->record(201); // company 201 uses 1

        $this->assertFalse($enforcer->isAllowed(200), 'Company 200 should be blocked');
        $this->assertTrue($enforcer->isAllowed(201), 'Company 201 should still be allowed');
    }

    public function test_cap_exceeded_exception_thrown_when_at_limit(): void
    {
        $limit    = new ConversationLimit(1);
        $enforcer = new UsageCapEnforcer($limit);

        $enforcer->record(300);

        $this->expectException(CapExceededException::class);
        $enforcer->assertAllowed(300);
    }

    public function test_cap_exception_code_is_429(): void
    {
        $limit = new ConversationLimit(1);
        $limit->increment(400, date('Y-m'));
        $enforcer = new UsageCapEnforcer($limit);

        try {
            $enforcer->assertAllowed(400);
            $this->fail('Expected CapExceededException');
        } catch (CapExceededException $e) {
            $this->assertSame(429, $e->getCode());
        }
    }

    // ─── Billing reconciliation ───────────────────────────────────────────────

    public function test_usage_tracker_accumulates_tokens_per_company(): void
    {
        $tracker = new UsageTracker();

        $record1 = UsageRecord::fromArray([
            'provider'          => 'openai',
            'model'             => 'gpt-4o-mini',
            'prompt_tokens'     => 100,
            'completion_tokens' => 50,
            'total_tokens'      => 150,
            'tenant_id'         => 500,
        ]);
        $record2 = UsageRecord::fromArray([
            'provider'          => 'openai',
            'model'             => 'gpt-4o-mini',
            'prompt_tokens'     => 200,
            'completion_tokens' => 100,
            'total_tokens'      => 300,
            'tenant_id'         => 500,
        ]);

        $tracker->record($record1);
        $tracker->record($record2);

        $today = date('Y-m-d');
        $total = $tracker->getTotalTokens(500, $today);
        $this->assertSame(450, $total, 'Token sum must equal 150 + 300 for company 500');
    }

    public function test_usage_tracker_isolated_per_tenant(): void
    {
        $tracker = new UsageTracker();

        $tracker->record(UsageRecord::fromArray(['prompt_tokens' => 50, 'completion_tokens' => 50, 'total_tokens' => 100, 'tenant_id' => 600]));
        $tracker->record(UsageRecord::fromArray(['prompt_tokens' => 200, 'completion_tokens' => 200, 'total_tokens' => 400, 'tenant_id' => 601]));

        $today = date('Y-m-d');
        $this->assertSame(100, $tracker->getTotalTokens(600, $today));
        $this->assertSame(400, $tracker->getTotalTokens(601, $today));
    }

    public function test_billing_period_on_message_billed_event_matches_current_month(): void
    {
        $event = MessageBilled::fromUsage(1, 'whatsapp', 'sess', 5, 100);
        $this->assertSame(date('Y-m'), $event->billingPeriod);
    }

    // ─── MessageBilled event fields ───────────────────────────────────────────

    public function test_message_billed_event_has_all_required_billing_fields(): void
    {
        $event = MessageBilled::fromUsage(777, 'telegram', 'sess-t', 20, 350, 0.025);
        $arr   = $event->toArray();

        foreach (['company_id', 'channel', 'session_id', 'chatbot_id', 'billing_period', 'token_count', 'cost', 'billed_at'] as $key) {
            $this->assertArrayHasKey($key, $arr, "MessageBilled must include '{$key}'");
        }
    }

    public function test_conversation_limit_reset_on_new_billing_period(): void
    {
        $limit = new ConversationLimit(1);
        $limit->increment(888, '2025-01');

        $this->assertTrue($limit->isExceeded(888, '2025-01'));
        $this->assertFalse($limit->isExceeded(888, '2025-02'), 'New billing period must start fresh');
    }
}
