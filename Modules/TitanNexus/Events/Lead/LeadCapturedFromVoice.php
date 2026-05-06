<?php

namespace Modules\TitanNexus\Events\Lead;

class LeadCapturedFromVoice
{
    public function __construct(public array $lead) {}
}
