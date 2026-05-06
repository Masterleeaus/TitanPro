<?php

namespace Modules\TitanNexus\Actions;

/** Pushes qualified leads into Ground Zero booking intake. */
class PushLeadToGroundZeroAction
{
    public function handle(): array
    {
        return ['enabled' => true, 'purpose' => 'Pushes qualified leads into Ground Zero booking intake.'];
    }
}
