<?php

namespace Modules\ZeroPayModule\Tests\Unit;

use LogicException;
use Modules\ZeroPayModule\Actions\CompleteZeroPaySessionAction;
use Modules\ZeroPayModule\Actions\ExpireZeroPaySessionAction;
use Modules\ZeroPayModule\Actions\FailZeroPaySessionAction;
use Modules\ZeroPayModule\Actions\OpenZeroPaySessionAction;
use Modules\ZeroPayModule\Models\ZeroPaySession;
use PHPUnit\Framework\TestCase;

class CreateZeroPaySessionActionTest extends TestCase
{
    // ── OpenZeroPaySessionAction ──────────────────────────────────────────────

    public function test_open_action_rejects_non_pending_session(): void
    {
        $session = new ZeroPaySession(['status' => ZeroPaySession::STATUS_OPENED, 'session_token' => 'tok']);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessageMatches('/Cannot open session/');

        (new OpenZeroPaySessionAction)->execute($session);
    }

    // ── CompleteZeroPaySessionAction ──────────────────────────────────────────

    public function test_complete_action_rejects_pending_session(): void
    {
        $session = new ZeroPaySession(['status' => ZeroPaySession::STATUS_PENDING, 'session_token' => 'tok']);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessageMatches('/Cannot complete session/');

        (new CompleteZeroPaySessionAction)->execute($session, 'ref-123');
    }

    public function test_complete_action_rejects_failed_session(): void
    {
        $session = new ZeroPaySession(['status' => ZeroPaySession::STATUS_FAILED, 'session_token' => 'tok']);

        $this->expectException(LogicException::class);

        (new CompleteZeroPaySessionAction)->execute($session, 'ref-123');
    }

    public function test_complete_action_rejects_expired_session(): void
    {
        $session = new ZeroPaySession(['status' => ZeroPaySession::STATUS_EXPIRED, 'session_token' => 'tok']);

        $this->expectException(LogicException::class);

        (new CompleteZeroPaySessionAction)->execute($session, 'ref-123');
    }

    // ── FailZeroPaySessionAction ──────────────────────────────────────────────

    public function test_fail_action_rejects_completed_session(): void
    {
        $session = new ZeroPaySession(['status' => ZeroPaySession::STATUS_COMPLETED, 'session_token' => 'tok']);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessageMatches('/Cannot fail session/');

        (new FailZeroPaySessionAction)->execute($session, 'some reason');
    }

    public function test_fail_action_rejects_expired_session(): void
    {
        $session = new ZeroPaySession(['status' => ZeroPaySession::STATUS_EXPIRED, 'session_token' => 'tok']);

        $this->expectException(LogicException::class);

        (new FailZeroPaySessionAction)->execute($session);
    }

    public function test_fail_action_rejects_already_failed_session(): void
    {
        $session = new ZeroPaySession(['status' => ZeroPaySession::STATUS_FAILED, 'session_token' => 'tok']);

        $this->expectException(LogicException::class);

        (new FailZeroPaySessionAction)->execute($session);
    }

    // ── ExpireZeroPaySessionAction ────────────────────────────────────────────

    public function test_expire_action_is_idempotent_for_already_expired_session(): void
    {
        $session = new ZeroPaySession(['status' => ZeroPaySession::STATUS_EXPIRED, 'session_token' => 'tok']);

        // Must not throw; returns the same session unchanged.
        $result = (new ExpireZeroPaySessionAction)->execute($session);

        $this->assertSame(ZeroPaySession::STATUS_EXPIRED, $result->status);
    }

    public function test_expire_action_rejects_completed_session(): void
    {
        $session = new ZeroPaySession(['status' => ZeroPaySession::STATUS_COMPLETED, 'session_token' => 'tok']);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessageMatches('/Cannot expire session/');

        (new ExpireZeroPaySessionAction)->execute($session);
    }

    public function test_expire_action_rejects_failed_session(): void
    {
        $session = new ZeroPaySession(['status' => ZeroPaySession::STATUS_FAILED, 'session_token' => 'tok']);

        $this->expectException(LogicException::class);

        (new ExpireZeroPaySessionAction)->execute($session);
    }

    public function test_expire_action_rejects_processing_session(): void
    {
        $session = new ZeroPaySession(['status' => ZeroPaySession::STATUS_PROCESSING, 'session_token' => 'tok']);

        $this->expectException(LogicException::class);

        (new ExpireZeroPaySessionAction)->execute($session);
    }
}
