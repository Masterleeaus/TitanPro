@php
    $activeJobs = $this->getActiveJobs();
    $unassignedJobs = $this->getUnassignedJobs();
    $technicians = $this->getTechnicians();
@endphp

<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Summary bar --}}
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Active Jobs</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $activeJobs->count() }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Unassigned</div>
                <div class="mt-2 text-3xl font-bold text-yellow-500">{{ $unassignedJobs->count() }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Technicians</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $technicians->count() }}</div>
            </div>
        </div>

        {{-- Active jobs board --}}
        <x-filament::section>
            <x-slot name="heading">Active Jobs</x-slot>
            <x-slot name="description">Jobs currently scheduled, en route, or in progress.</x-slot>

            @if ($activeJobs->isEmpty())
                <div class="rounded-xl border border-dashed border-gray-300 p-8 text-center text-gray-500 dark:border-gray-700">
                    No active jobs at this time.
                </div>
            @else
                <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-3 py-2 text-left font-medium">Scheduled</th>
                                <th class="px-3 py-2 text-left font-medium">Customer</th>
                                <th class="px-3 py-2 text-left font-medium">Service</th>
                                <th class="px-3 py-2 text-left font-medium">Technician</th>
                                <th class="px-3 py-2 text-left font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($activeJobs as $job)
                                <tr>
                                    <td class="px-3 py-2">{{ $job->scheduled_at?->format('M j, g:i A') }}</td>
                                    <td class="px-3 py-2">{{ $job->customer?->full_name ?? '—' }}</td>
                                    <td class="px-3 py-2">{{ $job->jobType?->name ?? '—' }}</td>
                                    <td class="px-3 py-2">{{ $job->assignedTechnician?->name ?? '—' }}</td>
                                    <td class="px-3 py-2 capitalize">{{ str_replace('_', ' ', $job->status) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-filament::section>

        {{-- Unassigned jobs --}}
        @if ($unassignedJobs->isNotEmpty())
            <x-filament::section>
                <x-slot name="heading">Unassigned Jobs</x-slot>
                <x-slot name="description">Scheduled jobs that have not been assigned to a technician.</x-slot>

                <div class="overflow-hidden rounded-xl border border-yellow-200 dark:border-yellow-700">
                    <table class="w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                        <thead class="bg-yellow-50 dark:bg-yellow-900/20">
                            <tr>
                                <th class="px-3 py-2 text-left font-medium">Scheduled</th>
                                <th class="px-3 py-2 text-left font-medium">Customer</th>
                                <th class="px-3 py-2 text-left font-medium">Service</th>
                                <th class="px-3 py-2 text-left font-medium">Property</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($unassignedJobs as $job)
                                <tr>
                                    <td class="px-3 py-2">{{ $job->scheduled_at?->format('M j, g:i A') }}</td>
                                    <td class="px-3 py-2">{{ $job->customer?->full_name ?? '—' }}</td>
                                    <td class="px-3 py-2">{{ $job->jobType?->name ?? '—' }}</td>
                                    <td class="px-3 py-2">{{ $job->property?->address_line1 ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
