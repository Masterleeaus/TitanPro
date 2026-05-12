<?php

namespace Modules\Accountings\Tests\Unit;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Modules\Accountings\Actions\GenerateAccountantExportPackAction;
use Modules\Accountings\Actions\MatchBankDepositAction;
use Modules\Accountings\Actions\PostGstForInvoiceAction;
use Modules\Accountings\Actions\PostInvoiceJournalAction;
use Modules\Accountings\Actions\RecalculateReceivableAgingAction;
use Modules\Accountings\Events\GstPosted;
use Modules\Accountings\Events\InvoiceJournalPosted;
use Modules\Accountings\Events\LedgerAdjustmentSuggested;
use Modules\Accountings\Events\ReceivablesAged;
use Modules\Accountings\Events\StatementGenerated;
use Modules\Accountings\Events\WriteOffPosted;
use Modules\Accountings\Events\ZeroPayPaymentConfirmed;
use Modules\Accountings\Services\LedgerSyncService;
use Modules\Accountings\Tools\SuggestJournalCorrectionTool;
use Tests\TestCase;

class AccountingsEventDispatchTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('acc_journalh')) {
            Schema::create('acc_journalh', function ($table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable();
                $table->string('no_journal')->nullable();
                $table->date('journal_date')->nullable();
                $table->string('reff_journal')->nullable();
                $table->string('remark')->nullable();
                $table->unsignedBigInteger('typejournal_id')->nullable();
                $table->timestamps();
            });
        }
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('acc_journalh');
        parent::tearDown();
    }

    public function test_post_invoice_journal_dispatches_invoice_journal_posted(): void
    {
        Event::fake([InvoiceJournalPosted::class]);

        app(PostInvoiceJournalAction::class)->execute(['company_id' => 7, 'invoice_id' => 1001]);

        Event::assertDispatched(InvoiceJournalPosted::class, fn (InvoiceJournalPosted $event): bool => $this->hasEnvelope($event->payload));
    }

    public function test_post_gst_for_invoice_dispatches_gst_posted(): void
    {
        Event::fake([GstPosted::class]);

        app(PostGstForInvoiceAction::class)->execute(['id' => 9, 'company_id' => 7, 'tax_total' => 25.50]);

        Event::assertDispatched(GstPosted::class, fn (GstPosted $event): bool => $this->hasEnvelope($event->payload));
    }

    public function test_recalculate_receivable_aging_dispatches_receivables_aged(): void
    {
        Event::fake([ReceivablesAged::class]);

        app(RecalculateReceivableAgingAction::class)->execute([
            ['due_date' => now()->subDays(5)->toDateString(), 'balance_due' => 140],
        ]);

        Event::assertDispatched(ReceivablesAged::class, fn (ReceivablesAged $event): bool => $this->hasEnvelope($event->payload));
    }

    public function test_generate_accountant_export_pack_dispatches_statement_generated(): void
    {
        Event::fake([StatementGenerated::class]);

        app(GenerateAccountantExportPackAction::class)->execute(['company_id' => 7]);

        Event::assertDispatched(StatementGenerated::class, fn (StatementGenerated $event): bool => $this->hasEnvelope($event->payload));
    }

    public function test_match_bank_deposit_dispatches_payment_confirmed_when_matched(): void
    {
        Event::fake([ZeroPayPaymentConfirmed::class]);

        app(MatchBankDepositAction::class)->execute(
            ['reference' => 'INV-1001', 'amount' => 150, 'company_id' => 7],
            [['reference' => 'INV-1001', 'amount_due' => 150, 'company_id' => 7]]
        );

        Event::assertDispatched(ZeroPayPaymentConfirmed::class, fn (ZeroPayPaymentConfirmed $event): bool => $this->hasEnvelope($event->payload));
    }

    public function test_suggest_journal_correction_dispatches_ledger_adjustment_suggested(): void
    {
        Event::fake([LedgerAdjustmentSuggested::class]);

        app(SuggestJournalCorrectionTool::class)->handle(['company_id' => 7, 'actor_id' => 101]);

        Event::assertDispatched(LedgerAdjustmentSuggested::class, fn (LedgerAdjustmentSuggested $event): bool => $this->hasEnvelope($event->payload));
    }

    public function test_post_write_off_dispatches_write_off_posted(): void
    {
        Event::fake([WriteOffPosted::class]);
        $this->actingAsCompany(7);

        app(LedgerSyncService::class)->postWriteOff(['id' => 88, 'company_id' => 7, 'balance_due' => 25.10]);

        Event::assertDispatched(WriteOffPosted::class, fn (WriteOffPosted $event): bool => $this->hasEnvelope($event->payload));
    }

    private function hasEnvelope(array $payload): bool
    {
        return array_key_exists('company_id', $payload)
            && array_key_exists('actor_id', $payload)
            && array_key_exists('occurred_at', $payload);
    }

    private function actingAsCompany(int $companyId): void
    {
        $user = new class extends Authenticatable {};
        $user->id = $companyId + 1000;
        $user->company_id = $companyId;

        $this->be($user);
    }
}
