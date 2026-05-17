<?php

namespace Modules\Complaint\Support\Enums;

enum ComplaintStatus: string
{
    case OPEN = 'open';
    case PENDING = 'pending';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';
}
