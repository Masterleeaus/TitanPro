@php
    $workWizardSteps = [
        ['key' => 'type', 'label' => __('Work Type')],
        ['key' => 'scope', 'label' => __('Scope')],
        ['key' => 'schedule', 'label' => __('Schedule')],
        ['key' => 'staffing', 'label' => __('Staffing')],
        ['key' => 'checklist', 'label' => __('Checklist')],
        ['key' => 'agents', 'label' => __('Agents')],
        ['key' => 'review', 'label' => __('Review')],
    ];

    $workWizardTypes = [
        ['key' => 'one_off', 'label' => __('One-off'), 'icon' => 'sparkles', 'hint' => __('Single visit job')],
        ['key' => 'recurring_service', 'label' => __('Recurring'), 'icon' => 'repeat', 'hint' => __('Repeat service plan')],
        ['key' => 'inspection', 'label' => __('Inspection'), 'icon' => 'shield-check', 'hint' => __('QA / audit style work')],
        ['key' => 'project_job', 'label' => __('Project'), 'icon' => 'briefcase', 'hint' => __('Multi-step project work')],
    ];

    $workWizardScopePresets = [
        __('1 room'), __('2 rooms'), __('3 rooms'), __('Full property'), __('Outdoor area'), __('Custom'),
    ];

    $workWizardChecklistTemplates = [
        __('Arrival and access confirmation'),
        __('Before photos'),
        __('Core work checklist'),
        __('After photos'),
        __('Customer handoff notes'),
    ];

    $workWizardAssistants = [
        ['key' => 'planner', 'label' => __('Job Planner'), 'hint' => __('Builds the job shape and estimates duration.')],
        ['key' => 'schedule', 'label' => __('Scheduling Assistant'), 'hint' => __('Finds the best slot and flags overload.')],
        ['key' => 'staffing', 'label' => __('Staffing Assistant'), 'hint' => __('Recommends crew size and best-fit staff.')],
        ['key' => 'checklist', 'label' => __('Checklist Assistant'), 'hint' => __('Generates execution and evidence tasks.')],
        ['key' => 'dispatch', 'label' => __('Dispatch Assistant'), 'hint' => __('Prepares dispatch-ready summaries and actions.')],
        ['key' => 'risk', 'label' => __('Review & Risk'), 'hint' => __('Scores the plan as green, amber, or red.')],
    ];
@endphp

<div class="space-y-4" x-data="workWizardEmbed()" x-init="init()">
    <div class="grid gap-3 lg:grid-cols-[minmax(0,1fr),auto] lg:items-center">
        <div class="grid gap-2 sm:grid-cols-3">
            <button type="button" class="rounded-2xl border px-4 py-3 text-left transition" :class="action === 'new_job' ? 'border-accent bg-accent/[0.09] ring-1 ring-accent/25' : 'border-border hover:bg-foreground/[0.03]'" @click="activate('new_job')">
                <div class="flex items-center gap-2 text-sm font-semibold text-heading-foreground"><x-tabler-plus class="size-4" /> @lang('New Job')</div>
                <p class="m-0 mt-1 text-xs text-foreground/65">@lang('Start a guided job build from scratch.')</p>
            </button>
            <button type="button" class="rounded-2xl border px-4 py-3 text-left transition" :class="action === 'dispatch' ? 'border-accent bg-accent/[0.09] ring-1 ring-accent/25' : 'border-border hover:bg-foreground/[0.03]'" @click="activate('dispatch')">
                <div class="flex items-center gap-2 text-sm font-semibold text-heading-foreground"><x-tabler-route class="size-4" /> @lang('Dispatch')</div>
                <p class="m-0 mt-1 text-xs text-foreground/65">@lang('Prepare route-ready instructions and send.')</p>
            </button>
            <button type="button" class="rounded-2xl border px-4 py-3 text-left transition" :class="action === 'auto_assign' ? 'border-accent bg-accent/[0.09] ring-1 ring-accent/25' : 'border-border hover:bg-foreground/[0.03]'" @click="activate('auto_assign')">
                <div class="flex items-center gap-2 text-sm font-semibold text-heading-foreground"><x-tabler-bolt class="size-4" /> @lang('Auto Assign')</div>
                <p class="m-0 mt-1 text-xs text-foreground/65">@lang('Rank the best-fit crew before dispatch.')</p>
            </button>
        </div>
        <div class="flex flex-wrap items-center gap-2 lg:justify-end">
            <span class="inline-flex items-center gap-2 rounded-full bg-foreground/5 px-3 py-1 text-[11px] font-semibold text-foreground/70">
                <span class="inline-block size-2 rounded-full" :class="riskTone"></span>
                <span x-text="riskLabel"></span>
            </span>
            <span class="inline-flex items-center gap-2 rounded-full border border-border px-3 py-1 text-[11px] font-semibold text-foreground/65">
                <x-tabler-device-floppy class="size-3.5" />
                @lang('Autosaving')
            </span>
        </div>
    </div>

    <div class="rounded-2xl border border-border bg-background/70 shadow-sm">
        <div class="sticky top-0 z-10 border-b border-border bg-background/95 px-4 py-4 backdrop-blur">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <div class="mb-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-foreground/50">@lang('Current step')</div>
                    <h4 class="m-0 text-base font-semibold text-heading-foreground" x-text="currentStep.label"></h4>
                    <p class="m-0 mt-1 text-sm text-foreground/65" x-text="stepSummary"></p>
                </div>
                <div class="min-w-[170px] lg:text-right">
                    <div class="mb-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-foreground/50">@lang('Completion')</div>
                    <div class="text-sm font-semibold text-heading-foreground"><span x-text="stepIndex + 1"></span>/7 · <span x-text="progressPercent + '%'"></span></div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-foreground/8">
                        <div class="h-full rounded-full bg-accent transition-all duration-300" :style="'width:' + progressPercent + '%'" ></div>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex gap-2 overflow-x-auto pb-1">
                @foreach ($workWizardSteps as $index => $step)
                    <button type="button" class="min-w-[96px] shrink-0 rounded-full border px-3 py-1.5 text-xs font-medium transition" :class="stepButtonClass({{ $index }})" @click="jumpTo({{ $index }})">
                        {{ $step['label'] }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="grid gap-4 p-4 xl:grid-cols-[minmax(0,1.65fr),320px]">
            <div class="min-h-[620px] rounded-2xl bg-foreground/[0.02] p-4">
                <div class="max-h-[70vh] overflow-y-auto pe-1">
                    <template x-if="stepIndex === 0">
                        <div class="space-y-4">
                            <div class="grid gap-3 sm:grid-cols-2">
                                @foreach ($workWizardTypes as $type)
                                    <button type="button" class="rounded-2xl border p-4 text-left transition" :class="form.workType === '{{ $type['key'] }}' ? 'border-accent bg-accent/[0.08] ring-1 ring-accent/30' : 'border-border hover:bg-background'" @click="form.workType = '{{ $type['key'] }}'; save(); computeAssistants();">
                                        <div class="mb-3 inline-flex size-10 items-center justify-center rounded-xl bg-foreground/5 text-heading-foreground">
                                            <x-dynamic-component :component="'tabler-' . $type['icon']" class="size-5" />
                                        </div>
                                        <div class="font-semibold text-heading-foreground">{{ $type['label'] }}</div>
                                        <div class="mt-1 text-sm text-foreground/65">{{ $type['hint'] }}</div>
                                    </button>
                                @endforeach
                            </div>
                            <div class="grid gap-4 lg:grid-cols-2">
                                <label class="block">
                                    <span class="mb-2 block text-sm font-medium text-heading-foreground">@lang('Job title')</span>
                                    <input x-model="form.title" @input="save(); computeAssistants();" type="text" class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm outline-none transition focus:border-accent" placeholder="@lang('e.g. End-of-lease clean, 3-bed townhouse')">
                                </label>
                                <div class="rounded-2xl border border-border bg-background p-4">
                                    <div class="text-xs uppercase tracking-[0.12em] text-foreground/45">@lang('Planner suggestion')</div>
                                    <div class="mt-2 text-sm text-foreground/75" x-text="plannerSuggestion"></div>
                                </div>
                            </div>
                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-heading-foreground">@lang('Description')</span>
                                <textarea x-model="form.description" @input="save(); computeAssistants();" rows="4" class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm outline-none transition focus:border-accent" placeholder="@lang('Access notes, customer expectations, hazards, or special instructions...')"></textarea>
                            </label>
                        </div>
                    </template>

                    <template x-if="stepIndex === 1">
                        <div class="space-y-4">
                            <div>
                                <div class="mb-2 text-sm font-medium text-heading-foreground">@lang('Quick scope presets')</div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($workWizardScopePresets as $scopePreset)
                                        <button type="button" class="rounded-full border px-3 py-2 text-sm font-medium transition" :class="form.scopePreset === '{{ $scopePreset }}' ? 'border-accent bg-accent/[0.08] text-heading-foreground' : 'border-border text-foreground/75 hover:bg-foreground/[0.03]'" @click="form.scopePreset = '{{ $scopePreset }}'; save(); computeEstimate();">
                                            {{ $scopePreset }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <label class="block">
                                    <span class="mb-2 block text-sm font-medium text-heading-foreground">@lang('Areas / units')</span>
                                    <input x-model="form.units" @input="save(); computeEstimate();" type="number" min="1" class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm outline-none transition focus:border-accent">
                                </label>
                                <label class="block">
                                    <span class="mb-2 block text-sm font-medium text-heading-foreground">@lang('Estimated size')</span>
                                    <input x-model="form.sizeNote" @input="save()" type="text" class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm outline-none transition focus:border-accent" placeholder="@lang('sqm, floors, garden, etc.')">
                                </label>
                            </div>
                            <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr),240px]">
                                <label class="block">
                                    <span class="mb-2 block text-sm font-medium text-heading-foreground">@lang('Scope detail')</span>
                                    <textarea x-model="form.scopeDetail" @input="save(); computeEstimate();" rows="4" class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm outline-none transition focus:border-accent" placeholder="@lang('List rooms, zones, required standards, exclusions, or special conditions.')"></textarea>
                                </label>
                                <div class="rounded-2xl border border-border bg-background p-4">
                                    <div class="text-xs uppercase tracking-[0.12em] text-foreground/45">@lang('Estimated load')</div>
                                    <div class="mt-2 text-lg font-semibold text-heading-foreground" x-text="durationLabel"></div>
                                    <p class="m-0 mt-2 text-sm text-foreground/70">@lang('Scope and crew recommendations update as you refine the brief.')</p>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="stepIndex === 2">
                        <div class="space-y-4">
                            <div class="grid gap-3 sm:grid-cols-3">
                                <button type="button" class="rounded-2xl border px-3 py-3 text-sm font-medium" :class="form.schedulePreset === 'today' ? 'border-accent bg-accent/[0.08]' : 'border-border hover:bg-foreground/[0.03]'" @click="setSchedulePreset('today')">@lang('Today')</button>
                                <button type="button" class="rounded-2xl border px-3 py-3 text-sm font-medium" :class="form.schedulePreset === 'tomorrow' ? 'border-accent bg-accent/[0.08]' : 'border-border hover:bg-foreground/[0.03]'" @click="setSchedulePreset('tomorrow')">@lang('Tomorrow')</button>
                                <button type="button" class="rounded-2xl border px-3 py-3 text-sm font-medium" :class="form.schedulePreset === 'next_available' ? 'border-accent bg-accent/[0.08]' : 'border-border hover:bg-foreground/[0.03]'" @click="setSchedulePreset('next_available')">@lang('Next available')</button>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <label class="block">
                                    <span class="mb-2 block text-sm font-medium text-heading-foreground">@lang('Date')</span>
                                    <input x-model="form.date" @input="save(); computeAssistants();" type="date" class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm outline-none transition focus:border-accent">
                                </label>
                                <label class="block">
                                    <span class="mb-2 block text-sm font-medium text-heading-foreground">@lang('Time window')</span>
                                    <input x-model="form.timeWindow" @input="save(); computeAssistants();" type="text" class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm outline-none transition focus:border-accent" placeholder="@lang('e.g. 9:00–11:30 AM')">
                                </label>
                            </div>
                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-heading-foreground">@lang('Schedule notes')</span>
                                <textarea x-model="form.scheduleNotes" @input="save(); computeAssistants();" rows="3" class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm outline-none transition focus:border-accent" placeholder="@lang('Travel, key access, preferred arrival, blackout windows...')"></textarea>
                            </label>
                            <div class="rounded-2xl border border-border bg-background p-4 text-sm text-foreground/75" x-text="scheduleSuggestion"></div>
                        </div>
                    </template>

                    <template x-if="stepIndex === 3">
                        <div class="space-y-4">
                            <div class="grid gap-4 sm:grid-cols-2">
                                <label class="block rounded-2xl border border-border bg-background p-4">
                                    <span class="mb-2 block text-sm font-medium text-heading-foreground">@lang('Crew size')</span>
                                    <input x-model="form.crewSize" @input="save(); computeAssistants();" type="range" min="1" max="6" class="w-full accent-[hsl(var(--accent))]">
                                    <div class="mt-2 flex items-center justify-between text-sm text-foreground/70"><span><span x-text="form.crewSize"></span> @lang('people')</span><span x-text="staffingTone"></span></div>
                                </label>
                                <label class="block rounded-2xl border border-border bg-background p-4">
                                    <span class="mb-2 block text-sm font-medium text-heading-foreground">@lang('Expected duration')</span>
                                    <select x-model="form.duration" @change="save(); computeAssistants();" class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm outline-none transition focus:border-accent">
                                        <option value="60">1 @lang('hour')</option>
                                        <option value="90">1.5 @lang('hours')</option>
                                        <option value="120">2 @lang('hours')</option>
                                        <option value="180">3 @lang('hours')</option>
                                        <option value="240">4 @lang('hours')</option>
                                        <option value="360">6 @lang('hours')</option>
                                    </select>
                                </label>
                            </div>
                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-heading-foreground">@lang('Staffing notes')</span>
                                <textarea x-model="form.staffingNotes" @input="save(); computeAssistants();" rows="3" class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm outline-none transition focus:border-accent" placeholder="@lang('Required skills, preferred staff, vehicle constraints, ladder work, etc.')"></textarea>
                            </label>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div class="rounded-2xl border border-border bg-background p-4 text-sm text-foreground/75">
                                    <div class="mb-1 font-semibold text-heading-foreground">@lang('Staffing preview')</div>
                                    <p class="m-0">@lang('Recommended crew'): <strong x-text="form.crewSize"></strong></p>
                                    <p class="m-0">@lang('Expected duration'): <strong x-text="durationLabel"></strong></p>
                                </div>
                                <div class="rounded-2xl border border-border bg-background p-4 text-sm text-foreground/75" x-text="staffingWarning"></div>
                            </div>
                        </div>
                    </template>

                    <template x-if="stepIndex === 4">
                        <div class="space-y-4">
                            <div>
                                <p class="mb-2 text-sm font-medium text-heading-foreground">@lang('Default checklist')</p>
                                <div class="space-y-2">
                                    @foreach ($workWizardChecklistTemplates as $check)
                                        <label class="flex items-center gap-3 rounded-xl border border-border bg-background px-3 py-3 text-sm text-foreground/80">
                                            <input type="checkbox" class="rounded border-border" x-model="form.checklist" value="{{ $check }}" @change="save(); computeAssistants();">
                                            <span>{{ $check }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-heading-foreground">@lang('Custom checklist items')</span>
                                <textarea x-model="form.customChecklist" @input="save(); computeAssistants();" rows="4" class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm outline-none transition focus:border-accent" placeholder="@lang('One item per line...')"></textarea>
                            </label>
                            <div class="rounded-2xl border border-border bg-background p-4 text-sm text-foreground/75">@lang('Checklist Assistant will later map these into task templates, evidence capture, and QA requirements.')</div>
                        </div>
                    </template>

                    <template x-if="stepIndex === 5">
                        <div class="space-y-3">
                            <template x-for="assistant in assistants" :key="assistant.key">
                                <button type="button" class="w-full rounded-2xl border p-4 text-left transition hover:bg-background" :class="assistant.enabled ? 'border-accent bg-accent/[0.06]' : 'border-border'" @click="assistant.enabled = !assistant.enabled; saveAssistants();">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="font-semibold text-heading-foreground" x-text="assistant.label"></div>
                                            <p class="m-0 mt-1 text-sm text-foreground/65" x-text="assistant.hint"></p>
                                        </div>
                                        <span class="rounded-full px-2 py-1 text-[11px] font-semibold" :class="assistant.enabled ? 'bg-accent text-accent-foreground' : 'bg-foreground/5 text-foreground/65'" x-text="assistant.enabled ? '{{ __('Ready') }}' : '{{ __('Off') }}'"></span>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </template>

                    <template x-if="stepIndex === 6">
                        <div class="space-y-4">
                            <div class="rounded-2xl border border-border bg-background p-4">
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <div>
                                        <p class="m-0 text-xs uppercase tracking-[0.14em] text-foreground/50">@lang('Execution summary')</p>
                                        <h4 class="m-0 text-base font-semibold text-heading-foreground" x-text="form.title || '{{ __('New work item') }}'"></h4>
                                    </div>
                                    <span class="rounded-full px-2 py-1 text-xs font-semibold" :class="riskBadgeClass()" x-text="riskLabel"></span>
                                </div>
                                <dl class="grid gap-3 text-sm sm:grid-cols-2">
                                    <div><dt class="text-foreground/50">@lang('Work type')</dt><dd class="m-0 font-medium text-heading-foreground" x-text="selectedTypeLabel"></dd></div>
                                    <div><dt class="text-foreground/50">@lang('Schedule')</dt><dd class="m-0 font-medium text-heading-foreground" x-text="scheduleSummary"></dd></div>
                                    <div><dt class="text-foreground/50">@lang('Crew')</dt><dd class="m-0 font-medium text-heading-foreground"><span x-text="form.crewSize"></span> @lang('people')</dd></div>
                                    <div><dt class="text-foreground/50">@lang('Duration')</dt><dd class="m-0 font-medium text-heading-foreground" x-text="durationLabel"></dd></div>
                                </dl>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-3">
                                <div class="rounded-2xl border border-border bg-background p-4 text-sm text-foreground/75">
                                    <div class="text-xs uppercase tracking-[0.12em] text-foreground/45">@lang('Checklist')</div>
                                    <div class="mt-2 text-lg font-semibold text-heading-foreground" x-text="checklistCount"></div>
                                </div>
                                <div class="rounded-2xl border border-border bg-background p-4 text-sm text-foreground/75">
                                    <div class="text-xs uppercase tracking-[0.12em] text-foreground/45">@lang('Assistants')</div>
                                    <div class="mt-2 text-lg font-semibold text-heading-foreground" x-text="enabledAssistantsCount"></div>
                                </div>
                                <div class="rounded-2xl border border-border bg-background p-4 text-sm text-foreground/75">
                                    <div class="text-xs uppercase tracking-[0.12em] text-foreground/45">@lang('Next outcome')</div>
                                    <div class="mt-2 font-semibold text-heading-foreground" x-text="nextOutcome"></div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="mt-4 flex items-center justify-between gap-3 border-t border-border pt-4 max-sm:flex-col">
                    <button type="button" class="inline-flex items-center gap-2 rounded-full border border-border px-4 py-2 text-sm font-semibold text-foreground transition hover:bg-foreground/[0.03] disabled:opacity-40" @click="prev()" :disabled="stepIndex === 0">
                        <x-tabler-arrow-left class="size-4" />
                        @lang('Back')
                    </button>
                    <div class="flex items-center gap-2 max-sm:w-full max-sm:flex-col">
                        <button type="button" class="inline-flex items-center gap-2 rounded-full border border-border px-4 py-2 text-sm font-semibold text-foreground transition hover:bg-foreground/[0.03] max-sm:w-full max-sm:justify-center" @click="save()">
                            <x-tabler-device-floppy class="size-4" />
                            @lang('Save progress')
                        </button>
                        <button type="button" class="inline-flex items-center gap-2 rounded-full bg-accent px-5 py-2 text-sm font-semibold text-accent-foreground transition hover:opacity-90 max-sm:w-full max-sm:justify-center" @click="nextOrSubmit()">
                            <span x-text="stepIndex === 6 ? '{{ __('Create + Prepare Dispatch') }}' : '{{ __('Continue') }}'"></span>
                            <x-tabler-arrow-right class="size-4" x-show="stepIndex !== 6"></x-tabler-arrow-right>
                            <x-tabler-check class="size-4" x-show="stepIndex === 6"></x-tabler-check>
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="rounded-2xl border border-border bg-background/60 p-4 shadow-sm">
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <div>
                            <h4 class="m-0 text-sm font-semibold text-heading-foreground">@lang('Assistant stack')</h4>
                            <p class="m-0 text-xs text-foreground/60">@lang('Operational assistants to automate this wizard over time.')</p>
                        </div>
                        <span class="rounded-full bg-foreground/5 px-2 py-1 text-[11px] font-semibold text-foreground/65" x-text="enabledAssistantsCount + ' {{ __('active') }}'"></span>
                    </div>
                    <div class="space-y-3">
                        @foreach ($workWizardAssistants as $assistant)
                            <div class="rounded-xl border border-border px-3 py-3">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <div class="text-sm font-semibold text-heading-foreground">{{ $assistant['label'] }}</div>
                                        <p class="m-0 mt-1 text-xs text-foreground/65">{{ $assistant['hint'] }}</p>
                                    </div>
                                    <span class="rounded-full px-2 py-1 text-[11px] font-semibold" :class="assistantStateClass('{{ $assistant['key'] }}')" x-text="assistantStateLabel('{{ $assistant['key'] }}')"></span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border border-border bg-background/60 p-4 shadow-sm">
                    <h4 class="m-0 text-sm font-semibold text-heading-foreground">@lang('Live summary')</h4>
                    <div class="mt-3 grid gap-3 text-sm">
                        <div class="rounded-xl border border-border px-3 py-3">
                            <div class="text-xs uppercase tracking-[0.12em] text-foreground/45">@lang('Quick state')</div>
                            <div class="mt-1 font-semibold text-heading-foreground" x-text="actionLabel"></div>
                        </div>
                        <div class="rounded-xl border border-border px-3 py-3">
                            <div class="text-xs uppercase tracking-[0.12em] text-foreground/45">@lang('Next outcome')</div>
                            <div class="mt-1 text-foreground/75" x-text="nextOutcome"></div>
                        </div>
                        <div class="rounded-xl border border-border px-3 py-3">
                            <div class="text-xs uppercase tracking-[0.12em] text-foreground/45">@lang('Recommended automation')</div>
                            <div class="mt-1 text-foreground/75" x-text="automationHint"></div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-border bg-background/60 p-4 shadow-sm">
                    <h4 class="m-0 text-sm font-semibold text-heading-foreground">@lang('Roadmap')</h4>
                    <div class="mt-3 space-y-2 text-sm text-foreground/75">
                        <div class="rounded-xl bg-foreground/[0.03] px-3 py-3"><strong class="text-heading-foreground">@lang('Level 1')</strong> — @lang('Prefill work, estimate duration, suggest checklist and staff.')</div>
                        <div class="rounded-xl bg-foreground/[0.03] px-3 py-3"><strong class="text-heading-foreground">@lang('Level 2')</strong> — @lang('Assist dispatch, split subtasks, generate customer updates.')</div>
                        <div class="rounded-xl bg-foreground/[0.03] px-3 py-3"><strong class="text-heading-foreground">@lang('Level 3')</strong> — @lang('Create, assign, and dispatch low-risk jobs automatically.')</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@once
    @push('script')
        <script>
            function workWizardEmbed() {
                return {
                    steps: @js($workWizardSteps),
                    assistantsCatalog: @js($workWizardAssistants),
                    stepIndex: 0,
                    action: 'new_job',
                    form: {
                        workType: 'one_off',
                        title: '',
                        description: '',
                        scopePreset: '1 room',
                        units: 1,
                        sizeNote: '',
                        scopeDetail: '',
                        schedulePreset: 'next_available',
                        date: '',
                        timeWindow: '',
                        scheduleNotes: '',
                        crewSize: 1,
                        duration: '90',
                        staffingNotes: '',
                        checklist: @js($workWizardChecklistTemplates),
                        customChecklist: ''
                    },
                    assistants: [],
                    init() {
                        const saved = localStorage.getItem('titan-work-dashboard-wizard');
                        if (saved) {
                            try {
                                const parsed = JSON.parse(saved);
                                this.stepIndex = parsed.stepIndex ?? 0;
                                this.action = parsed.action ?? 'new_job';
                                this.form = {...this.form, ...(parsed.form || {})};
                            } catch (e) {}
                        }
                        this.assistants = this.assistantsCatalog.map((assistant, index) => ({
                            ...assistant,
                            enabled: index < 4
                        }));
                        this.computeAssistants();
                    },
                    get currentStep() { return this.steps[this.stepIndex]; },
                    get progressPercent() { return Math.round(((this.stepIndex + 1) / this.steps.length) * 100); },
                    get plannerSuggestion() {
                        if (this.form.workType === 'inspection') return '{{ __('Build a short, evidence-heavy QA workflow with clear pass/fail items.') }}';
                        if (this.form.workType === 'recurring_service') return '{{ __('Set a repeatable scope, predictable duration, and reusable checklist template.') }}';
                        if (this.form.workType === 'project_job') return '{{ __('Break this into milestones, staged staffing, and structured handoff notes.') }}';
                        return '{{ __('Start with a compact job brief and let assistants recommend duration, staffing, and checklist defaults.') }}';
                    },
                    get stepSummary() {
                        if (this.stepIndex === 0) return this.selectedTypeLabel + ' • ' + (this.form.title || '{{ __('Add title and key context') }}');
                        if (this.stepIndex === 1) return (this.form.scopePreset || '{{ __('Custom scope') }}') + ' • ' + this.form.units + ' {{ __('units') }}';
                        if (this.stepIndex === 2) return this.scheduleSummary;
                        if (this.stepIndex === 3) return this.form.crewSize + ' {{ __('people') }} • ' + this.durationLabel;
                        if (this.stepIndex === 4) return this.checklistCount + ' {{ __('checklist items ready') }}';
                        if (this.stepIndex === 5) return this.enabledAssistantsCount + ' {{ __('assistants active') }}';
                        return this.riskLabel + ' • ' + this.nextOutcome;
                    },
                    get selectedTypeLabel() {
                        const map = {one_off:'{{ __('One-off') }}', recurring_service:'{{ __('Recurring') }}', inspection:'{{ __('Inspection') }}', project_job:'{{ __('Project') }}'};
                        return map[this.form.workType] || '{{ __('Work') }}';
                    },
                    get durationLabel() {
                        const mins = parseInt(this.form.duration || 90, 10);
                        return mins >= 60 ? (mins / 60).toString().replace('.5', '.5 ') + ' {{ __('hours') }}' : mins + ' {{ __('mins') }}';
                    },
                    get scheduleSummary() {
                        if (!this.form.date && !this.form.timeWindow) return '{{ __('Next available slot') }}';
                        return [this.form.date || '{{ __('date pending') }}', this.form.timeWindow || '{{ __('time pending') }}'].filter(Boolean).join(' • ');
                    },
                    get scheduleSuggestion() {
                        if (this.action === 'dispatch') return '{{ __('Dispatch mode is active — lock in a clear arrival window and access notes.') }}';
                        if (!this.form.date) return '{{ __('Pick a date or use a quick preset to get schedule and load recommendations.') }}';
                        return '{{ __('This slot looks usable. Scheduling Assistant can later compare capacity and proximity before dispatch.') }}';
                    },
                    get checklistCount() {
                        const custom = (this.form.customChecklist || '').split('\n').map(v => v.trim()).filter(Boolean);
                        return this.form.checklist.length + custom.length;
                    },
                    get enabledAssistantsCount() { return this.assistants.filter(a => a.enabled).length; },
                    get actionLabel() {
                        return {new_job:'{{ __('New Job flow active') }}', dispatch:'{{ __('Dispatch prep active') }}', auto_assign:'{{ __('Auto Assign flow active') }}'}[this.action] || '{{ __('Wizard active') }}';
                    },
                    get nextOutcome() {
                        if (this.action === 'dispatch') return '{{ __('Create job and prepare dispatch summary') }}';
                        if (this.action === 'auto_assign') return '{{ __('Create job and recommend best-fit staff') }}';
                        return '{{ __('Create draft job with staffing and checklist suggestions') }}';
                    },
                    get automationHint() {
                        if (this.action === 'auto_assign') return '{{ __('Auto Assign Assistant is best paired with staffing and schedule confidence.') }}';
                        if (this.action === 'dispatch') return '{{ __('Dispatch Assistant can package handoff notes, access, and arrival windows next.') }}';
                        return '{{ __('Job Planner, Staffing, and Checklist Assistants are the best first automations for this flow.') }}';
                    },
                    get staffingTone() {
                        if (parseInt(this.form.crewSize, 10) >= 4) return '{{ __('High staffing') }}';
                        if (parseInt(this.form.crewSize, 10) >= 2) return '{{ __('Balanced') }}';
                        return '{{ __('Lean crew') }}';
                    },
                    get staffingWarning() {
                        if (parseInt(this.form.crewSize, 10) > 3) return '{{ __('Large crew requested — confirm vehicle and access capacity.') }}';
                        if (parseInt(this.form.duration, 10) > 240) return '{{ __('Long job duration — consider split shifts or staged completion.') }}';
                        return '{{ __('Crew size and duration look manageable for standard dispatch.') }}';
                    },
                    get statusLabel() { return this.riskLabel + ' • ' + this.enabledAssistantsCount + ' {{ __('assistants ready') }}'; },
                    get riskLabel() {
                        if (this.form.workType === 'inspection' || parseInt(this.form.duration, 10) > 240 || parseInt(this.form.crewSize, 10) > 4) return '{{ __('Amber review') }}';
                        if (!this.form.title || !this.form.date) return '{{ __('Draft mode') }}';
                        return '{{ __('Green ready') }}';
                    },
                    get riskTone() {
                        if (this.riskLabel.includes('Amber')) return 'bg-yellow-500';
                        if (this.riskLabel.includes('Green')) return 'bg-green-500';
                        return 'bg-slate-400';
                    },
                    activate(action) { this.action = action; this.save(); },
                    stepButtonClass(index) {
                        if (index === this.stepIndex) return 'border-accent bg-accent text-accent-foreground';
                        if (index < this.stepIndex) return 'border-accent/30 bg-accent/[0.08] text-heading-foreground';
                        return 'border-border text-foreground/65 hover:bg-foreground/[0.03]';
                    },
                    riskBadgeClass() {
                        if (this.riskLabel.includes('Amber')) return 'bg-yellow-500/15 text-yellow-700 dark:text-yellow-300';
                        if (this.riskLabel.includes('Green')) return 'bg-green-500/15 text-green-700 dark:text-green-300';
                        return 'bg-foreground/5 text-foreground/65';
                    },
                    assistantStateClass(key) {
                        const assistant = this.assistants.find(a => a.key === key);
                        return assistant && assistant.enabled ? 'bg-accent text-accent-foreground' : 'bg-foreground/5 text-foreground/65';
                    },
                    assistantStateLabel(key) {
                        const assistant = this.assistants.find(a => a.key === key);
                        return assistant && assistant.enabled ? '{{ __('Active') }}' : '{{ __('Planned') }}';
                    },
                    saveAssistants() { this.save(); },
                    setSchedulePreset(preset) {
                        this.form.schedulePreset = preset;
                        const now = new Date();
                        if (preset === 'today') {
                            this.form.date = now.toISOString().slice(0, 10);
                            this.form.timeWindow = '09:00 - 11:00';
                        } else if (preset === 'tomorrow') {
                            now.setDate(now.getDate() + 1);
                            this.form.date = now.toISOString().slice(0, 10);
                            this.form.timeWindow = '10:00 - 12:00';
                        } else {
                            now.setDate(now.getDate() + 2);
                            this.form.date = now.toISOString().slice(0, 10);
                            this.form.timeWindow = 'Next best slot';
                        }
                        this.save();
                    },
                    computeEstimate() {
                        if (this.form.scopePreset === 'Full property' && parseInt(this.form.units, 10) < 3) this.form.units = 3;
                        if (parseInt(this.form.units, 10) >= 4) this.form.duration = '180';
                        this.save();
                    },
                    computeAssistants() {
                        const type = this.form.workType;
                        this.assistants.forEach(assistant => {
                            if (assistant.key === 'planner') assistant.enabled = !!(this.form.workType || this.form.title || this.form.description);
                            if (assistant.key === 'checklist') assistant.enabled = !!(this.form.description || this.form.customChecklist || this.form.checklist.length);
                            if (assistant.key === 'schedule') assistant.enabled = !!(this.form.date || this.form.scheduleNotes || this.action === 'dispatch');
                            if (assistant.key === 'staffing') assistant.enabled = parseInt(this.form.crewSize, 10) >= 1;
                            if (assistant.key === 'dispatch') assistant.enabled = this.action !== 'new_job';
                            if (assistant.key === 'risk') assistant.enabled = type === 'inspection' || parseInt(this.form.duration, 10) > 180 || parseInt(this.form.crewSize, 10) > 3;
                        });
                        this.save();
                    },
                    jumpTo(index) { this.stepIndex = index; this.save(); },
                    prev() { if (this.stepIndex > 0) { this.stepIndex -= 1; this.save(); } },
                    nextOrSubmit() {
                        if (this.stepIndex < this.steps.length - 1) { this.stepIndex += 1; this.save(); return; }
                        this.save();
                        this.action = this.action === 'new_job' ? 'dispatch' : this.action;
                    },
                    save() {
                        localStorage.setItem('titan-work-dashboard-wizard', JSON.stringify({ stepIndex: this.stepIndex, action: this.action, form: this.form }));
                    }
                }
            }
        </script>
    @endpush
@endonce
