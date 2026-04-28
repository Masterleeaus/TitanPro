<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-950 dark:text-white">Cleaner PWA</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Open the installable field app linked to this site. The public manifest starts at
                    <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-white/10">/technician/dashboard</code>.
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <x-filament::button tag="a" href="{{ url('/technician/dashboard') }}" icon="heroicon-o-device-phone-mobile" target="_blank">
                    Open Cleaner PWA
                </x-filament::button>
                <x-filament::button tag="a" href="{{ url('/manifest.json') }}" color="gray" icon="heroicon-o-document-text" target="_blank">
                    Manifest
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
