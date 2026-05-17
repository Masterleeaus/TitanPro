<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white">Module Health & Repair</h2>
                    <p class="mt-2 max-w-3xl text-sm text-gray-600 dark:text-gray-400">
                        Review module availability, repair module cache state, inspect manifests, and control whether a module is enabled for the platform.
                    </p>
                </div>
                <x-filament::button wire:click="rebuildManifestCache" icon="heroicon-o-arrow-path">
                    Rebuild Module Cache
                </x-filament::button>
            </div>
        </div>

        @if ($selectedModule)
            <div class="rounded-xl border border-primary-200 bg-primary-50 p-5 shadow-sm dark:border-primary-500/30 dark:bg-primary-500/10">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="font-semibold text-primary-950 dark:text-primary-100">Inspection: {{ $selectedModule['name'] }}</h3>
                        <p class="mt-1 text-sm text-primary-800 dark:text-primary-200">{{ $selectedModule['path'] }}</p>
                    </div>
                    <span class="rounded-full px-2 py-1 text-xs font-medium {{ $selectedModule['issues'] === 0 ? 'bg-success-100 text-success-700 dark:bg-success-500/10 dark:text-success-300' : 'bg-warning-100 text-warning-700 dark:bg-warning-500/10 dark:text-warning-300' }}">
                        {{ $selectedModule['status'] }}
                    </span>
                </div>
                <div class="mt-4 grid gap-2 md:grid-cols-2">
                    @foreach ($selectedModule['checks'] as $check)
                        <div class="flex items-center justify-between rounded-lg bg-white px-3 py-2 text-sm dark:bg-gray-900">
                            <span>{{ $check['label'] }}</span>
                            <span class="font-medium {{ $check['ok'] ? 'text-success-600' : 'text-danger-600' }}">{{ $check['ok'] ? 'OK' : 'Needs repair' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($this->modules as $module)
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-gray-950 dark:text-white">{{ $module['name'] }}</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">{{ $module['alias'] }}</p>
                        </div>
                        <span class="rounded-full px-2 py-1 text-xs font-medium {{ $module['enabled'] ? 'bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                            {{ $module['enabled'] ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>

                    @if ($module['description'])
                        <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">{{ $module['description'] }}</p>
                    @endif

                    <div class="mt-4 space-y-1 text-xs text-gray-500 dark:text-gray-500">
                        @if ($module['version'])<p>Version: {{ $module['version'] }}</p>@endif
                        <p>Path: {{ $module['path'] }}</p>
                        <p>Status: {{ $module['status'] }}</p>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-2">
                        <x-filament::button size="sm" color="gray" wire:click="inspectModule('{{ $module['name'] }}')">Inspect</x-filament::button>
                        <x-filament::button size="sm" color="warning" wire:click="repairModule('{{ $module['name'] }}')">Repair</x-filament::button>
                        @if ($module['enabled'])
                            <x-filament::button size="sm" color="danger" wire:click="disableModule('{{ $module['name'] }}')">Disable</x-filament::button>
                        @else
                            <x-filament::button size="sm" color="success" wire:click="enableModule('{{ $module['name'] }}')">Enable</x-filament::button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-gray-200 bg-white p-5 text-sm text-gray-600 shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400">
                    No modules were discovered.
                </div>
            @endforelse
        </div>
    </div>
</x-filament-panels::page>
