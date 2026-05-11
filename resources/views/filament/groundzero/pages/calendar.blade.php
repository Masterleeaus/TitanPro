@php
    $jobs = $this->getUpcomingJobs();
    $byDay = $jobs->groupBy(fn ($job) => $job->scheduled_at?->format('Y-m-d'));
@endphp

<x-filament-panels::page>
    <div class="space-y-6">
        @if ($byDay->isEmpty())
            <div class="rounded-xl border border-dashed border-gray-300 p-12 text-center text-gray-500 dark:border-gray-700">
                No jobs scheduled in the next 30 days.
            </div>
        @else
            @foreach ($byDay as $date => $dayJobs)
                @php
                    $carbon = \Carbon\Carbon::parse($date);
                @endphp
                <x-filament::section>
                    <x-slot name="heading">
                        {{ $carbon->format('l, F j, Y') }}
                        <span class="ml-2 text-sm font-normal text-gray-500">{{ $dayJobs->count() }} {{ Str::plural('job', $dayJobs->count()) }}</span>
                    </x-slot>

                    <div class="space-y-2">
                        @foreach ($dayJobs as $job)
                            <div class="flex items-center gap-4 rounded-lg border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-gray-800">
                                <div class="w-16 text-center text-sm font-medium text-gray-600 dark:text-gray-400">
                                    {{ $job->scheduled_at?->format('g:i A') }}
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium text-gray-950 dark:text-white">{{ $job->title }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $job->customer?->full_name ?? '—' }}
                                        @if ($job->jobType)
                                            · {{ $job->jobType->name }}
                                        @endif
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $job->assignedTechnician?->name ?? 'Unassigned' }}
                                </div>
                                <div class="text-sm capitalize text-gray-500">
                                    {{ str_replace('_', ' ', $job->status) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-filament::section>
            @endforeach
        @endif
    </div>
</x-filament-panels::page>
