@php
    $report = $this->getReportData();
    $jobsByStatus = $report['jobsByStatus'];
    $jobsByService = $report['jobsByService'];
    $quoteConversion = $report['quoteConversion'];
    $totalQuotes = $report['totalQuotes'];
    $approvedQuotes = $report['approvedQuotes'];
    $totalRevenue = $report['totalRevenue'];
    $outstanding = $report['outstanding'];
@endphp

<x-filament-panels::page>
    <div class="space-y-6">
        {{-- KPI row --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">${{ number_format($totalRevenue, 2) }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Outstanding AR</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">${{ number_format($outstanding, 2) }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Quote Conversion</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $quoteConversion }}%</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Quotes</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $totalQuotes }}</div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            {{-- Jobs by status --}}
            <x-filament::section>
                <x-slot name="heading">Jobs by Status</x-slot>
                <div class="space-y-3">
                    @forelse ($jobsByStatus as $status => $count)
                        @php
                            $maxCount = max(1, $jobsByStatus->max());
                            $width = min(100, round(($count / $maxCount) * 100));
                        @endphp
                        <div>
                            <div class="mb-1 flex justify-between text-sm">
                                <span class="font-medium text-gray-950 dark:text-white">{{ $status }}</span>
                                <span class="text-gray-500">{{ $count }}</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-800">
                                <div class="h-full rounded-full bg-primary-600" style="width: {{ $width }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-gray-300 p-8 text-center text-gray-500 dark:border-gray-700">No job data yet.</div>
                    @endforelse
                </div>
            </x-filament::section>

            {{-- Jobs by service --}}
            <x-filament::section>
                <x-slot name="heading">Jobs by Service</x-slot>
                <div class="space-y-3">
                    @forelse ($jobsByService as $service)
                        @php
                            $maxCount = max(1, $jobsByService->max('jobs_count'));
                            $width = min(100, round(($service->jobs_count / $maxCount) * 100));
                        @endphp
                        <div>
                            <div class="mb-1 flex justify-between text-sm">
                                <span class="font-medium text-gray-950 dark:text-white">{{ $service->name }}</span>
                                <span class="text-gray-500">{{ $service->jobs_count }} jobs</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-800">
                                <div class="h-full rounded-full bg-primary-600" style="width: {{ $width }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-gray-300 p-8 text-center text-gray-500 dark:border-gray-700">No service data yet.</div>
                    @endforelse
                </div>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
