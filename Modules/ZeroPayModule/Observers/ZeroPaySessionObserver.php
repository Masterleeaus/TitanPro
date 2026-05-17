<?php

namespace Modules\ZeroPayModule\Observers;

class ZeroPaySessionObserver
{
    public function creating($record): void
    {
        $record->company_id ??= tenant_company_id();
    }
}
