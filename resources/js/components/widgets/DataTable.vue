<script setup lang="ts">
import type { ControlPanelWidget } from '@/types/control-panel';
import { ChevronDown, ChevronUp } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const props = defineProps<{ widget: ControlPanelWidget }>();
const emit = defineEmits<{ rowClick: [row: Record<string, unknown>] }>();

const rows = computed(() =>
    Array.isArray(props.widget.data)
        ? (props.widget.data as Record<string, unknown>[])
        : [],
);
const columns = computed(() =>
    rows.value.length ? Object.keys(rows.value[0]) : [],
);

const PAGE_SIZE = 10;
const page = ref(0);
const sortKey = ref<string | null>(null);
const sortAsc = ref(true);

const sorted = computed(() => {
    if (!sortKey.value) return rows.value;
    const key = sortKey.value;
    return [...rows.value].sort((a, b) => {
        const av = a[key] ?? '';
        const bv = b[key] ?? '';
        const cmp = String(av).localeCompare(String(bv), undefined, {
            numeric: true,
        });
        return sortAsc.value ? cmp : -cmp;
    });
});

const pageCount = computed(() => Math.ceil(sorted.value.length / PAGE_SIZE));
const paginated = computed(() =>
    sorted.value.slice(page.value * PAGE_SIZE, (page.value + 1) * PAGE_SIZE),
);

function toggleSort(col: string) {
    if (sortKey.value === col) {
        sortAsc.value = !sortAsc.value;
    } else {
        sortKey.value = col;
        sortAsc.value = true;
    }
    page.value = 0;
}
</script>

<template>
    <article class="widget widget--table bg-card rounded-xl border shadow-sm">
        <div class="px-5 pt-5 pb-3">
            <p class="text-muted-foreground text-sm font-medium">
                {{ widget.title ?? 'Table' }}
            </p>
        </div>
        <div
            v-if="rows.length === 0"
            class="text-muted-foreground px-5 pb-5 text-xs"
        >
            No data
        </div>
        <div v-else class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-t">
                        <th
                            v-for="col in columns"
                            :key="col"
                            class="text-muted-foreground hover:text-foreground cursor-pointer px-4 py-2 text-left text-xs font-semibold tracking-wide uppercase select-none"
                            @click="toggleSort(col)"
                        >
                            <span class="flex items-center gap-1">
                                {{ col }}
                                <ChevronUp
                                    v-if="sortKey === col && sortAsc"
                                    class="size-3"
                                />
                                <ChevronDown
                                    v-else-if="sortKey === col && !sortAsc"
                                    class="size-3"
                                />
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(row, ri) in paginated"
                        :key="ri"
                        class="hover:bg-muted/50 cursor-pointer border-t"
                        @click="emit('rowClick', row)"
                    >
                        <td v-for="col in columns" :key="col" class="px-4 py-2">
                            {{ String(row[col] ?? '') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div
            v-if="pageCount > 1"
            class="text-muted-foreground flex items-center justify-between px-5 py-3 text-xs"
        >
            <span>Page {{ page + 1 }} of {{ pageCount }}</span>
            <div class="flex gap-2">
                <button
                    :disabled="page === 0"
                    class="hover:bg-muted rounded px-2 py-1 disabled:opacity-40"
                    @click="page--"
                >
                    Previous
                </button>
                <button
                    :disabled="page >= pageCount - 1"
                    class="hover:bg-muted rounded px-2 py-1 disabled:opacity-40"
                    @click="page++"
                >
                    Next
                </button>
            </div>
        </div>
    </article>
</template>
