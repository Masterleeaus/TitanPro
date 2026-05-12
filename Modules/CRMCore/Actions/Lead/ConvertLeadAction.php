<?php

namespace Modules\CRMCore\Actions\Lead;

use Modules\CRMCore\Actions\Contact\CreateContactAction;
use Modules\CRMCore\Actions\Deal\CreateDealAction;
use Modules\CRMCore\Models\Contact;
use Modules\CRMCore\Models\Deal;
use Modules\CRMCore\Models\Lead;
use Modules\CRMCore\Models\LeadStatus;

class ConvertLeadAction
{
    public function __construct(
        private readonly CreateContactAction $createContact,
        private readonly CreateDealAction $createDeal,
    ) {}

    /**
     * Convert a lead to a Contact (and optionally a Deal).
     *
     * Returns an array with 'contact' and optionally 'deal' keys.
     *
     * @param array<string, mixed> $overrides
     * @return array{contact: Contact, deal: Deal|null}
     */
    public function handle(Lead $lead, array $overrides = []): array
    {
        if (filled($lead->converted_at)) {
            throw new \RuntimeException("Lead #{$lead->getKey()} has already been converted.");
        }

        // Build contact data from lead
        $contactData = array_filter(array_merge([
            'company_id'    => $lead->company_id,
            'first_name'    => $lead->contact_name ?? $lead->title,
            'email_primary' => $lead->contact_email,
            'phone_primary' => $lead->contact_phone,
        ], $overrides['contact'] ?? []), fn ($v) => $v !== null && $v !== '');

        $contact = $this->createContact->handle($contactData);

        $deal = null;
        if (! empty($overrides['create_deal'])) {
            $dealData = array_merge([
                'company_id'   => $lead->company_id,
                'title'        => $lead->title,
                'value'        => $lead->value,
                'contact_id'   => $contact->getKey(),
                'description'  => $lead->description,
            ], $overrides['deal'] ?? []);

            $deal = $this->createDeal->handle($dealData);
        }

        // Mark lead as converted
        $convertedStatus = LeadStatus::query()->where('is_final', true)->orderBy('position')->value('id');

        $lead->forceFill([
            'converted_at'           => now(),
            'converted_to_contact_id'=> $contact->getKey(),
            'converted_to_deal_id'   => $deal?->getKey(),
            'lead_status_id'         => $convertedStatus ?? $lead->lead_status_id,
        ])->save();

        return ['contact' => $contact, 'deal' => $deal];
    }
}
