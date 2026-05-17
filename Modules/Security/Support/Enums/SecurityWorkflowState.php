<?php

namespace Modules\Security\Support\Enums;

enum SecurityWorkflowState: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case ManagerApproved = 'manager_approved';
    case BuildingManagerApproved = 'building_manager_approved';
    case Validated = 'validated';
    case Rejected = 'rejected';
    case Cancelled = 'cancelled';
}
