import { createApp } from 'vue';
import BusinessOsChatPanel from '@/components/titan-os/BusinessOsChatPanel.vue';

const mountEl = document.getElementById('titan-zero-panel-root');
if (mountEl) {
    const threadId = mountEl.dataset['threadId'] ?? undefined;
    const title = mountEl.dataset['title'] ?? undefined;

    createApp(BusinessOsChatPanel, {
        initialThreadId: threadId,
        title: title,
    }).mount(mountEl);
}
