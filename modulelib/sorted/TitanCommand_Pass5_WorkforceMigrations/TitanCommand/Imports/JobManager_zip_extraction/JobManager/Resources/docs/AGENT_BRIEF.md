# Agent Brief — AICopilot Integration (v3)
**Date:** 2025-11-01

## Scope (MVP)
- Wire your module to use **AICopilot** via **Facade** (`AICopilot::ask|tool|compliance`) or **API** (`/api/aicopilot/...`).
- Respect **plan-gating** (`user_modules()` includes `aicopilot`) and **permissions** (`view_aicopilot`, `manage_aicopilot`).
- If you add widgets, register them in `Config/<alias>_widgets.php` and render via the registry include.
- No core edits. Deliver a ZIP with `Modules/<YourModule>/...`

## Acceptance
- Plan-gated + permission-gated visibility passes QA
- Routes listed and enforce middleware
- Migrations idempotent and reversible where feasible
- API and/or Facade calls function under tenant auth context
