<?php

namespace Modules\ZeroPayModule\Actions;

use Illuminate\Support\Carbon;
use LogicException;
use Modules\ZeroPayModule\Events\PaymentCompleted;
use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Models\ZeroPayTransaction;

class CompleteZeroPaySessionAction
{
    /** Valid statuses from which a session can be completed. */
    private const COMPLETABLE_STATUSES = [
        ZeroPaySession::STATUS_OPENED,
        ZeroPaySession::STATUS_PROCESSING,
    ];

    /**
     * Mark a session as completed, record the transaction, and fire PaymentCompleted.
     *
     * @param  string  $gatewayReference  The gateway's payment reference / transaction ID
     * @param  float  $fee  Gateway fee amount (defaults to 0)
     *
     * @throws LogicException if the session is not in a completable state
     */
    public function execute(
        ZeroPaySession $session,
        string $gatewayReference,
        float $fee = 0.0
    ): ZeroPayTransaction {
        if (! in_array($session->status, self::COMPLETABLE_STATUSES, true)) {
            throw new LogicException(
                "Cannot complete session '{$session->session_token}': expected one of ["
                .implode(', ', self::COMPLETABLE_STATUSES)
                ."], got '{$session->status}'."
            );
        }

        $transaction = ZeroPayTransaction::create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'user_id' => $session->user_id,
            'gateway' => $session->gateway,
            'gateway_reference' => $gatewayReference,
            'amount' => $session->amount,
            'currency' => $session->currency,
            'status' => 'completed',
            'fee' => $fee,
            'net_amount' => (float) $session->amount - $fee,
        ]);

        $session->update([
            'status' => ZeroPaySession::STATUS_COMPLETED,
            'completed_at' => Carbon::now(),
        ]);

        $session->refresh();

        event(new PaymentCompleted($gatewayReference, $session->toArray()));

        return $transaction;
    }
}
