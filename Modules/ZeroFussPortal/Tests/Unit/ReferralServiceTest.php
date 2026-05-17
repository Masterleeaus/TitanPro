<?php

namespace Modules\ZeroFussPortal\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\ZeroFussPortal\Providers\ZeroFussPortalServiceProvider;
use Modules\ZeroFussPortal\Services\ReferralService;
use Tests\TestCase;

class ReferralServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->register(ZeroFussPortalServiceProvider::class);
        $this->artisan('migrate', ['--database' => 'sqlite'])->run();
    }

    public function test_creates_referral_and_counts_successful_referrals(): void
    {
        $service = app(ReferralService::class);

        $pending = $service->create(1, 77, 'friend@example.com', 'Friend');
        $converted = $service->create(1, 77, 'buyer@example.com', 'Buyer');
        $converted->update(['status' => 'converted']);

        $this->assertSame('pending', $pending->status);
        $this->assertCount(2, $service->listForCustomer(1, 77));
        $this->assertSame(1, $service->successfulCount(1, 77));
    }
}
