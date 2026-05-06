<?php

namespace Modules\TitanNexus\Listeners\Lead;

use Modules\TitanNexus\Events\Lead\LeadCapturedFromVoice;

class CreateNexusLeadFromVoice
{
    public function handle(LeadCapturedFromVoice $event): void
    {
        // Creates or updates TitanNexus lead from voice/SMS capture.
        // Persistence is intentionally deferred to repository once host schema is bound.
    }
}
