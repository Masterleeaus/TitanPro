<?php

namespace Modules\TitanRewind\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\TitanRewind\Models\RewindAction;
use Modules\TitanRewind\Models\RewindCase;
use Modules\TitanRewind\Models\RewindFix;

class RewindFixService
{
    public function proposeFix(
        RewindCase $case,
        array $proposal,
        array $proposedBy,
        bool $requiresConfirmation = true,
    ): RewindFix {
        return RewindFix::query()->create([
            'company_id' => $case->company_id,
            'case_id' => $case->id,
            'fix_type' => $proposal['fix_type'] ?? 'metadata_update',
            'proposed_by_type' => $proposedBy['type'] ?? 'user',
            'proposed_by_id' => $proposedBy['id'] ?? null,
            'requires_confirmation' => $requiresConfirmation,
            'status' => $requiresConfirmation ? 'proposed' : 'confirmed',
            'proposal_json' => $proposal,
            'confirm_token' => (string) Str::uuid(),
        ]);
    }

    public function confirmFix(RewindFix $fix, array $actor): RewindFix
    {
        if ($fix->status !== 'proposed') {
            return $fix;
        }

        $fix->forceFill([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'confirmed_by_type' => $actor['type'] ?? 'user',
            'confirmed_by_id' => $actor['id'] ?? null,
        ])->save();

        return $fix->refresh();
    }

    public function applyFix(RewindFix $fix, array $actor): RewindFix
    {
        if ($fix->requires_confirmation && $fix->status !== 'confirmed') {
            throw new \RuntimeException('Fix must be confirmed before it can be applied.');
        }

        if (in_array($fix->status, ['applied', 'failed'], true)) {
            return $fix;
        }

        return DB::transaction(function () use ($fix, $actor) {
            $fix->forceFill(['status' => 'applying'])->save();

            try {
                $result = $this->applyMetadataUpdate($fix);

                $fix->forceFill([
                    'status' => 'applied',
                    'applied_at' => now(),
                    'applied_by_type' => $actor['type'] ?? 'user',
                    'applied_by_id' => $actor['id'] ?? null,
                    'result_json' => $result,
                    'error_text' => null,
                ])->save();

                RewindAction::query()->create([
                    'company_id' => $fix->company_id,
                    'case_id' => $fix->case_id,
                    'fix_id' => $fix->id,
                    'action_type' => 'apply_fix',
                    'target_type' => $result['target_type'] ?? null,
                    'target_id' => $result['target_id'] ?? null,
                    'before_json' => $result['before'] ?? null,
                    'after_json' => $result['after'] ?? null,
                    'executed_by_type' => $actor['type'] ?? 'system',
                    'executed_by_id' => $actor['id'] ?? null,
                    'executed_at' => now(),
                    'success' => true,
                ]);
            } catch (\Throwable $exception) {
                $fix->forceFill([
                    'status' => 'failed',
                    'error_text' => $exception->getMessage(),
                ])->save();

                RewindAction::query()->create([
                    'company_id' => $fix->company_id,
                    'case_id' => $fix->case_id,
                    'fix_id' => $fix->id,
                    'action_type' => 'apply_fix',
                    'executed_by_type' => $actor['type'] ?? 'system',
                    'executed_by_id' => $actor['id'] ?? null,
                    'executed_at' => now(),
                    'success' => false,
                    'error_text' => $exception->getMessage(),
                ]);
            }

            return $fix->refresh();
        });
    }

    private function applyMetadataUpdate(RewindFix $fix): array
    {
        $proposal = $fix->proposal_json ?? [];

        if (($proposal['target_table'] ?? null) !== 'titan_rewind_cases') {
            throw new \RuntimeException('Target table not allowlisted.');
        }

        $case = RewindCase::query()
            ->whereKey($proposal['target_id'] ?? null)
            ->where('company_id', $fix->company_id)
            ->firstOrFail();

        $beforeMeta = $case->meta_json ?? [];
        $afterMeta = $beforeMeta;
        $afterMeta[$proposal['meta_key'] ?? 'note'] = $proposal['meta_value'] ?? null;

        $case->forceFill(['meta_json' => $afterMeta])->save();

        return [
            'target_type' => 'titan_rewind_cases',
            'target_id' => $case->id,
            'before' => ['meta_json' => $beforeMeta],
            'after' => ['meta_json' => $afterMeta],
        ];
    }
}
