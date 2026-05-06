<?php

namespace Modules\BookingModule\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingLifecycleMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $subjectLine,
        public readonly string $intro,
        public readonly array $details = [],
        public readonly ?string $actionUrl = null,
        public readonly ?string $actionLabel = null,
    ) {}

    public function build(): static
    {
        return $this->subject($this->subjectLine)
            ->view('bookingmodule::emails.booking_lifecycle')
            ->with([
                'intro' => $this->intro,
                'details' => $this->details,
                'actionUrl' => $this->actionUrl,
                'actionLabel' => $this->actionLabel,
            ]);
    }
}
