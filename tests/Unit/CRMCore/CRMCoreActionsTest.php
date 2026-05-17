<?php

use Illuminate\Support\Facades\Event;
use Modules\CRMCore\Actions\Contact\CreateContactAction;
use Modules\CRMCore\Actions\Deal\CreateDealAction;
use Modules\CRMCore\Actions\Deal\LoseDealAction;
use Modules\CRMCore\Actions\Deal\WinDealAction;
use Modules\CRMCore\Actions\Lead\CreateLeadAction;
use Modules\CRMCore\Events\ContactCreated;
use Modules\CRMCore\Events\DealLost;
use Modules\CRMCore\Events\DealWon;
use Modules\CRMCore\Models\Company;
use Modules\CRMCore\Models\Contact;
use Modules\CRMCore\Models\Deal;
use Modules\CRMCore\Models\DealPipeline;
use Modules\CRMCore\Models\DealStage;
use Modules\CRMCore\Models\Lead;

uses(\Tests\TestCase::class, \Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    request()->headers->remove('X-Company-Id');
});

// ─── CreateLeadAction ────────────────────────────────────────────────────────

test('CreateLeadAction creates a lead scoped to company_id', function () {
    $company = Company::withoutGlobalScopes()->create(['name' => 'Acme']);

    $lead = app(CreateLeadAction::class)->handle([
        'company_id'   => $company->id,
        'title'        => 'Hot Lead',
        'contact_email'=> 'lead@acme.test',
    ]);

    expect($lead)->toBeInstanceOf(Lead::class)
        ->and($lead->company_id)->toBe($company->id)
        ->and($lead->title)->toBe('Hot Lead');
});

test('CreateLeadAction throws when company_id is missing and no context exists', function () {
    expect(fn () => app(CreateLeadAction::class)->handle(['title' => 'No Tenant']))
        ->toThrow(\RuntimeException::class, 'company_id');
});

// ─── CreateContactAction ─────────────────────────────────────────────────────

test('CreateContactAction creates a contact and dispatches ContactCreated', function () {
    Event::fake([ContactCreated::class]);

    $company = Company::withoutGlobalScopes()->create(['name' => 'Acme']);

    $contact = app(CreateContactAction::class)->handle([
        'company_id'    => $company->id,
        'first_name'    => 'Jane',
        'email_primary' => 'jane@acme.test',
    ]);

    expect($contact)->toBeInstanceOf(Contact::class)
        ->and($contact->company_id)->toBe($company->id)
        ->and($contact->first_name)->toBe('Jane');

    Event::assertDispatched(ContactCreated::class);
});

test('CreateContactAction throws when company_id is missing', function () {
    expect(fn () => app(CreateContactAction::class)->handle(['first_name' => 'No Tenant']))
        ->toThrow(\RuntimeException::class, 'company_id');
});

// ─── CreateDealAction ────────────────────────────────────────────────────────

test('CreateDealAction creates a deal scoped to company_id', function () {
    $company = Company::withoutGlobalScopes()->create(['name' => 'Acme']);

    $pipeline = DealPipeline::withoutGlobalScopes()->create([
        'company_id' => $company->id,
        'name'       => 'Main Pipeline',
        'is_default' => true,
        'position'   => 1,
    ]);

    $stage = DealStage::withoutGlobalScopes()->create([
        'pipeline_id'              => $pipeline->id,
        'name'                     => 'New',
        'position'                 => 1,
        'is_default_for_pipeline'  => true,
    ]);

    request()->headers->set('X-Company-Id', (string) $company->id);

    $deal = app(CreateDealAction::class)->handle([
        'company_id' => $company->id,
        'title'      => 'Big Deal',
        'value'      => 5000,
    ]);

    expect($deal)->toBeInstanceOf(Deal::class)
        ->and($deal->company_id)->toBe($company->id)
        ->and($deal->title)->toBe('Big Deal');
});

// ─── WinDealAction ───────────────────────────────────────────────────────────

test('WinDealAction marks deal as won and dispatches DealWon', function () {
    Event::fake([DealWon::class]);

    $company = Company::withoutGlobalScopes()->create(['name' => 'Acme']);

    $pipeline = DealPipeline::withoutGlobalScopes()->create([
        'company_id' => $company->id,
        'name'       => 'Pipeline',
        'is_default' => true,
        'position'   => 1,
    ]);

    $stage = DealStage::withoutGlobalScopes()->create([
        'pipeline_id' => $pipeline->id,
        'name'        => 'New',
        'position'    => 1,
    ]);

    $deal = Deal::withoutGlobalScopes()->create([
        'company_id'    => $company->id,
        'pipeline_id'   => $pipeline->id,
        'deal_stage_id' => $stage->id,
        'title'         => 'Deal',
    ]);

    $result = app(WinDealAction::class)->handle($deal);

    expect($result->won_at)->not->toBeNull();

    Event::assertDispatched(DealWon::class);
});

// ─── LoseDealAction ──────────────────────────────────────────────────────────

test('LoseDealAction marks deal as lost and dispatches DealLost', function () {
    Event::fake([DealLost::class]);

    $company = Company::withoutGlobalScopes()->create(['name' => 'Acme']);

    $pipeline = DealPipeline::withoutGlobalScopes()->create([
        'company_id' => $company->id,
        'name'       => 'Pipeline',
        'is_default' => true,
        'position'   => 1,
    ]);

    $stage = DealStage::withoutGlobalScopes()->create([
        'pipeline_id' => $pipeline->id,
        'name'        => 'New',
        'position'    => 1,
    ]);

    $deal = Deal::withoutGlobalScopes()->create([
        'company_id'    => $company->id,
        'pipeline_id'   => $pipeline->id,
        'deal_stage_id' => $stage->id,
        'title'         => 'Deal',
    ]);

    $result = app(LoseDealAction::class)->handle($deal, 'Budget cut');

    expect($result->lost_at)->not->toBeNull()
        ->and($result->lost_reason)->toBe('Budget cut');

    Event::assertDispatched(DealLost::class);
});

// ─── UpdateDealAction prevents tenant boundary change ────────────────────────

test('UpdateDealAction cannot change company_id', function () {
    $companyA = Company::withoutGlobalScopes()->create(['name' => 'A']);
    $companyB = Company::withoutGlobalScopes()->create(['name' => 'B']);

    $pipeline = DealPipeline::withoutGlobalScopes()->create([
        'company_id' => $companyA->id,
        'name'       => 'Pipeline',
        'is_default' => true,
        'position'   => 1,
    ]);

    $stage = DealStage::withoutGlobalScopes()->create([
        'pipeline_id' => $pipeline->id,
        'name'        => 'New',
        'position'    => 1,
    ]);

    $deal = Deal::withoutGlobalScopes()->create([
        'company_id'    => $companyA->id,
        'pipeline_id'   => $pipeline->id,
        'deal_stage_id' => $stage->id,
        'title'         => 'Deal',
    ]);

    $updated = app(\Modules\CRMCore\Actions\Deal\UpdateDealAction::class)->handle($deal, [
        'company_id' => $companyB->id,
        'title'      => 'Renamed',
    ]);

    expect($updated->company_id)->toBe($companyA->id)
        ->and($updated->title)->toBe('Renamed');
});
