@php
    use App\Support\CleaningAdminMetrics;
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Finance Overview</x-slot>
        <x-slot name="description">Revenue this month, outstanding invoices, overdue count, and recent payments.</x-slot>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Revenue This Month</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ CleaningAdminMetrics::currency($totals['revenue_this_month']) }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Outstanding Invoices</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ CleaningAdminMetrics::currency($totals['outstanding_balance']) }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Overdue Invoices</div>
                <div class="mt-2 text-3xl font-bold text-red-600 dark:text-red-400">{{ $totals['overdue_invoices'] }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Revenue This Week</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ CleaningAdminMetrics::currency($totals['revenue_this_week']) }}</div>
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-3 py-2 text-left font-medium">Invoice Status</th>
                        <th class="px-3 py-2 text-right font-medium">Count</th>
                        <th class="px-3 py-2 text-right font-medium">Invoice Total</th>
                        <th class="px-3 py-2 text-right font-medium">Balance Due</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($statusBreakdown as $row)
                        <tr>
                            <td class="px-3 py-2 capitalize">{{ $row->status }}</td>
                            <td class="px-3 py-2 text-right">{{ $row->total }}</td>
                            <td class="px-3 py-2 text-right">{{ CleaningAdminMetrics::currency($row->invoice_total) }}</td>
                            <td class="px-3 py-2 text-right">{{ CleaningAdminMetrics::currency($row->balance_due) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-3 py-8 text-center text-gray-500">No invoice data found for this organisation.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($recentPayments->isNotEmpty())
            <div class="mt-6">
                <h3 class="mb-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Recent Payments</h3>
                <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-3 py-2 text-left font-medium">Invoice</th>
                                <th class="px-3 py-2 text-left font-medium">Method</th>
                                <th class="px-3 py-2 text-right font-medium">Amount</th>
                                <th class="px-3 py-2 text-right font-medium">Paid At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($recentPayments as $payment)
                                <tr>
                                    <td class="px-3 py-2">{{ $payment->invoice?->invoice_number ?? '#'.$payment->invoice_id }}</td>
                                    <td class="px-3 py-2 capitalize">{{ $payment->method }}</td>
                                    <td class="px-3 py-2 text-right">{{ CleaningAdminMetrics::currency($payment->amount) }}</td>
                                    <td class="px-3 py-2 text-right">{{ $payment->paid_at?->format('d M Y H:i') ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
