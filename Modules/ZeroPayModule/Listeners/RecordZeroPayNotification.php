<?php

namespace Modules\ZeroPayModule\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ZeroPayModule\Events\PaymentCompleted;
use Modules\ZeroPayModule\Events\PaymentFailed;
use Modules\ZeroPayModule\Events\PaymentPending;
use Modules\ZeroPayModule\Events\PaymentStarted;
use Modules\ZeroPayModule\Events\SessionExpiring;
use Modules\ZeroPayModule\Events\SessionOpened;
use Modules\ZeroPayModule\Events\ZeroPaySessionCreated;
use Modules\ZeroPayModule\Models\ZeroPayNotification;
use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Models\ZeroPayTransaction;

class RecordZeroPayNotification implements ShouldQueue
{
    /**
     * Handle any of the 7 ZeroPay events and write a record to zeropay_notifications.
     */
    public function handle(object $event): void
    {
        $records = $this->buildRecords($event);

        foreach ($records as $record) {
            ZeroPayNotification::create($record);
        }
    }

    /**
     * Build one record per applicable channel for the given event.
     *
     * @return array<int, array<string, mixed>>
     */
    private function buildRecords(object $event): array
    {
        if ($event instanceof ZeroPaySessionCreated) {
            return $this->recordsFromSessionData(
                $event->sessionData,
                'session.created',
                ['push', 'portal']
            );
        }

        if ($event instanceof SessionOpened) {
            return $this->recordsFromSession(
                $event->session,
                'session.opened',
                ['push', 'portal']
            );
        }

        if ($event instanceof PaymentStarted) {
            return $this->recordsFromSession(
                $event->session,
                'payment.started',
                ['push', 'portal']
            );
        }

        if ($event instanceof PaymentPending) {
            return $this->recordsFromSession(
                $event->session,
                'payment.pending',
                ['push', 'portal']
            );
        }

        if ($event instanceof PaymentCompleted) {
            $transaction = ZeroPayTransaction::where('gateway_reference', $event->reference)->first();
            $userId = $transaction?->user_id ?? ($event->paymentData['user_id'] ?? null);
            $companyId = $transaction?->company_id ?? ($event->paymentData['company_id'] ?? null);
            $sessionId = $transaction?->session_id ?? null;

            if (! $userId || ! $companyId) {
                return [];
            }

            return $this->buildRecordSet(
                (int) $companyId,
                (int) $userId,
                $sessionId,
                'payment.completed',
                ['email', 'push', 'portal', 'omni', 'titan_go'],
                ['reference' => $event->reference, 'amount' => $event->paymentData['amount'] ?? null, 'currency' => $event->paymentData['currency'] ?? 'AUD']
            );
        }

        if ($event instanceof PaymentFailed) {
            $transaction = ZeroPayTransaction::where('gateway_reference', $event->reference)->first();
            $userId = $transaction?->user_id ?? ($event->paymentData['user_id'] ?? null);
            $companyId = $transaction?->company_id ?? ($event->paymentData['company_id'] ?? null);
            $sessionId = $transaction?->session_id ?? null;

            if (! $userId || ! $companyId) {
                return [];
            }

            return $this->buildRecordSet(
                (int) $companyId,
                (int) $userId,
                $sessionId,
                'payment.failed',
                ['email', 'push', 'portal', 'omni'],
                ['reference' => $event->reference, 'reason' => $event->reason]
            );
        }

        if ($event instanceof SessionExpiring) {
            return $this->recordsFromSession(
                $event->session,
                'session.expiring',
                ['push', 'portal']
            );
        }

        return [];
    }

    /**
     * Build records from a ZeroPaySession model.
     *
     * @param  ZeroPaySession  $session
     * @param  array<int, string>  $channels
     * @return array<int, array<string, mixed>>
     */
    private function recordsFromSession(mixed $session, string $eventType, array $channels): array
    {
        if (! $session->user_id || ! $session->company_id) {
            return [];
        }

        return $this->buildRecordSet(
            (int) $session->company_id,
            (int) $session->user_id,
            $session->id,
            $eventType,
            $channels,
            ['session_token' => $session->session_token]
        );
    }

    /**
     * Build records from raw session data array (ZeroPaySessionCreated).
     *
     * @param  array<string, mixed>  $sessionData
     * @param  array<int, string>  $channels
     * @return array<int, array<string, mixed>>
     */
    private function recordsFromSessionData(array $sessionData, string $eventType, array $channels): array
    {
        $userId = $sessionData['user_id'] ?? null;
        $companyId = $sessionData['company_id'] ?? null;
        $sessionId = $sessionData['id'] ?? null;

        if (! $userId || ! $companyId) {
            return [];
        }

        return $this->buildRecordSet(
            (int) $companyId,
            (int) $userId,
            $sessionId,
            $eventType,
            $channels,
            $sessionData
        );
    }

    /**
     * Build one record per channel.
     *
     * @param  array<int, string>  $channels
     * @param  array<string, mixed>  $payload
     * @return array<int, array<string, mixed>>
     */
    private function buildRecordSet(
        int $companyId,
        int $userId,
        mixed $sessionId,
        string $eventType,
        array $channels,
        array $payload
    ): array {
        $now = now();
        $records = [];

        foreach ($channels as $channel) {
            $records[] = [
                'company_id' => $companyId,
                'user_id' => $userId,
                'session_id' => $sessionId,
                'event' => $eventType,
                'channel' => $channel,
                'status' => 'sent',
                'payload' => $payload,
                'sent_at' => $now,
            ];
        }

        return $records;
    }
}
