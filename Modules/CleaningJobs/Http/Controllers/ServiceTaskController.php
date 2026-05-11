<?php

namespace Modules\CleaningJobs\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CleaningJobs\Models\ServiceTask;

class ServiceTaskController extends Controller
{
    public function index()
    {
        return view('cleaningjobs::crud.index', ['resource' => 'ServiceTaskController']);
    }

    public function create()
    {
        return view('cleaningjobs::crud.create', ['resource' => 'ServiceTaskController']);
    }

    public function store(Request $request): RedirectResponse
    {
        ServiceTask::create($this->validated($request));

        return redirect()
            ->route('cleaningjobs.service-tasks.index')
            ->with('status', 'created');
    }

    public function show($id)
    {
        return view('cleaningjobs::crud.show', compact('id'));
    }

    public function edit($id)
    {
        return view('cleaningjobs::crud.edit', compact('id'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        ServiceTask::query()->findOrFail($id)->update($this->validated($request));

        return redirect()
            ->route('cleaningjobs.service-tasks.index')
            ->with('status', 'updated');
    }

    public function destroy(int $id): RedirectResponse
    {
        ServiceTask::query()->findOrFail($id)->delete();

        return redirect()
            ->route('cleaningjobs.service-tasks.index')
            ->with('status', 'deleted');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'service_id' => ['required', 'integer'],
            'task' => ['required', 'string', 'max:255'],
            'duration' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
