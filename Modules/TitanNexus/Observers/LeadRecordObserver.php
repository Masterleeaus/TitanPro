<?php

namespace Modules\TitanNexus\Observers;

class LeadRecordObserver
{
    public function creating($record): void { $record->company_id ??= tenant_company_id(); }
}
