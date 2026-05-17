<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'

type Message = { id: number; sender: string; text: string; created_at: string }
type Conversation = { id: number; channel: string; external_ref: string; messages: Message[] }

const props = defineProps<{
    conversations: Conversation[]
    graphqlEndpoint: string
}>()

const conversations = ref<Conversation[]>(props.conversations ?? [])
const activeConversationId = ref<number | null>(conversations.value[0]?.id ?? null)
const replyText = ref('')

const activeConversation = computed(() =>
    conversations.value.find((conversation) => conversation.id === activeConversationId.value) ?? null,
)

onMounted(() => {
    if (!window.WebSocket) return
    const wsUrl = props.graphqlEndpoint.replace(/^http/i, 'ws')
    const socket = new WebSocket(wsUrl)
    socket.onopen = () => {
        socket.send(JSON.stringify({ type: 'connection_init', payload: {} }))
    }
})

async function sendReply(): Promise<void> {
    if (!activeConversation.value || !replyText.value.trim()) return
    await fetch(`/aiconverse/team-inbox/${activeConversation.value.id}/reply`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? '',
        },
        body: JSON.stringify({ message: replyText.value }),
    })
    activeConversation.value.messages.push({
        id: Date.now(),
        sender: 'bot',
        text: replyText.value,
        created_at: new Date().toISOString(),
    })
    replyText.value = ''
}
</script>

<template>
    <div class="grid gap-4 p-4 md:grid-cols-[320px_1fr]">
        <aside class="rounded border p-3">
            <h1 class="mb-3 text-lg font-semibold">TitanTalk Team Inbox</h1>
            <button
                v-for="conversation in conversations"
                :key="conversation.id"
                class="mb-2 block w-full rounded border p-2 text-left"
                @click="activeConversationId = conversation.id"
            >
                <div class="text-xs uppercase text-gray-500">{{ conversation.channel }}</div>
                <div class="font-medium">{{ conversation.external_ref }}</div>
            </button>
        </aside>

        <section class="rounded border p-3">
            <div v-if="activeConversation" class="space-y-2">
                <div
                    v-for="message in activeConversation.messages"
                    :key="message.id"
                    class="rounded border p-2"
                >
                    <div class="text-xs uppercase text-gray-500">{{ message.sender }}</div>
                    <div>{{ message.text }}</div>
                </div>

                <div class="mt-3 flex gap-2">
                    <input v-model="replyText" class="w-full rounded border p-2" placeholder="Reply…" />
                    <button class="rounded bg-black px-3 py-2 text-white" @click="sendReply">Send</button>
                </div>
            </div>
        </section>
    </div>
</template>
