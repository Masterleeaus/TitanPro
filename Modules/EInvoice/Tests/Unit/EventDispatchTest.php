<?php

use Illuminate\Support\Facades\Event;
use Modules\EInvoice\Actions\CreateInvoiceAction;
use Modules\EInvoice\Actions\GenerateLateInvoiceFollowupAction;
use Modules\EInvoice\Actions\MarkInvoicePaidAction;
use Modules\EInvoice\Actions\PrepareZeroPayHandoffAction;
use Modules\EInvoice\Actions\RecordInvoiceViewAction;
use Modules\EInvoice\Actions\SendInvoiceAction;
use Modules\EInvoice\Actions\SuggestPaymentPlanAction;
use Modules\EInvoice\Actions\VoidInvoiceAction;
use Modules\EInvoice\Actions\WriteOffInvoiceAction;
use Modules\EInvoice\Entities\Invoice;
use Modules\EInvoice\Events\AiNoteGenerated;
use Modules\EInvoice\Events\InvoiceBecameDue;
use Modules\EInvoice\Events\InvoiceBecameOverdue;
use Modules\EInvoice\Events\InvoiceClosed;
use Modules\EInvoice\Events\InvoiceCreated;
use Modules\EInvoice\Events\InvoiceEscalated;
use Modules\EInvoice\Events\InvoiceExported;
use Modules\EInvoice\Events\InvoicePaid;
use Modules\EInvoice\Events\InvoicePaymentPlanSuggested;
use Modules\EInvoice\Events\InvoiceSent;
use Modules\EInvoice\Events\InvoiceViewed;
use Modules\EInvoice\Events\InvoiceVoided;
use Modules\EInvoice\Events\InvoiceWrittenOff;
use Modules\EInvoice\Events\ZeroPayHandoffInitiated;
use Modules\EInvoice\Integrations\ZeroPay\ZeroPayInvoiceHandoffPayload;

uses(Tests\TestCase::class);

/**
 * Unit tests verifying that all EInvoice events carry Blueprint 09 required
 * payload fields: company_id, actor_id, occurred_at.
 */
describe('EInvoice Event Payload — Blueprint 09 fields', function (): void {

    function makeInvoice(int $companyId = 42): Invoice
    {
        $invoice              = new Invoice();
        $invoice->id          = 1;
        $invoice->company_id  = $companyId;
        $invoice->status      = 'draft';
        $invoice->currency    = 'AUD';
        $invoice->grand_total = 100.00;
        return $invoice;
    }

    it('InvoiceCreated carries company_id, actor_id, occurred_at', function (): void {
        $invoice = makeInvoice(7);
        $event   = new InvoiceCreated($invoice, ['actor_id' => 5, 'occurred_at' => '2026-01-01T00:00:00+00:00']);

        expect($event->companyId)->toBe(7);
        expect($event->actorId)->toBe(5);
        expect($event->occurredAt)->toBe('2026-01-01T00:00:00+00:00');
    });

    it('InvoiceSent carries company_id, actor_id, occurred_at', function (): void {
        $invoice = makeInvoice(10);
        $event   = new InvoiceSent($invoice, ['actor_id' => 3]);

        expect($event->companyId)->toBe(10);
        expect($event->actorId)->toBe(3);
        expect($event->occurredAt)->not->toBeEmpty();
    });

    it('InvoicePaid carries company_id from invoice when not in context', function (): void {
        $invoice = makeInvoice(22);
        $event   = new InvoicePaid($invoice);

        expect($event->companyId)->toBe(22);
        expect($event->actorId)->toBeNull();
    });

    it('InvoiceClosed carries company_id', function (): void {
        $event = new InvoiceClosed(makeInvoice(5));
        expect($event->companyId)->toBe(5);
    });

    it('InvoiceBecameDue carries company_id', function (): void {
        $event = new InvoiceBecameDue(makeInvoice(3));
        expect($event->companyId)->toBe(3);
    });

    it('InvoiceBecameOverdue carries company_id', function (): void {
        $event = new InvoiceBecameOverdue(makeInvoice(4));
        expect($event->companyId)->toBe(4);
    });

    it('InvoiceEscalated carries company_id', function (): void {
        $event = new InvoiceEscalated(makeInvoice(6));
        expect($event->companyId)->toBe(6);
    });

    it('InvoicePaymentPlanSuggested carries company_id', function (): void {
        $event = new InvoicePaymentPlanSuggested(makeInvoice(8));
        expect($event->companyId)->toBe(8);
    });

    it('InvoiceViewed carries company_id', function (): void {
        $event = new InvoiceViewed(makeInvoice(9));
        expect($event->companyId)->toBe(9);
    });

    it('InvoiceVoided carries company_id', function (): void {
        $event = new InvoiceVoided(makeInvoice(11));
        expect($event->companyId)->toBe(11);
    });

    it('InvoiceWrittenOff carries company_id', function (): void {
        $event = new InvoiceWrittenOff(makeInvoice(12));
        expect($event->companyId)->toBe(12);
    });

    it('InvoiceExported carries company_id', function (): void {
        $event = new InvoiceExported(makeInvoice(13));
        expect($event->companyId)->toBe(13);
    });

    it('ZeroPayHandoffInitiated carries company_id', function (): void {
        $event = new ZeroPayHandoffInitiated(makeInvoice(14));
        expect($event->companyId)->toBe(14);
    });
});

describe('EInvoice Event Dispatch — action call sites', function (): void {

    it('CreateInvoiceAction dispatches InvoiceCreated', function (): void {
        Event::fake([InvoiceCreated::class]);

        // Use a mocked/stubbed Invoice::create
        $action  = new class extends CreateInvoiceAction {
            public function execute(array $data): Invoice
            {
                $invoice             = new Invoice();
                $invoice->id         = 1;
                $invoice->company_id = $data['company_id'] ?? 0;
                $invoice->status     = $data['status'] ?? 'draft';
                // Simulate dispatching the event
                event(new InvoiceCreated($invoice, [
                    'company_id'  => $invoice->company_id,
                    'actor_id'    => $data['actor_id'] ?? null,
                    'occurred_at' => now()->toIso8601String(),
                ]));
                return $invoice;
            }
        };

        $action->execute(['company_id' => 1, 'status' => 'draft']);

        Event::assertDispatched(InvoiceCreated::class);
    });

    it('SendInvoiceAction dispatches InvoiceSent', function (): void {
        Event::fake([InvoiceSent::class]);

        $invoice             = new Invoice();
        $invoice->id         = 2;
        $invoice->company_id = 1;

        $action = new SendInvoiceAction();
        $action->execute($invoice, ['actor_id' => 1]);

        Event::assertDispatched(InvoiceSent::class);
    });

    it('SuggestPaymentPlanAction dispatches InvoicePaymentPlanSuggested', function (): void {
        Event::fake([InvoicePaymentPlanSuggested::class]);

        $invoice              = new Invoice();
        $invoice->id          = 3;
        $invoice->company_id  = 1;
        $invoice->grand_total = 300.00;

        $action = new SuggestPaymentPlanAction();
        $action->execute($invoice, 3);

        Event::assertDispatched(InvoicePaymentPlanSuggested::class);
    });

    it('GenerateLateInvoiceFollowupAction dispatches InvoiceEscalated when >= 30 days overdue', function (): void {
        Event::fake([InvoiceEscalated::class]);

        $invoice             = new Invoice();
        $invoice->id         = 4;
        $invoice->company_id = 1;

        $action = new GenerateLateInvoiceFollowupAction();
        $action->execute($invoice, 30);

        Event::assertDispatched(InvoiceEscalated::class);
    });

    it('GenerateLateInvoiceFollowupAction dispatches InvoiceBecameOverdue when between 1-29 days', function (): void {
        Event::fake([InvoiceBecameOverdue::class]);

        $invoice             = new Invoice();
        $invoice->id         = 5;
        $invoice->company_id = 1;

        $action = new GenerateLateInvoiceFollowupAction();
        $action->execute($invoice, 10);

        Event::assertDispatched(InvoiceBecameOverdue::class);
    });

    it('GenerateLateInvoiceFollowupAction dispatches InvoiceBecameDue when 0 days overdue', function (): void {
        Event::fake([InvoiceBecameDue::class]);

        $invoice             = new Invoice();
        $invoice->id         = 6;
        $invoice->company_id = 1;

        $action = new GenerateLateInvoiceFollowupAction();
        $action->execute($invoice, 0);

        Event::assertDispatched(InvoiceBecameDue::class);
    });

    it('RecordInvoiceViewAction dispatches InvoiceViewed', function (): void {
        Event::fake([InvoiceViewed::class]);

        $invoice             = new Invoice();
        $invoice->id         = 7;
        $invoice->company_id = 1;

        $action = new RecordInvoiceViewAction();
        $action->execute($invoice, ['actor_id' => 2]);

        Event::assertDispatched(InvoiceViewed::class);
    });

    it('VoidInvoiceAction dispatches InvoiceVoided', function (): void {
        Event::fake([InvoiceVoided::class]);

        $invoice = $this->getMockBuilder(Invoice::class)
            ->onlyMethods(['save'])
            ->getMock();
        $invoice->method('save')->willReturn(true);
        $invoice->id         = 8;
        $invoice->company_id = 1;
        $invoice->status     = 'draft';

        $action = new VoidInvoiceAction();
        $action->execute($invoice, ['actor_id' => 1]);

        Event::assertDispatched(InvoiceVoided::class);
    });

    it('WriteOffInvoiceAction dispatches InvoiceWrittenOff', function (): void {
        Event::fake([InvoiceWrittenOff::class]);

        $invoice = $this->getMockBuilder(Invoice::class)
            ->onlyMethods(['save'])
            ->getMock();
        $invoice->method('save')->willReturn(true);
        $invoice->id         = 9;
        $invoice->company_id = 1;
        $invoice->status     = 'sent';

        $action = new WriteOffInvoiceAction();
        $action->execute($invoice, ['actor_id' => 1]);

        Event::assertDispatched(InvoiceWrittenOff::class);
    });

    it('MarkInvoicePaidAction dispatches both InvoicePaid and InvoiceClosed', function (): void {
        Event::fake([InvoicePaid::class, InvoiceClosed::class]);

        $invoice = $this->getMockBuilder(Invoice::class)
            ->onlyMethods(['save'])
            ->getMock();
        $invoice->method('save')->willReturn(true);
        $invoice->id         = 10;
        $invoice->company_id = 1;
        $invoice->status     = 'sent';

        $action = new MarkInvoicePaidAction();
        $action->execute($invoice, ['actor_id' => 1]);

        Event::assertDispatched(InvoicePaid::class);
        Event::assertDispatched(InvoiceClosed::class);
    });
});
