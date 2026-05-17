import type { AgentUiResponse, ChatMessage, ControlPanelWidget } from './control-panel-schema';

/**
 * Returns the Laravel CSRF token from the page meta tag.
 */
function getCsrfToken(): string {
    return document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? '';
}

export type BusinessOsApiOptions = {
    baseUrl?: string;
    extraHeaders?: Record<string, string>;
};

/**
 * Typed API client for the Titan Business OS chat endpoints.
 * Automatically includes the Laravel CSRF token on mutating requests.
 *
 * Canonical location: Modules/{Module}/UI/Vendor/Tambo/business-os-api.ts
 * Primary copy:       resources/js/services/business-os-api.ts
 */
export class BusinessOsApi {
    private readonly baseUrl: string;
    private readonly extraHeaders: Record<string, string>;

    constructor(options: BusinessOsApiOptions = {}) {
        this.baseUrl = options.baseUrl ?? '';
        this.extraHeaders = options.extraHeaders ?? {};
    }

    private buildHeaders(extra: Record<string, string> = {}): Record<string, string> {
        const csrf = getCsrfToken();
        return {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {}),
            ...this.extraHeaders,
            ...extra,
        };
    }

    async sendChatMessage(input: {
        threadId?: string;
        message: string;
        context?: Record<string, unknown>;
    }): Promise<AgentUiResponse> {
        const response = await fetch(`${this.baseUrl}/api/titan/zero/generate-ui`, {
            method: 'POST',
            headers: this.buildHeaders(),
            body: JSON.stringify(input),
        });

        if (!response.ok) {
            throw new Error(`Business OS request failed: ${response.status}`);
        }

        return response.json() as Promise<AgentUiResponse>;
    }

    async fetchThread(threadId: string): Promise<{ messages: ChatMessage[]; widgets: ControlPanelWidget[] }> {
        const response = await fetch(`${this.baseUrl}/api/titan/threads/${threadId}`, {
            headers: this.buildHeaders(),
        });

        if (!response.ok) {
            throw new Error(`Thread fetch failed: ${response.status}`);
        }

        return response.json() as Promise<{ messages: ChatMessage[]; widgets: ControlPanelWidget[] }>;
    }

    async fetchSuggestions(threadId?: string): Promise<string[]> {
        const query = threadId ? `?threadId=${encodeURIComponent(threadId)}` : '';
        const response = await fetch(`${this.baseUrl}/api/titan/suggestions${query}`, {
            headers: this.buildHeaders(),
        });

        if (!response.ok) {
            return [];
        }

        const payload = await response.json() as { suggestions?: string[] };
        return payload.suggestions ?? [];
    }
}
