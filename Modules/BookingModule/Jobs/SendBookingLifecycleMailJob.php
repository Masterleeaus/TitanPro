<?php

namespace Modules\BookingModule\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Modules\BookingModule\Mail\BookingLifecycleMail;

class SendBookingLifecycleMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 30;

    public function __construct(
        public readonly string $to,
        public readonly string $subjectLine,
        public readonly string $intro,
        public readonly array $details = [],
        public readonly ?string $actionUrl = null,
        public readonly ?string $actionLabel = null,
    ) {}

    public function handle(): void
    {
        if (!filter_var($this->to, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        Mail::to($this->to)->send(new BookingLifecycleMail(
            $this->subjectLine,
            $this->intro,
            $this->details,
            $this->actionUrl,
            $this->actionLabel,
        ));
    }

    public function uniqueId(): string
    {
        return sha1(implode('|', [$this->to, $this->subjectLine, json_encode($this->details)]));
    }
}
