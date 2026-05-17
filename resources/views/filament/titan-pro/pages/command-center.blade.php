<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold tracking-tight">Business Command Overview</h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Titan Pro is the operator workspace for customer growth, quoting, jobs, dispatch, billing, CRM, field readiness, and owner-level performance visibility.
                    </p>
                </div>
                <x-filament::button wire:click="refreshWorkspaceCache" icon="heroicon-o-arrow-path">
                    Refresh Workspace
                </x-filament::button>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-6">
            @foreach ($this->operationalSnapshot as $label => $value)
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ str($label)->headline() }}</div>
                    <div class="mt-2 text-3xl font-semibold">{{ $value }}</div>
                </div>
            @endforeach
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            @foreach ($this->focusAreas as $area)
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="font-semibold">{{ $area['title'] }}</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $area['description'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            @foreach ($this->workflowLanes as $lane => $steps)
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="font-semibold">{{ $lane }}</h3>
                    <ol class="mt-4 space-y-3 text-sm text-gray-600 dark:text-gray-400">
                        @foreach ($steps as $step)
                            <li class="flex gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-primary-500"></span><span>{{ $step }}</span></li>
                        @endforeach
                    </ol>
                </div>
            @endforeach
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h3 class="font-semibold">Titan Pro Readiness</h3>
            <dl class="mt-4 grid gap-3 md:grid-cols-2 xl:grid-cols-5">
                @foreach ($this->panelReadiness as $check)
                    <div class="rounded-lg bg-gray-50 px-4 py-3 text-sm dark:bg-gray-800">
                        <dt class="text-gray-500 dark:text-gray-400">{{ $check['label'] }}</dt>
                        <dd class="mt-1 font-medium">{{ $check['state'] }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </div>
</x-filament-panels::page>
