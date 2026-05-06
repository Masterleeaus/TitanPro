<script setup lang="ts">
import OwnerLayout from '@/layouts/OwnerLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

interface Stats {
    jobs_today: number;
    revenue_this_week: number;
    accounts_receivable: number;
    overdue_invoices: number;
    open_jobs: number;
    unassigned_jobs: number;
}

const props = defineProps<{ stats: Stats }>();

function formatCurrency(val: number): string {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val);
}
</script>

<template>
    <OwnerLayout title="Owner Dashboard">
        <Head title="Owner Dashboard" />

        <!-- KPI cards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-slate-200 dark:border-gray-700 p-5 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Jobs Today</p>
                <p class="mt-2 text-3xl font-bold text-slate-800 dark:text-slate-100">{{ stats.jobs_today }}</p>
                <Link href="/owner/jobs" class="mt-2 inline-block text-xs text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition-colors">View jobs →</Link>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-slate-200 dark:border-gray-700 p-5 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Revenue This Week</p>
                <p class="mt-2 text-3xl font-bold text-green-600 dark:text-green-400">{{ formatCurrency(stats.revenue_this_week) }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-slate-200 dark:border-gray-700 p-5 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Accounts Receivable</p>
                <p class="mt-2 text-3xl font-bold text-blue-600 dark:text-blue-400">{{ formatCurrency(stats.accounts_receivable) }}</p>
                <Link href="/owner/invoices" class="mt-2 inline-block text-xs text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition-colors">View invoices →</Link>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-rose-100 dark:border-rose-900/40 p-5 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Overdue Invoices</p>
                <p class="mt-2 text-3xl font-bold text-rose-600 dark:text-rose-400">{{ stats.overdue_invoices }}</p>
                <Link href="/owner/billing" class="mt-2 inline-block text-xs text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition-colors">View billing →</Link>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-slate-200 dark:border-gray-700 p-5 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Open Jobs</p>
                <p class="mt-2 text-3xl font-bold text-slate-800 dark:text-slate-100">{{ stats.open_jobs }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-amber-100 dark:border-amber-900/40 p-5 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Unassigned Jobs</p>
                <p class="mt-2 text-3xl font-bold text-amber-600 dark:text-amber-400">{{ stats.unassigned_jobs }}</p>
                <Link href="/owner/dispatch" class="mt-2 inline-block text-xs text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition-colors">Open dispatch →</Link>
            </div>
        </section>

        <!-- Quick actions -->
        <section class="mb-8">
            <h2 class="text-sm font-semibold text-slate-600 dark:text-slate-400 mb-3 uppercase tracking-wide">Quick Actions</h2>
            <div class="flex flex-wrap gap-3">
                <Link
                    href="/owner/jobs/create"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors shadow-sm"
                >
                    + New Job
                </Link>
                <Link
                    href="/owner/customers/create"
                    class="inline-flex items-center gap-2 rounded-lg bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-gray-700 transition-colors shadow-sm"
                >
                    + New Customer
                </Link>
                <Link
                    href="/owner/invoices/create"
                    class="inline-flex items-center gap-2 rounded-lg bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-gray-700 transition-colors shadow-sm"
                >
                    + New Invoice
                </Link>
                <Link
                    href="/owner/dispatch"
                    class="inline-flex items-center gap-2 rounded-lg bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-gray-700 transition-colors shadow-sm"
                >
                    Dispatch Board
                </Link>
            </div>
        </section>

        <!-- Quick links to reports -->
        <section>
            <h2 class="text-sm font-semibold text-slate-600 dark:text-slate-400 mb-3 uppercase tracking-wide">Reports</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <Link
                    href="/owner/reports/jobs-by-type"
                    class="block bg-white dark:bg-gray-800 rounded-xl border border-slate-200 dark:border-gray-700 p-4 shadow-sm hover:border-slate-300 dark:hover:border-gray-600 hover:shadow transition-all"
                >
                    <p class="font-semibold text-slate-800 dark:text-slate-100">Jobs by Type</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Breakdown of jobs grouped by service type</p>
                </Link>
                <Link
                    href="/owner/reports/job-profitability"
                    class="block bg-white dark:bg-gray-800 rounded-xl border border-slate-200 dark:border-gray-700 p-4 shadow-sm hover:border-slate-300 dark:hover:border-gray-600 hover:shadow transition-all"
                >
                    <p class="font-semibold text-slate-800 dark:text-slate-100">Job Profitability</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Revenue vs. parts cost per job</p>
                </Link>
                <Link
                    href="/owner/reports/technician-performance"
                    class="block bg-white dark:bg-gray-800 rounded-xl border border-slate-200 dark:border-gray-700 p-4 shadow-sm hover:border-slate-300 dark:hover:border-gray-600 hover:shadow transition-all"
                >
                    <p class="font-semibold text-slate-800 dark:text-slate-100">Technician Performance</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Jobs completed, revenue, and avg duration</p>
                </Link>
            </div>
        </section>
    </OwnerLayout>
</template>
