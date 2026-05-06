<?php

namespace Modules\TitanNexus\Automation\Handlers;

/** Sends follow-up through channel adapters. */
class SendFollowupHandler
{
    public function handle(): array
    {
        return ['enabled' => true, 'purpose' => 'Sends follow-up through channel adapters.'];
    }
}
