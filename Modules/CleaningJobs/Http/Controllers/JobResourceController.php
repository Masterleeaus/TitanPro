<?php

namespace Modules\CleaningJobs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CleaningJobs\Models\JobResourceAllocation;
use Modules\CleaningJobs\Models\JobResourceCapacity;
use Modules\CleaningJobs\Services\JobResourcePlanner;

class JobResourceController extends Controller
{
    public function allocations(Request $request)
    {
        return response()->json(JobResourceAllocation::query()
            ->when($request->integer('work_order_id'), fn ($q, $id) => $q->where('work_order_id', $id))
            ->when($request->integer('user_id'), fn ($q, $id) => $q->where('user_id', $id))
            ->latest()->paginate($request->integer('per_page', 25)));
    }

    public function storeAllocation(Request $request, JobResourcePlanner $planner)
    {
        $data = $request->validate([
            'work_order_id' => ['required','integer'], 'task_id' => ['nullable','integer'], 'user_id' => ['required','integer'],
            'start_date' => ['required','date'], 'end_date' => ['nullable','date'], 'allocation_percentage' => ['nullable','numeric'],
            'hours_per_day' => ['nullable','numeric'], 'allocation_type' => ['nullable','string'], 'notes' => ['nullable','string'],
            'is_billable' => ['nullable','boolean'], 'is_confirmed' => ['nullable','boolean'], 'status' => ['nullable','string'],
        ]);
        return response()->json($planner->allocate($data), 201);
    }

    public function capacity(Request $request)
    {
        return response()->json(JobResourceCapacity::query()
            ->when($request->integer('user_id'), fn ($q, $id) => $q->where('user_id', $id))
            ->when($request->date('from'), fn ($q, $date) => $q->whereDate('date', '>=', $date))
            ->when($request->date('to'), fn ($q, $date) => $q->whereDate('date', '<=', $date))
            ->orderBy('date')->get());
    }
}
