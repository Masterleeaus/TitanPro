<?php

namespace Modules\BookingModule\Listeners;

use Modules\BookingModule\Actions\Bookings\CreateBookingFromQuoteAcceptedAction;

class CreateBookingFromQuoteAcceptedSignal
{
    public function __construct(protected CreateBookingFromQuoteAcceptedAction $createFromQuote) {}

    public function handle(mixed $payload = null): void
    {
        // String-based Laravel events may pass listener args wrapped in an indexed array.
        if (is_array($payload) && isset($payload[0]) && is_array($payload[0])) {
            $payload = $payload[0];
        }

        if (! is_array($payload)) {
            return;
        }

        $quoteId = $payload['quote_id'] ?? $payload['id'] ?? null;
        if ($quoteId === null) {
            return;
        }

        $this->createFromQuote->execute($payload);
    }
}
