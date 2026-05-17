<?php

namespace Modules\ZeroFussPortal\Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Modules\ZeroFussPortal\Listeners\AwardPointsOnPaymentListener;
use Modules\ZeroFussPortal\Providers\EventServiceProvider;
use Modules\ZeroFussPortal\Providers\ZeroFussPortalServiceProvider;
use Tests\TestCase;

class InvoicePaidListenerWiringTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->register(ZeroFussPortalServiceProvider::class);
        $this->artisan('migrate', ['--database' => 'sqlite'])->run();
    }

    public function test_einvoice_invoice_paid_signal_is_wired_to_award_points_listener(): void
    {
        /** @var EventServiceProvider $provider */
        $provider = app(EventServiceProvider::class);
        $listen = (new \ReflectionClass($provider))->getProperty('listen');
        $listen->setAccessible(true);

        $mapping = $listen->getValue($provider);

        $this->assertArrayHasKey('EInvoice.InvoicePaid', $mapping);
        $this->assertContains(AwardPointsOnPaymentListener::class, $mapping['EInvoice.InvoicePaid']);
    }
}
