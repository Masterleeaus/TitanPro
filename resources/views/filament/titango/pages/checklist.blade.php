<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">Cleaning checklist</x-slot>
            <x-slot name="description">Fast on-site checklist for cleaners. Detailed template persistence can be wired to checklist runs next.</x-slot>
            <div class="space-y-4">
                @foreach ($defaultTasks as $task)
                    <label class="flex items-center gap-3 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                        <input type="checkbox" class="rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $task }}</span>
                    </label>
                @endforeach
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Open jobs</x-slot>
            <div class="space-y-3">
                @forelse ($jobs as $job)
                    <div class="rounded-2xl border border-gray-200 p-4 dark:border-gray-700">
                        <div class="font-semibold text-gray-950 dark:text-white">{{ $job->reference }}</div>
                        <div class="text-sm text-gray-500">{{ $job->description ?: 'Cleaning job' }}</div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-500 dark:border-gray-700">No open jobs.</div>
                @endforelse
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
