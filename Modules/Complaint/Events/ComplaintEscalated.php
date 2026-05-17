<?php

namespace Modules\Complaint\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Complaint\Entities\Complaint;

class ComplaintEscalated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Complaint $complaint, public ?string $reason = null)
    {
    }
}
