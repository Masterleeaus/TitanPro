<?php

namespace Modules\TitanNexus\Presenters;

class LeadRecordPresenter
{
    public function statusLabel($record): string { return ucfirst(str_replace("_"," ",$record->status)); }
}
