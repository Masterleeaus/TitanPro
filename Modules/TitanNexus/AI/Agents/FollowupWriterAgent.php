<?php

namespace Modules\TitanNexus\AI\Agents;

/** AI card for personalized overdue follow-ups. */
class FollowupWriterAgent
{
    public function handle(): array
    {
        return ['enabled' => true, 'purpose' => 'AI card for personalized overdue follow-ups.'];
    }
}
