<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type AppPageProps } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

/** Canonical platform-default widget set — matches UiStudio's `$allWidgets`. */
const PLATFORM_DEFAULT_WIDGETS = [
    { type: 'kpi-grid-card',        label: 'KPI Grid' },
    { type: 'stat-card',            label: 'Stat Card' },
    { type: 'recent-activity-card', label: 'Recent Activity' },
    { type: 'alert-notice-card',    label: 'Alert / Notice' },
    { type: 'chart-bar-card',       label: 'Bar Chart' },
    { type: 'chart-line-card',      label: 'Line Chart' },
    { type: 'map-card',             label: 'Live Map' },
    { type: 'table-card',           label: 'Data Table' },
] as const;

type DashboardWidget = { type: string; label: string };

const page = usePage<AppPageProps>();

/**
 * Returns the ordered list of widgets to display.
 *
 * - When `role_ui.widget_layout` is present and non-empty, only the listed
 *   types are shown, in the order they appear in the array.
 * - Otherwise the full platform-default set is returned.
 * - Unknown types in `widget_layout` are silently skipped.
 */
const visibleWidgets = computed((): DashboardWidget[] => {
    const layout = page.props.role_ui?.widget_layout ?? [];

    if (layout.length === 0) {
        return [...PLATFORM_DEFAULT_WIDGETS];
    }

    return layout
        .map((type) => PLATFORM_DEFAULT_WIDGETS.find((w) => w.type === type))
        .filter((w): w is DashboardWidget => w !== undefined);
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <div class="p-6 space-y-4">
            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Dashboard</h1>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div
                    v-for="widget in visibleWidgets"
                    :key="widget.type"
                    :data-widget-type="widget.type"
                    class="overflow-hidden bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-4"
                >
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ widget.label }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
