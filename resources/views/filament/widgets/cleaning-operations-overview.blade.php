@php
    use App\Support\CleaningAdminMetrics;
    $totals = CleaningAdminMetrics::dashboardTotals();
    $upcomingJobs = CleaningAdminMetrics::upcomingJobs();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Operations Overview</x-slot>
        <x-slot name="description">Today's jobs with scheduled vs completed progress and active technician coverage.</x-slot>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Today's Jobs</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $totals['jobs_today'] }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Scheduled Today</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $totals['scheduled_jobs_today'] }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Completed Today</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $totals['completed_jobs_today'] }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Active Technicians</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $totals['active_technicians'] }}</div>
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div>
                <h3 class="text-base font-semibold text-gray-950 dark:text-white">Upcoming Jobs</h3>
                <div class="mt-3 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-3 py-2 text-left font-medium">When</th>
                                <th class="px-3 py-2 text-left font-medium">Job</th>
                                <th class="px-3 py-2 text-left font-medium">Cleaner</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($upcomingJobs as $job)
                                <tr>
                                    <td class="px-3 py-2 text-gray-600 dark:text-gray-300">{{ optional($job->scheduled_at)->format('d M, g:ia') }}</td>
                                    <td class="px-3 py-2">
                                        <div class="font-medium text-gray-950 dark:text-white">{{ $job->title }}</div>
                                        <div class="text-xs text-gray-500">{{ $job->jobType?->name ?? 'No service' }}</div>
                                    </td>
                                    <td class="px-3 py-2 text-gray-600 dark:text-gray-300">{{ $job->assignedTechnician?->name ?? 'Unassigned' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-3 py-8 text-center text-gray-500">No upcoming jobs found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <h3 class="text-base font-semibold text-gray-950 dark:text-white">Quick Links</h3>
                <div class="mt-3 grid gap-3 sm:grid-cols-2">
                    <a href="/admin/reports" class="rounded-xl border border-gray-200 p-4 transition hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                        <div class="font-semibold text-gray-950 dark:text-white">Reports</div>
                        <div class="text-sm text-gray-500">Revenue, quotes, workload, services.</div>
                    </a>
                    <a href="/titango" target="_blank" class="rounded-xl border border-gray-200 p-4 transition hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                        <div class="font-semibold text-gray-950 dark:text-white">TitanGo</div>
                        <div class="text-sm text-gray-500">Launch the installable field app.</div>
                    </a>
                    <a href="/admin/driver-locations" class="rounded-xl border border-gray-200 p-4 transition hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                        <div class="font-semibold text-gray-950 dark:text-white">Cleaner Locations</div>
                        <div class="text-sm text-gray-500">Dispatch and live tracking.</div>
                    </a>
                    <a href="/admin/invoices" class="rounded-xl border border-gray-200 p-4 transition hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                        <div class="font-semibold text-gray-950 dark:text-white">Invoices</div>
                        <div class="text-sm text-gray-500">{{ $totals['overdue_invoices'] }} overdue invoices.</div>
                    </a>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
