<?php

namespace Modules\TitanEchoAssist\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Modules\TitanEchoAssist\Events\MessageBilled;

/**
 * Tests that MessageBilled event carries company_id and all required billing fields.
 */
class MessageBilledEventTest extends TestCase
{
    public function test_from_usage_creates_event_with_company_id(): void
    {
        $event = MessageBilled::fromUsage(
            companyId:  42,
            channel:    'whatsapp',
            sessionId:  'sess-001',
            chatbotId:  10,
            tokenCount: 500,
            cost:       0.002,
        );

        $this->assertSame(42, $event->companyId);
    }

    public function test_from_usage_sets_channel(): void
    {
        $event = MessageBilled::fromUsage(1, 'telegram', 'sess-1', 5);
        $this->assertSame('telegram', $event->channel);
    }

    public function test_from_usage_sets_billing_period_as_current_month(): void
    {
        $event = MessageBilled::fromUsage(1, 'website', 'sess-1', 5);
        $this->assertRegExp('/^\d{4}-\d{2}$/', $event->billingPeriod);
        $this->assertSame(date('Y-m'), $event->billingPeriod);
    }

    public function test_from_usage_sets_token_count(): void
    {
        $event = MessageBilled::fromUsage(1, 'website', 'sess', 5, 300);
        $this->assertSame(300, $event->tokenCount);
    }

    public function test_from_usage_sets_cost(): void
    {
        $event = MessageBilled::fromUsage(1, 'website', 'sess', 5, 0, 1.25);
        $this->assertSame(1.25, $event->cost);
    }

    public function test_from_usage_sets_billed_at_as_iso8601(): void
    {
        $event = MessageBilled::fromUsage(1, 'website', 'sess', 5);
        $this->assertNotEmpty($event->billedAt);
        // Verify it parses as a valid date
        $ts = strtotime($event->billedAt);
        $this->assertNotFalse($ts);
    }

    public function test_to_array_has_all_required_keys(): void
    {
        $event = MessageBilled::fromUsage(7, 'messenger', 'sess-abc', 15, 100, 0.01);
        $arr   = $event->toArray();

        $this->assertArrayHasKey('company_id', $arr);
        $this->assertArrayHasKey('channel', $arr);
        $this->assertArrayHasKey('session_id', $arr);
        $this->assertArrayHasKey('chatbot_id', $arr);
        $this->assertArrayHasKey('billing_period', $arr);
        $this->assertArrayHasKey('token_count', $arr);
        $this->assertArrayHasKey('cost', $arr);
        $this->assertArrayHasKey('billed_at', $arr);
    }

    public function test_to_array_company_id_matches(): void
    {
        $event = MessageBilled::fromUsage(99, 'voice', 'sess-v', 20);
        $arr   = $event->toArray();
        $this->assertSame(99, $arr['company_id']);
    }

    public function test_direct_constructor_works(): void
    {
        $event = new MessageBilled(
            companyId:     5,
            channel:       'website',
            sessionId:     'sess-x',
            chatbotId:     2,
            billingPeriod: '2025-01',
            tokenCount:    50,
            cost:          0.005,
            billedAt:      '2025-01-15T10:00:00+00:00',
        );

        $this->assertSame(5, $event->companyId);
        $this->assertSame('2025-01', $event->billingPeriod);
    }

    public function test_event_class_exists(): void
    {
        $this->assertTrue(class_exists(MessageBilled::class));
    }
}
