<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-3">
        <x-filament::section class="xl:col-span-2">
            <x-slot name="heading">Technician schedule board</x-slot>
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($technicians as $technician)
                    <div class="rounded-lg border p-4 dark:border-gray-800">
                        <div class="font-medium">{{ $technician->display_name ?: $technician->user?->name ?: 'Technician #'.$technician->id }}</div>
                        <div class="text-xs text-gray-500">{{ $technician->defaultZone?->name ?? 'No default zone' }} · {{ $technician->capacity_minutes_per_day }} min/day</div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">Create technician profiles to populate scheduler lanes.</div>
                @endforelse
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Unscheduled jobs</x-slot>
            <div class="space-y-3">
                @forelse ($unscheduledWorkOrders as $job)
                    <div class="rounded-lg border p-3 dark:border-gray-800">
                        <div class="font-medium">{{ $job->title ?? $job->wo_detail ?? 'Job #'.$job->id }}</div>
                        <div class="text-xs text-gray-500">{{ ucfirst((string) $job->priority) }} · {{ $job->location ?? 'No location' }}</div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">No unscheduled jobs found.</div>
                @endforelse
            </div>
        </x-filament::section>
    </div>

    <x-filament::section>
        <x-slot name="heading">Scheduled assignments</x-slot>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b text-left text-gray-500 dark:text-gray-400"><th class="py-2 pr-4">Date</th><th class="py-2 pr-4">Job</th><th class="py-2 pr-4">Technician</th><th class="py-2 pr-4">Shift</th><th class="py-2 pr-4">Status</th></tr></thead>
                <tbody>
                    @forelse ($scheduledAssignments as $assignment)
                        <tr class="border-b align-top dark:border-gray-800">
                            <td class="py-3 pr-4">{{ $assignment->date_added }}</td>
                            <td class="py-3 pr-4">{{ $assignment->workOrder?->title ?? $assignment->workOrder?->wo_detail ?? 'Job #'.$assignment->work_order_id }}</td>
                            <td class="py-3 pr-4">{{ $assignment->employee?->name ?? 'Unassigned' }}</td>
                            <td class="py-3 pr-4">{{ $assignment->shift?->name ?? 'Ad hoc' }}</td>
                            <td class="py-3 pr-4">{{ ucfirst(str_replace('_', ' ', (string) $assignment->dispatch_status)) }}</td>
                        </tr>
                    @empty
                        <tr><td class="py-6 text-gray-500" colspan="5">No scheduled assignments yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-panels::page>
