<?php

namespace Modules\CleaningJobs\Enums;

enum TimesheetStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case Approved = 'approved';
    case Rejected = 'rejected';
}
