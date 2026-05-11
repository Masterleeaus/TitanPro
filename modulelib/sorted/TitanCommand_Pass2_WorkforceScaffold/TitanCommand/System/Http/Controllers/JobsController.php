<?php

namespace App\Extensions\TitanCommand\System\Http\Controllers;

use Illuminate\Http\Request;
use App\Extensions\TitanCommand\System\Services\ProofPackService;
use App\Extensions\TitanCommand\System\Services\EvidenceRules;
use App\Extensions\TitanCommand\System\Models\Work\WorkJobTemplate;
use App\Extensions\TitanCommand\System\Models\Work\WorkJobTemplateItem;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

use App\Extensions\TitanCommand\System\Models\Work\WorkJob;
use App\Extensions\TitanCommand\System\Models\Work\WorkJobEvent;
use App\Extensions\TitanCommand\System\Models\Work\WorkJobItem;
use App\Extensions\TitanCommand\System\Models\Work\WorkJobAssignment;
use App\Extensions\TitanCommand\System\Models\Work\WorkJobChecklist;
use App\Extensions\TitanCommand\System\Models\Work\WorkJobChecklistItem;
use App\Extensions\TitanCommand\System\Models\Work\WorkJobPart;
use App\Extensions\TitanCommand\System\Models\Work\WorkJobLink;
use App\Extensions\TitanCommand\System\Models\Work\WorkAsset;
use App\Extensions\TitanCommand\System\Models\Work\WorkPermit;

use App\Extensions\TitanCommand\System\Models\Work\WorkJobEvidence;
use App\Extensions\TitanCommand\System\Models\Work\WorkJobInspection;
use App\Extensions\TitanCommand\System\Models\Work\WorkJobInspectionItem;
use App\Extensions\TitanCommand\System\Models\Work\WorkJobState;
use App\Extensions\TitanCommand\System\Models\Work\WorkJobReport;

class JobsController
{

    private function tenantIds(): array
    {
        $userId = (int) (\Illuminate\Support\Facades\Auth::id() ?? 0);
        // MVP rule: company_id == user_id
        $companyId = $userId;
        return [$companyId, $userId];
    }

    private function tenant(): array
    {
        $uid = (int) (auth()->id() ?? 0);

        // MVP rule (locked): company_id == user_id
        return [
            'company_id' => $uid,
            'user_id' => $uid,
            'team_id' => null,
            'created_by_team_id' => null,
        ];
    }

        private function wantsHtml(Request $r): bool
    {
        $accept = (string) $r->header('Accept', '');
        return str_contains($accept, 'text/html') || $r->query('html') === '1';
    }

    private function ensureTenantTemplates(int $companyId, int $userId): void
    {
        // Clone global defaults (0/0) into tenant on first use
        $types = ['checklist','inspection','evidence_rule'];
        foreach ($types as $type) {
            $has = WorkJobTemplate::query()->where('company_id',$companyId)->where('user_id',$userId)->where('template_type',$type)->exists();
            if ($has) continue;

            $global = WorkJobTemplate::query()->where('company_id',0)->where('user_id',0)->where('template_type',$type)->where('status','active')->first();
            if (!$global) continue;

            $newId = WorkJobTemplate::query()->insertGetId([
                'company_id' => $companyId,
                'user_id' => $userId,
                'team_id' => null,
                'created_by_team_id' => null,
                'template_type' => $global->template_type,
                'title' => $global->title,
                'status' => $global->status,
                'meta_json' => $global->meta_json ? json_encode($global->meta_json) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $items = WorkJobTemplateItem::query()->where('company_id',0)->where('user_id',0)->where('template_id',$global->id)->orderBy('sort_order')->get();
            foreach ($items as $it) {
                WorkJobTemplateItem::query()->insert([
                    'company_id' => $companyId,
                    'user_id' => $userId,
                    'team_id' => null,
                    'created_by_team_id' => null,
                    'template_id' => $newId,
                    'item_type' => $it->item_type,
                    'label' => $it->label,
                    'status' => $it->status,
                    'sort_order' => $it->sort_order,
                    'schema_json' => $it->schema_json ? json_encode($it->schema_json) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

private function ok(string $action, array $params = [], array $data = [])
    {
        return response()->json([
            'ok' => true,
            'titan' => 'command',
            'action' => $action,
            'params' => $params,
            'data' => $data,
        ]);
    }

    // A) Jobs list & core CRUD
    public function index(Request $r)
    {
        [$companyId, $userId] = $this->tenant();
        $this->ensureTenantTemplates($companyId, $userId);
        $jobs = \App\Extensions\TitanCommand\System\Models\Work\WorkJob::query()
            ->where('company_id', $companyId)->where('user_id', $userId)
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        if ($this->wantsHtml($r)) {
            return view('titancommand::jobs.index', ['jobs' => $jobs]);
        }

        return $this->ok('jobs.index', $r->all(), ['jobs' => $jobs]);
    }

    public function create(Request $r)
    {
        if ($this->wantsHtml($r)) {
            return view('titancommand::jobs.create', ['heading' => 'Create Job', 'title' => 'Create Job']);
        }
        return $this->ok('jobs.create');
    }

    public function store(Request $r)
    {
        $t = $this->tenant();

        $job = WorkJob::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],

            'title' => (string) $r->input('title', 'New Job'),
            'description' => $r->input('description'),
            'status' => (string) $r->input('status', 'draft'),
            'priority' => (string) $r->input('priority', 'normal'),
            'scheduled_start' => $r->input('scheduled_start'),
            'scheduled_end' => $r->input('scheduled_end'),
            'meta_json' => $r->input('meta_json'),
            'payload_json' => $r->input('payload_json'),
        ]);

        WorkJobEvent::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $job->id,
            'event_type' => 'created',
            'label' => 'Job created',
            'occurred_at' => now(),
            'meta_json' => ['source' => 'command'],
        ]);

        return $this->ok('jobs.store', $r->all(), ['job' => $job->toArray()]);
    }

    public function show(Request $r, $job)
    {
        [$companyId, $userId] = $this->tenant();
        $jobRow = \App\Extensions\TitanCommand\System\Models\Work\WorkJob::query()
            ->where('company_id', $companyId)->where('user_id', $userId)
            ->findOrFail($job);

        if ($this->wantsHtml($r)) {
            return view('titancommand::jobs.show', ['job' => $jobRow]);
        }

        return $this->ok('jobs.show', compact('job'), ['job' => $jobRow]);
    }

    public function edit($job)
    {
        return $this->ok('jobs.edit', compact('job'));
    }

    public function update(Request $r, $job)
    {
        $t = $this->tenant();

        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $jobRow->fill(Arr::only($r->all(), [
            'title','description','status','priority','scheduled_start','scheduled_end','meta_json','payload_json'
        ]));
        $jobRow->save();

        WorkJobEvent::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'event_type' => 'updated',
            'label' => 'Job updated',
            'occurred_at' => now(),
            'meta_json' => ['fields' => array_keys($r->all())],
        ]);

        return $this->ok('jobs.update', ['job' => $job], ['job' => $jobRow->toArray()]);
    }

    public function archive($job)
    {
        $t = $this->tenant();

        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);
        $jobRow->archived_at = now();
        $jobRow->status = $jobRow->status === 'completed' ? 'completed' : 'archived';
        $jobRow->save();

        WorkJobEvent::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'event_type' => 'archived',
            'label' => 'Job archived',
            'occurred_at' => now(),
        ]);

        return $this->ok('jobs.archive', compact('job'), ['job' => $jobRow->toArray()]);
    }

    public function restore($job)
    {
        $t = $this->tenant();

        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);
        $jobRow->archived_at = null;
        if ($jobRow->status === 'archived') {
            $jobRow->status = 'draft';
        }
        $jobRow->save();

        WorkJobEvent::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'event_type' => 'restored',
            'label' => 'Job restored',
            'occurred_at' => now(),
        ]);

        return $this->ok('jobs.restore', compact('job'), ['job' => $jobRow->toArray()]);
    }

    // B) Dispatch & schedule
    public function dispatch(Request $r)
    {
        [$companyId, $userId] = $this->tenant();

        $rows = \App\Extensions\TitanCommand\System\Models\Work\WorkJob::query()
            ->where('company_id',$companyId)->where('user_id',$userId)
            ->orderByRaw("CASE WHEN status IN ('open','scheduled','in_progress') THEN 0 ELSE 1 END")
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        $assign = \App\Extensions\TitanCommand\System\Models\Work\WorkJobAssignment::query()
            ->where('company_id',$companyId)->where('user_id',$userId)
            ->whereIn('job_id', $rows->pluck('id')->all())
            ->get();

        $jobs = $rows->map(function($j) use ($assign) {
            $assignees = $assign->where('job_id',$j->id)->map(function($a){
                return $a->assignee_user_id ? ('user#'.$a->assignee_user_id) : ('team#'.$a->assignee_team_id);
            })->values()->all();

            return [
                'id' => $j->id,
                'title' => $j->title,
                'status' => $j->status,
                'scheduled_start' => $j->scheduled_start,
                'scheduled_end' => $j->scheduled_end,
                'assignees' => $assignees,
            ];
        })->toArray();

        if ($this->wantsHtml($r)) {
            return view('titancommand::jobs.dispatch', ['heading' => 'Dispatch', 'title' => 'Dispatch', 'jobs' => $jobs]);
        }

        return $this->ok('jobs.dispatch', [], ['jobs' => $jobs]);
    }

    public function assign(Request $r, $job)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $assigneeUserId = $r->filled('assignee_user_id') ? (int) $r->input('assignee_user_id') : null;
        $assigneeTeamId = $r->filled('assignee_team_id') ? (int) $r->input('assignee_team_id') : null;

        $assignment = WorkJobAssignment::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'assignee_user_id' => $assigneeUserId,
            'assignee_team_id' => $assigneeTeamId,
            'role' => (string) $r->input('role', 'worker'),
            'status' => (string) $r->input('status', 'assigned'),
            'meta_json' => $r->input('meta_json'),
        ]);

        WorkJobEvent::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'event_type' => 'assigned',
            'label' => 'Assigned',
            'occurred_at' => now(),
            'meta_json' => [
                'assignment_id' => $assignment->id,
                'assignee_user_id' => $assigneeUserId,
                'assignee_team_id' => $assigneeTeamId,
            ],
        ]);

        return $this->ok('jobs.assign', ['job' => $jobRow->id], ['assignment' => $assignment->toArray()]);
    }

    public function unassign(Request $r, $job)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $assigneeUserId = $r->filled('assignee_user_id') ? (int) $r->input('assignee_user_id') : null;
        $assigneeTeamId = $r->filled('assignee_team_id') ? (int) $r->input('assignee_team_id') : null;

        $q = WorkJobAssignment::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id);

        if (!is_null($assigneeUserId)) $q->where('assignee_user_id', $assigneeUserId);
        if (!is_null($assigneeTeamId)) $q->where('assignee_team_id', $assigneeTeamId);

        $deleted = $q->delete();

        WorkJobEvent::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'event_type' => 'unassigned',
            'label' => 'Unassigned',
            'occurred_at' => now(),
            'meta_json' => ['deleted' => $deleted],
        ]);

        return $this->ok('jobs.unassign', ['job' => $jobRow->id], ['deleted' => $deleted]);
    }

    public function scheduleView($job)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        return $this->ok('jobs.schedule.view', ['job' => $jobRow->id], [
            'scheduled_start' => $jobRow->scheduled_start,
            'scheduled_end' => $jobRow->scheduled_end,
        ]);
    }

    public function scheduleSet(Request $r, $job)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $jobRow->scheduled_start = $r->input('scheduled_start');
        $jobRow->scheduled_end = $r->input('scheduled_end');
        $jobRow->save();

        WorkJobEvent::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'event_type' => 'scheduled',
            'label' => 'Scheduled',
            'occurred_at' => now(),
            'meta_json' => ['scheduled_start' => $jobRow->scheduled_start, 'scheduled_end' => $jobRow->scheduled_end],
        ]);

        return $this->ok('jobs.schedule.set', ['job' => $jobRow->id], ['job' => $jobRow->toArray()]);
    }

    public function reschedule(Request $r, $job)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $jobRow->scheduled_start = $r->input('scheduled_start', $jobRow->scheduled_start);
        $jobRow->scheduled_end = $r->input('scheduled_end', $jobRow->scheduled_end);
        $jobRow->save();

        WorkJobEvent::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'event_type' => 'rescheduled',
            'label' => 'Rescheduled',
            'occurred_at' => now(),
            'meta_json' => [
                'scheduled_start' => $jobRow->scheduled_start,
                'scheduled_end' => $jobRow->scheduled_end,
                'reason' => $r->input('reason'),
            ],
        ]);

        return $this->ok('jobs.reschedule', ['job' => $jobRow->id], ['job' => $jobRow->toArray()]);
    }

    // C) Job timeline / events
    public function timeline(Request $r, $job)
    {
        [$companyId, $userId] = $this->tenant();
        $jobRow = \App\Extensions\TitanCommand\System\Models\Work\WorkJob::query()
            ->where('company_id',$companyId)->where('user_id',$userId)
            ->findOrFail($job);

        $events = \App\Extensions\TitanCommand\System\Models\Work\WorkJobEvent::query()
            ->where('company_id',$companyId)->where('user_id',$userId)
            ->where('job_id',$jobRow->id)
            ->orderBy('id')
            ->get();

        if ($this->wantsHtml($r)) {
            return view('titancommand::jobs.timeline', ['heading' => 'Timeline', 'title' => 'Timeline', 'job' => $jobRow, 'events' => $events]);
        }

        return $this->ok('jobs.timeline', compact('job'), ['events' => $events]);
    }

    public function addEvent(Request $r, $job)
    {
        $t = $this->tenant();

        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $event = WorkJobEvent::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'event_type' => (string) $r->input('event_type', 'note'),
            'label' => $r->input('label'),
            'occurred_at' => $r->input('occurred_at', now()),
            'meta_json' => $r->input('meta_json'),
        ]);

        return $this->ok('jobs.events.store', ['job' => $jobRow->id], ['event' => $event->toArray()]);
    }

    // D) Tasks & sub-items
    public function tasks($job)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $tasks = WorkJobItem::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->where('item_type', 'task')
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();

        return $this->ok('jobs.tasks.index', ['job' => $jobRow->id], ['tasks' => $tasks]);
    }

    public function taskStore(Request $r, $job)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $task = WorkJobItem::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'item_type' => 'task',
            'title' => (string) $r->input('title', 'Task'),
            'description' => $r->input('description'),
            'status' => (string) $r->input('status', 'open'),
            'meta_json' => $r->input('meta_json'),
        ]);

        return $this->ok('jobs.tasks.store', ['job' => $jobRow->id], ['task' => $task->toArray()]);
    }

    public function taskUpdate(Request $r, $job, $task)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $taskRow = WorkJobItem::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->where('item_type', 'task')
            ->findOrFail($task);

        $taskRow->fill(Arr::only($r->all(), ['title','description','status','meta_json']));
        $taskRow->save();

        return $this->ok('jobs.tasks.update', ['job' => $jobRow->id, 'task' => $taskRow->id], ['task' => $taskRow->toArray()]);
    }

    public function taskComplete($job, $task)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $taskRow = WorkJobItem::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->where('item_type', 'task')
            ->findOrFail($task);

        $taskRow->status = 'done';
        $taskRow->save();

        return $this->ok('jobs.tasks.complete', ['job' => $jobRow->id, 'task' => $taskRow->id], ['task' => $taskRow->toArray()]);
    }

    public function taskReopen($job, $task)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $taskRow = WorkJobItem::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->where('item_type', 'task')
            ->findOrFail($task);

        $taskRow->status = 'open';
        $taskRow->save();

        return $this->ok('jobs.tasks.reopen', ['job' => $jobRow->id, 'task' => $taskRow->id], ['task' => $taskRow->toArray()]);
    }

    // E) Checklists
    public function checklists(Request $r, $job)
    {
        [$companyId, $userId] = $this->tenant();
        $jobRow = \App\Extensions\TitanCommand\System\Models\Work\WorkJob::query()
            ->where('company_id',$companyId)->where('user_id',$userId)
            ->findOrFail($job);

        $checklists = \App\Extensions\TitanCommand\System\Models\Work\WorkJobChecklist::query()
            ->where('company_id',$companyId)->where('user_id',$userId)
            ->where('job_id',$jobRow->id)
            ->orderByDesc('id')
            ->get();

        $items = \App\Extensions\TitanCommand\System\Models\Work\WorkJobChecklistItem::query()
            ->where('company_id',$companyId)->where('user_id',$userId)
            ->where('job_id',$jobRow->id)
            ->orderBy('sort_order')
            ->get();

        if ($this->wantsHtml($r)) {
            return view('titancommand::jobs.checklists', ['heading' => 'Checklists', 'title' => 'Checklists', 'job' => $jobRow, 'checklists' => $checklists, 'items' => $items]);
        }

        return $this->ok('jobs.checklists.index', compact('job'), ['checklists' => $checklists, 'items' => $items]);
    }

    public function checklistStore(Request $r, $job)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $checklist = WorkJobChecklist::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'title' => (string) $r->input('title', 'Checklist'),
            'status' => (string) $r->input('status', 'open'),
            'meta_json' => $r->input('meta_json'),
        ]);

        return $this->ok('jobs.checklists.store', ['job' => $jobRow->id], ['checklist' => $checklist->toArray()]);
    }

    public function checklistShow($job, $checklist)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $checklistRow = WorkJobChecklist::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->findOrFail($checklist);

        $items = WorkJobChecklistItem::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->where('checklist_id', $checklistRow->id)
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();

        return $this->ok('jobs.checklists.show', ['job' => $jobRow->id, 'checklist' => $checklistRow->id], [
            'checklist' => $checklistRow->toArray(),
            'items' => $items,
        ]);
    }

    public function checklistItemStore(Request $r, $job, $checklist)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $checklistRow = WorkJobChecklist::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->findOrFail($checklist);

        $item = WorkJobChecklistItem::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'checklist_id' => $checklistRow->id,
            'label' => (string) $r->input('label', 'Checklist item'),
            'status' => (string) $r->input('status', 'pending'),
            'meta_json' => $r->input('meta_json'),
        ]);

        return $this->ok('jobs.checklists.items.store', ['job' => $jobRow->id, 'checklist' => $checklistRow->id], ['item' => $item->toArray()]);
    }

    public function checklistItemCheck(Request $r, $job, $checklist, $item)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $checklistRow = WorkJobChecklist::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->findOrFail($checklist);

        $itemRow = WorkJobChecklistItem::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->where('checklist_id', $checklistRow->id)
            ->findOrFail($item);

        $itemRow->status = (string) $r->input('status', 'done');
        $itemRow->save();

        return $this->ok('jobs.checklists.items.check', ['job' => $jobRow->id, 'checklist' => $checklistRow->id, 'item' => $itemRow->id], ['item' => $itemRow->toArray()]);
    }

    public function checklistComplete($job, $checklist)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $checklistRow = WorkJobChecklist::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->findOrFail($checklist);

        $checklistRow->status = 'completed';
        $checklistRow->save();

        return $this->ok('jobs.checklists.complete', ['job' => $jobRow->id, 'checklist' => $checklistRow->id], ['checklist' => $checklistRow->toArray()]);
    }

    // F) Parts / materials
    public function parts($job)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $parts = WorkJobPart::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

        return $this->ok('jobs.parts.index', ['job' => $jobRow->id], ['parts' => $parts]);
    }

    public function partStore(Request $r, $job)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $part = WorkJobPart::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'name' => (string) $r->input('name', 'Part'),
            'qty' => (int) $r->input('qty', 1),
            'unit_cost' => $r->input('unit_cost'),
            'status' => (string) $r->input('status', 'planned'),
            'meta_json' => $r->input('meta_json'),
        ]);

        return $this->ok('jobs.parts.store', ['job' => $jobRow->id], ['part' => $part->toArray()]);
    }

    public function partUpdate(Request $r, $job, $part)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $partRow = WorkJobPart::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->findOrFail($part);

        $partRow->fill(Arr::only($r->all(), ['name','qty','unit_cost','status','meta_json']));
        $partRow->save();

        return $this->ok('jobs.parts.update', ['job' => $jobRow->id, 'part' => $partRow->id], ['part' => $partRow->toArray()]);
    }

    public function partUse($job, $part)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $partRow = WorkJobPart::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->findOrFail($part);

        $partRow->status = 'used';
        $partRow->save();

        return $this->ok('jobs.parts.use', ['job' => $jobRow->id, 'part' => $partRow->id], ['part' => $partRow->toArray()]);
    }

    public function partReturn($job, $part)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $partRow = WorkJobPart::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->findOrFail($part);

        $partRow->status = 'returned';
        $partRow->save();

        return $this->ok('jobs.parts.return', ['job' => $jobRow->id, 'part' => $partRow->id], ['part' => $partRow->toArray()]);
    }

    // G) Assets & permits
    public function assets($job)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $assetIds = WorkJobLink::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->where('target_type', 'asset')
            ->pluck('target_id')
            ->values()
            ->all();

        $assets = empty($assetIds) ? [] : WorkAsset::tenant($t['company_id'], $t['user_id'])
            ->whereIn('id', $assetIds)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

        return $this->ok('jobs.assets.index', ['job' => $jobRow->id], ['assets' => $assets]);
    }

    public function assetLink(Request $r, $job)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $assetId = (int) $r->input('asset_id', 0);
        if ($assetId <= 0) {
            return $this->ok('jobs.assets.link', ['job' => $jobRow->id], ['error' => 'asset_id required']);
        }

        WorkAsset::tenant($t['company_id'], $t['user_id'])->findOrFail($assetId);

        WorkJobLink::firstOrCreate([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'job_id' => $jobRow->id,
            'link_type' => 'asset',
            'target_type' => 'asset',
            'target_id' => $assetId,
        ], [
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'meta_json' => $r->input('meta_json'),
        ]);

        WorkJobEvent::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'event_type' => 'asset_linked',
            'label' => 'Asset linked',
            'occurred_at' => now(),
            'meta_json' => ['asset_id' => $assetId],
        ]);

        return $this->ok('jobs.assets.link', ['job' => $jobRow->id], ['linked' => true, 'asset_id' => $assetId]);
    }

    public function assetUnlink(Request $r, $job)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $assetId = (int) $r->input('asset_id', 0);
        if ($assetId <= 0) {
            return $this->ok('jobs.assets.unlink', ['job' => $jobRow->id], ['error' => 'asset_id required']);
        }

        WorkJobLink::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->where('target_type', 'asset')
            ->where('target_id', $assetId)
            ->delete();

        WorkJobEvent::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'event_type' => 'asset_unlinked',
            'label' => 'Asset unlinked',
            'occurred_at' => now(),
            'meta_json' => ['asset_id' => $assetId],
        ]);

        return $this->ok('jobs.assets.unlink', ['job' => $jobRow->id], ['unlinked' => true, 'asset_id' => $assetId]);
    }

    public function permits($job)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $permitIds = WorkJobLink::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->where('target_type', 'permit')
            ->pluck('target_id')
            ->values()
            ->all();

        $permits = empty($permitIds) ? [] : WorkPermit::tenant($t['company_id'], $t['user_id'])
            ->whereIn('id', $permitIds)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

        return $this->ok('jobs.permits.index', ['job' => $jobRow->id], ['permits' => $permits]);
    }

    public function permitLink(Request $r, $job)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $permitId = (int) $r->input('permit_id', 0);
        if ($permitId <= 0) {
            return $this->ok('jobs.permits.link', ['job' => $jobRow->id], ['error' => 'permit_id required']);
        }

        WorkPermit::tenant($t['company_id'], $t['user_id'])->findOrFail($permitId);

        WorkJobLink::firstOrCreate([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'job_id' => $jobRow->id,
            'link_type' => 'permit',
            'target_type' => 'permit',
            'target_id' => $permitId,
        ], [
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'meta_json' => $r->input('meta_json'),
        ]);

        WorkJobEvent::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'event_type' => 'permit_linked',
            'label' => 'Permit linked',
            'occurred_at' => now(),
            'meta_json' => ['permit_id' => $permitId],
        ]);

        return $this->ok('jobs.permits.link', ['job' => $jobRow->id], ['linked' => true, 'permit_id' => $permitId]);
    }

    public function permitUnlink(Request $r, $job)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $permitId = (int) $r->input('permit_id', 0);
        if ($permitId <= 0) {
            return $this->ok('jobs.permits.unlink', ['job' => $jobRow->id], ['error' => 'permit_id required']);
        }

        WorkJobLink::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->where('target_type', 'permit')
            ->where('target_id', $permitId)
            ->delete();

        WorkJobEvent::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'event_type' => 'permit_unlinked',
            'label' => 'Permit unlinked',
            'occurred_at' => now(),
            'meta_json' => ['permit_id' => $permitId],
        ]);

        return $this->ok('jobs.permits.unlink', ['job' => $jobRow->id], ['unlinked' => true, 'permit_id' => $permitId]);
    }

    // H) Inspections
    public function inspections($job)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $inspections = WorkJobInspection::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

        return $this->ok('jobs.inspections.index', ['job' => $jobRow->id], ['inspections' => $inspections]);
    }

    public function inspectionStore(Request $r, $job)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $inspection = WorkJobInspection::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'inspection_type' => (string) $r->input('inspection_type', 'post'),
            'status' => (string) $r->input('status', 'draft'),
            'title' => (string) $r->input('title', 'Inspection'),
            'notes' => $r->input('notes'),
        ]);

        WorkJobEvent::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'event_type' => 'inspection_created',
            'label' => 'Inspection created',
            'occurred_at' => now(),
            'meta_json' => ['inspection_id' => $inspection->id, 'inspection_type' => $inspection->inspection_type],
        ]);

        return $this->ok('jobs.inspections.store', ['job' => $jobRow->id], ['inspection' => $inspection->toArray()]);
    }

    public function inspectionShow($job, $inspection)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $inspectionRow = WorkJobInspection::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->findOrFail($inspection);

        $items = WorkJobInspectionItem::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->where('inspection_id', $inspectionRow->id)
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();

        return $this->ok('jobs.inspections.show', ['job' => $jobRow->id, 'inspection' => $inspectionRow->id], [
            'inspection' => $inspectionRow->toArray(),
            'items' => $items,
        ]);
    }

    public function inspectionItemStore(Request $r, $job, $inspection)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $inspectionRow = WorkJobInspection::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->findOrFail($inspection);

        $item = WorkJobInspectionItem::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'inspection_id' => $inspectionRow->id,
            'item_key' => $r->input('item_key'),
            'label' => (string) $r->input('label', 'Inspection item'),
            'status' => (string) $r->input('status', 'pending'),
            'score' => $r->input('score'),
            'notes' => $r->input('notes'),
            'meta_json' => $r->input('meta_json'),
        ]);

        return $this->ok('jobs.inspections.items.store', ['job' => $jobRow->id, 'inspection' => $inspectionRow->id], [
            'item' => $item->toArray(),
        ]);
    }

    public function inspectionSubmit($job, $inspection)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $inspectionRow = WorkJobInspection::tenant($t['company_id'], $t['user_id'])
            ->where('job_id', $jobRow->id)
            ->findOrFail($inspection);

        $inspectionRow->status = 'submitted';
        $inspectionRow->submitted_at = now();
        $inspectionRow->save();

        WorkJobEvent::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'event_type' => 'inspection_submitted',
            'label' => 'Inspection submitted',
            'occurred_at' => now(),
            'meta_json' => ['inspection_id' => $inspectionRow->id],
        ]);

        return $this->ok('jobs.inspections.submit', ['job' => $jobRow->id, 'inspection' => $inspectionRow->id], [
            'submitted' => true,
            'inspection' => $inspectionRow->toArray(),
        ]);
    }

    // I) Evidence / proof
    public function evidence(Request $r, $job)
    {
        [$companyId, $userId] = $this->tenant();
        $jobRow = \App\Extensions\TitanCommand\System\Models\Work\WorkJob::query()
            ->where('company_id',$companyId)->where('user_id',$userId)
            ->findOrFail($job);

        $evidence = \App\Extensions\TitanCommand\System\Models\Work\WorkJobEvidence::query()
            ->where('company_id',$companyId)->where('user_id',$userId)
            ->where('job_id',$jobRow->id)
            ->orderByDesc('id')
            ->get();

        if ($this->wantsHtml($r)) {
            return view('titancommand::jobs.evidence', ['heading' => 'Evidence', 'title' => 'Evidence', 'job' => $jobRow, 'evidence' => $evidence]);
        }

        return $this->ok('jobs.evidence.index', compact('job'), ['evidence' => $evidence]);
    }

    public function evidenceStore(Request $r, $job)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $row = WorkJobEvidence::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'evidence_type' => (string) $r->input('evidence_type', 'note'),
            'label' => $r->input('label'),
            'description' => $r->input('description'),
            'uri' => $r->input('uri'),
            'mime' => $r->input('mime'),
            'meta_json' => $r->input('meta_json'),
        ]);

        WorkJobEvent::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'event_type' => 'evidence_added',
            'label' => 'Evidence added',
            'occurred_at' => now(),
            'meta_json' => ['evidence_id' => $row->id, 'evidence_type' => $row->evidence_type],
        ]);

        return $this->ok('jobs.evidence.store', ['job' => $jobRow->id], ['evidence' => $row->toArray()]);
    }

    public function signoff(Request $r, $job)
    {
        $t = $this->tenant();
        $jobRow = WorkJob::tenant($t['company_id'], $t['user_id'])->findOrFail($job);

        $row = WorkJobEvidence::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'evidence_type' => 'signoff',
            'label' => (string) $r->input('label', 'Client sign-off'),
            'description' => $r->input('description'),
            'uri' => $r->input('uri'),
            'mime' => $r->input('mime'),
            'meta_json' => array_filter([
                'signer_name' => $r->input('signer_name'),
                'signer_email' => $r->input('signer_email'),
                'signed_at' => $r->input('signed_at'),
            ]) ?: null,
        ]);

        WorkJobState::updateOrCreate([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'job_id' => $jobRow->id,
            'state_key' => 'compliance_state',
        ], [
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'state_type' => 'compliance',
            'status' => 'ok',
            'label' => 'Signed off',
            'meta_json' => ['evidence_id' => $row->id],
        ]);

        WorkJobEvent::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'job_id' => $jobRow->id,
            'event_type' => 'signoff_captured',
            'label' => 'Client sign-off captured',
            'occurred_at' => now(),
            'meta_json' => ['evidence_id' => $row->id],
        ]);

        return $this->ok('jobs.signoff', ['job' => $jobRow->id], ['signoff' => $row->toArray()]);
    }

    public function proofPack(Request $r, $job)
    {
        [$companyId, $userId] = $this->tenant();
        $html = ProofPackService::html($companyId, $userId, (int)$job);

        if ($this->wantsHtml($r)) {
            return view('titancommand::jobs.proof_pack', ['html' => $html]);
        }

        return $this->ok('jobs.proofpack.view', compact('job'), ['html' => $html]);
    }

    public function proofPackExport(Request $r, $job)
    {
        [$companyId, $userId] = $this->tenant();
        $html = ProofPackService::html($companyId, $userId, (int)$job);
        $pdf = ProofPackService::pdfBytes($html);

        $filenameBase = 'job-proof-pack-'.$job;
        if ($pdf !== null) {
            return response($pdf, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filenameBase.'.pdf"',
            ]);
        }

        // Fallback if dompdf isn't installed: download HTML instead (still deterministic)
        return response($html, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$filenameBase.'.html"',
        ]);
    }

    // J) Reports
    public function reports(Request $r)
    {
        [$companyId, $userId] = $this->tenant();
        $reports = \App\Extensions\TitanCommand\System\Models\Work\WorkJobReport::query()
            ->where('company_id',$companyId)->where('user_id',$userId)
            ->orderByDesc('id')->limit(50)->get();

        if ($this->wantsHtml($r)) {
            return view('titancommand::jobs.reports', ['heading' => 'Reports', 'title' => 'Reports', 'reports' => $reports]);
        }

        return $this->ok('jobs.reports.index', [], ['reports' => $reports]);
    }

    public function reportsPerformance()
    {
        $t = $this->tenant();

        $total = WorkJob::tenant($t['company_id'], $t['user_id'])->count();
        $completed = WorkJob::tenant($t['company_id'], $t['user_id'])->where('status', 'completed')->count();
        $cancelled = WorkJob::tenant($t['company_id'], $t['user_id'])->where('status', 'cancelled')->count();

        $recent = WorkJobEvent::tenant($t['company_id'], $t['user_id'])
            ->orderBy('id', 'desc')
            ->limit(25)
            ->get()
            ->toArray();

        $report = WorkJobReport::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'report_type' => 'performance',
            'label' => 'Jobs Performance Snapshot',
            'params_json' => ['limit_events' => 25],
            'result_json' => [
                'total' => $total,
                'completed' => $completed,
                'cancelled' => $cancelled,
            ],
        ]);

        return $this->ok('jobs.reports.performance', [], [
            'report_id' => $report->id,
            'totals' => ['total' => $total, 'completed' => $completed, 'cancelled' => $cancelled],
            'recent_events' => $recent,
        ]);
    }

    public function reportsCompliance()
    {
        $t = $this->tenant();

        $states = WorkJobState::tenant($t['company_id'], $t['user_id'])
            ->where('state_type', 'compliance')
            ->orderBy('id', 'desc')
            ->limit(100)
            ->get()
            ->toArray();

        $counts = WorkJobState::tenant($t['company_id'], $t['user_id'])
            ->where('state_type', 'compliance')
            ->selectRaw('status, COUNT(*) as c')
            ->groupBy('status')
            ->pluck('c', 'status')
            ->toArray();

        $report = WorkJobReport::create([
            'company_id' => $t['company_id'],
            'user_id' => $t['user_id'],
            'team_id' => $t['team_id'],
            'created_by_team_id' => $t['created_by_team_id'],
            'report_type' => 'compliance',
            'label' => 'Jobs Compliance Snapshot',
            'params_json' => ['limit_states' => 100],
            'result_json' => ['counts' => $counts],
        ]);

        return $this->ok('jobs.reports.compliance', [], [
            'report_id' => $report->id,
            'counts' => $counts,
            'states' => $states,
        ]);
    }


    // K
    public function settings(Request $r)
    {
        if ($this->wantsHtml($r)) {
            return view('titancommand::jobs.settings', ['heading' => 'Settings', 'title' => 'Settings']);
        }
        return $this->ok('jobs.settings');
    }

}
