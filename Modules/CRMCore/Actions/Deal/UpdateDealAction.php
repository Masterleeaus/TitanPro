<?php

namespace Modules\CRMCore\Actions\Deal;

use Modules\CRMCore\Models\Deal;

class UpdateDealAction
{
    /**
     * Update an existing deal.
     *
     * @param array<string, mixed> $data
     */
    public function handle(Deal $deal, array $data): Deal
    {
        // Prevent tenant boundary changes
        unset($data['company_id']);

        $deal->fill($data)->save();

        return $deal->refresh();
    }
}
