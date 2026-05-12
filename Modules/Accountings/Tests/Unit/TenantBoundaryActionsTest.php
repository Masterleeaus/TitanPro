<?php

namespace Modules\Accountings\Tests\Unit;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Accountings\Actions\LookupLedgerAction;
use Modules\Accountings\Actions\PostInvoiceJournalAction;
use Tests\TestCase;

class TenantBoundaryActionsTest extends TestCase
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

    public function test_lookup_ledger_rejects_cross_tenant_company_override(): void
    {
        $this->actingAsCompany(7);

        $this->expectException(AuthorizationException::class);

        app(LookupLedgerAction::class)->execute(['company_id' => 8]);
    }

    public function test_lookup_ledger_defaults_to_authenticated_company_scope(): void
    {
        DB::table('acc_journalh')->insert([
            ['company_id' => 7, 'no_journal' => 'A-1', 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => 8, 'no_journal' => 'B-1', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->actingAsCompany(7);

        $rows = app(LookupLedgerAction::class)->execute(['limit' => 50]);

        $this->assertCount(1, $rows);
        $this->assertSame(7, (int) $rows->first()->company_id);
    }

    public function test_post_invoice_journal_uses_authenticated_company_when_company_id_missing(): void
    {
        $this->actingAsCompany(7);

        $journal = app(PostInvoiceJournalAction::class)->execute([
            'invoice_id' => 1001,
            'description' => 'Tenant boundary test',
        ]);

        $this->assertSame(7, (int) $journal->company_id);
    }

    public function test_post_invoice_journal_rejects_cross_tenant_company_override(): void
    {
        $this->actingAsCompany(7);

        $this->expectException(AuthorizationException::class);

        app(PostInvoiceJournalAction::class)->execute([
            'company_id' => 9,
            'invoice_id' => 1002,
        ]);
    }

    private function actingAsCompany(int $companyId): void
    {
        $user = new class extends Authenticatable {};
        $user->id = $companyId + 1000;
        $user->company_id = $companyId;

        $this->be($user);
    }
}
