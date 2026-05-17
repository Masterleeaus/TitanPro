<?php

namespace Modules\ZeroPayModule\Actions;

use Modules\ZeroPayModule\Models\ZeroPaySession;

class DeleteZeroPaySessionAction
{
    public function execute(ZeroPaySession $record): bool
    {
        return (bool) $record->delete();
    }
}
