@php
    $dispatchJobs = collect($dispatchJobs ?? [
        (object) ['id' => 2051, 'title' => __('End of Lease Refresh'), 'window' => __('Today • 1:30 PM'), 'site' => __('St Kilda Rd'), 'priority' => __('High'), 'duration' => __('3.5h'), 'fit' => __('Needs 2 cleaners + key pickup')],
        (object) ['id' => 2052, 'title' => __('Weekly Office Reset'), 'window' => __('Tomorrow • 7:00 AM'), 'site' => __('South Wharf'), 'priority' => __('Normal'), 'duration' => __('2h'), 'fit' => __('Strong recurring fit')],
        (object) ['id' => 2053, 'title' => __('Inspection Recovery'), 'window' => __('Today • 4:00 PM'), 'site' => __('South Yarra'), 'priority' => __('Urgent'), 'duration' => __('1h'), 'fit' => __('Inspector follow-up required')],
    ]);

    $dispatchCrew = collect($dispatchCrew ?? [
        (object) ['id' => 41, 'name' => __('Anna'), 'role' => __('Lead Cleaner'), 'status' => __('Free in 45 min'), 'load' => 62, 'fit' => __('Best match'), 'next' => __('Morning Deep Clean')],
        (object) ['id' => 42, 'name' => __('Mike'), 'role' => __('Inspector'), 'status' => __('On active visit'), 'load' => 88, 'fit' => __('Overloaded'), 'next' => __('Quarterly Inspection')],
        (object) ['id' => 43, 'name' => __('Sarah'), 'role' => __('Cleaner'), 'status' => __('Available now'), 'load' => 28, 'fit' => __('Fast dispatch'), 'next' => __('Ready for office reset')],
    ]);
@endphp

<x-card class="flex w-full flex-col" id="work-dispatch" size="md">
    <x-slot:head class="pb-4">
        <div class="flex items-start justify-between gap-3 max-sm:flex-col">
            <div>
                <p class="m-0 text-[11px] font-semibold uppercase tracking-[0.16em] text-foreground/45">@lang('Allocation')</p>
                <div class="mt-1 flex items-center gap-2">
                    <h4 class="m-0 text-[17px]">@lang('Dispatch')</h4>
                    @include('dashboard.partials.work-cards.components.status-pill', ['tone' => 'amber', 'label' => $dispatchJobs->count() . ' ' . __('unassigned')])
                </div>
                <p class="m-0 mt-1 text-xs text-foreground/60">@lang('Assign work quickly, see fit signals, and rebalance before the day slips.') </p>
            </div>
            <div class="grid grid-cols-2 gap-2 sm:min-w-[260px]">
                @include('dashboard.partials.work-cards.components.metric-chip', ['label' => __('Unassigned'), 'value' => $dispatchJobs->count()])
                @include('dashboard.partials.work-cards.components.metric-chip', ['label' => __('Crew live'), 'value' => $dispatchCrew->count()])
            </div>
        </div>
    </x-slot:head>

    <div class="grid gap-4 xl:grid-cols-[minmax(0,1.1fr),minmax(0,0.9fr)]" x-data="{ selectedJob: null, crewDrawer: false, pickedMember: null }">
        <div class="space-y-3">
            @foreach ($dispatchJobs as $job)
                <div class="rounded-2xl border border-border bg-background/60 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                @include('dashboard.partials.work-cards.components.status-pill', ['tone' => $job->priority === __('Urgent') ? 'red' : ($job->priority === __('High') ? 'amber' : 'default'), 'label' => $job->priority])
                                @include('dashboard.partials.work-cards.components.status-pill', ['tone' => 'default', 'label' => $job->duration])
                            </div>
                            <h5 class="mt-3 text-sm font-semibold text-heading-foreground">{{ $job->title }}</h5>
                            <p class="m-0 mt-1 text-xs text-foreground/60">{{ $job->window }} • {{ $job->site }}</p>
                        </div>
                        <button type="button" class="inline-flex items-center gap-2 rounded-full bg-accent px-3 py-1.5 text-xs font-semibold text-accent-foreground transition hover:opacity-90" @click="selectedJob = {{ $job->id }}; crewDrawer = true; pickedMember = null">
                            <x-tabler-route class="size-4" /> @lang('Assign now')
                        </button>
                    </div>
                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        @include('dashboard.partials.work-cards.components.assistant-chip', ['icon' => 'sparkles', 'tone' => 'accent'])
                            {{ $job->fit }}
                        @endinclude
                        @include('dashboard.partials.work-cards.components.assistant-chip', ['icon' => 'clock-hour-4', 'tone' => 'default'])
                            @lang('Window ready')
                        @endinclude
                    </div>
                </div>
            @endforeach
        </div>

        <div class="space-y-3">
            <div class="rounded-2xl border border-border bg-background/60 p-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="text-[11px] font-semibold uppercase tracking-[0.14em] text-foreground/45">@lang('Crew capacity')</div>
                        <h5 class="mt-1 text-sm font-semibold text-heading-foreground">@lang('Best fit suggestions')</h5>
                    </div>
                    @include('dashboard.partials.work-cards.components.assistant-chip', ['icon' => 'sparkles', 'tone' => 'accent'])
                        @lang('Dispatch assistant')
                    @endinclude
                </div>
                <div class="mt-4 space-y-3">
                    @foreach ($dispatchCrew as $member)
                        <button type="button" class="block w-full rounded-2xl border border-border bg-background p-3 text-left transition hover:border-foreground/15" @click="pickedMember = '{{ $member->name }}'">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-sm font-semibold text-heading-foreground">{{ $member->name }}</div>
                                    <p class="m-0 mt-1 text-xs text-foreground/60">{{ $member->role }} • {{ $member->status }}</p>
                                </div>
                                @include('dashboard.partials.work-cards.components.status-pill', ['tone' => $member->fit === __('Overloaded') ? 'red' : ($member->fit === __('Best match') ? 'green' : 'sky'), 'label' => $member->fit])
                            </div>
                            <div class="mt-3 flex items-center gap-3">
                                <div class="min-w-0 flex-1">
                                    <div class="mb-1 flex items-center justify-between text-[11px] text-foreground/50">
                                        <span>@lang('Load')</span>
                                        <span>{{ $member->load }}%</span>
                                    </div>
                                    <div class="h-2 overflow-hidden rounded-full bg-foreground/[0.06]">
                                        <div class="h-full rounded-full {{ $member->load >= 80 ? 'bg-rose-500' : ($member->load >= 60 ? 'bg-amber-500' : 'bg-accent') }}" style="width: {{ $member->load }}%"></div>
                                    </div>
                                </div>
                                <div class="text-xs text-foreground/60">{{ $member->next }}</div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-border bg-background p-4" x-show="crewDrawer" x-collapse>
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="text-[11px] font-semibold uppercase tracking-[0.14em] text-foreground/45">@lang('Inline dispatch wizard')</div>
                        <h5 class="mt-1 text-sm font-semibold text-heading-foreground">@lang('Confirm assignee + dispatch intent')</h5>
                    </div>
                    @include('dashboard.partials.work-cards.components.status-pill', ['tone' => 'sky', 'label' => __('No reload')])
                </div>
                <div class="mt-4 grid gap-3 lg:grid-cols-[minmax(0,1fr),auto] lg:items-end">
                    <label class="block text-xs font-semibold text-foreground/60">
                        @lang('Chosen crew member')
                        <select x-model="pickedMember" class="mt-2 w-full rounded-2xl border border-border bg-background px-3 py-2 text-sm text-foreground outline-none ring-0">
                            <option value="">@lang('Select team member')</option>
                            @foreach ($dispatchCrew as $member)
                                <option value="{{ $member->name }}">{{ $member->name }} — {{ $member->fit }}</option>
                            @endforeach
                        </select>
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        @include('dashboard.partials.work-cards.components.metric-chip', ['label' => __('Suggested'), 'value' => __('Best fit first')])
                        @include('dashboard.partials.work-cards.components.metric-chip', ['label' => __('Dispatch'), 'value' => __('Ready')])
                    </div>
                    <button type="button" class="inline-flex items-center justify-center gap-2 rounded-full bg-accent px-4 py-2 text-sm font-semibold text-accent-foreground transition hover:opacity-90" :disabled="!selectedJob || !pickedMember" @click="if(selectedJob && pickedMember){ assignJob(selectedJob, pickedMember); crewDrawer = false; pickedMember = null; selectedJob = null; }">
                        <x-tabler-check class="size-4" /> @lang('Assign job')
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-card>
