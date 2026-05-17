@php
    $qaJobs = collect($qaJobs ?? [
        (object) ['title' => __('Deep Clean Handover'), 'state' => __('Checklist incomplete'), 'severity' => 'amber', 'evidence' => __('2 of 4 photos uploaded'), 'completion' => 68, 'next' => __('Collect final evidence')],
        (object) ['title' => __('Inspection Follow-up'), 'state' => __('Failed inspection'), 'severity' => 'red', 'evidence' => __('Revisit required'), 'completion' => 40, 'next' => __('Reopen and assign revisit')],
        (object) ['title' => __('Office Reset'), 'state' => __('Passed QA'), 'severity' => 'green', 'evidence' => __('Evidence pack complete'), 'completion' => 100, 'next' => __('Ready for money trigger')],
    ]);
@endphp

<x-card class="flex w-full flex-col" id="work-qa" size="md">
    <x-slot:head class="pb-4">
        <div class="flex items-start justify-between gap-3 max-sm:flex-col">
            <div>
                <p class="m-0 text-[11px] font-semibold uppercase tracking-[0.16em] text-foreground/45">@lang('Governance')</p>
                <div class="mt-1 flex items-center gap-2">
                    <h4 class="m-0 text-[17px]">@lang('QA / Inspection')</h4>
                    @include('dashboard.partials.work-cards.components.status-pill', ['tone' => 'amber', 'label' => $qaJobs->whereIn('severity', ['red', 'amber'])->count() . ' ' . __('needs review')])
                </div>
                <p class="m-0 mt-1 text-xs text-foreground/60">@lang('Validate quality, evidence, and close work safely from here.')</p>
            </div>
            <div class="grid grid-cols-2 gap-2 sm:min-w-[260px]">
                @include('dashboard.partials.work-cards.components.metric-chip', ['label' => __('Needs review'), 'value' => $qaJobs->whereIn('severity', ['red','amber'])->count()])
                @include('dashboard.partials.work-cards.components.metric-chip', ['label' => __('Passed'), 'value' => $qaJobs->where('severity', 'green')->count()])
            </div>
        </div>
    </x-slot:head>

    <div class="space-y-3" x-data="{ qaExpanded: null, qaDecision: null }">
        @foreach ($qaJobs as $index => $job)
            @php
                $badgeTone = match ($job->severity) {
                    'red' => 'red',
                    'green' => 'green',
                    default => 'amber',
                };
            @endphp
            <div class="rounded-2xl border border-border bg-background/60 p-4">
                <div class="flex items-start justify-between gap-3 max-sm:flex-col">
                    <div>
                        <h5 class="text-sm font-semibold text-heading-foreground">{{ $job->title }}</h5>
                        <p class="m-0 mt-1 text-xs text-foreground/60">{{ $job->evidence }}</p>
                    </div>
                    @include('dashboard.partials.work-cards.components.status-pill', ['tone' => $badgeTone, 'label' => $job->state])
                </div>

                <div class="mt-4 grid gap-3 lg:grid-cols-[minmax(0,1fr),auto] lg:items-center">
                    <div>
                        <div class="mb-2 flex items-center justify-between text-[11px] text-foreground/50">
                            <span>@lang('Checklist / evidence completion')</span>
                            <span>{{ $job->completion }}%</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-foreground/[0.06]">
                            <div class="h-full rounded-full {{ $job->completion >= 100 ? 'bg-emerald-500' : ($job->completion >= 60 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $job->completion }}%"></div>
                        </div>
                    </div>
                    @include('dashboard.partials.work-cards.components.assistant-chip', ['icon' => 'arrow-right-circle', 'tone' => $job->severity === 'green' ? 'accent' : 'warning'])
                        {{ $job->next }}
                    @endinclude
                </div>

                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <button type="button" class="inline-flex items-center gap-2 rounded-full border border-border px-3 py-1.5 text-xs font-semibold text-foreground transition hover:bg-foreground/[0.03]" @click="qaExpanded === {{ $index }} ? qaExpanded = null : qaExpanded = {{ $index }}">
                        <x-tabler-clipboard-check class="size-4" /> @lang('Review')
                    </button>
                    <button type="button" class="inline-flex items-center gap-2 rounded-full border border-border px-3 py-1.5 text-xs font-semibold text-foreground transition hover:bg-foreground/[0.03]" @click="approveQa('{{ $job->title }}')">
                        <x-tabler-check class="size-4" /> @lang('Approve')
                    </button>
                    <button type="button" class="inline-flex items-center gap-2 rounded-full bg-foreground/[0.05] px-3 py-1.5 text-xs font-semibold text-foreground/80 transition hover:bg-foreground/[0.08]" @click="reopenQa('{{ $job->title }}'); qaDecision = {{ $index }}">
                        <x-tabler-flag class="size-4" /> @lang('Flag issue')
                    </button>
                </div>

                <div class="mt-4 space-y-3" x-show="qaExpanded === {{ $index }}" x-collapse>
                    <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                        @include('dashboard.partials.work-cards.components.metric-chip', ['label' => __('Checklist'), 'value' => $job->completion . '%'])
                        @include('dashboard.partials.work-cards.components.metric-chip', ['label' => __('Evidence'), 'value' => __('Review now')])
                        @include('dashboard.partials.work-cards.components.metric-chip', ['label' => __('Decision'), 'value' => __('Approve or reopen')])
                    </div>
                    <div class="rounded-xl border border-border bg-background p-3 text-sm text-foreground/75">
                        @lang('Inline QA wizard: verify evidence, confirm completion, and decide whether to close or reopen the job.')
                    </div>
                </div>

                <div class="mt-4 rounded-2xl border border-border bg-background p-4" x-show="qaDecision === {{ $index }}" x-collapse>
                    <div class="flex items-center justify-between gap-3 max-sm:flex-col max-sm:items-start">
                        <div>
                            <div class="text-[11px] font-semibold uppercase tracking-[0.14em] text-foreground/45">@lang('Issue workflow')</div>
                            <h6 class="mt-1 text-sm font-semibold text-heading-foreground">@lang('Reopen + notify')</h6>
                        </div>
                        @include('dashboard.partials.work-cards.components.assistant-chip', ['icon' => 'shield-alert', 'tone' => 'warning'])
                            @lang('Risk captured')
                        @endinclude
                    </div>
                    <div class="mt-4 grid gap-3 lg:grid-cols-[minmax(0,1fr),auto] lg:items-end">
                        <label class="block text-xs font-semibold text-foreground/60">
                            @lang('Issue summary')
                            <input type="text" value="@lang('Missing required evidence pack')" class="mt-2 w-full rounded-2xl border border-border bg-background px-3 py-2 text-sm text-foreground outline-none ring-0" />
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            @include('dashboard.partials.work-cards.components.metric-chip', ['label' => __('Action'), 'value' => __('Revisit')])
                            @include('dashboard.partials.work-cards.components.metric-chip', ['label' => __('Notify'), 'value' => __('Ready')])
                        </div>
                        <button type="button" class="inline-flex items-center justify-center gap-2 rounded-full bg-accent px-4 py-2 text-sm font-semibold text-accent-foreground transition hover:opacity-90" @click="qaDecision = null">
                            <x-tabler-send class="size-4" /> @lang('Save issue flow')
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-card>
