<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-7 gap-2 sm:grid-cols-14">
            @foreach ($calendar as $day)
                <div @class([
                    'rounded-2xl border p-3 text-center shadow-sm',
                    'border-orange-300 bg-orange-50 dark:border-orange-800 dark:bg-orange-950/30' => $day['jobs_count'] > 0,
                    'border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900' => $day['jobs_count'] === 0,
                ])>
                    <div class="text-xs text-gray-500">{{ $day['label'] }}</div>
                    <div class="text-xl font-bold text-gray-950 dark:text-white">{{ $day['number'] }}</div>
                    <div class="mt-1 text-xs text-gray-500">{{ $day['jobs_count'] }} jobs</div>
                    @if ($day['late_count'] > 0)
                        <div class="mt-1 rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-semibold text-red-700 dark:bg-red-900/40 dark:text-red-300">{{ $day['late_count'] }} late</div>
                    @endif
                </div>
            @endforeach
        </div>

        @forelse ($days as $day => $jobs)
            <x-filament::section>
                <x-slot name="heading">{{ $day }}</x-slot>
                <div class="space-y-3">
                    @foreach ($jobs as $job)
                        <div class="flex flex-col gap-3 rounded-2xl border border-gray-200 p-4 sm:flex-row sm:items-center sm:justify-between dark:border-gray-700">
                            <div>
                                <div class="font-semibold text-gray-950 dark:text-white">{{ $job->reference }}</div>
                                <div class="text-sm text-gray-500">{{ $job->description ?: 'Cleaning job' }}</div>
                                <div class="mt-1 text-xs text-gray-400">{{ str($job->status)->replace('_', ' ')->title() }} · {{ ucfirst($job->priority ?? 'normal') }}</div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-xl bg-gray-100 px-3 py-2 text-sm font-semibold dark:bg-gray-800">{{ $job->scheduled_start?->format('g:ia') ?? 'Any time' }}</span>
                                <a href="{{ url('/titango/check-in-page') }}" class="rounded-xl bg-green-600 px-3 py-2 text-xs font-semibold text-white">Check in</a>
                                <a href="{{ url('/titango/proof') }}" class="rounded-xl border border-gray-200 px-3 py-2 text-xs font-semibold dark:border-gray-700">Photos</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-filament::section>
        @empty
            <x-filament::section>
                <div class="py-8 text-center text-sm text-gray-500">No scheduled cleaning jobs in the next 14 days.</div>
            </x-filament::section>
        @endforelse
    </div>
</x-filament-panels::page>
