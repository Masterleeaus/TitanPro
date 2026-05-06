<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-2">
            <div class="text-base font-semibold text-gray-950 dark:text-white">
                {{ $title ?? static::$heading ?? 'Titan Nexus Widget' }}
            </div>

            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $description ?? 'Titan Nexus widget loaded successfully.' }}
            </div>

            <div class="text-xs font-medium text-gray-400 dark:text-gray-500">
                {{ $status ?? 'Ready' }}
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
