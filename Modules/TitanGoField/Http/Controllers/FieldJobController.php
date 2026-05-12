<?php

namespace Modules\TitanGoField\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\TitanGoField\Actions\CheckInFieldJobAction;
use Modules\TitanGoField\Actions\CompleteFieldJobAction;
use Modules\TitanGoField\Actions\CreateFieldJobAction;
use Modules\TitanGoField\Actions\UpdateFieldJobStatusAction;
use Modules\TitanGoField\Http\Requests\StoreFieldJobRequest;
use Modules\TitanGoField\Http\Requests\UpdateFieldJobRequest;
use Modules\TitanGoField\Models\FieldJob;
use Modules\TitanGoField\Support\DTOs\CreateFieldJobData;

class FieldJobController extends Controller
{
    public function __construct(
        private readonly CreateFieldJobAction $createAction,
        private readonly UpdateFieldJobStatusAction $statusAction,
        private readonly CompleteFieldJobAction $completeAction,
    ) {}

    public function index(): View
    {
        $jobs = FieldJob::scopeTenant(FieldJob::query(), auth()->user()->company_id)
            ->latest()
            ->paginate(25);

        return view('titango_field::jobs.index', compact('jobs'));
    }

    public function create(): View
    {
        return view('titango_field::jobs.create');
    }

    public function store(StoreFieldJobRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = CreateFieldJobData::fromArray(
            array_merge($request->validated(), [
                'company_id' => $user->company_id,
                'actor_id'   => $user->id,
            ])
        );

        $job = $this->createAction->execute($data);

        return redirect()->route('titango_field.jobs.show', $job->id)
            ->with('success', 'Field job created.');
    }

    public function show(FieldJob $job): View
    {
        $this->authorize('view', $job);
        $job->load(['serviceParts', 'serviceTasks', 'appointments', 'comments', 'permits', 'checklistRuns']);
        return view('titango_field::jobs.show', compact('job'));
    }

    public function edit(FieldJob $job): View
    {
        $this->authorize('update', $job);
        return view('titango_field::jobs.edit', compact('job'));
    }

    public function update(UpdateFieldJobRequest $request, FieldJob $job): RedirectResponse
    {
        $job->update(array_merge($request->validated(), ['updated_by' => $request->user()->id]));

        return redirect()->route('titango_field.jobs.show', $job->id)
            ->with('success', 'Field job updated.');
    }

    public function destroy(FieldJob $job): RedirectResponse
    {
        $this->authorize('delete', $job);
        $job->delete();

        return redirect()->route('titango_field.jobs.index')
            ->with('success', 'Field job deleted.');
    }

    public function updateStatus(\Illuminate\Http\Request $request, FieldJob $job): RedirectResponse
    {
        $this->authorize('update', $job);
        $request->validate(['status' => ['required', 'string']]);

        $this->statusAction->execute($job, $request->input('status'), $request->user()->id);

        return back()->with('success', 'Status updated.');
    }
}
