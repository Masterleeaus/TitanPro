<script setup lang="ts">
import BarChart from '@/components/widgets/BarChart.vue';
import ChatThread from '@/components/widgets/ChatThread.vue';
import DataTable from '@/components/widgets/DataTable.vue';
import JsonWidget from '@/components/widgets/JsonWidget.vue';
import LineChart from '@/components/widgets/LineChart.vue';
import LogList from '@/components/widgets/LogList.vue';
import McpServerList from '@/components/widgets/McpServerList.vue';
import MetricCard from '@/components/widgets/MetricCard.vue';
import ProjectList from '@/components/widgets/ProjectList.vue';
import SettingsForm from '@/components/widgets/SettingsForm.vue';
import ToolCallCard from '@/components/widgets/ToolCallCard.vue';
import type {
    ControlPanelWidget,
    ControlPanelWidgetKind,
} from '@/types/control-panel';
import { defineAsyncComponent } from 'vue';

defineProps<{
    widgets: ControlPanelWidget[];
}>();

const emit = defineEmits<{
    widgetAction: [
        kind: ControlPanelWidgetKind,
        event: string,
        payload: unknown,
    ];
}>();

const kindComponentMap: Record<
    ControlPanelWidgetKind,
    ReturnType<typeof defineAsyncComponent> | object
> = {
    'metric-card': MetricCard,
    'line-chart': LineChart,
    'bar-chart': BarChart,
    'data-table': DataTable,
    'log-list': LogList,
    'chat-thread': ChatThread,
    'tool-call': ToolCallCard,
    'settings-form': SettingsForm,
    'mcp-server-list': McpServerList,
    'project-list': ProjectList,
};

function resolveComponent(kind: string): object {
    return (kindComponentMap as Record<string, object>)[kind] ?? JsonWidget;
}
</script>

<template>
    <div
        v-if="!widgets.length"
        class="flex flex-col items-center justify-center gap-2 py-16 text-center"
    >
        <p class="text-foreground text-base font-semibold">
            No dashboard generated yet
        </p>
        <p class="text-muted-foreground text-sm">
            Ask the chatbot to show projects, customers, tasks, invoices,
            analytics, logs, or settings.
        </p>
    </div>

    <div v-else class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <component
            :is="resolveComponent(widget.kind)"
            v-for="widget in widgets"
            :key="widget.id"
            :widget="widget"
            @row-click="
                (payload: unknown) =>
                    emit('widgetAction', widget.kind, 'rowClick', payload)
            "
            @save="
                (payload: unknown) =>
                    emit('widgetAction', widget.kind, 'save', payload)
            "
            @connect="
                (payload: unknown) =>
                    emit('widgetAction', widget.kind, 'connect', payload)
            "
            @disconnect="
                (payload: unknown) =>
                    emit('widgetAction', widget.kind, 'disconnect', payload)
            "
            @open="
                (payload: unknown) =>
                    emit('widgetAction', widget.kind, 'open', payload)
            "
        />
    </div>
</template>
