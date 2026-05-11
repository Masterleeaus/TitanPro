<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Models\Tz\TzScheduledTask;
use App\Services\TitanScheduleEngine\ScheduledTaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TitanScheduledTaskController extends Controller
{
    public function __construct(protected ScheduledTaskService $scheduledTaskService)
    {
    }

    public function index()
    {
        $tasks = collect();
        if (Schema::hasTable('tz_scheduled_tasks')) {
            $tasks = TzScheduledTask::query()->orderByDesc('scheduled_for')->limit(100)->get();
        }

        return view('panel.user.calendar.scheduled-tasks', ['tasks' => $tasks]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'task_type' => ['required', 'string', 'max:100'],
            'scheduled_for' => ['required', 'date'],
            'priority' => ['nullable', 'integer', 'min:0', 'max:100'],
            'payload_json' => ['nullable', 'array'],
        ]);

        $data['handler'] = $request->input('handler');
        $this->scheduledTaskService->create($data);

        return back()->with('success', __('Scheduled task created.'));
    }

    public function run(TzScheduledTask $task): RedirectResponse
    {
        $this->scheduledTaskService->run($task);
        return back()->with('success', __('Scheduled task executed.'));
    }
}
