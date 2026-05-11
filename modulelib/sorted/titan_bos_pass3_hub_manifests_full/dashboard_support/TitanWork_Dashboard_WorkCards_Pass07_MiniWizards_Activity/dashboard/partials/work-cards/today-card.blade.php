@php
    $todayJobs = collect($todayJobs ?? [
        (object) ['id' => 1042, 'title' => __('Morning Deep Clean'), 'time' => '09:00', 'site' => __('Southbank Tower'), 'status' => 'pending', 'staff' => __('Anna + 1'), 'checklist' => 8, 'completed' => 3, 'priority' => __('High'), 'next_action' => __('Start arrival checklist'), 'risk' => __('Photo evidence missing'), 'eta' => __('2.0h')],
        (object) ['id' => 1043, 'title' => __('Quarterly Inspection'), 'time' => '11:30', 'site' => __('Docklands Office'), 'status' => 'active', 'staff' => __('Mike'), 'checklist' => 6, 'completed' => 4, 'priority' => __('Normal'), 'next_action' => __('Capture defect photos'), 'risk' => __('Running 15 min late'), 'eta' => __('1.5h')],
        (object) ['id' => 1044, 'title' => __('Move-out Reset'), 'time' => '14:00', 'site' => __('Richmond Unit 6'), 'status' => 'complete', 'staff' => __('Sarah + 2'), 'checklist' => 12, 'completed' => 12, 'priority' => __('Closed'), 'next_action' => __('Ready for QA handoff'), 'risk' => __('No issues detected'), 'eta' => __('Done')],
    ]);

    $liveCount = $todayJobs->whereIn('status', ['pending', 'active'])->count();
@endphp

<x-card class="flex w-full flex-col" id="work-today" size="md">
    <x-slot:head class="pb-4">
        <div class="flex items-start justify-between gap-3 max-sm:flex-col">
            <div>
                <p class="m-0 text-[11px] font-semibold uppercase tracking-[0.16em] text-foreground/45">@lang('Execution')</p>
                <div class="mt-1 flex items-center gap-2">
                    <h4 class="m-0 text-[17px]">@lang('Today')</h4>
                    @include('dashboard.partials.work-cards.components.status-pill', ['tone' => 'sky', 'label' => $liveCount . ' ' . __('live')])
                </div>
                <p class="m-0 mt-1 text-xs text-foreground/60">@lang('Run the day from one card: start, complete, and catch risks early.')</p>
            </div>
            <div class="grid grid-cols-2 gap-2 sm:min-w-[260px]">
                @include('dashboard.partials.work-cards.components.metric-chip', ['label' => __('Live jobs'), 'value' => $liveCount])
                @include('dashboard.partials.work-cards.components.metric-chip', ['label' => __('Ready for QA'), 'value' => $todayJobs->where('status', 'complete')->count()])
            </div>
        </div>
    </x-slot:head>

    <div class="space-y-3" x-data="{ expanded: null, completeFlow: null, noteDraft: '' }">
        @foreach ($todayJobs as $job)
            @php
                $statusTone = match ($job->status) {
                    'active' => 'green',
                    'complete' => 'sky',
                    default => 'amber',
                };
                $progress = max(0, min(100, (int) round(($job->completed / max(1, $job->checklist)) * 100)));
            @endphp
            <div class="rounded-2xl border border-border bg-background/60 p-4 transition hover:border-foreground/15">
                <div class="flex items-start justify-between gap-3 max-lg:flex-col">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <div class="rounded-xl bg-foreground/[0.04] px-2.5 py-1 text-xs font-semibold text-foreground/70">{{ $job->time }}</div>
                            @include('dashboard.partials.work-cards.components.status-pill', ['tone' => $statusTone, 'label' => ucfirst($job->status)])
                            @include('dashboard.partials.work-cards.components.status-pill', ['tone' => $job->status === 'complete' ? 'sky' : 'default', 'label' => $job->eta])
                        </div>
                        <h5 class="mt-3 text-sm font-semibold text-heading-foreground">{{ $job->title }}</h5>
                        <p class="m-0 mt-1 text-xs text-foreground/60">{{ $job->site }} • {{ $job->staff }} • {{ $job->priority }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" class="inline-flex items-center gap-2 rounded-full border border-border px-3 py-1.5 text-xs font-semibold text-foreground transition hover:bg-foreground/[0.03]" @click="startJob({{ $job->id }})">
                            <x-tabler-player-play class="size-4" /> @lang('Start')
                        </button>
                        <button type="button" class="inline-flex items-center gap-2 rounded-full border border-border px-3 py-1.5 text-xs font-semibold text-foreground transition hover:bg-foreground/[0.03]" @click="completeFlow === {{ $job->id }} ? completeFlow = null : completeFlow = {{ $job->id }}">
                            <x-tabler-check class="size-4" /> @lang('Complete')
                        </button>
                        <button type="button" class="inline-flex items-center gap-2 rounded-full bg-foreground/[0.05] px-3 py-1.5 text-xs font-semibold text-foreground/80 transition hover:bg-foreground/[0.08]" @click="expanded === {{ $job->id }} ? expanded = null : expanded = {{ $job->id }}">
                            <x-tabler-layout-list class="size-4" /> @lang('Open run sheet')
                        </button>
                    </div>
                </div>

                <div class="mt-4 grid gap-3 lg:grid-cols-[minmax(0,1fr),auto] lg:items-center">
                    <div>
                        <div class="mb-2 flex items-center justify-between text-[11px] text-foreground/50">
                            <span>@lang('Checklist progress')</span>
                            <span>{{ $job->completed }}/{{ $job->checklist }}</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-foreground/[0.06]">
                            <div class="h-full rounded-full bg-accent" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        @include('dashboard.partials.work-cards.components.assistant-chip', ['icon' => 'arrow-right-circle', 'tone' => 'accent'])
                            {{ $job->next_action }}
                        @endinclude
                        @include('dashboard.partials.work-cards.components.assistant-chip', ['icon' => 'shield-alert', 'tone' => $job->status === 'complete' ? 'default' : 'warning'])
                            {{ $job->risk }}
                        @endinclude
                    </div>
                </div>

                <div class="mt-4 space-y-3" x-show="expanded === {{ $job->id }}" x-collapse>
                    <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                        <div class="rounded-xl border border-border bg-background p-3 text-sm text-foreground/75">
                            <div class="mb-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-foreground/45">@lang('Run sheet')</div>
                            <p class="m-0">@lang('Access confirmed, checklist synced, and evidence capture is ready for this visit.')</p>
                        </div>
                        <div class="rounded-xl border border-border bg-background p-3 text-sm text-foreground/75">
                            <div class="mb-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-foreground/45">@lang('Assistant')</div>
                            <p class="m-0">@lang('Recommend finishing exterior photos before the final handoff to avoid QA rework.')</p>
                        </div>
                        <div class="rounded-xl border border-border bg-background p-3 text-sm text-foreground/75">
                            <div class="mb-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-foreground/45">@lang('Fast actions')</div>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" class="inline-flex items-center gap-1 rounded-full border border-border px-2.5 py-1 text-xs font-semibold text-foreground/75"><x-tabler-notes class="size-3.5" /> @lang('Note')</button>
                                <button type="button" class="inline-flex items-center gap-1 rounded-full border border-border px-2.5 py-1 text-xs font-semibold text-foreground/75"><x-tabler-camera class="size-3.5" /> @lang('Photo')</button>
                                <button type="button" class="inline-flex items-center gap-1 rounded-full border border-border px-2.5 py-1 text-xs font-semibold text-foreground/75"><x-tabler-shield-check class="size-3.5" /> @lang('QA')</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 rounded-2xl border border-border bg-background p-4" x-show="completeFlow === {{ $job->id }}" x-collapse>
                    <div class="flex items-center justify-between gap-3 max-sm:flex-col max-sm:items-start">
                        <div>
                            <div class="text-[11px] font-semibold uppercase tracking-[0.14em] text-foreground/45">@lang('Complete job')</div>
                            <h6 class="mt-1 text-sm font-semibold text-heading-foreground">@lang('Inline completion wizard')</h6>
                        </div>
                        @include('dashboard.partials.work-cards.components.assistant-chip', ['icon' => 'sparkles', 'tone' => 'accent'])
                            @lang('QA handoff prepared')
                        @endinclude
                    </div>
                    <div class="mt-4 grid gap-3 lg:grid-cols-[minmax(0,1fr),minmax(0,1fr),auto] lg:items-end">
                        <label class="block text-xs font-semibold text-foreground/60">
                            @lang('Completion note')
                            <textarea x-model="noteDraft" rows="3" class="mt-2 w-full rounded-2xl border border-border bg-background px-3 py-2 text-sm text-foreground outline-none ring-0 placeholder:text-foreground/35" placeholder="@lang('Add final note or blocker…')"></textarea>
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            @include('dashboard.partials.work-cards.components.metric-chip', ['label' => __('Evidence'), 'value' => __('Ready')])
                            @include('dashboard.partials.work-cards.components.metric-chip', ['label' => __('Next step'), 'value' => __('Send to QA')])
                        </div>
                        <button type="button" class="inline-flex items-center justify-center gap-2 rounded-full bg-accent px-4 py-2 text-sm font-semibold text-accent-foreground transition hover:opacity-90" @click="completeJob({{ $job->id }}); completeFlow = null; noteDraft = ''">
                            <x-tabler-check class="size-4" /> @lang('Complete & hand off')
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-card>
