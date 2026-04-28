@php
    use App\Support\CleaningAdminMetrics;
    $totals = CleaningAdminMetrics::dashboardTotals();
    $invoiceStatuses = CleaningAdminMetrics::invoiceStatusBreakdown();
    $monthlyRevenue = CleaningAdminMetrics::monthlyRevenue();
    $cleanerWorkload = CleaningAdminMetrics::cleanerWorkload();
    $servicePopularity = CleaningAdminMetrics::servicePopularity();
    $quoteTotal = $totals['quotes_open'] + $totals['quotes_accepted'];
    $conversionRate = $quoteTotal > 0 ? round(($totals['quotes_accepted'] / $quoteTotal) * 100, 1) : 0;
@endphp

<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Outstanding AR</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ CleaningAdminMetrics::currency($totals['outstanding_balance']) }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Overdue Invoices</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $totals['overdue_invoices'] }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Quote Conversion</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $conversionRate }}%</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Active Jobs</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $totals['active_jobs'] }}</div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <x-filament::section>
                <x-slot name="heading">Monthly Revenue</x-slot>
                <x-slot name="description">Payment-based revenue for the current organization.</x-slot>
                <div class="space-y-3">
                    @forelse ($monthlyRevenue as $row)
                        @php
                            $maxRevenue = max(1, (float) $monthlyRevenue->max('revenue'));
                            $width = min(100, round(((float) $row->revenue / $maxRevenue) * 100));
                        @endphp
                        <div>
                            <div class="mb-1 flex justify-between text-sm">
                                <span class="font-medium text-gray-950 dark:text-white">{{ $row->period }}</span>
                                <span class="text-gray-500">{{ CleaningAdminMetrics::currency($row->revenue) }} · {{ $row->payments }} payments</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-800"><div class="h-full rounded-full bg-primary-600" style="width: {{ $width }}%"></div></div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-gray-300 p-8 text-center text-gray-500 dark:border-gray-700">No payment revenue yet.</div>
                    @endforelse
                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">Invoice Status Breakdown</x-slot>
                <x-slot name="description">Matches existing invoice statuses: draft, sent, paid, partial, overdue, void.</x-slot>
                <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800"><tr><th class="px-3 py-2 text-left font-medium">Status</th><th class="px-3 py-2 text-right font-medium">Count</th><th class="px-3 py-2 text-right font-medium">Invoice Total</th><th class="px-3 py-2 text-right font-medium">Balance</th></tr></thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($invoiceStatuses as $row)
                                <tr><td class="px-3 py-2 capitalize">{{ $row->status }}</td><td class="px-3 py-2 text-right">{{ $row->total }}</td><td class="px-3 py-2 text-right">{{ CleaningAdminMetrics::currency($row->invoice_total) }}</td><td class="px-3 py-2 text-right">{{ CleaningAdminMetrics::currency($row->balance_due) }}</td></tr>
                            @empty
                                <tr><td colspan="4" class="px-3 py-8 text-center text-gray-500">No invoices found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-filament::section>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <x-filament::section>
                <x-slot name="heading">Cleaner Workload</x-slot>
                <x-slot name="description">Assigned jobs and completions by cleaner.</x-slot>
                <div class="space-y-3">
                    @forelse ($cleanerWorkload as $row)
                        @php
                            $name = $row->assignedTechnician?->name ?? 'Cleaner #' . $row->assigned_to;
                            $maxJobs = max(1, (int) $cleanerWorkload->max('jobs'));
                            $width = min(100, round(((int) $row->jobs / $maxJobs) * 100));
                        @endphp
                        <div><div class="mb-1 flex justify-between text-sm"><span class="font-medium text-gray-950 dark:text-white">{{ $name }}</span><span class="text-gray-500">{{ $row->jobs }} jobs · {{ $row->completed }} completed</span></div><div class="h-2 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-800"><div class="h-full rounded-full bg-primary-600" style="width: {{ $width }}%"></div></div></div>
                    @empty
                        <div class="rounded-xl border border-dashed border-gray-300 p-8 text-center text-gray-500 dark:border-gray-700">No assigned jobs yet.</div>
                    @endforelse
                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">Service Popularity</x-slot>
                <x-slot name="description">Most-used cleaning services from real jobs.</x-slot>
                <div class="space-y-3">
                    @forelse ($servicePopularity as $row)
                        @php
                            $name = $row->jobType?->name ?? 'Service #' . $row->job_type_id;
                            $maxJobs = max(1, (int) $servicePopularity->max('jobs'));
                            $width = min(100, round(((int) $row->jobs / $maxJobs) * 100));
                        @endphp
                        <div><div class="mb-1 flex justify-between text-sm"><span class="font-medium text-gray-950 dark:text-white">{{ $name }}</span><span class="text-gray-500">{{ $row->jobs }} jobs</span></div><div class="h-2 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-800"><div class="h-full rounded-full bg-primary-600" style="width: {{ $width }}%"></div></div></div>
                    @empty
                        <div class="rounded-xl border border-dashed border-gray-300 p-8 text-center text-gray-500 dark:border-gray-700">No service usage yet.</div>
                    @endforelse
                </div>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
