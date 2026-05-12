<?php

namespace Modules\CRMCore\Actions\Task;

use Modules\CRMCore\Models\Task;
use Modules\CRMCore\Models\TaskPriority;
use Modules\CRMCore\Models\TaskStatus;
use Modules\CRMCore\Scopes\ScopedByCompany;

class CreateTaskAction
{
    /**
     * Create a new tenant-scoped task.
     *
     * @param array<string, mixed> $data
     */
    public function handle(array $data): Task
    {
        $companyId = $data['company_id'] ?? ScopedByCompany::resolveCompanyId();

        if (! is_numeric($companyId)) {
            throw new \RuntimeException('company_id is required to create a task.');
        }

        $data['company_id'] = (int) $companyId;
        $data['task_status_id'] ??= $this->defaultStatusId();
        $data['task_priority_id'] ??= $this->defaultPriorityId();

        return Task::create($data);
    }

    private function defaultStatusId(): ?int
    {
        return TaskStatus::query()->where('is_default', true)->value('id')
            ?? TaskStatus::query()->orderBy('position')->value('id');
    }

    private function defaultPriorityId(): ?int
    {
        return TaskPriority::query()->where('is_default', true)->value('id')
            ?? TaskPriority::query()->orderBy('position')->value('id');
    }
}
