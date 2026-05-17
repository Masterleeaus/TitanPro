<?php

namespace Modules\ZeroPayModule\Tests\Unit;

use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as Capsule;
use Modules\ZeroPayModule\Jobs\ProcessBankDepositJob;
use Modules\ZeroPayModule\Models\ZeroPayBankDeposit;
use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Models\ZeroPayTransaction;
use Modules\ZeroPayModule\Services\BankTransferMatchingService;
use PHPUnit\Framework\TestCase;

class BankTransferMatchingServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! class_exists(Capsule::class)) {
            self::markTestSkipped('Illuminate database package is not available in this environment.');
        }

        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $schema = $capsule->schema();

        $schema->create('zeropay_sessions', function ($table): void {
            $table->increments('id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_token')->nullable();
            $table->string('reference')->nullable();
            $table->string('merchant_name')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('AUD');
            $table->string('status')->default('pending');
            $table->json('meta')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        $schema->create('zeropay_transactions', function ($table): void {
            $table->increments('id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('gateway');
            $table->string('gateway_reference')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('AUD');
            $table->string('status')->default('pending');
            $table->decimal('fee', 8, 2)->default(0);
            $table->decimal('net_amount', 12, 2)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        $schema->create('zeropay_bank_deposits', function ($table): void {
            $table->increments('id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->string('reference')->nullable();
            $table->decimal('amount', 18, 2);
            $table->string('currency', 3)->default('AUD');
            $table->string('depositor_name')->nullable();
            $table->string('depositor_account')->nullable();
            $table->timestamp('deposited_at')->nullable();
            $table->string('status')->default('pending_review');
            $table->integer('match_score')->nullable();
            $table->string('match_method')->nullable();
            $table->json('raw_data')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function test_exact_match_auto_completes_session_and_creates_transaction(): void
    {
        $service = new BankTransferMatchingService;

        $session = ZeroPaySession::create([
            'company_id' => 1,
            'user_id' => 42,
            'session_token' => 'sess-1',
            'reference' => 'REF-001',
            'merchant_name' => 'Alice Johnson',
            'amount' => 50.00,
            'currency' => 'AUD',
            'status' => 'pending',
            'created_at' => Carbon::now()->subHour(),
            'updated_at' => Carbon::now()->subHour(),
        ]);

        $deposit = ZeroPayBankDeposit::create([
            'company_id' => 1,
            'reference' => 'REF-001',
            'amount' => 50.00,
            'currency' => 'AUD',
            'depositor_name' => 'Alice Johnson',
            'deposited_at' => Carbon::now(),
            'status' => 'pending_review',
        ]);

        $result = $service->match($deposit);

        $this->assertTrue($result->matched);
        $this->assertSame('auto_matched', $result->status);
        $this->assertSame(1.0, $result->confidence);
        $this->assertSame(
            ['reference' => true, 'amount' => true, 'customer' => true, 'timestamp' => true],
            $result->matchedCriteria
        );

        $this->assertSame('matched', $deposit->fresh()->status);
        $this->assertSame('completed', $session->fresh()->status);
        $this->assertSame(1, ZeroPayTransaction::query()->count());
        $this->assertSame('completed', ZeroPayTransaction::query()->first()->status);
    }

    public function test_partial_match_is_sent_to_manual_review_queue(): void
    {
        $service = new BankTransferMatchingService;

        ZeroPaySession::create([
            'company_id' => 1,
            'user_id' => 43,
            'session_token' => 'sess-2',
            'reference' => 'REF-002',
            'merchant_name' => 'Alice Johnson',
            'amount' => 30.00,
            'currency' => 'AUD',
            'status' => 'pending',
            'created_at' => Carbon::now()->subHour(),
            'updated_at' => Carbon::now()->subHour(),
        ]);

        $deposit = ZeroPayBankDeposit::create([
            'company_id' => 1,
            'reference' => 'REF-002',
            'amount' => 30.00,
            'currency' => 'AUD',
            'depositor_name' => 'Different Name',
            'deposited_at' => Carbon::now(),
            'status' => 'pending_review',
        ]);

        $result = $service->match($deposit);

        $this->assertFalse($result->matched);
        $this->assertSame('needs_review', $result->status);
        $this->assertSame(0.75, $result->confidence);
        $this->assertFalse($result->matchedCriteria['customer']);
        $this->assertSame('pending_review', $deposit->fresh()->status);
        $this->assertSame(0, ZeroPayTransaction::query()->count());
    }

    public function test_no_match_stays_pending_review(): void
    {
        $service = new BankTransferMatchingService;

        ZeroPaySession::create([
            'company_id' => 1,
            'user_id' => 44,
            'session_token' => 'sess-3',
            'reference' => 'REF-003',
            'merchant_name' => 'Bob Carter',
            'amount' => 90.00,
            'currency' => 'AUD',
            'status' => 'pending',
            'created_at' => Carbon::now()->subDays(5),
            'updated_at' => Carbon::now()->subDays(5),
        ]);

        $deposit = ZeroPayBankDeposit::create([
            'company_id' => 1,
            'reference' => 'NOPE-REF',
            'amount' => 10.00,
            'currency' => 'AUD',
            'depositor_name' => 'Unknown',
            'deposited_at' => Carbon::now(),
            'status' => 'pending_review',
        ]);

        $result = $service->match($deposit);

        $this->assertFalse($result->matched);
        $this->assertSame('needs_review', $result->status);
        $this->assertSame(0.0, $result->confidence);
        $this->assertSame('pending_review', $deposit->fresh()->status);
        $this->assertSame(0, ZeroPayTransaction::query()->count());
    }

    public function test_process_bank_deposit_job_is_idempotent_when_re_run(): void
    {
        $service = new BankTransferMatchingService;

        ZeroPaySession::create([
            'company_id' => 1,
            'user_id' => 77,
            'session_token' => 'sess-4',
            'reference' => 'REF-004',
            'merchant_name' => 'Mary Lane',
            'amount' => 12.50,
            'currency' => 'AUD',
            'status' => 'pending',
            'created_at' => Carbon::now()->subHour(),
            'updated_at' => Carbon::now()->subHour(),
        ]);

        $payload = [
            'company_id' => 1,
            'reference' => 'REF-004',
            'amount' => 12.50,
            'currency' => 'AUD',
            'depositor_name' => 'Mary Lane',
            'deposited_at' => Carbon::now()->toDateTimeString(),
        ];

        $job = new ProcessBankDepositJob($payload);
        $job->handle($service);
        $job->handle($service);

        $this->assertSame(1, ZeroPayBankDeposit::query()->count());
        $this->assertSame(1, ZeroPayTransaction::query()->count());
        $this->assertSame('matched', ZeroPayBankDeposit::query()->first()->status);
    }
}
