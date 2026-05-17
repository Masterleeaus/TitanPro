<?php

namespace Modules\ZeroPayModule\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\ZeroPayModule\Events\PaymentCompleted;
use Modules\ZeroPayModule\Events\PaymentFailed;
use Modules\ZeroPayModule\Events\PaymentPending;
use Modules\ZeroPayModule\Events\PaymentStarted;
use Modules\ZeroPayModule\Events\SessionExpiring;
use Modules\ZeroPayModule\Events\SessionOpened;
use Modules\ZeroPayModule\Events\ZeroPaySessionCreated;
use Modules\ZeroPayModule\Listeners\HandlePaymentCompleted;
use Modules\ZeroPayModule\Listeners\HandlePaymentFailed;
use Modules\ZeroPayModule\Listeners\HandleZeroPaySessionCreated;
use Modules\ZeroPayModule\Listeners\RecordZeroPayNotification;
use Modules\ZeroPayModule\Listeners\SendPushForPaymentCompleted;
use Modules\ZeroPayModule\Listeners\SendPushForPaymentFailed;
use Modules\ZeroPayModule\Listeners\SendPushForPaymentPending;
use Modules\ZeroPayModule\Listeners\SendPushForPaymentStarted;
use Modules\ZeroPayModule\Listeners\SendPushForSessionExpiring;
use Modules\ZeroPayModule\Listeners\SendPushForSessionOpened;
use Modules\ZeroPayModule\Listeners\SendZeroPayNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ZeroPaySessionCreated::class => [
            HandleZeroPaySessionCreated::class,
            RecordZeroPayNotification::class,
        ],
        SessionOpened::class => [
            SendPushForSessionOpened::class,
            RecordZeroPayNotification::class,
        ],
        PaymentStarted::class => [
            SendPushForPaymentStarted::class,
            RecordZeroPayNotification::class,
        ],
        PaymentPending::class => [
            SendPushForPaymentPending::class,
            SendZeroPayNotification::class,
            RecordZeroPayNotification::class,
        ],
        PaymentCompleted::class => [
            HandlePaymentCompleted::class,
            SendPushForPaymentCompleted::class,
            SendZeroPayNotification::class,
            RecordZeroPayNotification::class,
        ],
        PaymentFailed::class => [
            HandlePaymentFailed::class,
            SendPushForPaymentFailed::class,
            SendZeroPayNotification::class,
            RecordZeroPayNotification::class,
        ],
        SessionExpiring::class => [
            SendPushForSessionExpiring::class,
            SendZeroPayNotification::class,
            RecordZeroPayNotification::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
