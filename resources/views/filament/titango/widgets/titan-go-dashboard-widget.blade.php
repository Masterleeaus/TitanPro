<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">TitanGo Dashboard</x-slot>
        <x-slot name="description">Mobile field operations overview.</x-slot>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500">Active Technicians</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $stats['active_technicians'] }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500">Active Jobs</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $stats['active_jobs'] }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500">Scheduled Jobs</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $stats['scheduled_jobs'] }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500">Offline Syncs</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $stats['offline_technicians'] }}</div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
