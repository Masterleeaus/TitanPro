import React from "react";
import type { ControlPanelWidget } from "../schemas/control-panel-schema";

export type ControlPanelRendererProps = {
  widgets: ControlPanelWidget[];
};

export function ControlPanelRenderer({ widgets }: ControlPanelRendererProps) {
  if (!widgets.length) {
    return (
      <div className="control-panel-empty">
        <h3>No dashboard generated yet</h3>
        <p>Ask the chatbot to show projects, customers, tasks, invoices, analytics, logs, or settings.</p>
      </div>
    );
  }

  return (
    <div className="control-panel-grid">
      {widgets.map((widget) => (
        <WidgetCard key={widget.id} widget={widget} />
      ))}
    </div>
  );
}

function WidgetCard({ widget }: { widget: ControlPanelWidget }) {
  switch (widget.kind) {
    case "metric-card":
      return <MetricCard widget={widget} />;
    case "data-table":
      return <DataTable widget={widget} />;
    case "log-list":
      return <LogList widget={widget} />;
    default:
      return <JsonWidget widget={widget} label={widget.title ?? widget.kind} />;
  }
}

function MetricCard({ widget }: { widget: ControlPanelWidget }) {
  const data = (widget.data ?? {}) as Record<string, unknown>;
  return (
    <article className="widget widget--metric">
      <h3>{widget.title ?? "Metric"}</h3>
      <strong>{String(data.value ?? "—")}</strong>
      {data.change && <small>{String(data.change)}</small>}
    </article>
  );
}

function DataTable({ widget }: { widget: ControlPanelWidget }) {
  const rows = Array.isArray(widget.data) ? widget.data as Record<string, unknown>[] : [];
  const columns = rows.length ? Object.keys(rows[0]) : [];
  return (
    <article className="widget widget--table">
      <h3>{widget.title ?? "Table"}</h3>
      <table>
        <thead>
          <tr>{columns.map((column) => <th key={column}>{column}</th>)}</tr>
        </thead>
        <tbody>
          {rows.map((row, index) => (
            <tr key={index}>
              {columns.map((column) => <td key={column}>{String(row[column] ?? "")}</td>)}
            </tr>
          ))}
        </tbody>
      </table>
    </article>
  );
}

function LogList({ widget }: { widget: ControlPanelWidget }) {
  const logs = Array.isArray(widget.data) ? widget.data : [];
  return (
    <article className="widget widget--logs">
      <h3>{widget.title ?? "Logs"}</h3>
      <ul>
        {logs.map((log, index) => <li key={index}>{typeof log === "string" ? log : JSON.stringify(log)}</li>)}
      </ul>
    </article>
  );
}

function JsonWidget({ widget, label }: { widget: ControlPanelWidget; label: string }) {
  return (
    <article className="widget widget--json">
      <h3>{label}</h3>
      {widget.description && <p>{widget.description}</p>}
      <pre>{JSON.stringify(widget.data ?? widget.props ?? {}, null, 2)}</pre>
    </article>
  );
}
