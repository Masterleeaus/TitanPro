<?php

namespace Modules\ZeroPayModule\Actions;

use Modules\ZeroPayModule\Data\ZeroPaySessionData;
use Modules\ZeroPayModule\Models\ZeroPaySession;

class UpdateZeroPaySessionAction
{
    public function execute(ZeroPaySession $record, ZeroPaySessionData $data): ZeroPaySession
    {
        $record->update($data->toArray());

        return $record->refresh();
    }
}
