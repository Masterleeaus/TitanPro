@php
    $teamStatus = collect($teamStatus ?? [
        (object) ['name' => __('Anna'), 'status' => __('Working'), 'job' => __('Morning Deep Clean'), 'tone' => 'green', 'utilisation' => 58, 'next' => __('Can take 1 more job')],
        (object) ['name' => __('Mike'), 'status' => __('Break'), 'job' => __('Inspection Run'), 'tone' => 'amber', 'utilisation' => 64, 'next' => __('Back in 20 min')],
        (object) ['name' => __('Sarah'), 'status' => __('Available'), 'job' => __('Ready for dispatch'), 'tone' => 'slate', 'utilisation' => 26, 'next' => __('Best fit for office reset')],
        (object) ['name' => __('Luis'), 'status' => __('Overloaded'), 'job' => __('Two back-to-back jobs'), 'tone' => 'red', 'utilisation' => 94, 'next' => __('Recommend rebalance')],
    ]);
@endphp

<x-card class="flex w-full flex-col" id="work-team-status" size="md">
    <x-slot:head class="pb-4">
        <div class="flex items-start justify-between gap-3 max-sm:flex-col">
            <div>
                <p class="m-0 text-[11px] font-semibold uppercase tracking-[0.16em] text-foreground/45">@lang('Signals')</p>
                <div class="mt-1 flex items-center gap-2">
                    <h4 class="m-0 text-[17px]">@lang('Team Status')</h4>
                    @include('dashboard.partials.work-cards.components.status-pill', ['tone' => 'sky', 'label' => $teamStatus->where('tone', 'red')->count() . ' ' . __('overload alerts')])
                </div>
                <p class="m-0 mt-1 text-xs text-foreground/60">@lang('Live workforce capacity, status, and next best assignment signals.')</p>
            </div>
            <div class="grid grid-cols-2 gap-2 sm:min-w-[260px]">
                @include('dashboard.partials.work-cards.components.metric-chip', ['label' => __('Active team'), 'value' => $teamStatus->count()])
                @include('dashboard.partials.work-cards.components.metric-chip', ['label' => __('Overloaded'), 'value' => $teamStatus->where('tone', 'red')->count()])
            </div>
        </div>
    </x-slot:head>

    <div class="space-y-3" x-data="{ rebalanceMember: null }">
        @foreach ($teamStatus as $member)
            @php
                $tone = match ($member->tone) {
                    'red' => 'bg-rose-500',
                    'amber' => 'bg-amber-500',
                    'green' => 'bg-emerald-500',
                    default => 'bg-slate-400',
                };
            @endphp
            <div class="rounded-2xl border border-border bg-background/60 p-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-block size-2.5 rounded-full {{ $tone }}"></span>
                            <span class="text-sm font-semibold text-heading-foreground">{{ $member->name }}</span>
                        </div>
                        <p class="m-0 mt-1 text-xs text-foreground/60">{{ $member->job }}</p>
                    </div>
                    @include('dashboard.partials.work-cards.components.status-pill', ['tone' => $member->tone === 'slate' ? 'default' : $member->tone, 'label' => $member->status])
                </div>
                <div class="mt-4 grid gap-3 lg:grid-cols-[minmax(0,1fr),auto] lg:items-center">
                    <div>
                        <div class="mb-2 flex items-center justify-between text-[11px] text-foreground/50">
                            <span>@lang('Utilisation')</span>
                            <span>{{ $member->utilisation }}%</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-foreground/[0.06]">
                            <div class="h-full rounded-full {{ $member->utilisation >= 85 ? 'bg-rose-500' : ($member->utilisation >= 60 ? 'bg-amber-500' : 'bg-accent') }}" style="width: {{ $member->utilisation }}%"></div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        @include('dashboard.partials.work-cards.components.assistant-chip', ['icon' => 'arrow-badge-right', 'tone' => $member->tone === 'red' ? 'warning' : 'default'])
                            {{ $member->next }}
                        @endinclude
                        <button type="button" class="inline-flex items-center gap-2 rounded-full border border-border px-3 py-1.5 text-xs font-semibold text-foreground transition hover:bg-foreground/[0.03]" @click="rebalanceMember === '{{ $member->name }}' ? rebalanceMember = null : rebalanceMember = '{{ $member->name }}'">
                            <x-tabler-arrows-shuffle class="size-4" /> @lang('Rebalance')
                        </button>
                    </div>
                </div>

                <div class="mt-4 rounded-2xl border border-border bg-background p-4" x-show="rebalanceMember === '{{ $member->name }}'" x-collapse>
                    <div class="flex items-center justify-between gap-3 max-sm:flex-col max-sm:items-start">
                        <div>
                            <div class="text-[11px] font-semibold uppercase tracking-[0.14em] text-foreground/45">@lang('Inline rebalance wizard')</div>
                            <h6 class="mt-1 text-sm font-semibold text-heading-foreground">@lang('Move load and free capacity')</h6>
                        </div>
                        @include('dashboard.partials.work-cards.components.assistant-chip', ['icon' => 'sparkles', 'tone' => 'accent'])
                            @lang('Suggested reassignment ready')
                        @endinclude
                    </div>
                    <div class="mt-4 grid gap-3 lg:grid-cols-[minmax(0,1fr),minmax(0,1fr),auto] lg:items-end">
                        @include('dashboard.partials.work-cards.components.metric-chip', ['label' => __('Current load'), 'value' => $member->utilisation . '%'])
                        @include('dashboard.partials.work-cards.components.metric-chip', ['label' => __('Suggested move'), 'value' => __('Office reset → Sarah')])
                        <button type="button" class="inline-flex items-center justify-center gap-2 rounded-full bg-accent px-4 py-2 text-sm font-semibold text-accent-foreground transition hover:opacity-90" @click="rebalance('{{ $member->name }}'); rebalanceMember = null">
                            <x-tabler-check class="size-4" /> @lang('Apply rebalance')
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-card>
