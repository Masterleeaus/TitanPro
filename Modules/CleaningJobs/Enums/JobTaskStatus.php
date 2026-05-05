<?php

namespace Modules\CleaningJobs\Enums;

enum JobTaskStatus: string
{
    case Todo = 'todo';
    case InProgress = 'in_progress';
    case Review = 'review';
    case Done = 'done';
    case Blocked = 'blocked';
}
