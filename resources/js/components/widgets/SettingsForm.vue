<script setup lang="ts">
import type { ControlPanelWidget } from '@/types/control-panel';
import { computed, reactive } from 'vue';

const props = defineProps<{ widget: ControlPanelWidget }>();
const emit = defineEmits<{ save: [values: Record<string, unknown>] }>();

type FieldDef = {
    type?: string;
    label?: string;
    value?: unknown;
    options?: string[];
    placeholder?: string;
};

const fieldDefs = computed(
    () => (props.widget.props ?? {}) as Record<string, FieldDef>,
);

const values = reactive<Record<string, unknown>>({});

// Initialise from defaults
Object.entries(fieldDefs.value).forEach(([key, def]) => {
    values[key] = def.value ?? (def.type === 'boolean' ? false : '');
});

function fieldType(def: FieldDef): string {
    const t = def.type?.toLowerCase();
    if (t === 'boolean' || t === 'toggle' || t === 'checkbox') return 'boolean';
    if (t === 'number') return 'number';
    if (t === 'select') return 'select';
    return 'text';
}

function handleSubmit() {
    emit('save', { ...values });
}
</script>

<template>
    <article
        class="widget widget--settings bg-card rounded-xl border shadow-sm"
    >
        <div class="px-5 pt-5 pb-3">
            <p class="text-muted-foreground text-sm font-medium">
                {{ widget.title ?? 'Settings' }}
            </p>
        </div>
        <div
            v-if="Object.keys(fieldDefs).length === 0"
            class="text-muted-foreground px-5 pb-5 text-xs"
        >
            No fields defined
        </div>
        <form v-else class="divide-y" @submit.prevent="handleSubmit">
            <div
                v-for="(def, key) in fieldDefs"
                :key="key"
                class="flex items-center justify-between px-5 py-3"
            >
                <label :for="`sf-${key}`" class="text-sm font-medium">
                    {{ def.label ?? key }}
                </label>

                <!-- Boolean toggle -->
                <input
                    v-if="fieldType(def) === 'boolean'"
                    :id="`sf-${key}`"
                    type="checkbox"
                    :checked="Boolean(values[key])"
                    class="accent-primary size-4 cursor-pointer rounded"
                    @change="
                        values[key] = (
                            $event.target as HTMLInputElement
                        ).checked
                    "
                />

                <!-- Select -->
                <select
                    v-else-if="fieldType(def) === 'select'"
                    :id="`sf-${key}`"
                    :value="String(values[key] ?? '')"
                    class="bg-background rounded-md border px-2 py-1 text-sm"
                    @change="
                        values[key] = ($event.target as HTMLSelectElement).value
                    "
                >
                    <option
                        v-for="opt in def.options ?? []"
                        :key="opt"
                        :value="opt"
                    >
                        {{ opt }}
                    </option>
                </select>

                <!-- Number -->
                <input
                    v-else-if="fieldType(def) === 'number'"
                    :id="`sf-${key}`"
                    type="number"
                    :value="Number(values[key])"
                    :placeholder="def.placeholder"
                    class="bg-background w-32 rounded-md border px-2 py-1 text-sm"
                    @input="
                        values[key] = Number(
                            ($event.target as HTMLInputElement).value,
                        )
                    "
                />

                <!-- Text (default) -->
                <input
                    v-else
                    :id="`sf-${key}`"
                    type="text"
                    :value="String(values[key] ?? '')"
                    :placeholder="def.placeholder"
                    class="bg-background w-48 rounded-md border px-2 py-1 text-sm"
                    @input="
                        values[key] = ($event.target as HTMLInputElement).value
                    "
                />
            </div>

            <div class="px-5 py-4">
                <button
                    type="submit"
                    class="bg-primary text-primary-foreground hover:bg-primary/90 rounded-md px-4 py-1.5 text-sm font-medium"
                >
                    Save
                </button>
            </div>
        </form>
    </article>
</template>
