<?php

namespace Modules\TitanEchoAssist\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyPortalBookingRequestJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly int $bookingRequestId) {}

    public function handle(): void
    {
        Log::info('Portal booking request notification queued.', [
            'booking_request_id' => $this->bookingRequestId,
        ]);
    }
}
