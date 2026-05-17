/**
 * Control-panel TypeScript types for the Titan Business OS chat panel.
 *
 * Canonical source: resources/js/types/control-panel-schema.ts
 * This copy is provided so modules can import these types without depending on
 * a cross-module resource path.  Keep in sync with the canonical source.
 */

export type ControlPanelWidgetKind =
  | "metric-card"
  | "line-chart"
  | "bar-chart"
  | "data-table"
  | "log-list"
  | "chat-thread"
  | "tool-call"
  | "settings-form"
  | "mcp-server-list"
  | "project-list";

export type ControlPanelWidget = {
  id: string;
  kind: ControlPanelWidgetKind;
  title?: string;
  description?: string;
  data?: unknown;
  props?: Record<string, unknown>;
};

export type AgentUiResponse = {
  is_task_complete?: boolean;
  message?: string;
  parts?: ControlPanelWidget[];
  errors?: string[];
  meta?: Record<string, unknown>;
};

export type ChatMessage = {
  id: string;
  role: "user" | "assistant" | "system" | "tool";
  content: string;
  createdAt?: string;
  widgets?: ControlPanelWidget[];
  toolCalls?: ToolCallRecord[];
};

export type ToolCallRecord = {
  id: string;
  name: string;
  arguments?: Record<string, unknown>;
  result?: unknown;
  status?: "queued" | "running" | "completed" | "failed";
  startedAt?: string;
  completedAt?: string;
};

export type BusinessOsPanelState = {
  activeThreadId?: string;
  messages: ChatMessage[];
  widgets: ControlPanelWidget[];
  suggestions: string[];
  loading: boolean;
  error?: string;
};
