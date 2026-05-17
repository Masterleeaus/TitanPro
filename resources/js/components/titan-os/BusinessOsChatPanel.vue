<template>
  <section class="business-os-panel flex flex-col h-full">
    <!-- Header -->
    <header class="business-os-panel__header flex items-center justify-between px-3 py-2 border-b bg-white dark:bg-gray-900">
      <h2 class="text-sm font-semibold truncate">{{ title }}</h2>
      <span v-if="state.activeThreadId" class="text-xs text-gray-400 truncate ml-2">
        Thread: {{ state.activeThreadId }}
      </span>
    </header>

    <!-- Body: chat + widget workspace -->
    <div class="business-os-panel__body flex flex-1 overflow-hidden">

      <!-- Chat column -->
      <aside class="business-os-panel__chat flex flex-col flex-1 overflow-hidden">

        <!-- Message list -->
        <div ref="messageListRef" class="business-os-panel__messages flex-1 overflow-y-auto p-2 space-y-1">
          <div
            v-for="message in state.messages"
            :key="message.id"
            :class="['titan-zero-message p-2 my-1 rounded text-sm',
              message.role === 'user'
                ? 'titan-zero-message-user bg-blue-50 dark:bg-blue-900 self-end ml-4'
                : 'titan-zero-message-assistant bg-gray-100 dark:bg-gray-800 mr-4']"
          >
            <strong class="block text-xs text-gray-500 dark:text-gray-400 mb-0.5 capitalize">{{ message.role }}</strong>
            {{ message.content }}
          </div>
          <div v-if="state.loading" class="titan-zero-message titan-zero-message-assistant p-2 my-1 text-sm text-gray-400 italic">
            Thinking…
          </div>
          <div v-if="state.error" class="titan-zero-message titan-zero-message-error p-2 my-1 text-sm text-red-500">
            {{ state.error }}
          </div>
        </div>

        <!-- Suggestion chips -->
        <div v-if="state.suggestions.length > 0" class="flex flex-wrap gap-1 px-2 pb-1">
          <button
            v-for="suggestion in state.suggestions"
            :key="suggestion"
            type="button"
            class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600"
            @click="inputText = suggestion"
          >
            {{ suggestion }}
          </button>
        </div>

        <!-- Composer -->
        <div class="business-os-panel__composer p-2 border-t bg-white dark:bg-gray-900 flex items-end space-x-2">
          <textarea
            v-model="inputText"
            rows="1"
            placeholder="Ask the chatbot to run your business…"
            class="flex-1 resize-none border rounded p-2 text-sm dark:bg-gray-800 dark:border-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500"
            @keydown.ctrl.enter.prevent="submit"
            @keydown.meta.enter.prevent="submit"
          />
          <button
            type="button"
            :disabled="state.loading || !inputText.trim()"
            class="bg-blue-600 text-white px-3 py-2 rounded text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-blue-700"
            @click="submit"
          >
            Send
          </button>
        </div>
      </aside>

      <!-- Widget workspace (only shown when widgets exist) -->
      <main
        v-if="visibleWidgets.length > 0"
        class="business-os-panel__workspace border-l w-64 flex-shrink-0 overflow-hidden"
      >
        <ControlPanelRenderer :widgets="visibleWidgets" />
      </main>
    </div>
  </section>
</template>

<script setup lang="ts">
import { computed, nextTick, onMounted, reactive, ref } from 'vue';
import type { BusinessOsPanelState, ChatMessage, ControlPanelWidget } from '@/types/control-panel-schema';
import { BusinessOsApi } from '@/services/business-os-api';
import ControlPanelRenderer from './ControlPanelRenderer.vue';

const props = withDefaults(defineProps<{
  initialThreadId?: string;
  title?: string;
}>(), {
  title: 'Business OS',
});

const api = new BusinessOsApi();

const state = reactive<BusinessOsPanelState>({
  messages: [],
  widgets: [],
  suggestions: [],
  loading: false,
  activeThreadId: props.initialThreadId,
});

const inputText = ref('');
const messageListRef = ref<HTMLElement | null>(null);

/** All widgets from the global state plus any inline message widgets */
const visibleWidgets = computed<ControlPanelWidget[]>(() => {
  const messageWidgets = state.messages.flatMap((m) => m.widgets ?? []);
  return mergeWidgets(state.widgets, messageWidgets);
});

/** Scroll the message list to the bottom */
async function scrollToBottom() {
  await nextTick();
  if (messageListRef.value) {
    messageListRef.value.scrollTop = messageListRef.value.scrollHeight;
  }
}

/** Merge widget arrays, deduplicating by id (later wins) */
function mergeWidgets(existing: ControlPanelWidget[], incoming: ControlPanelWidget[]): ControlPanelWidget[] {
  const map = new Map<string, ControlPanelWidget>();
  for (const w of existing) map.set(w.id, w);
  for (const w of incoming) map.set(w.id, w);
  return Array.from(map.values());
}

/** Send the current input to the backend */
async function submit() {
  const trimmed = inputText.value.trim();
  if (!trimmed || state.loading) return;

  const userMessage: ChatMessage = {
    id: `user-${Date.now()}`,
    role: 'user',
    content: trimmed,
    createdAt: new Date().toISOString(),
  };

  state.messages.push(userMessage);
  state.loading = true;
  state.error = undefined;
  inputText.value = '';
  await scrollToBottom();

  try {
    const response = await api.sendChatMessage({
      threadId: state.activeThreadId,
      message: trimmed,
      context: window.titanOsContext ?? undefined,
    });

    const assistantMessage: ChatMessage = {
      id: `assistant-${Date.now()}`,
      role: 'assistant',
      content: response.message ?? '',
      createdAt: new Date().toISOString(),
      widgets: response.parts,
    };

    state.messages.push(assistantMessage);
    state.widgets = mergeWidgets(state.widgets, response.parts ?? []);

    const threadId = response.meta?.['threadId'];
    if (typeof threadId === 'string' && threadId.length > 0) {
      state.activeThreadId = threadId;
    }

    if (Array.isArray(response.meta?.['suggestions'])) {
      state.suggestions = response.meta['suggestions'] as string[];
    }
  } catch (error) {
    state.error = error instanceof Error ? error.message : 'Unknown error';
  } finally {
    state.loading = false;
    await scrollToBottom();
  }
}

/** Fetch suggestions from the API on mount */
async function loadSuggestions() {
  try {
    const suggestions = await api.fetchSuggestions(state.activeThreadId);
    if (suggestions.length > 0) {
      state.suggestions = suggestions;
    }
  } catch {
    // Suggestions are non-critical; silently ignore errors
  }
}

onMounted(() => {
  loadSuggestions();
});
</script>
