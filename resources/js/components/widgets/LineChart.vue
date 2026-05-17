<script setup lang="ts">
import type { ChartData, ControlPanelWidget } from '@/types/control-panel';
import { computed } from 'vue';

const props = defineProps<{ widget: ControlPanelWidget }>();

const chart = computed(
    () => (props.widget.data ?? { labels: [], datasets: [] }) as ChartData,
);

const COLORS = ['#6366f1', '#22c55e', '#f59e0b', '#ec4899', '#14b8a6'];
const PAD = { top: 16, right: 16, bottom: 32, left: 44 };
const W = 480;
const H = 220;
const innerW = W - PAD.left - PAD.right;
const innerH = H - PAD.top - PAD.bottom;

const allValues = computed(() =>
    chart.value.datasets
        .flatMap((d) => d.data)
        .filter((v) => typeof v === 'number'),
);

const minVal = computed(() => Math.min(0, ...allValues.value));
const maxVal = computed(() => Math.max(1, ...allValues.value));

function xPos(i: number, total: number) {
    if (total <= 1) return PAD.left + innerW / 2;
    return PAD.left + (i / (total - 1)) * innerW;
}

function yPos(v: number) {
    const range = maxVal.value - minVal.value || 1;
    return PAD.top + innerH - ((v - minVal.value) / range) * innerH;
}

function polyline(dataPoints: number[]) {
    return dataPoints
        .map((v, i) => `${xPos(i, dataPoints.length)},${yPos(v)}`)
        .join(' ');
}

const yTicks = computed(() => {
    const range = maxVal.value - minVal.value || 1;
    const count = 4;
    return Array.from({ length: count + 1 }, (_, i) => {
        const v = minVal.value + (i / count) * range;
        const y = yPos(v);
        return { v: Math.round(v), y };
    });
});
</script>

<template>
    <article
        class="widget widget--line-chart bg-card rounded-xl border p-5 shadow-sm"
    >
        <p class="text-muted-foreground mb-3 text-sm font-medium">
            {{ widget.title ?? 'Line Chart' }}
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
            aria-label="line chart"
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
                    class="fill-muted-foreground text-[10px]"
                    font-size="10"
                >
                    {{ tick.v }}
                </text>
            </g>

            <!-- X-axis labels -->
            <text
                v-for="(label, i) in chart.labels"
                :key="i"
                :x="xPos(i, chart.labels.length)"
                :y="PAD.top + innerH + 20"
                text-anchor="middle"
                class="fill-muted-foreground text-[10px]"
                font-size="10"
            >
                {{ label }}
            </text>

            <!-- Dataset lines -->
            <polyline
                v-for="(dataset, di) in chart.datasets"
                :key="di"
                :points="polyline(dataset.data)"
                fill="none"
                :stroke="COLORS[di % COLORS.length]"
                stroke-width="2"
                stroke-linejoin="round"
                stroke-linecap="round"
            />

            <!-- Dataset dots -->
            <g v-for="(dataset, di) in chart.datasets" :key="`dots-${di}`">
                <circle
                    v-for="(v, i) in dataset.data"
                    :key="i"
                    :cx="xPos(i, dataset.data.length)"
                    :cy="yPos(v)"
                    r="3"
                    :fill="COLORS[di % COLORS.length]"
                >
                    <title>{{ dataset.label }}: {{ v }}</title>
                </circle>
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
