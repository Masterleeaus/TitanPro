<?php

namespace Modules\TitanNexus\ViewModels;

class LeadRecordViewModel
{
    public function __construct(public readonly \Modules\TitanNexus\Models\LeadRecord $record){}
}
