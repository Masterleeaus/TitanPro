<?php

namespace Modules\ZeroPayModule\Services;

use Minishlink\WebPush\MessageSentReport;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Modules\ZeroPayModule\Models\ZeroPayPushSubscription;
use Psr\Log\LoggerInterface;

/**
 * Sends VAPID-signed Web Push notifications to all subscribed devices
 * belonging to a user (or company).
 */
class WebPushService
{
    private WebPush $webPush;

    public function __construct(private readonly LoggerInterface $logger)
    {
        $auth = [
            'VAPID' => [
                'subject' => config('zeropay-module.vapid.subject', 'mailto:admin@zeropay.io'),
                'publicKey' => config('zeropay-module.vapid.public_key', ''),
                'privateKey' => config('zeropay-module.vapid.private_key', ''),
            ],
        ];

        $this->webPush = new WebPush($auth);
        $this->webPush->setReuseVAPIDHeaders(true);
    }

    /**
     * Send a notification to every push subscription owned by $userId.
     *
     * @param  array<string,mixed>  $payload  Keys: title, body, url (optional), event (optional)
     */
    public function notifyUser(int $userId, array $payload): void
    {
        $subscriptions = ZeroPayPushSubscription::where('user_id', $userId)->get();

        if ($subscriptions->isEmpty()) {
            return;
        }

        $json = json_encode($payload);

        foreach ($subscriptions as $sub) {
            $this->webPush->queueNotification(
                Subscription::create([
                    'endpoint' => $sub->endpoint,
                    'contentEncoding' => $sub->content_encoding,
                    'keys' => [
                        'p256dh' => $sub->p256dh_key,
                        'auth' => $sub->auth_key,
                    ],
                ]),
                $json
            );
        }

        /** @var MessageSentReport $report */
        foreach ($this->webPush->flush() as $report) {
            if (! $report->isSuccess()) {
                $this->logger->warning('[WebPush] Delivery failed', [
                    'endpoint' => $report->getEndpoint(),
                    'reason' => $report->getReason(),
                    'expired' => $report->isSubscriptionExpired(),
                ]);

                // Remove stale / invalid subscriptions automatically.
                if ($report->isSubscriptionExpired()) {
                    ZeroPayPushSubscription::where('endpoint', $report->getEndpoint())->delete();
                }
            }
        }
    }

    /**
     * Send a notification to every push subscription owned by a company.
     *
     * @param  array<string,mixed>  $payload
     */
    public function notifyCompany(int $companyId, array $payload): void
    {
        $userIds = ZeroPayPushSubscription::where('company_id', $companyId)
            ->distinct()
            ->pluck('user_id');

        foreach ($userIds as $userId) {
            $this->notifyUser((int) $userId, $payload);
        }
    }
}
