<?php

namespace Modules\ExampleModule\Presenters;

class ExampleRecordPresenter
{
    public function statusLabel($record): string { return ucfirst(str_replace("_"," ",$record->status)); }
}
