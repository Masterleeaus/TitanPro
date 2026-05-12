<?php

namespace Modules\ZeroFussPortal\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Modules\ZeroFussPortal\Providers\ZeroFussPortalServiceProvider;
use Tests\TestCase;

class PortalFeatureFlowsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->register(ZeroFussPortalServiceProvider::class);
        $this->artisan('migrate', ['--database' => 'sqlite'])->run();

        Schema::table('users', function ($table): void {
            if (! Schema::hasColumn('users', 'company_id')) {
                $table->unsignedBigInteger('company_id')->nullable();
            }
        });
    }

    public function test_feedback_submission_referral_creation_and_loyalty_award_on_payment(): void
    {
        $customer = User::factory()->create([
            'organization_id' => 10,
            'company_id' => 10,
        ]);

        $this->actingAs($customer, 'customer');

        $feedbackResponse = $this->postJson('/api/zerofuss-portal/feedback', [
            'message' => 'Great service',
            'rating' => 5,
        ]);

        $feedbackResponse->assertCreated();
        $this->assertDatabaseHas('zerofuss_feedback', [
            'company_id' => 10,
            'customer_id' => $customer->id,
            'message' => 'Great service',
        ]);

        $referralResponse = $this->postJson('/api/zerofuss-portal/referrals', [
            'referred_email' => 'new@example.com',
            'referred_name' => 'New Customer',
        ]);

        $referralResponse->assertCreated();
        $this->assertDatabaseHas('zerofuss_referrals', [
            'company_id' => 10,
            'customer_id' => $customer->id,
            'referred_email' => 'new@example.com',
        ]);

        event('EInvoice.InvoicePaid', [[
            'company_id' => 10,
            'customer_id' => $customer->id,
            'invoice_id' => 9001,
            'points' => 42,
        ]]);

        $this->assertDatabaseHas('zerofuss_loyalty_points', [
            'company_id' => 10,
            'customer_id' => $customer->id,
            'source_type' => 'invoice_payment',
            'source_id' => 9001,
            'points' => 42,
        ]);
    }

    public function test_cross_customer_data_isolation(): void
    {
        $customerA = User::factory()->create(['organization_id' => 1, 'company_id' => 1]);
        $customerB = User::factory()->create(['organization_id' => 1, 'company_id' => 1]);

        \Modules\ZeroFussPortal\Models\Referral::query()->create([
            'company_id' => 1,
            'customer_id' => $customerB->id,
            'referral_code' => 'B-REF-CODE',
            'referred_email' => 'b@example.com',
            'status' => 'pending',
        ]);

        $this->actingAs($customerA, 'customer');

        $response = $this->getJson('/api/zerofuss-portal/referrals');

        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    }
}
