<?php

namespace Modules\Security\Support\Enums;

enum SecurityWorkflowStatus: string
{
    case Draft = 'draft';
    case PendingApproval = 'pending_approval';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case PendingValidation = 'pending_validation';
    case Validated = 'validated';
    case Closed = 'closed';
}
