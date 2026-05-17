<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">Technicians</x-slot>
        <x-slot name="description">Mobile workforce status and activity.</x-slot>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($technicians as $technician)
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="font-semibold text-gray-950 dark:text-white">{{ $technician['name'] }}</div>
                            <div class="text-xs text-gray-500">{{ $technician['email'] }}</div>
                        </div>
                        <span @class([
                            'rounded-full px-2 py-0.5 text-xs font-medium',
                            'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' => $technician['status'] === 'Online',
                            'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' => $technician['status'] !== 'Online',
                        ])>{{ $technician['status'] }}</span>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                        <div class="rounded-xl bg-gray-50 p-3 dark:bg-gray-800">
                            <div class="text-xs text-gray-500">Active jobs</div>
                            <div class="text-xl font-bold">{{ $technician['active_jobs_count'] }}</div>
                        </div>
                        <div class="rounded-xl bg-gray-50 p-3 dark:bg-gray-800">
                            <div class="text-xs text-gray-500">Last seen</div>
                            <div class="text-sm font-medium">{{ $technician['last_seen']?->diffForHumans() ?? 'Never' }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-gray-300 p-6 text-sm text-gray-500 dark:border-gray-700">No technicians found.</div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-panels::page>
