<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Active Jobs Monitor</x-slot>
        <x-slot name="description">In-progress and scheduled field work.</x-slot>

        <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-3 py-2 text-left font-medium">Job</th>
                        <th class="px-3 py-2 text-left font-medium">Status</th>
                        <th class="px-3 py-2 text-left font-medium">Technician</th>
                        <th class="px-3 py-2 text-left font-medium">Scheduled</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($jobs as $job)
                        <tr>
                            <td class="px-3 py-2">
                                <div class="font-medium text-gray-950 dark:text-white">{{ $job->title }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $job->customer?->business_name ?: trim(($job->customer?->first_name ?? '').' '.($job->customer?->last_name ?? '')) }}
                                </div>
                            </td>
                            <td class="px-3 py-2">{{ str_replace('_', ' ', $job->status) }}</td>
                            <td class="px-3 py-2">{{ $job->assignedTechnician?->name ?? 'Unassigned' }}</td>
                            <td class="px-3 py-2 text-gray-500">{{ $job->scheduled_at?->format('d M, g:ia') ?? 'Not scheduled' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-3 py-8 text-center text-gray-500">No active jobs.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
