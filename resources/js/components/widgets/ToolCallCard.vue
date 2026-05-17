<script setup lang="ts">
import type { ControlPanelWidget, ToolCallRecord } from '@/types/control-panel';
import { ChevronDown, ChevronRight } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const props = defineProps<{ widget: ControlPanelWidget }>();

const record = computed(() => (props.widget.data ?? {}) as ToolCallRecord);

const expanded = ref(false);

const statusConfig = computed(() => {
    const status = record.value.status ?? 'queued';
    const configs: Record<string, { label: string; classes: string }> = {
        queued: { label: 'Queued', classes: 'bg-muted text-muted-foreground' },
        running: {
            label: 'Running',
            classes:
                'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
        },
        completed: {
            label: 'Completed',
            classes:
                'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
        },
        failed: {
            label: 'Failed',
            classes: 'bg-destructive/15 text-destructive',
        },
    };
    return configs[status] ?? configs['queued'];
});

const hasDetails = computed(
    () =>
        record.value.arguments !== undefined ||
        record.value.result !== undefined,
);
</script>

<template>
    <article
        class="widget widget--tool-call bg-card rounded-xl border shadow-sm"
    >
        <div class="flex items-start justify-between px-5 pt-5 pb-3">
            <div>
                <p class="text-muted-foreground text-sm font-medium">
                    {{ widget.title ?? 'Tool Call' }}
                </p>
                <p class="mt-0.5 font-mono text-base font-semibold">
                    {{ record.name ?? '—' }}
                </p>
                <p
                    v-if="record.id"
                    class="text-muted-foreground mt-0.5 font-mono text-xs"
                >
                    ID: {{ record.id }}
                </p>
            </div>
            <span
                class="mt-0.5 shrink-0 rounded-full px-2.5 py-1 text-xs font-medium"
                :class="statusConfig.classes"
            >
                {{ statusConfig.label }}
            </span>
        </div>

        <!-- Timestamps -->
        <div
            v-if="record.startedAt || record.completedAt"
            class="text-muted-foreground px-5 pb-2 text-xs"
        >
            <span v-if="record.startedAt">Started: {{ record.startedAt }}</span>
            <span v-if="record.startedAt && record.completedAt"> · </span>
            <span v-if="record.completedAt"
                >Completed: {{ record.completedAt }}</span
            >
        </div>

        <!-- Collapsible arguments/result -->
        <div v-if="hasDetails" class="border-t">
            <button
                class="text-muted-foreground hover:bg-muted/50 flex w-full items-center gap-1 px-5 py-2.5 text-xs"
                @click="expanded = !expanded"
            >
                <component
                    :is="expanded ? ChevronDown : ChevronRight"
                    class="size-3.5"
                />
                {{ expanded ? 'Hide' : 'Show' }} details
            </button>
            <div v-if="expanded" class="space-y-3 px-5 pb-5">
                <div v-if="record.arguments !== undefined">
                    <p
                        class="text-muted-foreground mb-1 text-xs font-semibold tracking-wide uppercase"
                    >
                        Arguments
                    </p>
                    <pre
                        class="bg-muted overflow-auto rounded-md p-3 text-xs"
                        >{{ JSON.stringify(record.arguments, null, 2) }}</pre
                    >
                </div>
                <div v-if="record.result !== undefined">
                    <p
                        class="text-muted-foreground mb-1 text-xs font-semibold tracking-wide uppercase"
                    >
                        Result
                    </p>
                    <pre
                        class="bg-muted overflow-auto rounded-md p-3 text-xs"
                        >{{ JSON.stringify(record.result, null, 2) }}</pre
                    >
                </div>
            </div>
        </div>
    </article>
</template>
