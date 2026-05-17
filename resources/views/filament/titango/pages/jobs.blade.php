<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-3">
            @foreach ([['Today', $counts['today'] ?? 0], ['Active', $counts['active'] ?? 0], ['Completed', $counts['completed'] ?? 0]] as [$label, $value])
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="text-sm text-gray-500">{{ $label }}</div>
                    <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $value }}</div>
                </div>
            @endforeach
        </div>

        <x-filament::section>
            <x-slot name="heading">My cleaning jobs</x-slot>
            <x-slot name="description">Cleaner-safe view of assigned work only.</x-slot>
            <div class="grid gap-3">
                @forelse ($jobs as $job)
                    <div class="rounded-2xl border border-gray-200 p-4 dark:border-gray-700">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <div class="font-semibold text-gray-950 dark:text-white">{{ $job->reference }}</div>
                                <div class="text-sm text-gray-500">{{ $job->description ?: 'Cleaning job' }}</div>
                                <div class="mt-1 text-xs text-gray-400">{{ $job->scheduled_start?->format('D d M, g:ia') ?? 'Not scheduled' }} · {{ str($job->status)->replace('_', ' ')->title() }}</div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ url('/titango/check-in-page') }}" class="rounded-xl bg-green-600 px-3 py-2 text-xs font-semibold text-white">Check in</a>
                                <a href="{{ url('/titango/job-checklist-page') }}" class="rounded-xl border border-gray-200 px-3 py-2 text-xs font-semibold dark:border-gray-700">Checklist</a>
                                <a href="{{ url('/titango/proof') }}" class="rounded-xl border border-gray-200 px-3 py-2 text-xs font-semibold dark:border-gray-700">Photos</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-500 dark:border-gray-700">No assigned jobs.</div>
                @endforelse
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
