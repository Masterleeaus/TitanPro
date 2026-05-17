import React, { useMemo, useState } from "react";
import { BusinessOsApi } from "../services/business-os-api";
import type { BusinessOsPanelState, ChatMessage, ControlPanelWidget } from "../schemas/control-panel-schema";
import { ControlPanelRenderer } from "./ControlPanelRenderer";

export type BusinessOsChatPanelProps = {
  api?: BusinessOsApi;
  initialThreadId?: string;
  title?: string;
};

const defaultState: BusinessOsPanelState = {
  messages: [],
  widgets: [],
  suggestions: [],
  loading: false,
};

export function BusinessOsChatPanel({
  api = new BusinessOsApi(),
  initialThreadId,
  title = "Business OS",
}: BusinessOsChatPanelProps) {
  const [state, setState] = useState<BusinessOsPanelState>({
    ...defaultState,
    activeThreadId: initialThreadId,
  });
  const [input, setInput] = useState("");

  const visibleWidgets = useMemo(() => {
    const messageWidgets = state.messages.flatMap((message) => message.widgets ?? []);
    return [...state.widgets, ...messageWidgets];
  }, [state.widgets, state.messages]);

  async function submit() {
    const trimmed = input.trim();
    if (!trimmed || state.loading) return;

    const userMessage: ChatMessage = {
      id: `user-${Date.now()}`,
      role: "user",
      content: trimmed,
      createdAt: new Date().toISOString(),
    };

    setState((current) => ({
      ...current,
      loading: true,
      error: undefined,
      messages: [...current.messages, userMessage],
    }));
    setInput("");

    try {
      const response = await api.sendChatMessage({
        threadId: state.activeThreadId,
        message: trimmed,
      });

      const assistantMessage: ChatMessage = {
        id: `assistant-${Date.now()}`,
        role: "assistant",
        content: response.message ?? "",
        createdAt: new Date().toISOString(),
        widgets: response.parts,
      };

      setState((current) => ({
        ...current,
        loading: false,
        messages: [...current.messages, assistantMessage],
        widgets: mergeWidgets(current.widgets, response.parts ?? []),
        suggestions: response.meta?.suggestions as string[] ?? current.suggestions,
      }));
    } catch (error) {
      setState((current) => ({
        ...current,
        loading: false,
        error: error instanceof Error ? error.message : "Unknown error",
      }));
    }
  }

  return (
    <section className="business-os-panel">
      <header className="business-os-panel__header">
        <h2>{title}</h2>
        {state.activeThreadId && <span>Thread: {state.activeThreadId}</span>}
      </header>

      <div className="business-os-panel__body">
        <aside className="business-os-panel__chat">
          <div className="business-os-panel__messages">
            {state.messages.map((message) => (
              <article key={message.id} className={`message message--${message.role}`}>
                <strong>{message.role}</strong>
                <p>{message.content}</p>
              </article>
            ))}
            {state.loading && <p className="message message--system">Thinking…</p>}
            {state.error && <p className="message message--error">{state.error}</p>}
          </div>

          <div className="business-os-panel__composer">
            <textarea
              value={input}
              placeholder="Ask the chatbot to run your business…"
              onChange={(event) => setInput(event.target.value)}
              onKeyDown={(event) => {
                if (event.key === "Enter" && (event.metaKey || event.ctrlKey)) submit();
              }}
            />
            <button type="button" onClick={submit} disabled={state.loading || !input.trim()}>
              Send
            </button>
          </div>

          {state.suggestions.length > 0 && (
            <div className="business-os-panel__suggestions">
              {state.suggestions.map((suggestion) => (
                <button key={suggestion} type="button" onClick={() => setInput(suggestion)}>
                  {suggestion}
                </button>
              ))}
            </div>
          )}
        </aside>

        <main className="business-os-panel__workspace">
          <ControlPanelRenderer widgets={visibleWidgets} />
        </main>
      </div>
    </section>
  );
}

function mergeWidgets(existing: ControlPanelWidget[], incoming: ControlPanelWidget[]): ControlPanelWidget[] {
  const map = new Map<string, ControlPanelWidget>();
  for (const widget of existing) map.set(widget.id, widget);
  for (const widget of incoming) map.set(widget.id, widget);
  return Array.from(map.values());
}
