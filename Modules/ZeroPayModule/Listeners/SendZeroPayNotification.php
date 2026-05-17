<?php

namespace Modules\ZeroPayModule\Listeners;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Modules\ZeroPayModule\Events\PaymentCompleted;
use Modules\ZeroPayModule\Events\PaymentFailed;
use Modules\ZeroPayModule\Events\PaymentPending;
use Modules\ZeroPayModule\Events\SessionExpiring;
use Modules\ZeroPayModule\Models\ZeroPayTransaction;
use Modules\ZeroPayModule\Notifications\PaymentCompletedNotification;
use Modules\ZeroPayModule\Notifications\PaymentFailedNotification;
use Modules\ZeroPayModule\Notifications\PaymentPendingNotification;
use Modules\ZeroPayModule\Notifications\SessionExpiringNotification;

class SendZeroPayNotification implements ShouldQueue
{
    /**
     * Handle any of the 7 ZeroPay events and dispatch the appropriate notification
     * to the session owner.
     */
    public function handle(object $event): void
    {
        [$notifiable, $notification] = $this->resolveNotification($event);

        if ($notifiable === null || $notification === null) {
            return;
        }

        $notifiable->notify($notification);
    }

    /**
     * @return array{0: Notifiable|null, 1: Notification|null}
     */
    private function resolveNotification(object $event): array
    {
        if ($event instanceof PaymentCompleted) {
            $transaction = ZeroPayTransaction::where('gateway_reference', $event->reference)->first();
            $userId = $transaction?->user_id ?? ($event->paymentData['user_id'] ?? null);

            if (! $userId) {
                return [null, null];
            }

            $user = $this->findUser($userId);
            $amount = number_format((float) ($event->paymentData['amount'] ?? 0), 2);
            $currency = $event->paymentData['currency'] ?? 'AUD';

            return [$user, new PaymentCompletedNotification($event->reference, $amount, $currency, $event->paymentData)];
        }

        if ($event instanceof PaymentFailed) {
            $transaction = ZeroPayTransaction::where('gateway_reference', $event->reference)->first();
            $userId = $transaction?->user_id ?? ($event->paymentData['user_id'] ?? null);

            if (! $userId) {
                return [null, null];
            }

            return [$this->findUser($userId), new PaymentFailedNotification($event->reference, $event->reason, $event->paymentData)];
        }

        if ($event instanceof PaymentPending) {
            $session = $event->session;
            if (! $session->user_id) {
                return [null, null];
            }

            return [$this->findUser($session->user_id), new PaymentPendingNotification($session)];
        }

        if ($event instanceof SessionExpiring) {
            $session = $event->session;
            if (! $session->user_id) {
                return [null, null];
            }

            return [$this->findUser($session->user_id), new SessionExpiringNotification($session)];
        }

        // session.created, session.opened, payment.started — push only (handled by dedicated listeners)
        return [null, null];
    }

    /**
     * Resolve the user model instance for the given user ID.
     */
    private function findUser(int|string $userId): mixed
    {
        /** @var class-string $userClass */
        $userClass = config('auth.providers.users.model', User::class);

        return $userClass::find($userId);
    }
}
