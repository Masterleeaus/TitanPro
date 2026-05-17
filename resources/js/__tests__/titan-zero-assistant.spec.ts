import { afterEach, beforeAll, beforeEach, describe, expect, it, vi } from 'vitest';

function renderPanel(): void {
    document.body.innerHTML = `
        <meta name="csrf-token" content="test-token" />
        <div id="titan-zero-chat-panel">
            <span data-current-thread-title></span>
            <span data-current-thread-app></span>
            <button data-thread-dropdown-toggle type="button">Threads</button>
            <div data-thread-dropdown-panel class="hidden">
                <ul data-thread-list></ul>
            </div>
            <button data-new-thread type="button">New thread</button>
            <div data-message-list></div>
            <div data-suggestions-container></div>
            <form data-chat-form>
                <textarea></textarea>
                <button type="submit">Send</button>
            </form>
        </div>
    `;
}

function createJsonResponse(payload: unknown) {
    return {
        ok: true,
        headers: { get: () => 'application/json' },
        json: async () => payload,
    };
}

describe('titan-zero-assistant', () => {
    beforeAll(async () => {
        await import('../titan-zero-assistant.js');
    });

    beforeEach(() => {
        localStorage.clear();
        renderPanel();
        // @ts-expect-error test-only window extension
        window.titanOsContext = { app_key: 'test-app' };
    });

    afterEach(() => {
        vi.restoreAllMocks();
        document.dispatchEvent(new Event('inertia:before'));
        document.body.innerHTML = '';
    });

    it('submits only one generate request after repeated Inertia initialisation', async () => {
        const fetchMock = vi.fn(async (url: string) => {
            if (url.includes('/api/titan/zero/generate-ui')) {
                return createJsonResponse({ message: 'done', meta: {} });
            }

            return createJsonResponse({ suggestions: [] });
        });
        vi.stubGlobal('fetch', fetchMock);

        document.dispatchEvent(new Event('DOMContentLoaded'));
        document.dispatchEvent(new Event('inertia:navigate'));

        const input = document.querySelector('#titan-zero-chat-panel textarea') as HTMLTextAreaElement;
        const form = document.querySelector('[data-chat-form]') as HTMLFormElement;
        input.value = 'Hello';
        form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
        await Promise.resolve();
        await Promise.resolve();

        const generateCalls = fetchMock.mock.calls.filter(([url]) => String(url).includes('/api/titan/zero/generate-ui'));
        expect(generateCalls).toHaveLength(1);
    });

    it('renders ndjson streaming tokens incrementally into the assistant bubble', async () => {
        const encoder = new TextEncoder();

        const fetchMock = vi.fn(async (url: string) => {
            if (!url.includes('/api/titan/zero/generate-ui')) {
                return createJsonResponse({ suggestions: [] });
            }

            const body = new ReadableStream({
                start(controller) {
                    controller.enqueue(encoder.encode('{"token":"Hel"}\n'));
                    controller.enqueue(encoder.encode('{"token":"lo"}\n'));
                    controller.close();
                },
            });

            return {
                ok: true,
                headers: { get: () => 'application/x-ndjson' },
                body,
                json: async () => ({}),
            };
        });
        vi.stubGlobal('fetch', fetchMock);

        document.dispatchEvent(new Event('DOMContentLoaded'));

        const input = document.querySelector('#titan-zero-chat-panel textarea') as HTMLTextAreaElement;
        const form = document.querySelector('[data-chat-form]') as HTMLFormElement;
        const messageList = document.querySelector('[data-message-list]') as HTMLElement;

        input.value = 'Stream please';
        form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));

        await Promise.resolve();
        await Promise.resolve();
        await Promise.resolve();

        const assistantMessages = messageList.querySelectorAll('.titan-zero-message-assistant');
        const latestAssistant = assistantMessages[assistantMessages.length - 1] as HTMLElement;
        expect(latestAssistant.textContent).toBe('Hello');
    });
});
