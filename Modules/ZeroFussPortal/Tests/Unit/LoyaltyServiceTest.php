<?php

namespace Modules\ZeroFussPortal\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\ZeroFussPortal\Providers\ZeroFussPortalServiceProvider;
use Modules\ZeroFussPortal\Services\LoyaltyService;
use Tests\TestCase;

class LoyaltyServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->register(ZeroFussPortalServiceProvider::class);
        $this->artisan('migrate', ['--database' => 'sqlite'])->run();

        Schema::create('bookings', function ($table): void {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('company_id');
            $table->string('customer_id');
            $table->string('booking_status')->default('pending');
            $table->timestamp('service_schedule')->nullable();
            $table->timestamps();
        });

        Schema::create('einvoice_invoices', function ($table): void {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('client_id');
            $table->string('status')->default('draft');
            $table->date('due_date')->nullable();
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function test_calculates_balance_and_scoped_read_only_history_queries(): void
    {
        $service = app(LoyaltyService::class);

        $service->awardForInvoicePayment(1, 10, 5001, 20);
        $service->awardForInvoicePayment(1, 10, 5002, 5);

        DB::table('zerofuss_loyalty_points')->insert([
            'company_id' => 1,
            'customer_id' => 10,
            'points' => 10,
            'direction' => 'redeem',
            'reason' => 'Redeemed',
            'source_type' => 'redemption',
            'source_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('bookings')->insert([
            [
                'id' => 'a1111111-1111-1111-1111-111111111111',
                'company_id' => 1,
                'customer_id' => '10',
                'booking_status' => 'completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 'b2222222-2222-2222-2222-222222222222',
                'company_id' => 1,
                'customer_id' => '11',
                'booking_status' => 'completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('einvoice_invoices')->insert([
            [
                'company_id' => 1,
                'client_id' => 10,
                'status' => 'paid',
                'grand_total' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'client_id' => 11,
                'status' => 'paid',
                'grand_total' => 999,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->assertSame(15, $service->balanceForCustomer(1, 10));
        $this->assertCount(1, $service->bookingHistoryForCustomer(1, 10));
        $this->assertCount(1, $service->invoiceHistoryForCustomer(1, 10));
    }
}
