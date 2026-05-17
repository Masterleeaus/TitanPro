<?php

namespace Modules\CRMCore\Actions\Contact;

use Modules\CRMCore\Models\Contact;
use Modules\CRMCore\Scopes\ScopedByCompany;

class CreateContactAction
{
    /**
     * Create a new tenant-scoped contact.
     *
     * @param array<string, mixed> $data
     */
    public function handle(array $data): Contact
    {
        $companyId = $data['company_id'] ?? ScopedByCompany::resolveCompanyId();

        if (! is_numeric($companyId)) {
            throw new \RuntimeException('company_id is required to create a contact.');
        }

        $data['company_id'] = (int) $companyId;
        $data['is_active'] ??= true;

        return Contact::create($data);
    }
}
