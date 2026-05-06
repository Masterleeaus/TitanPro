<?php
namespace Modules\CleaningJobs\Automation\Triggers;
class ChecklistOverdueTrigger
{
    public function matches(?string $dueAt, ?string $completedAt): bool
    {
        return $dueAt !== null && $completedAt === null && now()->greaterThan($dueAt);
    }
}
