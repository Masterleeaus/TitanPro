<?php

namespace Modules\CleaningJobs\Enums;

enum JobStatus: string
{
    case Draft='draft'; case Open='open'; case Scheduled='scheduled'; case InProgress='in_progress'; case Completed='completed'; case Cancelled='cancelled';
}
