<script setup lang="ts">
import type { ChatMessage, ControlPanelWidget } from '@/types/control-panel';
import { computed } from 'vue';

const props = defineProps<{ widget: ControlPanelWidget }>();

const messages = computed(() =>
    Array.isArray(props.widget.data)
        ? (props.widget.data as ChatMessage[])
        : [],
);

function roleLabel(role: string): string {
    const map: Record<string, string> = {
        user: 'You',
        assistant: 'Assistant',
        system: 'System',
        tool: 'Tool',
    };
    return map[role] ?? role;
}

function bubbleClass(role: string): string {
    if (role === 'user') return 'ml-auto bg-primary text-primary-foreground';
    if (role === 'system')
        return 'bg-muted text-muted-foreground text-xs italic';
    if (role === 'tool')
        return 'bg-yellow-50 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300 font-mono text-xs';
    return 'bg-muted text-foreground';
}
</script>

<template>
    <article class="widget widget--chat bg-card rounded-xl border shadow-sm">
        <div class="px-5 pt-5 pb-3">
            <p class="text-muted-foreground text-sm font-medium">
                {{ widget.title ?? 'Chat Thread' }}
            </p>
        </div>
        <div
            v-if="messages.length === 0"
            class="text-muted-foreground px-5 pb-5 text-xs"
        >
            No messages
        </div>
        <div
            v-else
            class="flex max-h-72 flex-col gap-2 overflow-y-auto px-5 pb-5"
        >
            <div
                v-for="msg in messages"
                :key="msg.id"
                class="flex max-w-[85%] flex-col gap-0.5"
                :class="
                    msg.role === 'user'
                        ? 'items-end self-end'
                        : 'items-start self-start'
                "
            >
                <span class="text-muted-foreground text-[10px]">{{
                    roleLabel(msg.role)
                }}</span>
                <div
                    class="rounded-2xl px-3 py-2 text-sm leading-snug"
                    :class="bubbleClass(msg.role)"
                >
                    {{ msg.content }}
                </div>
                <span
                    v-if="msg.createdAt"
                    class="text-muted-foreground text-[10px]"
                    >{{ msg.createdAt }}</span
                >
            </div>
        </div>
    </article>
</template>
