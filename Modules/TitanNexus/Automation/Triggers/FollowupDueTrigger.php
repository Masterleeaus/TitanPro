<?php

namespace Modules\TitanNexus\Automation\Triggers;

/** Detects follow-up due events. */
class FollowupDueTrigger
{
    public function handle(): array
    {
        return ['enabled' => true, 'purpose' => 'Detects follow-up due events.'];
    }
}
