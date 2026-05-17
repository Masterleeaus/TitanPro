<?php

namespace Modules\Security\Actions\Approvals;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Security\Support\DTOs\SecurityWorkflowTransitionResult;
use Modules\Security\Support\Enums\SecurityWorkflowState;
use Modules\Security\Workflows\Guards\SecurityWorkflowGuard;
use Throwable;

class ApproveSecurityRecordAction
{
    public function __construct(private readonly SecurityWorkflowGuard $guard)
    {
    }

    public function execute(Model $record, mixed $user = null, string $approvalColumn = 'status_approve', string $approvedByColumn = 'approved_by'): SecurityWorkflowTransitionResult
    {
        if (! $this->guard->canApprove($record, $user)) {
            return new SecurityWorkflowTransitionResult(false, 'User is not allowed to approve this security record.', null);
        }

        try {
            DB::transaction(function () use ($record, $user, $approvalColumn, $approvedByColumn): void {
                $record->setAttribute($approvalColumn, true);

                if ($approvedByColumn && $user) {
                    $record->setAttribute($approvedByColumn, $user->id);
                }

                $record->save();
            });
        } catch (Throwable $exception) {
            return new SecurityWorkflowTransitionResult(false, $exception->getMessage(), null);
        }

        return new SecurityWorkflowTransitionResult(true, 'Security record approved.', SecurityWorkflowState::ManagerApproved->value, [
            'record_id' => $record->getKey(),
            'record_type' => $record::class,
        ]);
    }
}
