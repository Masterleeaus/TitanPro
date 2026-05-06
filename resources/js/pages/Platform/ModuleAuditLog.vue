<script setup lang="ts">
import PlatformLayout from '@/layouts/PlatformLayout.vue';
import { Head } from '@inertiajs/vue3';

type AuditEntry = {
    id: number;
    actor: string;
    action: string;
    module: string;
    outcome: string;
    context: Record<string, unknown> | null;
    created_at: string;
};

type PaginatedEntries = {
    data: AuditEntry[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    prev_page_url: string | null;
    next_page_url: string | null;
};

defineProps<{
    entries: PaginatedEntries;
}>();

function outcomeClass(outcome: string): string {
    return outcome === 'success' ? 'text-green-600' : 'text-red-600';
}

function actionBadge(action: string): string {
    const map: Record<string, string> = {
        sync:    'bg-blue-100 text-blue-800',
        enable:  'bg-green-100 text-green-800',
        disable: 'bg-yellow-100 text-yellow-800',
    };
    return map[action] ?? 'bg-gray-100 text-gray-800';
}
</script>

<template>
    <PlatformLayout>
        <Head title="Module Audit Log" />

        <div class="px-6 py-8 max-w-7xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Module Audit Log</h1>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">#</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Timestamp</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Actor</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Action</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Module</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Outcome</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="entries.data.length === 0">
                            <td colspan="6" class="px-4 py-6 text-center text-gray-400">No audit entries found.</td>
                        </tr>
                        <tr v-for="entry in entries.data" :key="entry.id" class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500">{{ entry.id }}</td>
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap">{{ entry.created_at }}</td>
                            <td class="px-4 py-3 font-mono text-gray-700">{{ entry.actor }}</td>
                            <td class="px-4 py-3">
                                <span :class="['inline-block px-2 py-0.5 rounded text-xs font-medium', actionBadge(entry.action)]">
                                    {{ entry.action }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-800">{{ entry.module }}</td>
                            <td class="px-4 py-3 font-medium" :class="outcomeClass(entry.outcome)">
                                {{ entry.outcome }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="entries.last_page > 1" class="mt-4 flex justify-between text-sm text-gray-600">
                <span>Page {{ entries.current_page }} of {{ entries.last_page }} ({{ entries.total }} entries)</span>
                <div class="flex gap-2">
                    <a v-if="entries.prev_page_url" :href="entries.prev_page_url"
                       class="px-3 py-1 border rounded hover:bg-gray-100">← Prev</a>
                    <a v-if="entries.next_page_url" :href="entries.next_page_url"
                       class="px-3 py-1 border rounded hover:bg-gray-100">Next →</a>
                </div>
            </div>
        </div>
    </PlatformLayout>
</template>
