<script setup lang="ts">
import type { ChartData, ControlPanelWidget } from '@/types/control-panel';
import { computed } from 'vue';

const props = defineProps<{ widget: ControlPanelWidget }>();

const chart = computed(
    () => (props.widget.data ?? { labels: [], datasets: [] }) as ChartData,
);

const COLORS = ['#6366f1', '#22c55e', '#f59e0b', '#ec4899', '#14b8a6'];
const PAD = { top: 16, right: 16, bottom: 36, left: 44 };
const W = 480;
const H = 220;
const innerW = W - PAD.left - PAD.right;
const innerH = H - PAD.top - PAD.bottom;

const allValues = computed(() =>
    chart.value.datasets
        .flatMap((d) => d.data)
        .filter((v) => typeof v === 'number'),
);
const maxVal = computed(() => Math.max(1, ...allValues.value));

const labelCount = computed(() => chart.value.labels.length || 1);
const groupWidth = computed(() => innerW / labelCount.value);
const dsCount = computed(() => chart.value.datasets.length || 1);
const barWidth = computed(() =>
    Math.max(4, (groupWidth.value / dsCount.value) * 0.7),
);

function barX(groupIdx: number, dsIdx: number) {
    const groupStart = PAD.left + groupIdx * groupWidth.value;
    const groupCenter = groupStart + groupWidth.value / 2;
    const totalBarsWidth =
        barWidth.value * dsCount.value + (dsCount.value - 1) * 2;
    const firstBarStart = groupCenter - totalBarsWidth / 2;
    return firstBarStart + dsIdx * (barWidth.value + 2);
}

function barH(v: number) {
    return (v / maxVal.value) * innerH;
}

function barY(v: number) {
    return PAD.top + innerH - barH(v);
}

const yTicks = computed(() => {
    const count = 4;
    return Array.from({ length: count + 1 }, (_, i) => {
        const v = (i / count) * maxVal.value;
        const y = PAD.top + innerH - (v / maxVal.value) * innerH;
        return { v: Math.round(v), y };
    });
});
</script>

<template>
    <article
        class="widget widget--bar-chart bg-card rounded-xl border p-5 shadow-sm"
    >
        <p class="text-muted-foreground mb-3 text-sm font-medium">
            {{ widget.title ?? 'Bar Chart' }}
        </p>
        <div
            v-if="!chart.datasets.length"
            class="text-muted-foreground flex h-32 items-center justify-center text-xs"
        >
            No data
        </div>
        <svg
            v-else
            :viewBox="`0 0 ${W} ${H}`"
            class="w-full"
            aria-label="bar chart"
            role="img"
        >
            <!-- Y-axis grid lines + labels -->
            <g v-for="tick in yTicks" :key="tick.y">
                <line
                    :x1="PAD.left"
                    :x2="PAD.left + innerW"
                    :y1="tick.y"
                    :y2="tick.y"
                    stroke="currentColor"
                    stroke-opacity="0.1"
                    stroke-width="1"
                />
                <text
                    :x="PAD.left - 6"
                    :y="tick.y + 4"
                    text-anchor="end"
                    class="fill-muted-foreground"
                    font-size="10"
                >
                    {{ tick.v }}
                </text>
            </g>

            <!-- X-axis labels -->
            <text
                v-for="(label, i) in chart.labels"
                :key="i"
                :x="PAD.left + i * groupWidth + groupWidth / 2"
                :y="PAD.top + innerH + 20"
                text-anchor="middle"
                class="fill-muted-foreground"
                font-size="10"
            >
                {{ label }}
            </text>

            <!-- Bars -->
            <g v-for="(dataset, di) in chart.datasets" :key="di">
                <rect
                    v-for="(v, i) in dataset.data"
                    :key="i"
                    :x="barX(i, di)"
                    :y="barY(v)"
                    :width="barWidth"
                    :height="barH(v)"
                    :fill="COLORS[di % COLORS.length]"
                    rx="2"
                >
                    <title>{{ dataset.label }}: {{ v }}</title>
                </rect>
            </g>
        </svg>

        <!-- Legend -->
        <div v-if="chart.datasets.length > 1" class="mt-2 flex flex-wrap gap-3">
            <div
                v-for="(dataset, di) in chart.datasets"
                :key="di"
                class="flex items-center gap-1 text-xs"
            >
                <span
                    class="inline-block size-2.5 rounded-full"
                    :style="{ background: COLORS[di % COLORS.length] }"
                />
                <span class="text-muted-foreground">{{ dataset.label }}</span>
            </div>
        </div>
    </article>
</template>
