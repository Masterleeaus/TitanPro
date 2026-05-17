<?php

namespace Modules\ZeroFussPortal\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\ZeroFussPortal\Models\PortalFeedback;

class FeedbackSubmitted
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public PortalFeedback $feedback)
    {
    }
}
