<x-filament-panels::page>
    @php
        $areas = $this->installedNexusTables();
    @endphp

    <div class="space-y-6">
        <x-filament::section heading="TitanNexus System Status" description="Verify panel data areas, migrations, and discovery state.">
            <div class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                <p>Legacy module resource discovery is disabled for this panel. App-level TitanNexus pages and resources are the active Filament surface.</p>
                <p>Module services, routes, actions, and models remain available to the application. Legacy Filament stubs are not loaded into panel navigation.</p>
            </div>
        </x-filament::section>

        <x-filament::section heading="Data Areas" description="Ready means the database table exists and can be queried.">
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($areas as $area)
                    <div class="rounded-xl border border-gray-200 p-4 text-sm dark:border-white/10">
                        <div class="flex items-center justify-between gap-3">
                            <span class="font-medium text-gray-950 dark:text-white">{{ $area['label'] }}</span>
                            <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $area['installed'] ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300' }}">
                                {{ $area['installed'] ? 'Ready' : 'Pending' }}
                            </span>
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ $area['table'] }} · {{ number_format($area['count']) }} records</p>
                    </div>
                @endforeach
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
