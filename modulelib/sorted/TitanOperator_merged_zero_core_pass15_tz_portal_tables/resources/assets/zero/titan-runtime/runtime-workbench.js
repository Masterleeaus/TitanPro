window.TitanRuntimeWorkbench = function (state) {
    return {
        hub: state.hub || 'work',
        chatbot: state.chatbot || { title: 'Titan Zero', first_message: 'Titan Zero is ready.' },
        prompt: '',
        messages: state.chat?.messages || [],
        quickPrompts: state.chat?.quick_prompts || ['Show unassigned jobs', 'Create new job', 'What is overdue?', 'Who is available?'],
        canvas: state.canvas || { title: 'Canvas Ready', body: 'Waiting...' },
        artifact: state.artifact || { title: 'Current Artefact', summary: 'No saved artefact yet.', type: 'placeholder' },
        assistants: state.assistants || [],
        memory: state.memory || { site_memory: { summary: 'Loading...' }, job_memory: { summary: 'Loading...' } },
        plugins: state.plugins || [],
        timeline: state.timeline || [],
        node: state.node || { node_key: 'local-web-node', trust: 0, status: 'unknown' },
        activeState: 'idle',
        activePrompt: 'No active request',
        activePlugin: null,

        async init() {
            try {
                const res = await fetch('/api/titan-runtime/boot', { headers: { 'Accept': 'application/json' } });
                const payload = await res.json();
                this.chatbot = payload.chatbot || this.chatbot;
                this.memory = payload.memory || this.memory;
                this.timeline = payload.timeline || this.timeline;
                this.plugins = payload.plugins || this.plugins;
                this.assistants = payload.assistants || this.assistants;
                this.node = payload.node || this.node;
                this.artifact = payload.artifact || this.artifact;

                if (!this.messages.length && this.chatbot.first_message) {
                    const stamp = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    this.messages.unshift({ role: this.chatbot.title || 'Titan Zero', body: this.chatbot.first_message, time: stamp });
                }
            } catch (e) {}
        },

        async sendPrompt(value = null) {
            const next = (value ?? this.prompt).trim();
            if (!next) return;

            const stamp = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            this.messages.unshift({ role: 'You', body: next, time: stamp });
            this.activeState = 'processing';
            this.activePrompt = next;

            try {
                const res = await window.TitanRuntimeAPI.dispatch(next);
                this.activePlugin = res.plugin?.label || null;
                this.messages.unshift({ role: this.chatbot.title || 'Titan Zero', body: (res.result?.body || res.result?.result || 'Done'), time: stamp });
                this.canvas.title = res.canvas?.title || 'Tool Result';
                this.canvas.body = res.canvas?.body || (res.result?.body || 'Prepared runtime output');
                this.artifact.summary = 'Latest generated output: ' + (res.result?.body || res.result?.result || 'Done');

                if (this.assistants.length) {
                    this.assistants[0].status = res.event?.state || 'processed';
                }
            } catch (e) {
                this.messages.unshift({ role: this.chatbot.title || 'Titan Zero', body: 'Runtime dispatch failed.', time: stamp });
            }

            this.prompt = '';
        }
    };
};
