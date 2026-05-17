<script setup lang="ts">
import type { ControlPanelWidget } from '@/types/control-panel';

defineProps<{ widget: ControlPanelWidget }>();
</script>

<template>
    <article
        class="widget widget--json bg-card rounded-xl border p-5 shadow-sm"
    >
        <p class="text-muted-foreground mb-2 text-sm font-medium">
            {{ widget.title ?? widget.kind }}
        </p>
        <p v-if="widget.description" class="text-muted-foreground mb-2 text-xs">
            {{ widget.description }}
        </p>
        <pre class="bg-muted overflow-auto rounded-md p-3 text-xs">{{
            JSON.stringify(widget.data ?? widget.props ?? {}, null, 2)
        }}</pre>
    </article>
</template>
