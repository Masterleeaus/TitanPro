<?php

namespace Modules\ZeroFussPortal\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Modules\ZeroFussPortal\Actions\AwardLoyaltyPointsAction;
use Modules\ZeroFussPortal\Providers\ZeroFussPortalServiceProvider;
use Tests\TestCase;

class AwardLoyaltyPointsActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->register(ZeroFussPortalServiceProvider::class);
        $this->artisan('migrate', ['--database' => 'sqlite'])->run();
    }

    public function test_awards_points_idempotently_per_source(): void
    {
        $action = app(AwardLoyaltyPointsAction::class);

        $first = $action->execute(100, 55, 25, 'Invoice paid', 'invoice_payment', 9001);
        $second = $action->execute(100, 55, 25, 'Invoice paid', 'invoice_payment', 9001);

        $this->assertSame($first->id, $second->id);
        $this->assertDatabaseCount('zerofuss_loyalty_points', 1);
        $this->assertDatabaseHas('zerofuss_loyalty_points', [
            'company_id' => 100,
            'customer_id' => 55,
            'points' => 25,
            'source_type' => 'invoice_payment',
            'source_id' => 9001,
        ]);
    }
}
