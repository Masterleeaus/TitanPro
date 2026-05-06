<?php
namespace Modules\TitanNexus\Enums;

enum AutomationRunStatus: string
{
    case QUEUED = 'queued';
    case RUNNING = 'running';
    case WAITING_APPROVAL = 'waiting_approval';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
}
