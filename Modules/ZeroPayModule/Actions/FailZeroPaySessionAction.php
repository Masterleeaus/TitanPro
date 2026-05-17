<?php

namespace Modules\ZeroPayModule\Actions;

use LogicException;
use Modules\ZeroPayModule\Events\PaymentFailed;
use Modules\ZeroPayModule\Models\ZeroPaySession;

class FailZeroPaySessionAction
{
    /** Statuses that can transition to failed. */
    private const FAILABLE_STATUSES = [
        ZeroPaySession::STATUS_PENDING,
        ZeroPaySession::STATUS_OPENED,
        ZeroPaySession::STATUS_PROCESSING,
    ];

    /**
     * Mark a session as failed, record the failure reason, and fire PaymentFailed.
     *
     * @throws LogicException if the session is already in a terminal state
     */
    public function execute(ZeroPaySession $session, string $reason = ''): ZeroPaySession
    {
        if (! in_array($session->status, self::FAILABLE_STATUSES, true)) {
            throw new LogicException(
                "Cannot fail session '{$session->session_token}': expected one of ["
                .implode(', ', self::FAILABLE_STATUSES)
                ."], got '{$session->status}'."
            );
        }

        $session->update([
            'status' => ZeroPaySession::STATUS_FAILED,
            'failed_reason' => $reason,
        ]);

        $session->refresh();

        event(new PaymentFailed($session->session_token, $reason, $session->toArray()));

        return $session;
    }
}
