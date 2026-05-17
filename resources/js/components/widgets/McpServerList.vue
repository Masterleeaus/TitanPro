<script setup lang="ts">
import type { ControlPanelWidget, McpServer } from '@/types/control-panel';
import { AlertCircle, CheckCircle, Link, XCircle } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{ widget: ControlPanelWidget }>();
const emit = defineEmits<{
    connect: [server: McpServer];
    disconnect: [server: McpServer];
}>();

const servers = computed(() =>
    Array.isArray(props.widget.data) ? (props.widget.data as McpServer[]) : [],
);

function statusIcon(status: string) {
    if (status === 'connected') return CheckCircle;
    if (status === 'error') return AlertCircle;
    return XCircle;
}

function statusClass(status: string): string {
    if (status === 'connected') return 'text-green-600 dark:text-green-400';
    if (status === 'error') return 'text-yellow-600 dark:text-yellow-400';
    return 'text-muted-foreground';
}

function statusLabel(status: string): string {
    if (status === 'connected') return 'Connected';
    if (status === 'error') return 'Error';
    return 'Disconnected';
}
</script>

<template>
    <article
        class="widget widget--mcp-server-list bg-card rounded-xl border shadow-sm"
    >
        <div class="px-5 pt-5 pb-3">
            <p class="text-muted-foreground text-sm font-medium">
                {{ widget.title ?? 'MCP Servers' }}
            </p>
        </div>
        <div
            v-if="servers.length === 0"
            class="text-muted-foreground px-5 pb-5 text-xs"
        >
            No servers configured
        </div>
        <ul v-else class="divide-y">
            <li
                v-for="server in servers"
                :key="server.name"
                class="flex items-start gap-3 px-5 py-3"
            >
                <component
                    :is="statusIcon(server.status)"
                    class="mt-0.5 size-4 shrink-0"
                    :class="statusClass(server.status)"
                />
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium">
                        {{ server.name }}
                    </p>
                    <p
                        class="text-muted-foreground flex items-center gap-1 truncate text-xs"
                    >
                        <Link class="size-3 shrink-0" />
                        {{ server.url }}
                    </p>
                    <div
                        v-if="server.capabilities?.length"
                        class="mt-1 flex flex-wrap gap-1"
                    >
                        <span
                            v-for="cap in server.capabilities"
                            :key="cap"
                            class="bg-muted text-muted-foreground rounded px-1.5 py-0.5 text-[10px]"
                        >
                            {{ cap }}
                        </span>
                    </div>
                </div>
                <div class="flex shrink-0 flex-col items-end gap-1">
                    <span class="text-xs" :class="statusClass(server.status)">{{
                        statusLabel(server.status)
                    }}</span>
                    <button
                        v-if="server.status !== 'connected'"
                        class="text-primary hover:bg-muted rounded px-2 py-0.5 text-xs"
                        @click="emit('connect', server)"
                    >
                        Connect
                    </button>
                    <button
                        v-else
                        class="text-muted-foreground hover:bg-muted rounded px-2 py-0.5 text-xs"
                        @click="emit('disconnect', server)"
                    >
                        Disconnect
                    </button>
                </div>
            </li>
        </ul>
    </article>
</template>
