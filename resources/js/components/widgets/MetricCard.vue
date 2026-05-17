<script setup lang="ts">
import type { ControlPanelWidget } from '@/types/control-panel';
import { Minus, TrendingDown, TrendingUp } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{ widget: ControlPanelWidget }>();

const data = computed(
    () => (props.widget.data ?? {}) as Record<string, unknown>,
);

const trendIcon = computed(() => {
    const trend = data.value.trend as string | undefined;
    if (trend === 'up') return TrendingUp;
    if (trend === 'down') return TrendingDown;
    return Minus;
});

const trendClass = computed(() => {
    const trend = data.value.trend as string | undefined;
    if (trend === 'up') return 'text-green-600 dark:text-green-400';
    if (trend === 'down') return 'text-red-600 dark:text-red-400';
    return 'text-muted-foreground';
});
</script>

<template>
    <article
        class="widget widget--metric bg-card rounded-xl border p-5 shadow-sm"
    >
        <p class="text-muted-foreground text-sm font-medium">
            {{ widget.title ?? 'Metric' }}
        </p>
        <p class="mt-1 text-3xl font-bold tracking-tight">
            {{ String(data.value ?? '—') }}
        </p>
        <div
            v-if="data.change !== undefined"
            class="mt-2 flex items-center gap-1 text-sm"
            :class="trendClass"
        >
            <component :is="trendIcon" class="size-4" aria-hidden="true" />
            <span>{{ String(data.change) }}</span>
        </div>
        <p v-if="widget.description" class="text-muted-foreground mt-1 text-xs">
            {{ widget.description }}
        </p>
    </article>
</template>
