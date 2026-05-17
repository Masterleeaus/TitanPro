<x-filament-panels::page>
    <div class="grid gap-6 lg:grid-cols-3">
        <x-filament::section class="lg:col-span-2">
            <x-slot name="heading">Scheduled jobs linked to shifts</x-slot>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-gray-500 dark:text-gray-400">
                            <th class="py-2 pr-4">Job</th>
                            <th class="py-2 pr-4">Technician</th>
                            <th class="py-2 pr-4">Window</th>
                            <th class="py-2 pr-4">Shift</th>
                            <th class="py-2 pr-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($appointments as $appointment)
                            <tr class="border-b align-top dark:border-gray-800">
                                <td class="py-3 pr-4 font-medium">
                                    {{ $appointment->workOrder?->title ?? $appointment->workOrder?->wo_detail ?? 'Work order #'.$appointment->work_order_id }}
                                    <div class="text-xs text-gray-500">{{ $appointment->location }}</div>
                                </td>
                                <td class="py-3 pr-4">{{ $appointment->technician?->name ?? 'Unassigned' }}</td>
                                <td class="py-3 pr-4">
                                    {{ optional($appointment->starts_at)->format('d M Y H:i') ?? trim(($appointment->start_date ?? '').' '.($appointment->start_time ?? '')) }}
                                    <div class="text-xs text-gray-500">
                                        to {{ optional($appointment->ends_at)->format('d M Y H:i') ?? trim(($appointment->end_date ?? '').' '.($appointment->end_time ?? '')) }}
                                    </div>
                                </td>
                                <td class="py-3 pr-4">{{ $appointment->shiftAssignment?->shift?->name ?? 'Ad hoc' }}</td>
                                <td class="py-3 pr-4">{{ ucfirst(str_replace('_', ' ', (string) $appointment->status)) }}</td>
                            </tr>
                        @empty
                            <tr><td class="py-6 text-gray-500" colspan="5">No linked appointments yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Shift templates</x-slot>
            <div class="space-y-3">
                @forelse ($shiftTemplates as $shift)
                    <div class="rounded-lg border p-3 dark:border-gray-800">
                        <div class="font-medium">{{ $shift->name }}</div>
                        <div class="text-sm text-gray-500">{{ $shift->start_time ?? '—' }} → {{ $shift->finish_time ?? '—' }}</div>
                        @if ($shift->tag)<div class="text-xs text-gray-500">{{ $shift->tag }}</div>@endif
                    </div>
                @empty
                    <div class="text-sm text-gray-500">Create shift templates to standardise technician availability.</div>
                @endforelse
            </div>
        </x-filament::section>
    </div>

    <x-filament::section>
        <x-slot name="heading">Open jobs</x-slot>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left text-gray-500 dark:text-gray-400">
                        <th class="py-2 pr-4">Job</th>
                        <th class="py-2 pr-4">Assigned tech</th>
                        <th class="py-2 pr-4">Scheduled</th>
                        <th class="py-2 pr-4">Shift link</th>
                        <th class="py-2 pr-4">Priority</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($openWorkOrders as $job)
                        <tr class="border-b align-top dark:border-gray-800">
                            <td class="py-3 pr-4 font-medium">
                                {{ $job->title ?? $job->wo_detail ?? 'Work order #'.$job->id }}
                                <div class="text-xs text-gray-500">{{ $job->location }}</div>
                            </td>
                            <td class="py-3 pr-4">{{ $job->technician?->name ?? $job->assigned?->name ?? 'Unassigned' }}</td>
                            <td class="py-3 pr-4">{{ optional($job->scheduled_for)->format('d M Y H:i') ?? 'Not scheduled' }}</td>
                            <td class="py-3 pr-4">{{ $job->primaryShiftAssignment?->shift?->name ?? 'None' }}</td>
                            <td class="py-3 pr-4">{{ ucfirst((string) $job->priority) }}</td>
                        </tr>
                    @empty
                        <tr><td class="py-6 text-gray-500" colspan="5">No open jobs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">Unlinked shift assignments</x-slot>
        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($unlinkedShiftAssignments as $assignment)
                <div class="rounded-lg border p-3 dark:border-gray-800">
                    <div class="font-medium">{{ $assignment->shift?->name ?? 'Shift assignment #'.$assignment->id }}</div>
                    <div class="text-sm text-gray-500">{{ $assignment->employee?->name ?? 'No employee' }} · {{ $assignment->date_added }}</div>
                </div>
            @empty
                <div class="text-sm text-gray-500">All shift assignments are linked or no legacy assignments exist.</div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-panels::page>
