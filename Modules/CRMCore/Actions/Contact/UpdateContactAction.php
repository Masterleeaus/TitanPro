<?php

namespace Modules\CRMCore\Actions\Contact;

use Modules\CRMCore\Models\Contact;

class UpdateContactAction
{
    /**
     * Update an existing contact.
     *
     * @param array<string, mixed> $data
     */
    public function handle(Contact $contact, array $data): Contact
    {
        // Prevent tenant boundary changes
        unset($data['company_id']);

        $contact->fill($data)->save();

        return $contact->refresh();
    }
}
