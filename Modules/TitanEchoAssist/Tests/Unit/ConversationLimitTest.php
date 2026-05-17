<?php

namespace Modules\TitanEchoAssist\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Modules\TitanEchoAssist\Billing\Limits\ConversationLimit;
use Modules\TitanEchoAssist\Billing\UsageCapEnforcer;
use Modules\TitanEchoAssist\Billing\CapExceededException;

/**
 * Tests that ConversationLimit and UsageCapEnforcer enforce per-company usage caps.
 *
 * Blueprint 22: all cap decisions scoped by company_id.
 */
class ConversationLimitTest extends TestCase
{
    protected function setUp(): void
    {
        \TitanChatbotCacheStub::flush();
    }

    // ─── ConversationLimit ────────────────────────────────────────────────────

    public function test_limit_is_not_exceeded_when_no_usage(): void
    {
        $limit = new ConversationLimit(100);
        $this->assertFalse($limit->isExceeded(1, '2025-01'));
    }

    public function test_limit_is_exceeded_at_cap(): void
    {
        $limit = new ConversationLimit(2);

        $limit->increment(5, '2025-06');
        $limit->increment(5, '2025-06');

        $this->assertTrue($limit->isExceeded(5, '2025-06'));
    }

    public function test_limit_not_exceeded_just_below_cap(): void
    {
        $limit = new ConversationLimit(3);

        $limit->increment(7, '2025-06');
        $limit->increment(7, '2025-06');

        $this->assertFalse($limit->isExceeded(7, '2025-06'));
    }

    public function test_increment_returns_new_count(): void
    {
        $limit = new ConversationLimit(100);

        $count1 = $limit->increment(10, '2025-01');
        $count2 = $limit->increment(10, '2025-01');

        $this->assertSame(1, $count1);
        $this->assertSame(2, $count2);
    }

    public function test_get_usage_returns_current_count(): void
    {
        $limit = new ConversationLimit(100);

        $limit->increment(20, '2025-03');
        $limit->increment(20, '2025-03');
        $limit->increment(20, '2025-03');

        $this->assertSame(3, $limit->getUsage(20, '2025-03'));
    }

    public function test_reset_clears_usage(): void
    {
        $limit = new ConversationLimit(100);

        $limit->increment(30, '2025-04');
        $limit->increment(30, '2025-04');
        $limit->reset(30, '2025-04');

        $this->assertSame(0, $limit->getUsage(30, '2025-04'));
    }

    public function test_different_companies_are_isolated(): void
    {
        $limit = new ConversationLimit(2);

        $limit->increment(100, '2025-01');
        $limit->increment(100, '2025-01');
        // Company 100 is at cap; Company 101 should not be affected
        $this->assertTrue($limit->isExceeded(100, '2025-01'));
        $this->assertFalse($limit->isExceeded(101, '2025-01'));
    }

    public function test_different_billing_periods_are_isolated(): void
    {
        $limit = new ConversationLimit(1);

        $limit->increment(50, '2025-01');
        // Company 50 is at cap for 2025-01 but not 2025-02
        $this->assertTrue($limit->isExceeded(50, '2025-01'));
        $this->assertFalse($limit->isExceeded(50, '2025-02'));
    }

    public function test_get_cap_returns_configured_value(): void
    {
        $limit = new ConversationLimit(500);
        $this->assertSame(500, $limit->getCap());
    }

    // ─── UsageCapEnforcer ─────────────────────────────────────────────────────

    public function test_enforcer_allows_when_under_cap(): void
    {
        $limit    = new ConversationLimit(100);
        $enforcer = new UsageCapEnforcer($limit);

        $this->assertTrue($enforcer->isAllowed(200));
    }

    public function test_enforcer_denies_when_at_cap(): void
    {
        $limit    = new ConversationLimit(1);
        $period   = date('Y-m');
        $limit->increment(300, $period);

        $enforcer = new UsageCapEnforcer($limit);
        $this->assertFalse($enforcer->isAllowed(300));
    }

    public function test_enforcer_assert_allowed_throws_cap_exceeded_exception(): void
    {
        $limit = new ConversationLimit(1);
        $limit->increment(400, date('Y-m'));

        $enforcer = new UsageCapEnforcer($limit);

        $this->expectException(CapExceededException::class);
        $enforcer->assertAllowed(400, 'whatsapp');
    }

    public function test_cap_exceeded_exception_carries_company_id(): void
    {
        $limit = new ConversationLimit(1);
        $limit->increment(500, date('Y-m'));

        $enforcer = new UsageCapEnforcer($limit);

        try {
            $enforcer->assertAllowed(500);
            $this->fail('Expected CapExceededException');
        } catch (CapExceededException $e) {
            $this->assertSame(500, $e->companyId);
        }
    }

    public function test_cap_exceeded_exception_has_429_code(): void
    {
        $e = new CapExceededException('exceeded', 1);
        $this->assertSame(429, $e->getCode());
    }

    public function test_enforcer_record_increments_counter(): void
    {
        $limit    = new ConversationLimit(100);
        $enforcer = new UsageCapEnforcer($limit);

        $count = $enforcer->record(600);
        $this->assertSame(1, $count);

        $count2 = $enforcer->record(600);
        $this->assertSame(2, $count2);
    }
}
