<?php

namespace Modules\CleaningJobs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CleaningJobs\Models\JobTimesheet;

class JobTimesheetController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(JobTimesheet::query()->with(['workOrder','task'])
            ->when($request->integer('work_order_id'), fn ($q, $id) => $q->where('work_order_id', $id))
            ->latest()->paginate($request->integer('per_page', 25)));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'work_order_id' => ['required','integer'], 'task_id' => ['nullable','integer'], 'user_id' => ['required','integer'],
            'work_date' => ['required','date'], 'hours' => ['required','numeric','min:0'], 'description' => ['nullable','string'],
            'is_billable' => ['nullable','boolean'], 'billing_rate' => ['nullable','numeric'], 'cost_rate' => ['nullable','numeric'], 'status' => ['nullable','string'],
        ]);
        return response()->json(JobTimesheet::create($data), 201);
    }

    public function update(Request $request, JobTimesheet $timesheet)
    {
        $timesheet->update($request->only(['task_id','work_date','hours','description','is_billable','billing_rate','cost_rate','status']));
        return response()->json($timesheet->fresh());
    }

    public function approve(Request $request, JobTimesheet $timesheet)
    {
        $timesheet->update(['status' => 'approved', 'approved_by_id' => optional($request->user())->id, 'approved_at' => now()]);
        return response()->json($timesheet->fresh());
    }
}
