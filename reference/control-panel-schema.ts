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
