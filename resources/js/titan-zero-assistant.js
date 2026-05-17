// Titan Zero Assistant behaviours
//
// This script provides basic chat functionality such as appending user
// messages to the conversation list and displaying generic suggestions.
// Actual AI integration is not included in this shell pass.

let cleanupAssistantListeners = null;

const initializeTitanZeroAssistant = () => {
    if (cleanupAssistantListeners) {
        cleanupAssistantListeners();
        cleanupAssistantListeners = null;
    }

    const panel = document.querySelector('#titan-zero-chat-panel');
    if (!panel) return;

    const messageList = panel.querySelector('[data-message-list]');
    const input = panel.querySelector('textarea');
    const form = panel.querySelector('[data-chat-form]');
    const suggestions = panel.querySelectorAll('[data-suggestion]');

    // Thread management elements
    const currentThreadTitleEl = panel.querySelector('[data-current-thread-title]');
    const currentThreadAppEl = panel.querySelector('[data-current-thread-app]');
    const threadDropdownToggle = panel.querySelector('[data-thread-dropdown-toggle]');
    const threadDropdownPanel = panel.querySelector('[data-thread-dropdown-panel]');
    const threadListEl = panel.querySelector('[data-thread-list]');
    const newThreadBtn = panel.querySelector('[data-new-thread]');

    // Thread state stored per app.  Threads are stored in localStorage under
    // THREAD_STORAGE_KEY as an object keyed by appKey.  Each entry is an
    // array of thread objects: { id, title, messages }.  The active
    // thread ID is stored separately in memory.
    const THREAD_STORAGE_KEY = 'titanZeroThreads';
    const context = window.titanOsContext || {};
    const appKey = context.app_key || 'default';
    let threads = {};
    let currentThreadId = null;

    /**
     * Load threads from localStorage.  If none exist for the current
     * appKey, initialise with a default thread.  Returns the parsed
     * threads object.
     */
    const loadThreads = () => {
        try {
            const raw = localStorage.getItem(THREAD_STORAGE_KEY);
            threads = raw ? JSON.parse(raw) : {};
        } catch {
            threads = {};
        }
        if (!threads[appKey] || threads[appKey].length === 0) {
            // Create a default thread
            const threadId = Date.now().toString();
            threads[appKey] = [
                { id: threadId, title: 'New Thread', messages: [] },
            ];
            currentThreadId = threadId;
            saveThreads();
        } else {
            // Use the first thread as the active if none selected
            currentThreadId = threads[appKey][0].id;
        }
        return threads;
    };

    /**
     * Persist threads back to localStorage.  Catch any errors silently.
     */
    const saveThreads = () => {
        try {
            localStorage.setItem(THREAD_STORAGE_KEY, JSON.stringify(threads));
        } catch {
            // localStorage may be unavailable in private browsing
        }
    };

    /**
     * Find the thread object for the current thread ID.
     */
    const getCurrentThread = () => {
        const list = threads[appKey] || [];
        return list.find((t) => t.id === currentThreadId) || null;
    };

    /**
     * Render the thread list inside the dropdown.  Each list item
     * includes a click handler to switch to the selected thread.
     */
    const renderThreadList = () => {
        if (!threadListEl) return;
        threadListEl.innerHTML = '';
        const list = threads[appKey] || [];
        if (list.length === 0) {
            const li = document.createElement('li');
            li.textContent = 'No conversations yet.';
            li.className = 'p-2 text-gray-500';
            threadListEl.appendChild(li);
        }
        list.forEach((thread) => {
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = '#';
            a.textContent = thread.title;
            a.className = 'block px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-800';
            a.dataset.threadId = thread.id;
            a.addEventListener('click', (e) => {
                e.preventDefault();
                selectThread(thread.id);
                closeThreadDropdown();
            });
            li.appendChild(a);
            threadListEl.appendChild(li);
        });
    };

    /**
     * Update header labels and message list when switching threads.
     */
    const selectThread = (id) => {
        currentThreadId = id;
        const thread = getCurrentThread();
        if (!thread) return;
        if (currentThreadTitleEl) currentThreadTitleEl.textContent = thread.title;
        if (currentThreadAppEl) currentThreadAppEl.textContent = appKey;
        // Clear existing messages
        while (messageList.firstChild) {
            messageList.removeChild(messageList.firstChild);
        }
        // Show greeting without saving to the thread history
        appendMessage('Titan Zero is ready. Ask me to navigate, explain this screen, or open an app.', 'assistant', false, false);
        // Render stored messages (if any) without re-saving them, otherwise
        // switching threads would duplicate the local history.
        thread.messages.forEach((msg) => {
            appendMessage(msg.text, msg.author, false, false);
        });
    };

    /**
     * Create a new thread and select it.  The new thread title is
     * generated based on the number of existing threads.
     */
    const createNewThread = () => {
        const list = threads[appKey] || [];
        const nextNumber = list.length + 1;
        const title = 'Thread ' + nextNumber;
        const id = Date.now().toString();
        const newThread = { id, title, messages: [] };
        list.push(newThread);
        threads[appKey] = list;
        saveThreads();
        renderThreadList();
        selectThread(id);
    };

    /**
     * Toggle the visibility of the thread dropdown panel.
     */
    const toggleThreadDropdown = () => {
        if (!threadDropdownPanel) return;
        const isHidden = threadDropdownPanel.classList.contains('hidden');
        if (isHidden) {
            threadDropdownPanel.classList.remove('hidden');
        } else {
            threadDropdownPanel.classList.add('hidden');
        }
    };

    /**
     * Close the thread dropdown panel.
     */
    const closeThreadDropdown = () => {
        if (threadDropdownPanel) {
            threadDropdownPanel.classList.add('hidden');
        }
    };

    // Close dropdown when clicking outside
    const handleDocumentClick = (event) => {
        if (!threadDropdownPanel || !threadDropdownToggle) return;
        if (threadDropdownPanel.classList.contains('hidden')) return;
        const target = event.target;
        if (!threadDropdownPanel.contains(target) && target !== threadDropdownToggle) {
            closeThreadDropdown();
        }
    };
    document.addEventListener('click', handleDocumentClick);

    // ── Server-side thread ID storage ────────────────────────────────────────
    // After a successful generate-ui call the server returns a numeric thread ID
    // (meta.threadId).  We persist that ID in localStorage so the thread can be
    // restored from the server on the next page load.
    const SERVER_THREAD_ID_KEY = 'titanZeroServerThreadId_' + appKey;

    const getServerThreadId = () => localStorage.getItem(SERVER_THREAD_ID_KEY) || null;

    const setServerThreadId = (id) => {
        if (id) {
            try { localStorage.setItem(SERVER_THREAD_ID_KEY, String(id)); } catch { /* ignore */ }
        }
    };

    // ── Fetch thread history from the server ─────────────────────────────────
    const fetchServerThread = async (serverThreadId) => {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const headers = { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' };
            if (csrfToken) headers['X-CSRF-TOKEN'] = csrfToken;
            const response = await fetch(`/api/titan/threads/${serverThreadId}`, { headers });
            if (!response.ok) return null;
            return await response.json();
        } catch {
            return null;
        }
    };

    // ── Fetch suggestion chips from the server ───────────────────────────────
    const fetchSuggestions = async () => {
        try {
            const serverThreadId = getServerThreadId();
            const query = serverThreadId ? `?appKey=${encodeURIComponent(appKey)}&threadId=${encodeURIComponent(serverThreadId)}` : `?appKey=${encodeURIComponent(appKey)}`;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const headers = { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' };
            if (csrfToken) headers['X-CSRF-TOKEN'] = csrfToken;
            const response = await fetch(`/api/titan/suggestions${query}`, { headers });
            if (!response.ok) return;
            const data = await response.json();
            renderSuggestions(data.suggestions || []);
        } catch { /* ignore */ }
    };

    // ── Render dynamic suggestion chips ──────────────────────────────────────
    const renderSuggestions = (chips) => {
        const container = panel.querySelector('[data-suggestions-container]');
        if (!container) return;
        container.innerHTML = '';
        chips.forEach((text) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.setAttribute('data-suggestion', text);
            btn.className = 'titan-zero-suggestion text-xs px-2 py-1 rounded bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700';
            btn.textContent = text;
            btn.addEventListener('click', () => {
                input.value = text;
                input.focus();
            });
            container.appendChild(btn);
        });
    };

    // Initialize threads
    loadThreads();
    renderThreadList();
    selectThread(currentThreadId);

    // Restore thread messages from server if a server thread ID exists
    const storedServerThreadId = getServerThreadId();
    if (storedServerThreadId) {
        fetchServerThread(storedServerThreadId).then((data) => {
            if (!data) return;
            const msgs = data.messages || [];
            if (msgs.length === 0) return;
            // Clear the current message list and replay server messages without
            // adding an extra greeting (the history already provides context)
            while (messageList.firstChild) messageList.removeChild(messageList.firstChild);
            msgs.forEach((m) => {
                if (m.role === 'user' || m.role === 'assistant') {
                    appendMessage(m.content || '', m.role === 'user' ? 'user' : 'assistant', false, false);
                }
            });
        });
    }

    // Pre-load suggestion chips
    fetchSuggestions();

    // Event listeners for dropdown toggle and new thread
    if (threadDropdownToggle) {
        threadDropdownToggle.addEventListener('click', (e) => {
            e.preventDefault();
            toggleThreadDropdown();
        });
    }
    if (newThreadBtn) {
        newThreadBtn.addEventListener('click', (e) => {
            e.preventDefault();
            createNewThread();
        });
    }

    /**
     * Append a message to the transcript. When returnElement is true the
     * created DOM element is returned so callers can update its contents later.
     */
    /**
     * Append a message element to the chat transcript.  Messages are
     * optionally persisted to the current thread's message history.
     *
     * @param {string} content The message text
     * @param {string} author 'user' | 'assistant'
     * @param {boolean} returnElement When true, return the created element
     * @param {boolean} saveToThread When false, skip saving the message in the thread history
     */
    function appendMessage(content, author = 'user', returnElement = false, saveToThread = true) {
        const msg = document.createElement('div');
        msg.className = `titan-zero-message titan-zero-message-${author} p-2 my-1`;
        msg.textContent = content;
        messageList.appendChild(msg);
        messageList.scrollTop = messageList.scrollHeight;
        if (saveToThread) {
            const thread = getCurrentThread();
            if (thread) {
                thread.messages.push({ author, text: content });
                saveThreads();
            }
        }
        return returnElement ? msg : undefined;
    }

    // Suggestion buttons insert the suggestion into the textarea
    suggestions.forEach((button) => {
        button.addEventListener('click', () => {
            const text = button.getAttribute('data-suggestion');
            input.value = text;
            input.focus();
        });
    });

    /**
     * Call the backend assistant endpoint.  On success update the placeholder
     * element with the reply and render any widget parts.  On failure show a
     * clear error message.
     */
    const callAssistantEndpoint = async (message, placeholderEl) => {
        try {
            const contextData = window.titanOsContext || {};
            const serverThreadId = getServerThreadId();
            const payload = {
                message,
                context: contextData,
                threadId: serverThreadId || undefined,
            };
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'text/event-stream, application/x-ndjson, application/json',
                'X-Requested-With': 'XMLHttpRequest',
            };
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }
            const response = await fetch('/api/titan/zero/generate-ui', {
                method: 'POST',
                headers,
                body: JSON.stringify(payload),
            });
            if (!response.ok) {
                throw new Error('Endpoint returned status ' + response.status);
            }
            const extractReply = (data) => {
                if (typeof data === 'string') return data;
                if (!data || typeof data !== 'object') return '';
                if (data.message) return data.message;
                if (data.reply) return data.reply;
                if (data.content) return data.content;
                if (Array.isArray(data.messages) && data.messages.length > 0) {
                    const last = data.messages[data.messages.length - 1];
                    return last.content || last.message || '';
                }
                return '';
            };

            const applyMeta = (meta) => {
                if (!meta || typeof meta !== 'object') return;
                if (meta.threadId) {
                    setServerThreadId(meta.threadId);
                }
                if (Array.isArray(meta.suggestions)) {
                    renderSuggestions(meta.suggestions);
                }
            };

            const contentType = (response.headers.get('content-type') || '').toLowerCase();
            let reply = '';

            if (contentType.includes('text/event-stream') || contentType.includes('application/x-ndjson')) {
                const reader = response.body?.getReader();
                if (!reader) {
                    throw new Error('Streaming response body is not readable');
                }

                const decoder = new TextDecoder();
                let buffer = '';
                let firstTokenSeen = false;
                const appendToken = (token) => {
                    if (token === null || token === undefined) return;
                    const nextToken = String(token);
                    if (!nextToken) return;
                    firstTokenSeen = true;
                    reply += nextToken;
                    if (placeholderEl) {
                        placeholderEl.textContent = reply;
                    }
                };
                const extractToken = (chunk) => {
                    if (typeof chunk === 'string') return chunk;
                    if (!chunk || typeof chunk !== 'object') return '';
                    return chunk.token ?? chunk.delta ?? chunk.content ?? chunk.message ?? chunk.text ?? '';
                };
                const handleStreamChunk = (chunk) => {
                    if (!chunk || chunk === '[DONE]') return;
                    if (typeof chunk === 'object' && chunk.meta) {
                        applyMeta(chunk.meta);
                    }
                    appendToken(extractToken(chunk));
                };
                const parseChunkPayload = (payload) => {
                    if (!payload) return null;
                    try {
                        return JSON.parse(payload);
                    } catch {
                        return payload;
                    }
                };

                const processSseEvent = (rawEvent) => {
                    const dataLines = rawEvent
                        .split(/\r?\n/)
                        .filter((line) => line.startsWith('data:'))
                        .map((line) => line.slice(5).trimStart());
                    if (dataLines.length === 0) return;
                    const payload = dataLines.join('\n');
                    const parsed = parseChunkPayload(payload);
                    handleStreamChunk(parsed);
                };

                const processNdjsonLine = (line) => {
                    const trimmed = line.trim();
                    if (!trimmed) return;
                    handleStreamChunk(parseChunkPayload(trimmed));
                };

                // Keep the placeholder at typing indicator while waiting for first token.
                if (placeholderEl) {
                    placeholderEl.textContent = '…';
                }

                while (true) {
                    const { value, done } = await reader.read();
                    if (done) break;
                    buffer += decoder.decode(value, { stream: true });

                    if (contentType.includes('text/event-stream')) {
                        let boundary = buffer.indexOf('\n\n');
                        while (boundary !== -1) {
                            const rawEvent = buffer.slice(0, boundary);
                            buffer = buffer.slice(boundary + 2);
                            processSseEvent(rawEvent);
                            boundary = buffer.indexOf('\n\n');
                        }
                    } else {
                        let newline = buffer.indexOf('\n');
                        while (newline !== -1) {
                            const line = buffer.slice(0, newline);
                            buffer = buffer.slice(newline + 1);
                            processNdjsonLine(line);
                            newline = buffer.indexOf('\n');
                        }
                    }
                }

                buffer += decoder.decode();
                if (buffer.trim()) {
                    if (contentType.includes('text/event-stream')) {
                        processSseEvent(buffer);
                    } else {
                        processNdjsonLine(buffer);
                    }
                }

                if (!firstTokenSeen) {
                    reply = 'Ok.';
                }
            } else {
                const data = await response.json();
                applyMeta(data.meta);
                reply = extractReply(data);
            }

            if (!reply) {
                reply = 'Ok.';
            }
            if (placeholderEl) {
                placeholderEl.textContent = reply;
                placeholderEl.className = 'titan-zero-message titan-zero-message-assistant p-2 my-1';
                // Do not re-save to local thread; server is the source of truth
            } else {
                appendMessage(reply, 'assistant', false, false);
            }
        } catch (error) {
            console.warn('Titan Zero backend unavailable', error);
            const fallback = 'Titan Zero endpoint is not available yet.';
            if (placeholderEl) {
                placeholderEl.textContent = fallback;
                placeholderEl.className = 'titan-zero-message titan-zero-message-assistant p-2 my-1';
            } else {
                appendMessage(fallback, 'assistant', false, false);
            }
        }
    };

    // Handle form submission
    const handleFormSubmit = (event) => {
        event.preventDefault();
        const text = input.value.trim();
        if (!text) return;
        appendMessage(text, 'user');
        input.value = '';
        // Rename brand-new threads from the first user prompt so history is clearer.
        const currentThread = getCurrentThread();
        if (currentThread && currentThread.title === 'New Thread') {
            currentThread.title = text.length > 36 ? text.slice(0, 36) + '…' : text;
            saveThreads();
            renderThreadList();
            if (currentThreadTitleEl) currentThreadTitleEl.textContent = currentThread.title;
        }
        // Create a placeholder element that will be updated when the reply arrives.
        const placeholder = appendMessage('…', 'assistant', true, false);
        callAssistantEndpoint(text, placeholder);
    };
    form.addEventListener('submit', handleFormSubmit);

    cleanupAssistantListeners = () => {
        document.removeEventListener('click', handleDocumentClick);
        form.removeEventListener('submit', handleFormSubmit);
    };
};

document.addEventListener('DOMContentLoaded', initializeTitanZeroAssistant);
document.addEventListener('inertia:navigate', initializeTitanZeroAssistant);
document.addEventListener('inertia:before', () => {
    if (cleanupAssistantListeners) {
        cleanupAssistantListeners();
        cleanupAssistantListeners = null;
    }
});
