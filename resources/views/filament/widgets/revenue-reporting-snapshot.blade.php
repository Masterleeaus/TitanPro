@php
    use App\Support\CleaningAdminMetrics;
    $totals = CleaningAdminMetrics::dashboardTotals();
    $statusBreakdown = CleaningAdminMetrics::invoiceStatusBreakdown();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Finance Snapshot</x-slot>
        <x-slot name="description">Uses the real invoice statuses in this project: draft, sent, paid, partial, overdue, void.</x-slot>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Invoices</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $totals['total_invoices'] }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Overdue</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $totals['overdue_invoices'] }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Paid This Month</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ CleaningAdminMetrics::currency($totals['payments_this_month']) }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm text-gray-500 dark:text-gray-400">Accepted Quotes</div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $totals['quotes_accepted'] }}</div>
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-3 py-2 text-left font-medium">Status</th>
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
                        <tr><td colspan="4" class="px-3 py-8 text-center text-gray-500">No invoice data found for this organization.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
