<?php

namespace Modules\Security\Actions\Approvals;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Security\Support\DTOs\SecurityWorkflowTransitionResult;
use Modules\Security\Support\Enums\SecurityWorkflowState;
use Modules\Security\Workflows\Guards\SecurityWorkflowGuard;
use Throwable;

class ValidateSecurityRecordAction
{
    public function __construct(private readonly SecurityWorkflowGuard $guard)
    {
    }

    public function execute(Model $record, mixed $user = null, string $validatedColumn = 'status_validated', string $validatedByColumn = 'validated_by'): SecurityWorkflowTransitionResult
    {
        if (! $this->guard->canValidate($record, $user)) {
            return new SecurityWorkflowTransitionResult(false, 'User is not allowed to validate this security record.', null);
        }

        try {
            DB::transaction(function () use ($record, $user, $validatedColumn, $validatedByColumn): void {
                $record->setAttribute($validatedColumn, true);

                if ($validatedByColumn && $user) {
                    $record->setAttribute($validatedByColumn, $user->id);
                }

                $record->save();
            });
        } catch (Throwable $exception) {
            return new SecurityWorkflowTransitionResult(false, $exception->getMessage(), null);
        }

        return new SecurityWorkflowTransitionResult(true, 'Security record validated.', SecurityWorkflowState::Validated->value, [
            'record_id' => $record->getKey(),
            'record_type' => $record::class,
        ]);
    }
}
