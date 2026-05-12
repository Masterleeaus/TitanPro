<?php

namespace Modules\BookingModule\Tests\Unit;

use Tests\TestCase;

class SignalsManifestContractTest extends TestCase
{
    public function test_signals_manifest_declares_required_booking_signal_routes(): void
    {
        $contents = file_get_contents(module_path('BookingModule', 'manifests/signals.json'));

        $this->assertNotFalse($contents);
        $manifest = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

        $this->assertSame('titan.signals.v1', $manifest['schema'] ?? null);
        $this->assertContains('QuoteEngine.QuoteAccepted', $manifest['consumes'] ?? []);
        $this->assertContains('EInvoice.DraftInvoice', $manifest['emits'] ?? []);
        $this->assertContains('CleanQuality.TriggerInspection', $manifest['emits'] ?? []);
        $this->assertContains('ZeroFussPortal.NotifyCustomer', $manifest['emits'] ?? []);
    }
}

