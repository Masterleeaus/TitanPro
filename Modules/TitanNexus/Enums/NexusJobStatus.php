<?php
namespace Modules\TitanNexus\Enums;

enum NexusJobStatus: string
{
    case QUEUED = 'queued';
    case SCHEDULED = 'scheduled';
    case DISPATCHED = 'dispatched';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case ISSUE_REPORTED = 'issue_reported';
}
