<?php

namespace Modules\GroundZeroOps\Support\Enums;

enum JobStatus: string
{
    case Pending = 'pending';
    case Assigned = 'assigned';
    case InProgress = 'in_progress';
    case Completed = 'completed';
}
