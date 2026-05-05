<?php

namespace Modules\CleaningJobs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CleaningJobs\Models\JobTask;
use Modules\CleaningJobs\Models\WorkOrder;
use Modules\CleaningJobs\Services\JobKanbanService;

class JobTaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = JobTask::query()->with(['stage','milestone','subtasks'])
            ->when($request->integer('work_order_id'), fn ($q, $id) => $q->where('work_order_id', $id))
            ->latest()->paginate($request->integer('per_page', 25));
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'work_order_id' => ['required','integer'], 'title' => ['required','string','max:255'], 'description' => ['nullable','string'],
            'priority' => ['nullable','string'], 'stage_id' => ['nullable','integer'], 'milestone_id' => ['nullable','integer'],
            'assigned_to' => ['nullable','string'], 'start_date' => ['nullable','date'], 'due_date' => ['nullable','date'], 'estimated_hours' => ['nullable','numeric'],
        ]);
        $task = JobTask::create($data);
        return response()->json($task->load(['stage','milestone']), 201);
    }

    public function update(Request $request, JobTask $task)
    {
        $task->update($request->only(['title','description','priority','status','stage_id','milestone_id','assigned_to','start_date','due_date','estimated_hours','actual_hours','order','is_billable']));
        return response()->json($task->fresh(['stage','milestone','subtasks']));
    }

    public function move(Request $request, JobTask $task, JobKanbanService $kanban)
    {
        $data = $request->validate(['stage_id' => ['required','integer'], 'order' => ['nullable','integer']]);
        return response()->json($kanban->move($task, (int) $data['stage_id'], (int) ($data['order'] ?? 0)));
    }

    public function destroy(JobTask $task)
    {
        $task->delete();
        return response()->noContent();
    }
}
