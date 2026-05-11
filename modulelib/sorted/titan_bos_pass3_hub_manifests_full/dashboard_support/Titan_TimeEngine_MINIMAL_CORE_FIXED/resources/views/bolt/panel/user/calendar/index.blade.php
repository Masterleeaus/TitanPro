@extends('panel.layout.app', ['disable_tblr' => true])
@section('title', __('Titan Calendar'))

@section('content')
    <div class="py-10">
        <div class="container-xl space-y-6">
            @include('panel.user.calendar.partials.titlebar')

            <x-card class="overflow-hidden" id="time-engine-card">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <p class="mb-1 text-xs font-semibold uppercase tracking-[0.2em] text-primary">{{ __('Titan Time Engine') }}</p>
                        <h2 class="mb-2 text-2xl font-semibold">{{ __('Schedule, dispatch, roster and automation in one surface') }}</h2>
                        <p class="max-w-3xl text-sm opacity-70">{{ __('Use tabs to move between planning, job allocation, staff load, scheduled automation and the live operating timeline.') }}</p>
                    </div>
                    <div class="grid w-full gap-3 sm:grid-cols-2 xl:w-[720px] xl:grid-cols-4">
                        <div class="rounded-2xl border border-black/5 bg-background/80 p-4">
                            <p class="text-xs uppercase opacity-60">{{ __('Tracked events') }}</p>
                            <h3 class="mt-2 text-2xl font-semibold">{{ number_format($calendarSummary['events_count'] ?? 0) }}</h3>
                            <p class="mt-1 text-xs opacity-70">{{ $calendarSummary['event_share'] ?? 0 }}% {{ __('of tracked time items') }}</p>
                        </div>
                        <div class="rounded-2xl border border-black/5 bg-background/80 p-4">
                            <p class="text-xs uppercase opacity-60">{{ __('Scheduled tasks') }}</p>
                            <h3 class="mt-2 text-2xl font-semibold">{{ number_format($calendarSummary['tasks_count'] ?? 0) }}</h3>
                            <p class="mt-1 text-xs opacity-70">{{ $calendarSummary['task_share'] ?? 0 }}% {{ __('of tracked time items') }}</p>
                        </div>
                        <div class="rounded-2xl border border-black/5 bg-background/80 p-4">
                            <p class="text-xs uppercase opacity-60">{{ __('Jobs today') }}</p>
                            <h3 class="mt-2 text-2xl font-semibold">{{ number_format(data_get($calendarOps, 'dispatch.scheduled_today', 0)) }}</h3>
                            <p class="mt-1 text-xs opacity-70">{{ __('Unassigned: :count', ['count' => data_get($calendarOps, 'dispatch.unassigned', 0)]) }}</p>
                        </div>
                        <div class="rounded-2xl border border-black/5 bg-background/80 p-4">
                            <p class="text-xs uppercase opacity-60">{{ __('Staff load') }}</p>
                            <h3 class="mt-2 text-2xl font-semibold">{{ number_format(data_get($calendarOps, 'roster.scheduled_staff', 0)) }}</h3>
                            <p class="mt-1 text-xs opacity-70">{{ __('Available: :count', ['count' => data_get($calendarOps, 'roster.available_staff', 0)]) }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap gap-2 border-t pt-5">
                    @foreach ([
                        'schedule' => __('Schedule'),
                        'dispatch' => __('Dispatch'),
                        'roster' => __('Roster'),
                        'tasks' => __('Tasks'),
                        'timeline' => __('Timeline'),
                    ] as $tabKey => $tabLabel)
                        <button
                            type="button"
                            class="time-engine-tab rounded-full border px-4 py-2 text-sm font-medium transition hover:border-primary hover:text-primary"
                            data-tab-target="{{ $tabKey }}"
                            @if ($loop->first) data-active="true" @endif
                        >
                            {{ $tabLabel }}
                        </button>
                    @endforeach
                </div>
            </x-card>

            <div class="space-y-6">
                <section class="time-engine-panel" data-tab-panel="schedule">
                    <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
                        <div class="space-y-6">
                            @include('panel.user.calendar.partials.filters')
                            <x-card class="overflow-hidden">
                                <div class="mb-4 grid gap-3 md:grid-cols-4">
                                    <div class="rounded-xl border p-4"><p class="mb-1 text-xs uppercase opacity-70">{{ __('Calendar events table') }}</p><strong>{{ !empty($calendarBootStatus['events_table']) ? __('Ready') : __('Missing') }}</strong></div>
                                    <div class="rounded-xl border p-4"><p class="mb-1 text-xs uppercase opacity-70">{{ __('Scheduled tasks table') }}</p><strong>{{ !empty($calendarBootStatus['scheduled_tasks_table']) ? __('Ready') : __('Missing') }}</strong></div>
                                    <div class="rounded-xl border p-4"><p class="mb-1 text-xs uppercase opacity-70">{{ __('Sync accounts table') }}</p><strong>{{ !empty($calendarBootStatus['sync_accounts_table']) ? __('Ready') : __('Missing') }}</strong></div>
                                    <div class="rounded-xl border p-4"><p class="mb-1 text-xs uppercase opacity-70">{{ __('Recurrence rules') }}</p><strong>{{ !empty($calendarBootStatus['recurrence_rules_table']) ? __('Ready') : __('Missing') }}</strong></div>
                                </div>
                                <div id="titan-calendar" class="min-h-[680px]"></div>
                            </x-card>
                        </div>
                        <div class="space-y-6">
                            <x-card>
                                <h3 class="mb-3 text-lg font-semibold">{{ __('Create manual event') }}</h3>
                                <form method="POST" action="{{ route('dashboard.user.calendar.store') }}" class="space-y-3">
                                    @csrf
                                    <input class="form-control" type="text" name="title" placeholder="{{ __('Event title') }}" required>
                                    <textarea class="form-control" name="description" rows="3" placeholder="{{ __('Description') }}"></textarea>
                                    <div class="grid grid-cols-2 gap-3">
                                        <input class="form-control" type="datetime-local" name="starts_at" required>
                                        <input class="form-control" type="datetime-local" name="ends_at">
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <select class="form-control" name="event_type">
                                            <option value="event">{{ __('Event') }}</option>
                                            <option value="appointment">{{ __('Appointment') }}</option>
                                            <option value="reminder">{{ __('Reminder') }}</option>
                                            <option value="follow_up">{{ __('Follow-up') }}</option>
                                        </select>
                                        <input class="form-control" type="text" name="color" placeholder="#2563eb">
                                    </div>
                                    <input class="form-control" type="text" name="location" placeholder="{{ __('Location / site') }}">
                                    <div class="grid grid-cols-2 gap-3">
                                        <select class="form-control" name="recurrence_frequency">
                                            <option value="">{{ __('No recurrence') }}</option>
                                            <option value="DAILY">{{ __('Daily') }}</option>
                                            <option value="WEEKLY">{{ __('Weekly') }}</option>
                                            <option value="MONTHLY">{{ __('Monthly') }}</option>
                                        </select>
                                        <input class="form-control" type="number" min="1" max="90" name="recurrence_interval" placeholder="{{ __('Every X units') }}">
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <input class="form-control" type="date" name="recurrence_until">
                                        <input class="form-control" type="number" min="1" max="365" name="recurrence_count" placeholder="{{ __('Occurrences') }}">
                                    </div>
                                    <div class="grid grid-cols-4 gap-2 text-xs">
                                        @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                                            <label class="flex items-center gap-1 rounded-lg border px-2 py-2"><input type="checkbox" name="recurrence_days[]" value="{{ $day }}"> <span>{{ $day }}</span></label>
                                        @endforeach
                                    </div>
                                    <label class="flex items-center gap-2"><input type="checkbox" name="all_day" value="1"> <span>{{ __('All day') }}</span></label>
                                    <button class="btn btn-primary w-full">{{ __('Create event') }}</button>
                                </form>
                            </x-card>
                            <x-card>
                                <h3 class="mb-3 text-lg font-semibold">{{ __('Schedule engine') }}</h3>
                                <p class="mb-3 text-sm opacity-70">{{ __('Use Titan Schedule Engine for reminders, dispatch and follow-ups.') }}</p>
                                <a href="{{ route('dashboard.user.calendar.scheduled-tasks') }}" class="btn btn-secondary w-full">{{ __('Open scheduled tasks') }}</a>
                            </x-card>
                        </div>
                    </div>
                </section>

                <section class="time-engine-panel hidden" data-tab-panel="dispatch">
                    <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
                        <x-card>
                            <div class="mb-4 flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold">{{ __('Dispatch board') }}</h3>
                                    <p class="text-sm opacity-70">{{ __('Today\'s jobs, assignment gaps and completion pressure.') }}</p>
                                </div>
                                <a href="{{ route('dashboard.user.calendar.index') }}?source=job" class="btn btn-outline-primary">{{ __('View jobs on calendar') }}</a>
                            </div>
                            <div class="mb-5 grid gap-3 md:grid-cols-3">
                                <div class="rounded-xl border p-4"><p class="text-xs uppercase opacity-60">{{ __('Scheduled today') }}</p><h4 class="mt-2 text-2xl font-semibold">{{ data_get($calendarOps, 'dispatch.scheduled_today', 0) }}</h4></div>
                                <div class="rounded-xl border p-4"><p class="text-xs uppercase opacity-60">{{ __('Unassigned') }}</p><h4 class="mt-2 text-2xl font-semibold">{{ data_get($calendarOps, 'dispatch.unassigned', 0) }}</h4></div>
                                <div class="rounded-xl border p-4"><p class="text-xs uppercase opacity-60">{{ __('Completed today') }}</p><h4 class="mt-2 text-2xl font-semibold">{{ data_get($calendarOps, 'dispatch.completed_today', 0) }}</h4></div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Job') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Scheduled') }}</th>
                                            <th>{{ __('Assignee') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(data_get($calendarOps, 'dispatch.items', collect()) as $job)
                                            <tr>
                                                <td>
                                                    <div class="font-medium">{{ $job['title'] ?? __('Job') }}</div>
                                                    @if(!empty($job['customer']))<div class="text-xs opacity-60">{{ $job['customer'] }}</div>@endif
                                                </td>
                                                <td>{{ str($job['status'] ?? 'pending')->headline() }}</td>
                                                <td>{{ !empty($job['scheduled_at']) ? \\Illuminate\\Support\\Carbon::parse($job['scheduled_at'])->format('Y-m-d H:i') : '—' }}</td>
                                                <td>{{ $job['assignee'] ?: __('Unassigned') }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center opacity-70">{{ __('No jobs available for dispatch yet.') }}</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </x-card>
                        <x-card>
                            <h3 class="mb-3 text-lg font-semibold">{{ __('Dispatch guidance') }}</h3>
                            <div class="space-y-3 text-sm">
                                <div class="rounded-xl border p-4">
                                    <strong class="block">{{ __('Priority 1') }}</strong>
                                    <span class="opacity-70">{{ __('Assign unallocated jobs first, then move overdue reminders into the next available slot.') }}</span>
                                </div>
                                <div class="rounded-xl border p-4">
                                    <strong class="block">{{ __('Priority 2') }}</strong>
                                    <span class="opacity-70">{{ __('Use drag and drop on the Schedule tab to rebalance start times without leaving the board.') }}</span>
                                </div>
                                <div class="rounded-xl border p-4">
                                    <strong class="block">{{ __('Priority 3') }}</strong>
                                    <span class="opacity-70">{{ __('Open the Tasks tab to run failed dispatch or reminder automations immediately.') }}</span>
                                </div>
                            </div>
                        </x-card>
                    </div>
                </section>

                <section class="time-engine-panel hidden" data-tab-panel="roster">
                    <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
                        <x-card>
                            <div class="mb-4 flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold">{{ __('Roster overview') }}</h3>
                                    <p class="text-sm opacity-70">{{ __('Who is loaded, who is free, and where capacity is thin.') }}</p>
                                </div>
                            </div>
                            <div class="mb-5 grid gap-3 md:grid-cols-3">
                                <div class="rounded-xl border p-4"><p class="text-xs uppercase opacity-60">{{ __('Active staff') }}</p><h4 class="mt-2 text-2xl font-semibold">{{ data_get($calendarOps, 'roster.active_staff', 0) }}</h4></div>
                                <div class="rounded-xl border p-4"><p class="text-xs uppercase opacity-60">{{ __('With assignments') }}</p><h4 class="mt-2 text-2xl font-semibold">{{ data_get($calendarOps, 'roster.scheduled_staff', 0) }}</h4></div>
                                <div class="rounded-xl border p-4"><p class="text-xs uppercase opacity-60">{{ __('Available') }}</p><h4 class="mt-2 text-2xl font-semibold">{{ data_get($calendarOps, 'roster.available_staff', 0) }}</h4></div>
                            </div>
                            <div class="grid gap-3">
                                @forelse(data_get($calendarOps, 'roster.items', collect()) as $member)
                                    <div class="flex items-center justify-between rounded-xl border p-4">
                                        <div>
                                            <div class="font-medium">{{ $member['name'] ?? __('Staff member') }}</div>
                                            <div class="text-xs opacity-60">{{ $member['email'] ?: __('No email recorded') }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs uppercase opacity-60">{{ __('Assigned jobs') }}</div>
                                            <div class="text-lg font-semibold">{{ $member['assigned_jobs'] ?? 0 }}</div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="rounded-xl border p-5 text-center opacity-70">{{ __('No staff records available yet.') }}</div>
                                @endforelse
                            </div>
                        </x-card>
                        <x-card>
                            <h3 class="mb-3 text-lg font-semibold">{{ __('Roster cues') }}</h3>
                            <div class="space-y-3 text-sm">
                                <div class="rounded-xl border p-4">
                                    <strong class="block">{{ __('Balance the week') }}</strong>
                                    <span class="opacity-70">{{ __('Use the Schedule tab for visual balancing once you identify overloaded staff here.') }}</span>
                                </div>
                                <div class="rounded-xl border p-4">
                                    <strong class="block">{{ __('Protect free capacity') }}</strong>
                                    <span class="opacity-70">{{ __('Keep at least one unallocated staff slot for urgent jobs and follow-up work.') }}</span>
                                </div>
                            </div>
                        </x-card>
                    </div>
                </section>

                <section class="time-engine-panel hidden" data-tab-panel="tasks">
                    <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
                        <x-card>
                            <div class="mb-4 flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold">{{ __('Task queue') }}</h3>
                                    <p class="text-sm opacity-70">{{ __('Reminder, dispatch and follow-up automations waiting to run or recover.') }}</p>
                                </div>
                                <a href="{{ route('dashboard.user.calendar.scheduled-tasks') }}" class="btn btn-secondary">{{ __('Open full queue') }}</a>
                            </div>
                            <div class="mb-5 grid gap-3 md:grid-cols-3">
                                <div class="rounded-xl border p-4"><p class="text-xs uppercase opacity-60">{{ __('Pending') }}</p><h4 class="mt-2 text-2xl font-semibold">{{ data_get($calendarOps, 'tasks.pending', 0) }}</h4></div>
                                <div class="rounded-xl border p-4"><p class="text-xs uppercase opacity-60">{{ __('Running') }}</p><h4 class="mt-2 text-2xl font-semibold">{{ data_get($calendarOps, 'tasks.running', 0) }}</h4></div>
                                <div class="rounded-xl border p-4"><p class="text-xs uppercase opacity-60">{{ __('Failed') }}</p><h4 class="mt-2 text-2xl font-semibold">{{ data_get($calendarOps, 'tasks.failed', 0) }}</h4></div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Type') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Scheduled') }}</th>
                                            <th>{{ __('Priority') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(data_get($calendarOps, 'tasks.items', collect()) as $task)
                                            <tr>
                                                <td>{{ str($task->task_type ?? 'task')->headline() }}</td>
                                                <td>{{ str($task->status ?? 'pending')->headline() }}</td>
                                                <td>{{ !empty($task->scheduled_for) ? \\Illuminate\\Support\\Carbon::parse($task->scheduled_for)->format('Y-m-d H:i') : '—' }}</td>
                                                <td>{{ $task->priority ?? 0 }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center opacity-70">{{ __('No scheduled tasks found.') }}</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </x-card>
                        <x-card>
                            <h3 class="mb-3 text-lg font-semibold">{{ __('Engine actions') }}</h3>
                            <div class="space-y-3">
                                <a href="{{ route('dashboard.user.calendar.scheduled-tasks') }}" class="btn btn-primary w-full">{{ __('Create or run tasks') }}</a>
                                <a href="{{ route('dashboard.user.calendar.settings') }}" class="btn btn-outline-primary w-full">{{ __('Manage sync settings') }}</a>
                            </div>
                        </x-card>
                    </div>
                </section>

                <section class="time-engine-panel hidden" data-tab-panel="timeline">
                    <x-card>
                        <div class="mb-4 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold">{{ __('Operational timeline') }}</h3>
                                <p class="text-sm opacity-70">{{ __('Recent events and automation moments across the Time Engine.') }}</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            @forelse(data_get($calendarOps, 'timeline', collect()) as $item)
                                <div class="flex items-start justify-between gap-4 rounded-xl border p-4">
                                    <div>
                                        <div class="font-medium">{{ $item['title'] }}</div>
                                        <div class="text-xs uppercase opacity-60">{{ $item['meta'] }}</div>
                                    </div>
                                    <div class="text-sm opacity-70">{{ !empty($item['stamp']) ? \\Illuminate\\Support\\Carbon::parse($item['stamp'])->format('Y-m-d H:i') : '—' }}</div>
                                </div>
                            @empty
                                <div class="rounded-xl border p-5 text-center opacity-70">{{ __('Timeline will populate as calendar events and engine tasks are created.') }}</div>
                            @endforelse
                        </div>
                    </x-card>
                </section>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ custom_theme_url('/assets/libs/fullcalendar/index.global.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const panels = Array.from(document.querySelectorAll('.time-engine-panel'));
            const tabButtons = Array.from(document.querySelectorAll('.time-engine-tab'));
            const activateTab = (target) => {
                tabButtons.forEach((button) => {
                    const active = button.dataset.tabTarget === target;
                    button.dataset.active = active ? 'true' : 'false';
                    button.classList.toggle('bg-primary');
                    button.classList.toggle('text-white');
                    button.classList.toggle('border-primary', active);
                });
                panels.forEach((panel) => {
                    panel.classList.toggle('hidden', panel.dataset.tabPanel !== target);
                });
            };

            tabButtons.forEach((button) => {
                button.addEventListener('click', () => activateTab(button.dataset.tabTarget));
            });
            activateTab(tabButtons.find((button) => button.dataset.active === 'true')?.dataset.tabTarget || 'schedule');

            const el = document.getElementById('titan-calendar');
            if (!el || typeof FullCalendar === 'undefined') return;
            const sourceSelect = document.getElementById('calendar-source-filter');
            const statusSelect = document.getElementById('calendar-status-filter');
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const getFeedUrl = (info) => {
                const params = new URLSearchParams({ start: info.startStr, end: info.endStr });
                if (sourceSelect?.value) params.set('source', sourceSelect.value);
                if (statusSelect?.value) params.set('status', statusSelect.value);
                return `{{ route('dashboard.user.calendar.feed') }}?${params.toString()}`;
            };
            const persistMove = async (changeInfo) => {
                const response = await fetch(`{{ route('dashboard.user.calendar.events.move') }}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: JSON.stringify({
                        id: changeInfo.event.id,
                        start: changeInfo.event.startStr,
                        end: changeInfo.event.endStr,
                        allDay: changeInfo.event.allDay,
                    })
                });
                const data = await response.json();
                if (!data.success) {
                    throw new Error(data.message || 'Move failed');
                }
            };
            const calendar = new FullCalendar.Calendar(el, {
                initialView: 'dayGridMonth',
                height: 'auto',
                editable: true,
                eventStartEditable: true,
                eventDurationEditable: true,
                headerToolbar: { start: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek', center: 'title', end: 'today prev,next' },
                eventSources: [{
                    id: 'titan-calendar-feed',
                    events: async (info, success, failure) => {
                        try {
                            const response = await fetch(getFeedUrl(info));
                            const data = await response.json();
                            success(data.events || []);
                        } catch (error) { failure(error); }
                    }
                }],
                eventDrop: async (info) => {
                    try { await persistMove(info); } catch (e) { info.revert(); }
                },
                eventResize: async (info) => {
                    try { await persistMove(info); } catch (e) { info.revert(); }
                }
            });
            sourceSelect?.addEventListener('change', () => calendar.refetchEvents());
            statusSelect?.addEventListener('change', () => calendar.refetchEvents());
            calendar.render();
        });
    </script>
@endpush
