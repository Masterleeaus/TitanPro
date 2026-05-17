<?php

namespace Modules\ZeroPayModule\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\ZeroPayModule\Actions\CreateZeroPaySessionAction;
use Modules\ZeroPayModule\Data\ZeroPaySessionData;
use Modules\ZeroPayModule\Events\PaymentCompleted;
use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Models\ZeroPayTransaction;

class PaymentSessionService
{
    public function __construct(
        protected GatewayFactory $gatewayFactory,
        protected CreateZeroPaySessionAction $createAction,
    ) {}

    public function initiate(array $data): ZeroPaySession
    {
        return DB::transaction(function () use ($data) {
            $sessionData = ZeroPaySessionData::fromArray(array_merge($data, [
                'session_token' => $data['session_token'] ?? Str::uuid()->toString(),
                'status' => 'pending',
            ]));

            $session = $this->createAction->execute($sessionData);

            $gateway = $this->gatewayFactory->make($session->gateway);
            $result = $gateway->createPayment($session);

            $session->update([
                'status' => 'open',
                'meta' => array_merge($session->meta ?? [], ['gateway_response' => $result->toArray()]),
            ]);

            return $session->refresh();
        });
    }

    public function complete(ZeroPaySession $session, string $reference): ZeroPayTransaction
    {
        $gateway = $this->gatewayFactory->make($session->gateway);
        $verification = $gateway->verifyPayment($reference);

        $fee = $gateway->calculateFee((float) $session->amount);
        $status = $verification->status === 'completed' ? 'completed' : 'pending';

        $transaction = ZeroPayTransaction::create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'user_id' => $session->user_id,
            'gateway' => $session->gateway,
            'gateway_reference' => $reference,
            'amount' => $session->amount,
            'currency' => $session->currency,
            'status' => $status,
            'fee' => $fee,
            'net_amount' => (float) $session->amount - $fee,
        ]);

        if ($transaction->status === 'completed') {
            $session->update(['status' => 'completed']);
            event(new PaymentCompleted($transaction));
        }

        return $transaction;
    }

    public function pay(ZeroPaySession $session, string $gateway, int $payerUserId): ZeroPayTransaction
    {
        $gatewayInstance = $this->gatewayFactory->make($gateway);
        $result = $gatewayInstance->createPayment($session);
        $fee = $gatewayInstance->calculateFee((float) $session->amount);

        $qrCode = $session->qrCodes()->latest()->first();

        $transaction = ZeroPayTransaction::create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'user_id' => $payerUserId,
            'gateway' => $gateway,
            'gateway_reference' => $result->reference,
            'amount' => $session->amount,
            'currency' => $session->currency,
            'status' => 'pending',
            'fee' => $fee,
            'net_amount' => (float) $session->amount - $fee,
            'meta' => [
                'gateway_response' => $result->toArray(),
                'merchant_name' => $qrCode?->merchant_name,
            ],
        ]);

        $session->update(['status' => 'processing', 'gateway' => $gateway]);

        return $transaction;
    }

    public function expire(ZeroPaySession $session): void
    {
        $session->update(['status' => 'expired']);
    }

    public function cancel(ZeroPaySession $session): void
    {
        $session->update(['status' => 'cancelled']);
    }
}
