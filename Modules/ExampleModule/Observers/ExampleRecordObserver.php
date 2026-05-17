<?php

namespace Modules\ExampleModule\Observers;

class ExampleRecordObserver
{
    public function creating($record): void { $record->company_id ??= tenant_company_id(); }
}
