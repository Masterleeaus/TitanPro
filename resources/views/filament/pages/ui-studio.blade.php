<x-filament-panels::page>
    @php
        $summary = $this->audit['summary'] ?? ['total' => 0, 'passed' => 0, 'failed' => 0, 'dismissed' => 0];
        $tokens = $this->audit['tokens'] ?? [];
        $checks = $this->audit['checks'] ?? [];
    @endphp

    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-gray-950 dark:text-white">Accessibility</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Audit active theme tokens, store timestamped reports, and auto-fix the managed WCAG checks.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <x-filament::button tag="a" color="gray" href="{{ $themeManagerUrl }}">
                    Theme Manager
                </x-filament::button>
                <x-filament::button wire:click="rerunAudit">
                    Re-run audit
                </x-filament::button>
                <x-filament::button wire:click="autoFix" color="success">
                    Auto-fix failing tokens
                </x-filament::button>
            </div>
        </div>

        <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-3 dark:border-white/10">
            <a
                href="{{ $themeManagerUrl }}"
                class="rounded-full px-4 py-2 text-sm font-medium text-gray-500 transition hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white"
            >
                Theme Manager
            </a>
            <span class="rounded-full bg-primary-600 px-4 py-2 text-sm font-medium text-white">
                Accessibility
            </span>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            <x-filament::section>
                <x-slot name="heading">Checks</x-slot>
                <div class="text-3xl font-semibold text-gray-950 dark:text-white">{{ $summary['total'] }}</div>
            </x-filament::section>
            <x-filament::section>
                <x-slot name="heading">Passing</x-slot>
                <div class="text-3xl font-semibold text-success-600">{{ $summary['passed'] }}</div>
            </x-filament::section>
            <x-filament::section>
                <x-slot name="heading">Failing</x-slot>
                <div class="text-3xl font-semibold text-danger-600">{{ $summary['failed'] }}</div>
            </x-filament::section>
            <x-filament::section>
                <x-slot name="heading">Dismissed</x-slot>
                <div class="text-3xl font-semibold text-warning-600">{{ $summary['dismissed'] }}</div>
            </x-filament::section>
        </div>

        <div class="grid gap-6 xl:grid-cols-[2fr,1fr]">
            <x-filament::section>
                <x-slot name="heading">Accessibility audit checks</x-slot>
                <x-slot name="description">Each check lists pass/fail state and the theme tokens affected by the rule.</x-slot>

                <div class="space-y-4">
                    @foreach ($checks as $check)
                        <div class="rounded-2xl border border-gray-200 p-4 dark:border-white/10">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="font-semibold text-gray-950 dark:text-white">{{ $check['label'] }}</h3>
                                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $check['passed'] ? 'bg-success-100 text-success-700 dark:bg-success-500/10 dark:text-success-300' : 'bg-danger-100 text-danger-700 dark:bg-danger-500/10 dark:text-danger-300' }}">
                                            {{ $check['passed'] ? 'Pass' : 'Fail' }}
                                        </span>
                                        @if ($check['dismissed'])
                                            <span class="rounded-full bg-warning-100 px-2 py-0.5 text-xs font-medium text-warning-700 dark:bg-warning-500/10 dark:text-warning-300">
                                                Dismissed
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $check['standard'] }}</p>
                                </div>

                                @if (! $check['passed'] && ! $check['dismissed'])
                                    <x-filament::button size="sm" color="gray" wire:click="dismissWarning('{{ $check['key'] }}')">
                                        Dismiss warning
                                    </x-filament::button>
                                @elseif ($check['dismissed'])
                                    <x-filament::button size="sm" color="gray" wire:click="restoreWarning('{{ $check['key'] }}')">
                                        Restore warning
                                    </x-filament::button>
                                @endif
                            </div>

                            <dl class="mt-4 grid gap-3 md:grid-cols-2">
                                <div>
                                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Current</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $check['value'] }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Target</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $check['target'] }}</dd>
                                </div>
                                <div class="md:col-span-2">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Affected tokens</dt>
                                    <dd class="mt-2 flex flex-wrap gap-2">
                                        @foreach ($check['affected_tokens'] as $token)
                                            <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-white/5 dark:text-gray-300">{{ $token }}</span>
                                        @endforeach
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    @endforeach
                </div>
            </x-filament::section>

            <div class="space-y-6">
                <x-filament::section>
                    <x-slot name="heading">Active theme tokens</x-slot>
                    <div class="space-y-3">
                        @foreach ($tokens as $token => $value)
                            <div class="flex items-center justify-between gap-3 rounded-xl border border-gray-200 px-3 py-2 text-sm dark:border-white/10">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $token }}</span>
                                <span class="font-mono text-gray-950 dark:text-white">{{ is_numeric($value) ? number_format((float) $value, 1) : $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-filament::section>

                <x-filament::section>
                    <x-slot name="heading">Recent reports</x-slot>
                    <div class="space-y-3">
                        @forelse ($recentReports as $report)
                            <div class="rounded-xl border border-gray-200 px-3 py-2 text-sm dark:border-white/10">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="font-medium text-gray-950 dark:text-white">Report #{{ $report->id }}</span>
                                    <span class="text-gray-500 dark:text-gray-400">{{ $report->created_at?->diffForHumans() }}</span>
                                </div>
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ data_get($report->summary, 'passed', 0) }}/{{ data_get($report->summary, 'total', 0) }} passing ·
                                    {{ count($report->applied_fixes ?? []) }} fixes applied
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">No accessibility reports stored yet.</p>
                        @endforelse
                    </div>
                </x-filament::section>
            </div>
        </div>
    </div>
</x-filament-panels::page>
