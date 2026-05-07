<script setup lang="ts">
import PlatformLayout from '@/layouts/PlatformLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import {
    cloneLayoutTokens,
    extractLayoutTokens,
    layoutPreviewStyles,
    layoutTokenDefaults,
    mergeLayoutTokenCss,
    sanitizeLayoutTokens,
    snapValue,
    type LayoutTokens,
} from './layoutTokens';

type PlatformSettings = {
    app_name: string;
    logo_url: string | null;
    favicon_url: string | null;
    primary_color: string;
    support_email: string | null;
    footer_text: string | null;
    custom_css: string | null;
};

const props = defineProps<{
    settings: PlatformSettings;
}>();

const page = usePage();
const flashSuccess = computed(() => (page.props.flash as { success?: string } | undefined)?.success);

const logoPreview = ref<string | null>(props.settings.logo_url);
const faviconPreview = ref<string | null>(props.settings.favicon_url);

const form = useForm({
    app_name: props.settings.app_name ?? 'FieldOps Hub',
    primary_color: props.settings.primary_color ?? '#2563eb',
    support_email: props.settings.support_email ?? '',
    footer_text: props.settings.footer_text ?? '',
    custom_css: props.settings.custom_css ?? '',
    logo: null as File | null,
    favicon: null as File | null,
    remove_logo: false,
    remove_favicon: false,
});

const previewGrid = ref<HTMLElement | null>(null);
const showGridOverlay = ref(true);
const snapSize = ref<4 | 8>(8);
const layoutTokens = ref<LayoutTokens>(extractLayoutTokens(props.settings.custom_css));
const history = ref<LayoutTokens[]>([cloneLayoutTokens(layoutTokens.value)]);
const historyIndex = ref(0);
const isDragging = ref(false);
const isApplyingHistory = ref(false);

type DragTarget = 'sidebar' | 'content' | 'primary-card' | 'secondary-card' | 'rows';

type DragState = {
    target: DragTarget;
    startX: number;
    startY: number;
    startTokens: LayoutTokens;
};

const dragState = ref<DragState | null>(null);
const canUndo = computed(() => historyIndex.value > 0);
const canRedo = computed(() => historyIndex.value < history.value.length - 1);
const previewVars = computed(() => layoutPreviewStyles(layoutTokens.value));
const tokenSummary = computed(() => [
    ['--grid-columns', String(layoutTokens.value.gridColumns)],
    ['--sidebar-width', `${layoutTokens.value.sidebarWidth}px`],
    ['--content-max-width', `${layoutTokens.value.contentMaxWidth}px`],
    ['--card-min-height', `${layoutTokens.value.cardMinHeight}px`],
    ['--layout-section-gap', `${layoutTokens.value.sectionGap}px`],
]);

syncCustomCss();

function previewFile(event: Event, target: 'logo' | 'favicon'): void {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0] ?? null;

    if (target === 'logo') {
        form.logo = file;
        form.remove_logo = false;
        logoPreview.value = file ? URL.createObjectURL(file) : props.settings.logo_url;
        return;
    }

    form.favicon = file;
    form.remove_favicon = false;
    faviconPreview.value = file ? URL.createObjectURL(file) : props.settings.favicon_url;
}

function removeLogo(): void {
    form.logo = null;
    form.remove_logo = true;
    logoPreview.value = null;
}

function removeFavicon(): void {
    form.favicon = null;
    form.remove_favicon = true;
    faviconPreview.value = null;
}

function submit(): void {
    syncCustomCss();
    form.post('/platform/settings', {
        forceFormData: true,
        preserveScroll: true,
    });
}

function syncCustomCss(): void {
    form.custom_css = mergeLayoutTokenCss(form.custom_css, layoutTokens.value);
}

function applyTokens(tokens: Partial<LayoutTokens>, options: { pushHistory?: boolean } = {}): void {
    layoutTokens.value = sanitizeLayoutTokens(tokens);
    syncCustomCss();

    if (!options.pushHistory || isApplyingHistory.value) {
        return;
    }

    const snapshot = cloneLayoutTokens(layoutTokens.value);
    const current = history.value[historyIndex.value];

    if (JSON.stringify(current) === JSON.stringify(snapshot)) {
        return;
    }

    history.value = [...history.value.slice(0, historyIndex.value + 1), snapshot];
    historyIndex.value = history.value.length - 1;
}

function undo(): void {
    if (!canUndo.value) {
        return;
    }

    isApplyingHistory.value = true;
    historyIndex.value -= 1;
    layoutTokens.value = cloneLayoutTokens(history.value[historyIndex.value]);
    syncCustomCss();
    isApplyingHistory.value = false;
}

function redo(): void {
    if (!canRedo.value) {
        return;
    }

    isApplyingHistory.value = true;
    historyIndex.value += 1;
    layoutTokens.value = cloneLayoutTokens(history.value[historyIndex.value]);
    syncCustomCss();
    isApplyingHistory.value = false;
}

function resetLayout(): void {
    applyTokens(layoutTokenDefaults, { pushHistory: true });
}

function startDrag(target: DragTarget, event: PointerEvent): void {
    dragState.value = {
        target,
        startX: event.clientX,
        startY: event.clientY,
        startTokens: cloneLayoutTokens(layoutTokens.value),
    };
    isDragging.value = true;
    window.addEventListener('pointermove', handlePointerMove);
    window.addEventListener('pointerup', stopDrag);
}

function updateCardDimensions(startTokens: LayoutTokens, target: DragTarget, deltaX: number, deltaY: number): Partial<LayoutTokens> {
    const gridWidth = previewGrid.value?.clientWidth ?? 720;
    const gapWidth = 16 * (startTokens.gridColumns - 1);
    const columnWidth = Math.max((gridWidth - gapWidth) / startTokens.gridColumns, 1);
    const spanDelta = Math.round(deltaX / columnWidth);
    const minSpan = target === 'primary-card' ? 4 : 2;

    if (target === 'primary-card') {
        const primaryCardSpan = Math.min(
            Math.max(startTokens.primaryCardSpan + spanDelta, minSpan),
            startTokens.gridColumns - 2,
        );
        const primaryCardHeight = snapValue(startTokens.primaryCardHeight + deltaY, snapSize.value, 128, 480);

        return {
            primaryCardSpan,
            secondaryCardSpan: Math.max(startTokens.gridColumns - primaryCardSpan, 2),
            primaryCardHeight,
            cardMinHeight: Math.min(primaryCardHeight, startTokens.secondaryCardHeight),
        };
    }

    const secondaryCardSpan = Math.min(
        Math.max(startTokens.secondaryCardSpan + spanDelta, minSpan),
        startTokens.gridColumns - 2,
    );
    const secondaryCardHeight = snapValue(startTokens.secondaryCardHeight + deltaY, snapSize.value, 128, 480);

    return {
        secondaryCardSpan,
        primaryCardSpan: Math.max(startTokens.gridColumns - secondaryCardSpan, 4),
        secondaryCardHeight,
        cardMinHeight: Math.min(startTokens.primaryCardHeight, secondaryCardHeight),
    };
}

function handlePointerMove(event: PointerEvent): void {
    if (!dragState.value) {
        return;
    }

    const deltaX = event.clientX - dragState.value.startX;
    const deltaY = event.clientY - dragState.value.startY;
    const startTokens = dragState.value.startTokens;

    switch (dragState.value.target) {
        case 'sidebar':
            applyTokens({
                ...startTokens,
                sidebarWidth: snapValue(startTokens.sidebarWidth + deltaX, snapSize.value, 192, 384),
            });
            return;
        case 'content':
            applyTokens({
                ...startTokens,
                contentMaxWidth: snapValue(startTokens.contentMaxWidth + deltaX, snapSize.value, 720, 1440),
            });
            return;
        case 'rows':
            applyTokens({
                ...startTokens,
                sectionGap: snapValue(startTokens.sectionGap + deltaY, snapSize.value, 8, 96),
            });
            return;
        case 'primary-card':
        case 'secondary-card':
            applyTokens({
                ...startTokens,
                ...updateCardDimensions(startTokens, dragState.value.target, deltaX, deltaY),
            });
    }
}

function stopDrag(): void {
    window.removeEventListener('pointermove', handlePointerMove);
    window.removeEventListener('pointerup', stopDrag);

    if (dragState.value) {
        applyTokens(layoutTokens.value, { pushHistory: true });
    }

    dragState.value = null;
    isDragging.value = false;
}

function handleKeydown(event: KeyboardEvent): void {
    if (!(event.ctrlKey || event.metaKey)) {
        return;
    }

    if (event.key.toLowerCase() === 'z' && !event.shiftKey) {
        event.preventDefault();
        undo();
    }

    if (event.key.toLowerCase() === 'y' || (event.key.toLowerCase() === 'z' && event.shiftKey)) {
        event.preventDefault();
        redo();
    }
}

onMounted(() => {
    window.addEventListener('keydown', handleKeydown);
});

onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleKeydown);
    stopDrag();
});
</script>

<template>
    <PlatformLayout title="Platform Settings">
        <Head title="Platform Settings" />

        <div class="mx-auto max-w-5xl space-y-6">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Global SaaS branding</h2>
                <p class="mt-1 text-sm text-slate-500">Control the platform name, logo, favicon, support contact, and primary theme color.</p>

                <div v-if="flashSuccess" class="mt-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ flashSuccess }}
                </div>
            </div>

            <form class="grid grid-cols-1 gap-6 lg:grid-cols-3" @submit.prevent="submit">
                <div class="space-y-6 lg:col-span-2">
                    <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="font-semibold text-slate-900">Brand identity</h3>

                        <div class="mt-5 grid gap-5">
                            <label class="block text-sm">
                                <span class="font-medium text-slate-700">App name</span>
                                <input v-model="form.app_name" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2" required />
                                <span v-if="form.errors.app_name" class="mt-1 block text-xs text-rose-600">{{ form.errors.app_name }}</span>
                            </label>

                            <label class="block text-sm">
                                <span class="font-medium text-slate-700">Primary color</span>
                                <div class="mt-1 flex gap-3">
                                    <input v-model="form.primary_color" type="color" class="h-10 w-14 rounded-md border border-slate-300 p-1" />
                                    <input v-model="form.primary_color" class="w-full rounded-md border border-slate-300 px-3 py-2" placeholder="#2563eb" />
                                </div>
                                <span v-if="form.errors.primary_color" class="mt-1 block text-xs text-rose-600">{{ form.errors.primary_color }}</span>
                            </label>

                            <label class="block text-sm">
                                <span class="font-medium text-slate-700">Support email</span>
                                <input v-model="form.support_email" type="email" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2" placeholder="support@example.com" />
                                <span v-if="form.errors.support_email" class="mt-1 block text-xs text-rose-600">{{ form.errors.support_email }}</span>
                            </label>

                            <label class="block text-sm">
                                <span class="font-medium text-slate-700">Footer text</span>
                                <input v-model="form.footer_text" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2" placeholder="© TitanZero" />
                                <span v-if="form.errors.footer_text" class="mt-1 block text-xs text-rose-600">{{ form.errors.footer_text }}</span>
                            </label>
                        </div>
                    </section>

                    <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <h3 class="font-semibold text-slate-900">UI Studio grid editor</h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    Drag handles directly on the preview to resize the sidebar, content width, widget spans, card heights, and spacing between layout rows.
                                </p>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <label class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700">
                                    <input v-model="showGridOverlay" type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                                    Grid overlay
                                </label>

                                <label class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700">
                                    Snap
                                    <select v-model="snapSize" class="rounded-md border border-slate-200 bg-white px-2 py-1 text-sm">
                                        <option :value="4">4px</option>
                                        <option :value="8">8px</option>
                                    </select>
                                </label>

                                <button
                                    type="button"
                                    class="rounded-full border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
                                    :disabled="!canUndo"
                                    @click="undo"
                                >
                                    Undo
                                </button>
                                <button
                                    type="button"
                                    class="rounded-full border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
                                    :disabled="!canRedo"
                                    @click="redo"
                                >
                                    Redo
                                </button>
                                <button
                                    type="button"
                                    class="rounded-full border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50"
                                    @click="resetLayout"
                                >
                                    Reset layout
                                </button>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_18rem]">
                            <div>
                                <div
                                    class="relative overflow-hidden rounded-2xl border border-slate-200 bg-slate-950 p-4 shadow-inner sm:p-5"
                                    :style="previewVars"
                                >
                                    <div
                                        v-if="showGridOverlay"
                                        class="pointer-events-none absolute inset-4 rounded-xl opacity-70 sm:inset-5"
                                        :style="{ backgroundImage: 'linear-gradient(to right, rgba(99,102,241,0.15) 1px, transparent 1px), linear-gradient(to bottom, rgba(99,102,241,0.12) 1px, transparent 1px)', backgroundSize: `calc(100% / ${layoutTokens.gridColumns}) ${snapSize}px` }"
                                    />

                                    <div class="relative flex min-h-[480px] overflow-hidden rounded-xl border border-white/10 bg-slate-900/80">
                                        <aside
                                            class="relative shrink-0 border-r border-white/10 bg-slate-950/90 p-4 text-slate-200"
                                            :style="{ width: 'var(--sidebar-width)' }"
                                        >
                                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-indigo-300">Sidebar</p>
                                            <div class="mt-5 space-y-3">
                                                <div class="rounded-lg bg-white/5 px-3 py-2 text-sm">Overview</div>
                                                <div class="rounded-lg bg-white/5 px-3 py-2 text-sm">Layouts</div>
                                                <div class="rounded-lg bg-white/5 px-3 py-2 text-sm">Widgets</div>
                                                <div class="rounded-lg bg-white/5 px-3 py-2 text-sm">Tokens</div>
                                            </div>

                                            <button
                                                type="button"
                                                class="absolute inset-y-8 -right-2 flex w-4 cursor-ew-resize items-center justify-center rounded-full border border-indigo-300/30 bg-indigo-500/80 text-white shadow-lg"
                                                title="Drag to resize sidebar width"
                                                @pointerdown.prevent="startDrag('sidebar', $event)"
                                            >
                                                ⋮
                                            </button>
                                        </aside>

                                        <div class="relative flex min-w-0 flex-1 flex-col bg-slate-900/70">
                                            <div class="flex items-center justify-between border-b border-white/10 px-5 py-4 text-slate-100">
                                                <div>
                                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Live preview</p>
                                                    <h4 class="mt-1 text-lg font-semibold">Service operations dashboard</h4>
                                                </div>

                                                <button
                                                    type="button"
                                                    class="cursor-ew-resize rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-medium text-slate-200"
                                                    title="Drag to resize content width"
                                                    @pointerdown.prevent="startDrag('content', $event)"
                                                >
                                                    Resize content
                                                </button>
                                            </div>

                                            <div class="p-5">
                                                <div class="relative mx-auto w-full" :style="{ maxWidth: 'var(--content-max-width)' }">
                                                    <div
                                                        ref="previewGrid"
                                                        class="grid gap-4"
                                                        :style="{ gridTemplateColumns: `repeat(${layoutTokens.gridColumns}, minmax(0, 1fr))` }"
                                                    >
                                                        <article
                                                            class="relative overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-br from-indigo-500/20 via-slate-900 to-slate-950 p-4 text-slate-100 shadow-lg"
                                                            :style="{
                                                                gridColumn: `span ${layoutTokens.primaryCardSpan} / span ${layoutTokens.primaryCardSpan}`,
                                                                minHeight: 'var(--card-min-height)',
                                                                height: 'var(--widget-primary-height)',
                                                            }"
                                                        >
                                                            <p class="text-xs uppercase tracking-[0.3em] text-indigo-200">Primary widget</p>
                                                            <h5 class="mt-3 text-xl font-semibold">Revenue pulse</h5>
                                                            <p class="mt-2 max-w-sm text-sm text-slate-300">Drag the handle to change the widget width span and card height.</p>
                                                            <button
                                                                type="button"
                                                                class="absolute bottom-3 right-3 flex h-8 w-8 cursor-se-resize items-center justify-center rounded-full border border-indigo-300/40 bg-indigo-500/80 text-xs text-white shadow-lg"
                                                                title="Drag to resize widget"
                                                                @pointerdown.prevent="startDrag('primary-card', $event)"
                                                            >
                                                                ↘
                                                            </button>
                                                        </article>

                                                        <article
                                                            class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-4 text-slate-100 shadow-lg"
                                                            :style="{
                                                                gridColumn: `span ${layoutTokens.secondaryCardSpan} / span ${layoutTokens.secondaryCardSpan}`,
                                                                minHeight: 'var(--card-min-height)',
                                                                height: 'var(--widget-secondary-height)',
                                                            }"
                                                        >
                                                            <p class="text-xs uppercase tracking-[0.3em] text-cyan-200">Secondary widget</p>
                                                            <h5 class="mt-3 text-lg font-semibold">Activity stream</h5>
                                                            <p class="mt-2 text-sm text-slate-300">Snap-to-grid keeps layout changes aligned while you drag.</p>
                                                            <button
                                                                type="button"
                                                                class="absolute bottom-3 right-3 flex h-8 w-8 cursor-se-resize items-center justify-center rounded-full border border-cyan-300/40 bg-cyan-500/80 text-xs text-white shadow-lg"
                                                                title="Drag to resize widget"
                                                                @pointerdown.prevent="startDrag('secondary-card', $event)"
                                                            >
                                                                ↘
                                                            </button>
                                                        </article>
                                                    </div>

                                                    <button
                                                        type="button"
                                                        class="mx-auto mt-[var(--layout-section-gap)] flex h-7 w-28 cursor-ns-resize items-center justify-center rounded-full border border-dashed border-indigo-300/40 bg-indigo-500/20 text-xs font-medium text-indigo-100"
                                                        title="Drag to resize row spacing"
                                                        @pointerdown.prevent="startDrag('rows', $event)"
                                                    >
                                                        Resize rows
                                                    </button>

                                                    <div
                                                        class="mt-4 grid gap-4"
                                                        :style="{ gridTemplateColumns: `repeat(${layoutTokens.gridColumns}, minmax(0, 1fr))` }"
                                                    >
                                                        <article
                                                            class="col-span-full rounded-2xl border border-dashed border-white/10 bg-white/[0.03] p-4 text-sm text-slate-300"
                                                            :style="{ minHeight: `calc(var(--card-min-height) - 32px)` }"
                                                        >
                                                            Section spacing and card heights update live and are persisted as design tokens inside the existing token storage layer.
                                                        </article>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <p class="mt-3 text-xs text-slate-500">
                                    Tip: drag any highlighted handle, then use Ctrl/Cmd+Z or Ctrl/Cmd+Y to undo or redo a full drag operation.
                                </p>
                            </div>

                            <aside class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <h4 class="text-sm font-semibold text-slate-900">Design token output</h4>
                                <p class="mt-1 text-xs text-slate-500">Saved to the existing <code>custom_css</code> token storage block.</p>

                                <dl class="mt-4 space-y-3 text-sm">
                                    <div v-for="[token, value] in tokenSummary" :key="token" class="rounded-xl border border-white bg-white px-3 py-2 shadow-sm">
                                        <dt class="font-mono text-xs text-slate-500">{{ token }}</dt>
                                        <dd class="mt-1 font-semibold text-slate-900">{{ value }}</dd>
                                    </div>
                                </dl>

                                <div class="mt-4 rounded-xl border border-dashed border-slate-300 bg-white px-3 py-3 text-xs text-slate-500">
                                    <p class="font-medium text-slate-700">Operation state</p>
                                    <p class="mt-1">Overlay: {{ showGridOverlay ? 'On' : 'Off' }}</p>
                                    <p>Snap: {{ snapSize }}px</p>
                                    <p>Dragging: {{ isDragging ? 'Yes' : 'No' }}</p>
                                </div>
                            </aside>
                        </div>
                    </section>

                    <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="font-semibold text-slate-900">Assets</h3>

                        <div class="mt-5 grid gap-6 sm:grid-cols-2">
                            <div class="rounded-lg border border-slate-200 p-4">
                                <div class="flex h-24 items-center justify-center rounded-md bg-slate-50">
                                    <img v-if="logoPreview" :src="logoPreview" alt="Logo preview" class="max-h-16 max-w-full object-contain" />
                                    <span v-else class="text-sm text-slate-400">No logo</span>
                                </div>
                                <label class="mt-4 block text-sm font-medium text-slate-700">Logo</label>
                                <input type="file" accept="image/*" class="mt-2 block w-full text-sm" @change="previewFile($event, 'logo')" />
                                <button type="button" class="mt-3 text-sm font-medium text-rose-600 hover:text-rose-700" @click="removeLogo">Remove logo</button>
                                <span v-if="form.errors.logo" class="mt-1 block text-xs text-rose-600">{{ form.errors.logo }}</span>
                            </div>

                            <div class="rounded-lg border border-slate-200 p-4">
                                <div class="flex h-24 items-center justify-center rounded-md bg-slate-50">
                                    <img v-if="faviconPreview" :src="faviconPreview" alt="Favicon preview" class="h-12 w-12 object-contain" />
                                    <span v-else class="text-sm text-slate-400">No favicon</span>
                                </div>
                                <label class="mt-4 block text-sm font-medium text-slate-700">Favicon</label>
                                <input type="file" accept="image/*" class="mt-2 block w-full text-sm" @change="previewFile($event, 'favicon')" />
                                <button type="button" class="mt-3 text-sm font-medium text-rose-600 hover:text-rose-700" @click="removeFavicon">Remove favicon</button>
                                <span v-if="form.errors.favicon" class="mt-1 block text-xs text-rose-600">{{ form.errors.favicon }}</span>
                            </div>
                        </div>
                    </section>
                </div>

                <aside class="space-y-6">
                    <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="font-semibold text-slate-900">Preview</h3>
                        <div class="mt-5 rounded-lg border border-slate-200 p-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg text-white" :style="{ backgroundColor: form.primary_color }">
                                    <img v-if="logoPreview" :src="logoPreview" alt="Logo preview" class="h-10 w-10 object-contain" />
                                    <span v-else class="font-bold">{{ form.app_name.slice(0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900">{{ form.app_name }}</p>
                                    <p class="text-xs text-slate-500">{{ form.support_email || 'No support email set' }}</p>
                                </div>
                            </div>
                            <p class="mt-4 text-sm text-slate-500">{{ form.footer_text || 'Footer text preview' }}</p>
                        </div>
                    </section>

                    <button type="submit" class="w-full rounded-lg bg-slate-950 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800" :disabled="form.processing">
                        {{ form.processing ? 'Saving…' : 'Save platform settings' }}
                    </button>
                </aside>
            </form>
        </div>
    </PlatformLayout>
</template>
