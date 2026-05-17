<script setup lang="ts">
import type { ControlPanelWidget, LogEntry } from '@/types/control-panel';
import { computed } from 'vue';

const props = defineProps<{ widget: ControlPanelWidget }>();

const logs = computed(() =>
    Array.isArray(props.widget.data) ? (props.widget.data as LogEntry[]) : [],
);

function levelClass(entry: LogEntry): string {
    if (typeof entry === 'string') return 'text-foreground';
    const level = entry.level?.toLowerCase();
    if (level === 'error') return 'text-destructive font-medium';
    if (level === 'warn' || level === 'warning')
        return 'text-yellow-600 dark:text-yellow-400';
    return 'text-foreground';
}

function levelBadgeClass(entry: LogEntry): string {
    if (typeof entry === 'string') return 'bg-muted text-muted-foreground';
    const level = entry.level?.toLowerCase();
    if (level === 'error') return 'bg-destructive/15 text-destructive';
    if (level === 'warn' || level === 'warning')
        return 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400';
    return 'bg-muted text-muted-foreground';
}

function displayText(entry: LogEntry): string {
    if (typeof entry === 'string') return entry;
    return entry.message;
}

function entryLevel(entry: LogEntry): string | null {
    if (typeof entry === 'string') return null;
    return entry.level ?? null;
}

function entryTimestamp(entry: LogEntry): string | null {
    if (typeof entry === 'string') return null;
    return entry.timestamp ?? null;
}
</script>

<template>
    <article class="widget widget--logs bg-card rounded-xl border shadow-sm">
        <div class="px-5 pt-5 pb-3">
            <p class="text-muted-foreground text-sm font-medium">
                {{ widget.title ?? 'Logs' }}
            </p>
        </div>
        <div
            v-if="logs.length === 0"
            class="text-muted-foreground px-5 pb-5 text-xs"
        >
            No log entries
        </div>
        <ul v-else class="divide-y text-xs">
            <li
                v-for="(entry, i) in logs"
                :key="i"
                class="flex items-start gap-2 px-5 py-2"
                :class="levelClass(entry)"
            >
                <span
                    v-if="entryLevel(entry)"
                    class="mt-0.5 shrink-0 rounded px-1.5 py-0.5 font-mono text-[10px] uppercase"
                    :class="levelBadgeClass(entry)"
                >
                    {{ entryLevel(entry) }}
                </span>
                <span class="min-w-0 flex-1 font-mono break-all">{{
                    displayText(entry)
                }}</span>
                <span
                    v-if="entryTimestamp(entry)"
                    class="text-muted-foreground shrink-0"
                >
                    {{ entryTimestamp(entry) }}
                </span>
            </li>
        </ul>
    </article>
</template>
