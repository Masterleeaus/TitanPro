<?php

namespace Modules\BookingModule\Tests\Unit;

use Tests\TestCase;

class QuoteAcceptedConsumerTest extends TestCase
{
    public function test_event_provider_registers_quote_accepted_consumer(): void
    {
        $contents = file_get_contents(module_path('BookingModule', 'Providers/EventServiceProvider.php'));

        $this->assertNotFalse($contents);
        $this->assertStringContainsString("'QuoteEngine.QuoteAccepted'", $contents);
        $this->assertStringContainsString('CreateBookingFromQuoteAcceptedSignal::class', $contents);
    }
}
