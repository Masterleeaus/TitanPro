<script setup lang="ts">
import PlatformLayout from '@/layouts/PlatformLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

type Module = {
    name: string;
    alias: string;
    enabled: boolean;
    version: string | null;
    description: string | null;
};

type ManifestPayload = Record<string, unknown> | null;

const props = defineProps<{
    modules: Module[];
}>();

const modules = ref<Module[]>([...props.modules]);
const loading = ref<Record<string, boolean>>({});
const syncing = ref(false);
const selectedManifest = ref<{ name: string; data: ManifestPayload } | null>(null);
const manifestLoading = ref(false);

async function toggleModule(mod: Module): Promise<void> {
    loading.value[mod.name] = true;
    const action = mod.enabled ? 'disable' : 'enable';
    try {
        const res = await fetch(`/admin/titan/modules/${encodeURIComponent(mod.name)}/${action}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content ?? '',
                'Accept': 'application/json',
            },
        });
        if (res.ok) {
            const idx = modules.value.findIndex((m) => m.name === mod.name);
            if (idx !== -1) {
                modules.value[idx] = { ...modules.value[idx], enabled: !mod.enabled };
            }
        }
    } finally {
        loading.value[mod.name] = false;
    }
}

async function syncModules(): Promise<void> {
    syncing.value = true;
    try {
        const res = await fetch('/admin/titan/modules/sync', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content ?? '',
                'Accept': 'application/json',
            },
        });
        if (res.ok) {
            const json = await res.json();
            if (Array.isArray(json.data)) {
                modules.value = json.data as Module[];
            }
        }
    } finally {
        syncing.value = false;
    }
}

async function viewManifest(mod: Module): Promise<void> {
    manifestLoading.value = true;
    selectedManifest.value = null;
    try {
        const res = await fetch(`/admin/titan/modules/${encodeURIComponent(mod.name)}/manifests`, {
            headers: {
                'Accept': 'application/json',
            },
        });
        if (res.ok) {
            const json = await res.json();
            selectedManifest.value = { name: mod.name, data: json.manifest as ManifestPayload };
        }
    } finally {
        manifestLoading.value = false;
    }
}

async function viewHealth(mod: Module): Promise<void> {
    loading.value[`health_${mod.name}`] = true;
    try {
        const res = await fetch(`/admin/titan/modules/${encodeURIComponent(mod.name)}/health`, {
            headers: {
                'Accept': 'application/json',
            },
        });
        if (res.ok) {
            const json = await res.json();
            selectedManifest.value = { name: `${mod.name} — Health`, data: json.health as ManifestPayload };
        }
    } finally {
        loading.value[`health_${mod.name}`] = false;
    }
}

function statusClass(enabled: boolean): string {
    return enabled
        ? 'inline-block rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700'
        : 'inline-block rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-500';
}
</script>

<template>
    <PlatformLayout title="Module Admin">
        <Head title="Module Admin" />

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Module Admin</h1>
                <p class="mt-1 text-sm text-slate-500">View, enable, disable, and sync platform modules.</p>
            </div>
            <div class="flex gap-3">
                <a href="/platform/modules/audit-log" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Audit log
                </a>
                <button
                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                    :disabled="syncing"
                    @click="syncModules"
                >
                    {{ syncing ? 'Syncing…' : 'Sync manifests' }}
                </button>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Module</th>
                        <th class="px-5 py-3">Alias</th>
                        <th class="px-5 py-3">Version</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr v-if="modules.length === 0">
                        <td colspan="5" class="px-5 py-6 text-center text-slate-400">No modules found.</td>
                    </tr>
                    <tr v-for="mod in modules" :key="mod.name" class="hover:bg-slate-50">
                        <td class="px-5 py-3">
                            <div class="font-medium text-slate-900">{{ mod.name }}</div>
                            <div v-if="mod.description" class="mt-0.5 text-xs text-slate-400">{{ mod.description }}</div>
                        </td>
                        <td class="px-5 py-3 font-mono text-slate-600">{{ mod.alias }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ mod.version ?? '—' }}</td>
                        <td class="px-5 py-3">
                            <span :class="statusClass(mod.enabled)">{{ mod.enabled ? 'Enabled' : 'Disabled' }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex flex-wrap gap-2">
                                <button
                                    class="rounded-md px-3 py-1 text-xs font-medium transition-colors disabled:opacity-50"
                                    :class="mod.enabled
                                        ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200'
                                        : 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200'"
                                    :disabled="!!loading[mod.name]"
                                    @click="toggleModule(mod)"
                                >
                                    {{ loading[mod.name] ? '…' : (mod.enabled ? 'Disable' : 'Enable') }}
                                </button>
                                <button
                                    class="rounded-md bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700 hover:bg-slate-200 disabled:opacity-50"
                                    :disabled="manifestLoading"
                                    @click="viewManifest(mod)"
                                >
                                    Manifest
                                </button>
                                <button
                                    class="rounded-md bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700 hover:bg-slate-200 disabled:opacity-50"
                                    :disabled="!!loading[`health_${mod.name}`]"
                                    @click="viewHealth(mod)"
                                >
                                    {{ loading[`health_${mod.name}`] ? '…' : 'Health' }}
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Manifest / Health drawer -->
        <div v-if="selectedManifest" class="mt-6 rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <h2 class="font-semibold text-slate-900">{{ selectedManifest.name }}</h2>
                <button class="text-sm text-slate-500 hover:text-slate-900" @click="selectedManifest = null">Close ✕</button>
            </div>
            <pre class="overflow-x-auto p-5 text-xs text-slate-700">{{ JSON.stringify(selectedManifest.data, null, 2) }}</pre>
        </div>
    </PlatformLayout>
</template>
