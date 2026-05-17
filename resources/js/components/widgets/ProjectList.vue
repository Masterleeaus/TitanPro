<script setup lang="ts">
import type { ControlPanelWidget, ProjectItem } from '@/types/control-panel';
import { CalendarClock, User } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{ widget: ControlPanelWidget }>();
const emit = defineEmits<{ open: [project: ProjectItem] }>();

const projects = computed(() =>
    Array.isArray(props.widget.data)
        ? (props.widget.data as ProjectItem[])
        : [],
);

function statusClass(status: string): string {
    const s = status?.toLowerCase();
    if (s === 'completed' || s === 'done')
        return 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400';
    if (s === 'in progress' || s === 'active')
        return 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400';
    if (s === 'overdue') return 'bg-destructive/15 text-destructive';
    if (s === 'cancelled') return 'bg-muted text-muted-foreground line-through';
    return 'bg-muted text-muted-foreground';
}
</script>

<template>
    <article
        class="widget widget--project-list bg-card rounded-xl border shadow-sm"
    >
        <div class="px-5 pt-5 pb-3">
            <p class="text-muted-foreground text-sm font-medium">
                {{ widget.title ?? 'Projects' }}
            </p>
        </div>
        <div
            v-if="projects.length === 0"
            class="text-muted-foreground px-5 pb-5 text-xs"
        >
            No projects
        </div>
        <div v-else class="grid grid-cols-1 gap-3 p-5 sm:grid-cols-2">
            <button
                v-for="project in projects"
                :key="project.id"
                class="bg-muted/30 hover:bg-muted/60 focus-visible:ring-ring flex flex-col gap-2 rounded-lg border p-3 text-left transition-colors focus-visible:ring-2 focus-visible:outline-none"
                @click="emit('open', project)"
            >
                <div class="flex items-start justify-between gap-2">
                    <span class="truncate text-sm font-medium">{{
                        project.name
                    }}</span>
                    <span
                        class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-medium"
                        :class="statusClass(project.status)"
                    >
                        {{ project.status }}
                    </span>
                </div>
                <div class="text-muted-foreground flex flex-wrap gap-3 text-xs">
                    <span
                        v-if="project.assignee"
                        class="flex items-center gap-1"
                    >
                        <User class="size-3" />
                        {{ project.assignee }}
                    </span>
                    <span
                        v-if="project.dueDate"
                        class="flex items-center gap-1"
                    >
                        <CalendarClock class="size-3" />
                        {{ project.dueDate }}
                    </span>
                </div>
            </button>
        </div>
    </article>
</template>
