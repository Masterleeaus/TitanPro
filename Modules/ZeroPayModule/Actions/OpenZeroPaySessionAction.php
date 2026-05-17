<?php

namespace Modules\ZeroPayModule\Actions;

use Illuminate\Support\Carbon;
use LogicException;
use Modules\ZeroPayModule\Events\SessionOpened;
use Modules\ZeroPayModule\Models\ZeroPaySession;

class OpenZeroPaySessionAction
{
    /**
     * Transition a session from pending to opened and fire the SessionOpened event.
     *
     * @throws LogicException if the session is not in a pending state
     */
    public function execute(ZeroPaySession $session): ZeroPaySession
    {
        if ($session->status !== ZeroPaySession::STATUS_PENDING) {
            throw new LogicException(
                "Cannot open session '{$session->session_token}': expected status 'pending', got '{$session->status}'."
            );
        }

        $session->update([
            'status' => ZeroPaySession::STATUS_OPENED,
            'opened_at' => Carbon::now(),
        ]);

        $session->refresh();

        event(new SessionOpened($session));

        return $session;
    }
}
