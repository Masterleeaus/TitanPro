<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">TitanSolo</x-slot>
        <x-slot name="description">Single-operator dashboard for sole trader cleaning businesses.</x-slot>

        <div class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            <x-filament::button tag="a" href="{{ route('filament.titansolo.resources.jobs.create') }}" icon="heroicon-o-plus-circle">
                Quick-create job
            </x-filament::button>
            <x-filament::button tag="a" color="gray" href="{{ route('filament.titansolo.resources.invoices.create') }}" icon="heroicon-o-document-plus">
                Create invoice
            </x-filament::button>
            <x-filament::button tag="a" color="gray" href="{{ route('filament.titansolo.resources.jobs.index') }}" icon="heroicon-o-calendar-days">
                View all jobs
            </x-filament::button>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="space-y-3 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Today's jobs</h3>
                @forelse($todayJobs as $job)
                    <div class="rounded-lg border border-gray-100 p-3 text-sm dark:border-gray-800">
                        <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $job->title }}</div>
                        <div class="text-gray-500 dark:text-gray-400">{{ $job->customer?->full_name ?? 'No customer' }}</div>
                        <div class="text-xs text-gray-400 dark:text-gray-500">{{ $job->scheduled_at?->format('g:i A') ?? 'Unscheduled' }}</div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">No jobs scheduled for today.</p>
                @endforelse
            </div>

            <div class="space-y-3 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Outstanding invoices</h3>
                @forelse($outstandingInvoices as $invoice)
                    <div class="rounded-lg border border-gray-100 p-3 text-sm dark:border-gray-800">
                        <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $invoice->invoice_number ?? 'Draft invoice' }}</div>
                        <div class="text-gray-500 dark:text-gray-400">{{ $invoice->customer?->full_name ?? 'No customer' }}</div>
                        <div class="text-xs text-amber-600 dark:text-amber-400">${{ number_format((float) $invoice->balance_due, 2) }} due</div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">No outstanding invoices.</p>
                @endforelse
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
