<?php

namespace Modules\ZeroPayModule\Actions;

use LogicException;
use Modules\ZeroPayModule\Events\SessionExpiring;
use Modules\ZeroPayModule\Models\ZeroPaySession;

class ExpireZeroPaySessionAction
{
    /** Statuses eligible for expiry. */
    private const EXPIRABLE_STATUSES = [
        ZeroPaySession::STATUS_PENDING,
        ZeroPaySession::STATUS_OPENED,
    ];

    /**
     * Mark a session as expired and fire the SessionExpiring event.
     *
     * This action is idempotent: calling it on an already-expired session is a no-op.
     *
     * @throws LogicException if the session is in a non-expirable terminal state
     */
    public function execute(ZeroPaySession $session): ZeroPaySession
    {
        // Idempotent: already expired — do nothing.
        if ($session->status === ZeroPaySession::STATUS_EXPIRED) {
            return $session;
        }

        if (! in_array($session->status, self::EXPIRABLE_STATUSES, true)) {
            throw new LogicException(
                "Cannot expire session '{$session->session_token}': expected one of ["
                .implode(', ', self::EXPIRABLE_STATUSES)
                ."], got '{$session->status}'."
            );
        }

        $session->update(['status' => ZeroPaySession::STATUS_EXPIRED]);

        $session->refresh();

        event(new SessionExpiring($session));

        return $session;
    }
}
