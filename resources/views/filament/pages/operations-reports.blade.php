<x-filament-panels::page>
    @php($data = $this->getReportData())

    <div class="grid gap-6 lg:grid-cols-3">
        <x-filament::section>
            <x-slot name="heading">Quote conversion</x-slot>
            <div class="text-3xl font-bold text-gray-950 dark:text-white">{{ $data['quoteConversion'] }}%</div>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $data['approvedQuotes'] }} approved from {{ $data['totalQuotes'] }} total quotes.
            </p>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Invoice value</x-slot>
            <div class="text-3xl font-bold text-gray-950 dark:text-white">${{ number_format($data['totalRevenue'], 2) }}</div>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">All invoice totals.</p>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Outstanding</x-slot>
            <div class="text-3xl font-bold text-gray-950 dark:text-white">${{ number_format($data['outstanding'], 2) }}</div>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Sent, partial, or overdue invoices.</p>
        </x-filament::section>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <x-filament::section>
            <x-slot name="heading">Jobs by status</x-slot>
            <div class="space-y-3">
                @forelse($data['jobsByStatus'] as $status => $total)
                    <div class="flex items-center justify-between border-b border-gray-200 pb-2 text-sm last:border-0 dark:border-white/10">
                        <span>{{ $status }}</span>
                        <span class="font-semibold text-gray-950 dark:text-white">{{ $total }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">No job data yet.</p>
                @endforelse
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Top services</x-slot>
            <div class="space-y-3">
                @forelse($data['jobsByService'] as $service)
                    <div class="flex items-center justify-between border-b border-gray-200 pb-2 text-sm last:border-0 dark:border-white/10">
                        <span>{{ $service->name }}</span>
                        <span class="font-semibold text-gray-950 dark:text-white">{{ $service->jobs_count }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">No service usage yet.</p>
                @endforelse
            </div>
        </x-filament::section>
    </div>

    <x-filament::section>
        <x-slot name="heading">Existing owner reports</x-slot>
        <div class="flex flex-wrap gap-2">
            <x-filament::button tag="a" href="{{ route('owner.reports.jobs-by-type') }}" target="_blank" color="gray">Jobs by service</x-filament::button>
            <x-filament::button tag="a" href="{{ route('owner.reports.job-profitability') }}" target="_blank" color="gray">Job profitability</x-filament::button>
            <x-filament::button tag="a" href="{{ route('owner.reports.technician-performance') }}" target="_blank" color="gray">Cleaner performance</x-filament::button>
        </div>
    </x-filament::section>
</x-filament-panels::page>
