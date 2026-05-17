<?php

namespace Modules\ZeroPayModule\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentFailed
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  array<string, mixed>  $paymentData
     */
    public function __construct(
        public readonly string $reference,
        public readonly string $reason = '',
        public readonly array $paymentData = []
    ) {}
}
