<?php

namespace Modules\Payroll\Support\Enums;

enum PayrollRunStatus: string
{
    case Draft = 'draft';
    case Previewed = 'previewed';
    case Processing = 'processing';
    case AwaitingApproval = 'awaiting_approval';
    case Approved = 'approved';
    case Paid = 'paid';
    case Failed = 'failed';
}
