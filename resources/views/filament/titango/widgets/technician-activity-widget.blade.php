<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Technician Activity Overview</x-slot>
        <x-slot name="description">Live status of active technicians.</x-slot>

        <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-3 py-2 text-left font-medium">Technician</th>
                        <th class="px-3 py-2 text-left font-medium">Status</th>
                        <th class="px-3 py-2 text-left font-medium">Active Jobs</th>
                        <th class="px-3 py-2 text-left font-medium">Last Seen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($technicians as $technician)
                        <tr>
                            <td class="px-3 py-2 font-medium text-gray-950 dark:text-white">{{ $technician['name'] }}</td>
                            <td class="px-3 py-2">{{ $technician['status'] }}</td>
                            <td class="px-3 py-2">{{ $technician['active_jobs_count'] }}</td>
                            <td class="px-3 py-2 text-gray-500">{{ $technician['last_seen'] ?? 'Never' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-3 py-8 text-center text-gray-500">No technicians found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
