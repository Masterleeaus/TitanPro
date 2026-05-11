window.TitanRuntime = window.TitanRuntime || {};
window.TitanRuntime.dashboard = function (hub) {
    return {
        hub: hub || 'work',
        prompt: '',
        messages: [],
        quickPrompts: ['Show unassigned jobs', 'Create new job', 'What is overdue?', 'Who is available?'],
        canvas: { title: 'Canvas Ready', body: 'Assistant outputs, forms, previews, and generated artefacts render here.' },
        assistants: [
            { key: 'work-ai', label: 'Work AI', focus: 'Jobs & staffing', status: 'ready' },
            { key: 'memory-ai', label: 'Memory AI', focus: 'Site & job memory', status: 'ready' },
            { key: 'signal-ai', label: 'Signal AI', focus: 'Signal lifecycle', status: 'ready' }
        ],
        memory: {
            site_memory: { summary: 'Access notes, preferences, recurring instructions.' },
            job_memory: { summary: 'Recent job history and repeat-service context.' }
        },
        activeState: 'idle',
        activePrompt: 'No active request',
        async init() {},
        async sendPrompt(value = null) {
            const next = (value ?? this.prompt).trim();
            if (!next) return;
            const stamp = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            this.messages.unshift({ role: 'Titan Zero', body: 'Accepted: ' + next, time: stamp });
            this.messages.unshift({ role: 'You', body: next, time: stamp });
            this.activeState = 'processing';
            this.activePrompt = next;
            this.canvas.title = 'Tool Result';
            this.canvas.body = 'Prepared runtime output for: ' + next;
            this.assistants[0].status = 'processing';
            this.prompt = '';
        }
    };
};
