import type { AgentUiResponse, ChatMessage, ControlPanelWidget } from "../schemas/control-panel-schema";

export type BusinessOsApiOptions = {
  baseUrl?: string;
  headers?: Record<string, string>;
};

export class BusinessOsApi {
  private baseUrl: string;
  private headers: Record<string, string>;

  constructor(options: BusinessOsApiOptions = {}) {
    this.baseUrl = options.baseUrl ?? "";
    this.headers = options.headers ?? {};
  }

  async sendChatMessage(input: {
    threadId?: string;
    message: string;
    context?: Record<string, unknown>;
  }): Promise<AgentUiResponse> {
    const response = await fetch(`${this.baseUrl}/api/titan/zero/generate-ui`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        ...this.headers,
      },
      body: JSON.stringify(input),
    });

    if (!response.ok) {
      throw new Error(`Business OS request failed: ${response.status}`);
    }

    return response.json();
  }

  async fetchThread(threadId: string): Promise<{ messages: ChatMessage[]; widgets: ControlPanelWidget[] }> {
    const response = await fetch(`${this.baseUrl}/api/titan/threads/${threadId}`, {
      headers: this.headers,
    });

    if (!response.ok) {
      throw new Error(`Thread fetch failed: ${response.status}`);
    }

    return response.json();
  }

  async fetchSuggestions(threadId?: string): Promise<string[]> {
    const query = threadId ? `?threadId=${encodeURIComponent(threadId)}` : "";
    const response = await fetch(`${this.baseUrl}/api/titan/suggestions${query}`, {
      headers: this.headers,
    });

    if (!response.ok) {
      return [];
    }

    const payload = await response.json();
    return payload.suggestions ?? [];
  }
}
