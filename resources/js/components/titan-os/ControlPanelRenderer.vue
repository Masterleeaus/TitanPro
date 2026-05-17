<template>
  <div v-if="widgets.length === 0" class="control-panel-empty flex flex-col items-center justify-center h-full text-center p-6 text-gray-500 dark:text-gray-400">
    <slot name="empty">
      <h3 class="text-base font-semibold mb-1">No dashboard generated yet</h3>
      <p class="text-sm">Ask the chatbot to show projects, customers, tasks, invoices, analytics, logs, or settings.</p>
    </slot>
  </div>

  <div v-else class="control-panel-grid grid gap-3 p-3 overflow-y-auto">
    <component
      :is="resolveWidget(widget)"
      v-for="widget in widgets"
      :key="widget.id"
      :widget="widget"
    />
  </div>
</template>

<script setup lang="ts">
import { defineComponent, h } from 'vue';
import type { ControlPanelWidget } from '@/types/control-panel-schema';

const props = defineProps<{
  widgets: ControlPanelWidget[];
}>();

/** Select the sub-component to render based on widget.kind */
function resolveWidget(widget: ControlPanelWidget) {
  switch (widget.kind) {
    case 'metric-card':
      return MetricCard;
    case 'data-table':
      return DataTable;
    case 'log-list':
      return LogList;
    default:
      return JsonWidget;
  }
}

// ---------------------------------------------------------------------------
// Sub-components defined inline to keep the single-file structure clean.
// ---------------------------------------------------------------------------

const MetricCard = defineComponent({
  props: { widget: { type: Object as () => ControlPanelWidget, required: true } },
  setup(p) {
    return () => {
      const data = (p.widget.data ?? {}) as Record<string, unknown>;
      return h('article', { class: 'widget widget--metric rounded-lg border p-4 bg-white dark:bg-gray-800' }, [
        h('h3', { class: 'text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1' }, p.widget.title ?? 'Metric'),
        h('strong', { class: 'text-2xl font-bold text-gray-900 dark:text-white' }, String(data['value'] ?? '—')),
        data['change'] ? h('small', { class: 'block text-xs text-gray-500 mt-1' }, String(data['change'])) : null,
      ]);
    };
  },
});

const DataTable = defineComponent({
  props: { widget: { type: Object as () => ControlPanelWidget, required: true } },
  setup(p) {
    return () => {
      const rows = Array.isArray(p.widget.data) ? (p.widget.data as Record<string, unknown>[]) : [];
      const columns = rows.length ? Object.keys(rows[0]) : [];
      return h('article', { class: 'widget widget--table rounded-lg border overflow-auto bg-white dark:bg-gray-800' }, [
        h('h3', { class: 'text-sm font-semibold p-3 border-b text-gray-700 dark:text-gray-200' }, p.widget.title ?? 'Table'),
        h('table', { class: 'w-full text-xs' }, [
          h('thead', { class: 'bg-gray-50 dark:bg-gray-700' },
            h('tr', {}, columns.map((col) => h('th', { key: col, class: 'px-2 py-1 text-left text-gray-600 dark:text-gray-300' }, col)))
          ),
          h('tbody', {},
            rows.map((row, i) =>
              h('tr', { key: i, class: 'border-t border-gray-100 dark:border-gray-700' },
                columns.map((col) => h('td', { key: col, class: 'px-2 py-1 text-gray-800 dark:text-gray-100' }, String(row[col] ?? '')))
              )
            )
          ),
        ]),
      ]);
    };
  },
});

const LogList = defineComponent({
  props: { widget: { type: Object as () => ControlPanelWidget, required: true } },
  setup(p) {
    return () => {
      const logs = Array.isArray(p.widget.data) ? p.widget.data : [];
      return h('article', { class: 'widget widget--logs rounded-lg border bg-white dark:bg-gray-800' }, [
        h('h3', { class: 'text-sm font-semibold p-3 border-b text-gray-700 dark:text-gray-200' }, p.widget.title ?? 'Logs'),
        h('ul', { class: 'p-2 space-y-1 text-xs font-mono text-gray-700 dark:text-gray-200 overflow-auto max-h-48' },
          logs.map((log, i) =>
            h('li', { key: i }, typeof log === 'string' ? log : JSON.stringify(log))
          )
        ),
      ]);
    };
  },
});

const JsonWidget = defineComponent({
  props: { widget: { type: Object as () => ControlPanelWidget, required: true } },
  setup(p) {
    return () => {
      const label = p.widget.title ?? p.widget.kind;
      return h('article', { class: 'widget widget--json rounded-lg border bg-white dark:bg-gray-800' }, [
        h('h3', { class: 'text-sm font-semibold p-3 border-b text-gray-700 dark:text-gray-200' }, label),
        p.widget.description ? h('p', { class: 'px-3 py-1 text-xs text-gray-500 dark:text-gray-400' }, p.widget.description) : null,
        h('pre', { class: 'p-3 text-xs overflow-auto max-h-48 text-gray-800 dark:text-gray-100' },
          JSON.stringify(p.widget.data ?? p.widget.props ?? {}, null, 2)
        ),
      ]);
    };
  },
});
</script>
