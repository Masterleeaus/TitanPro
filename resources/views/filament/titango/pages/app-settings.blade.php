<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">TitanGo App Settings</x-slot>
        <x-slot name="description">Production mobile app configuration overview.</x-slot>

        <div class="grid gap-3 md:grid-cols-2">
            @foreach ($settings as $label => $value)
                <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                    <div class="text-xs uppercase tracking-wide text-gray-500">{{ $label }}</div>
                    <div class="mt-1 font-semibold text-gray-950 dark:text-white">{{ $value }}</div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-panels::page>
