@extends('panel.layout.app')
@section('title', __('Titan Scheduled Tasks'))

@section('content')
    <div class="py-10">
        <div class="container-xl grid gap-6 lg:grid-cols-[360px_1fr]">
            <x-card>
                <h2 class="mb-3 text-lg font-semibold">{{ __('Create scheduled task') }}</h2>
                <form method="POST" action="{{ route('dashboard.user.calendar.scheduled-tasks.store') }}" class="space-y-3">
                    @csrf
                    <select class="form-control" name="task_type" required>
                        <option value="send_reminder">{{ __('Send reminder') }}</option>
                        <option value="publish_post">{{ __('Publish post') }}</option>
                        <option value="create_follow_up">{{ __('Create follow-up') }}</option>
                        <option value="invoice_due_reminder">{{ __('Invoice due reminder') }}</option>
                        <option value="dispatch_work">{{ __('Dispatch work') }}</option>
                    </select>
                    <input class="form-control" type="datetime-local" name="scheduled_for" required>
                    <input class="form-control" type="number" name="priority" min="0" max="100" value="10" placeholder="{{ __('Priority') }}">
                    <button class="btn btn-primary w-full">{{ __('Create scheduled task') }}</button>
                </form>
            </x-card>
            <x-card>
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold">{{ __('Recent tasks') }}</h2>
                    <a href="{{ route('dashboard.user.calendar.index') }}" class="btn btn-secondary">{{ __('Back to calendar') }}</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead><tr><th>{{ __('ID') }}</th><th>{{ __('Type') }}</th><th>{{ __('Status') }}</th><th>{{ __('Scheduled for') }}</th><th>{{ __('Action') }}</th></tr></thead>
                        <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td>{{ $task->id }}</td>
                                <td>{{ $task->task_type }}</td>
                                <td>{{ $task->status }}</td>
                                <td>{{ optional($task->scheduled_for)->format('Y-m-d H:i') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('dashboard.user.calendar.scheduled-tasks.run', $task) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-primary">{{ __('Run now') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center opacity-70">{{ __('No scheduled tasks found.') }}</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
@endsection
