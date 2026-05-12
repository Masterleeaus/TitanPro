<?php

use Illuminate\Support\Facades\Event;
use Modules\CRMCore\Events\ContactCreated;
use Modules\CRMCore\Events\DealLost;
use Modules\CRMCore\Events\DealWon;
use Modules\CRMCore\Models\Company;
use Modules\CRMCore\Models\Contact;
use Modules\CRMCore\Models\Deal;
use Modules\CRMCore\Models\DealPipeline;
use Modules\CRMCore\Models\DealStage;
use Modules\CRMCore\Scopes\ScopedByCompany;

beforeEach(function () {
    request()->headers->remove('X-Company-Id');
});

test('crmcore contact creation is scoped to company_id', function () {
    Event::fake([ContactCreated::class]);

    $company = Company::create(['name' => 'Company A']);
    request()->headers->set('X-Company-Id', (string) $company->id);

    $contact = Contact::create([
        'company_id' => $company->id,
        'first_name' => 'Alice',
        'email_primary' => 'alice@example.test',
    ]);

    expect($contact->company_id)->toBe($company->id);
    expect(Contact::query()->count())->toBe(1);

    Event::assertDispatched(ContactCreated::class, fn (ContactCreated $event): bool => $event->contact->is($contact));
});

test('crmcore cross tenant contact query returns zero results', function () {
    $companyA = Company::create(['name' => 'Company A']);
    $companyB = Company::create(['name' => 'Company B']);

    Contact::withoutGlobalScopes()->create([
        'company_id' => $companyA->id,
        'first_name' => 'Alpha',
        'email_primary' => 'alpha@example.test',
    ]);

    Contact::withoutGlobalScopes()->create([
        'company_id' => $companyB->id,
        'first_name' => 'Beta',
        'email_primary' => 'beta@example.test',
    ]);

    request()->headers->set('X-Company-Id', (string) $companyA->id);
    expect(Contact::query()->where('company_id', $companyB->id)->count())->toBe(0);

    request()->headers->set('X-Company-Id', (string) $companyB->id);
    expect(Contact::query()->where('company_id', $companyA->id)->count())->toBe(0);
});

test('crmcore deal won and lost states dispatch the correct signals', function () {
    Event::fake([DealWon::class, DealLost::class]);

    $company = Company::create(['name' => 'Company A']);
    request()->headers->set('X-Company-Id', (string) $company->id);

    $pipeline = DealPipeline::withoutGlobalScope(ScopedByCompany::class)->create([
        'company_id' => $company->id,
        'name' => 'Default Pipeline',
        'is_default' => true,
        'position' => 1,
    ]);

    $stage = DealStage::create([
        'pipeline_id' => $pipeline->id,
        'name' => 'Qualified',
        'position' => 1,
    ]);

    $wonDeal = Deal::create([
        'company_id' => $company->id,
        'pipeline_id' => $pipeline->id,
        'deal_stage_id' => $stage->id,
        'title' => 'Won Deal',
        'won_at' => now(),
    ]);

    $lostDeal = Deal::create([
        'company_id' => $company->id,
        'pipeline_id' => $pipeline->id,
        'deal_stage_id' => $stage->id,
        'title' => 'Lost Deal',
        'lost_at' => now(),
    ]);

    Event::assertDispatched(DealWon::class, fn (DealWon $event): bool => $event->deal->is($wonDeal));
    Event::assertDispatched(DealLost::class, fn (DealLost $event): bool => $event->deal->is($lostDeal));
});
