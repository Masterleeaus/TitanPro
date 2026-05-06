<?php

namespace Modules\BookingModule\Workflows;

class BookingLifecycleWorkflow
{
    public function stages(): array
    {
        return ['requested', 'scheduled', 'assigned', 'reminded', 'completed', 'cancelled'];
    }
}
