<?php

namespace Modules\CRMCore\Listeners;

use Illuminate\Support\Arr;
use Modules\CRMCore\Models\Contact;

class SyncContactFromBookingCustomerCreated
{
    public function handle(mixed $event): void
    {
        $payload = $this->normalizePayload($event);
        $companyId = Arr::get($payload, 'company_id');
        $email = Arr::get($payload, 'email');

        if (! is_numeric($companyId) || ! filled($email)) {
            return;
        }

        $companyId = (int) $companyId;

        $attributes = [
            'company_id' => $companyId,
            'first_name' => Arr::get($payload, 'first_name', Arr::get($payload, 'name', 'Customer')),
            'last_name' => Arr::get($payload, 'last_name'),
            'email_primary' => $email,
            'phone_primary' => Arr::get($payload, 'phone'),
            'address_street' => Arr::get($payload, 'address_street'),
            'address_city' => Arr::get($payload, 'address_city'),
            'address_state' => Arr::get($payload, 'address_state'),
            'address_postal_code' => Arr::get($payload, 'address_postal_code'),
            'address_country' => Arr::get($payload, 'address_country'),
        ];

        $contact = Contact::withoutGlobalScopes()
            ->where('company_id', $companyId)
            ->where('email_primary', $email)
            ->first();

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
