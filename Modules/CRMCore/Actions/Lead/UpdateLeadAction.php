<?php

namespace Modules\CRMCore\Actions\Lead;

use Modules\CRMCore\Models\Lead;

class UpdateLeadAction
{
    /**
     * Update an existing lead.
     *
     * @param array<string, mixed> $data
     */
    public function handle(Lead $lead, array $data): Lead
    {
        // Prevent changing company_id – tenant boundary must not be altered after creation
        unset($data['company_id']);

        $lead->fill($data)->save();

        return $lead->refresh();
    }
}
