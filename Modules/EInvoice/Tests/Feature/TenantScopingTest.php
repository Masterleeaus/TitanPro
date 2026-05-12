<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\EInvoice\Entities\Invoice;

uses(Tests\TestCase::class, RefreshDatabase::class);

/**
 * Cross-tenant isolation security tests.
 *
 * Blueprint ref: 19-TENANCY-IDENTITY-BOUNDARY-BLUEPRINT.md
 */
describe('EInvoice Tenant Scoping', function (): void {

    it('assigns company_id from authenticated user on Invoice creation', function (): void {
        // Create two fake users with different company IDs
        $userA = \Illuminate\Foundation\Testing\WithFaker::class;

        $companyA = \App\Models\Company::factory()->create();
        $companyB = \App\Models\Company::factory()->create();

        $userA = \App\Models\User::factory()->create(['company_id' => $companyA->id]);
        $userB = \App\Models\User::factory()->create(['company_id' => $companyB->id]);

        // Authenticate as User A and create an invoice
        $this->actingAs($userA);

        $invoiceA = Invoice::create([
            'currency'    => 'AUD',
            'status'      => 'draft',
            'grand_total' => 500.00,
        ]);

        expect($invoiceA->company_id)->toBe($companyA->id);
    });

    it('tenant A cannot see tenant B invoices via Invoice::all()', function (): void {
        $companyA = \App\Models\Company::factory()->create();
        $companyB = \App\Models\Company::factory()->create();

        $userA = \App\Models\User::factory()->create(['company_id' => $companyA->id]);
        $userB = \App\Models\User::factory()->create(['company_id' => $companyB->id]);

        // Create invoices for each tenant directly (bypassing scope)
        Invoice::withoutGlobalScopes()->create([
            'company_id'  => $companyA->id,
            'currency'    => 'AUD',
            'status'      => 'draft',
            'grand_total' => 100.00,
        ]);

        Invoice::withoutGlobalScopes()->create([
            'company_id'  => $companyB->id,
            'currency'    => 'USD',
            'status'      => 'sent',
            'grand_total' => 200.00,
        ]);

        // Authenticate as User A — should only see tenant A's invoice
        $this->actingAs($userA);

        $results = Invoice::all();

        expect($results)->toHaveCount(1);
        expect($results->first()->company_id)->toBe($companyA->id);

        // Authenticate as User B — should only see tenant B's invoice
        $this->actingAs($userB);

        $resultsB = Invoice::all();

        expect($resultsB)->toHaveCount(1);
        expect($resultsB->first()->company_id)->toBe($companyB->id);
    });

    it('withoutGlobalScopes bypasses tenant filter (admin use-case)', function (): void {
        $companyA = \App\Models\Company::factory()->create();
        $companyB = \App\Models\Company::factory()->create();

        Invoice::withoutGlobalScopes()->create([
            'company_id' => $companyA->id, 'currency' => 'AUD', 'status' => 'draft', 'grand_total' => 1.00,
        ]);
        Invoice::withoutGlobalScopes()->create([
            'company_id' => $companyB->id, 'currency' => 'USD', 'status' => 'draft', 'grand_total' => 2.00,
        ]);

        expect(Invoice::withoutGlobalScopes()->count())->toBe(2);
    });
});
