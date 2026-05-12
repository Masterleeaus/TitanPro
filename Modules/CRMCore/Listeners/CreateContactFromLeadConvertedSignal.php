<?php

namespace Modules\CRMCore\Listeners;

use Illuminate\Support\Arr;
use Modules\CRMCore\Models\Contact;

class CreateContactFromLeadConvertedSignal
{
    public function handle(mixed $event): void
    {
        $payload = $this->normalizePayload($event);
        $companyId = Arr::get($payload, 'company_id');

        if (! is_numeric($companyId)) {
            return;
        }

        $companyId = (int) $companyId;
        $email = Arr::get($payload, 'contact_email');
        $phone = Arr::get($payload, 'contact_phone');

        if (! filled($email) && ! filled($phone)) {
            return;
        }

        $query = Contact::withoutGlobalScopes()->where('company_id', $companyId);
        if (filled($email)) {
            $query->where('email_primary', $email);
        } else {
            $query->where('phone_primary', $phone);
        }

        $contact = $query->first();

        $attributes = [
            'company_id' => $companyId,
            'first_name' => Arr::get($payload, 'first_name', Arr::get($payload, 'contact_name', 'Lead')),
            'last_name' => Arr::get($payload, 'last_name'),
            'email_primary' => $email,
            'phone_primary' => $phone,
            'job_title' => Arr::get($payload, 'job_title'),
            'department' => Arr::get($payload, 'department'),
            'description' => Arr::get($payload, 'description'),
        ];

        if ($contact) {
            $contact->fill($attributes)->save();

            return;
        }

        Contact::withoutGlobalScopes()->create($attributes);
    }

    /**
     * @return array<string, mixed>
     */
    private function normalizePayload(mixed $event): array
    {
        if (is_array($event)) {
            return $event;
        }

        if (is_object($event) && property_exists($event, 'payload') && is_array($event->payload)) {
            return $event->payload;
        }

        if (is_object($event) && method_exists($event, 'toArray')) {
            $array = $event->toArray();
            if (is_array($array)) {
                return $array;
            }
        }

        return [];
    }
}
