<?php

namespace Modules\BookingModule\Tests\Unit;

use Tests\TestCase;

class BookingLifecycleSignalListenerTest extends TestCase
{
    public function test_lifecycle_signal_listener_emits_required_downstream_signals(): void
    {
        $contents = file_get_contents(module_path('BookingModule', 'Listeners/EmitBookingLifecycleSignals.php'));

        $this->assertNotFalse($contents);
        $this->assertStringContainsString('EInvoice.DraftInvoice', $contents);
        $this->assertStringContainsString('CleanQuality.TriggerInspection', $contents);
        $this->assertStringContainsString('ZeroFussPortal.NotifyCustomer', $contents);
    }
}

