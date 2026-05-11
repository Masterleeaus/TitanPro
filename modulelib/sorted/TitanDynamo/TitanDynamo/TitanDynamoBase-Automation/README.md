# TitanPulse (v2.0)

Universal **automation + intelligence kernel** for TitanZero.

TitanPulse consumes **tenant-scoped signals** from `tz_signals` (Titan Signal) and produces:
- **AI Suggestions** (`tz_ai_suggestions`)
- **Pending Actions (approval-gated)** (`tz_pending_actions`)
- **Analyses** (`tz_analyses`)
- **Automation Run Logs** (`tz_automation_runs`)

## What TitanPulse does (non-destructive)
Pulse **never** mutates jobs, invoices, posts, or client records silently.
It may only:
- create suggestions
- queue pending actions (requires human approval)
- run analysis
- emit follow-up signals (optional)

## Runner command
```bash
php artisan titan:pulse-run --limit=200
```

Options:
- `--signals-only`
- `--sweeps-only`
- `--team_id=...`

## Default rule packs included (MVP)
- Work Cleaning Ops Pack (Rules 1–10)
- Quality & Retention Pack (Rules 11–20)

## Install
See `POST_INSTALL.txt`.

## Signal contract (minimum)
Pulse expects `tz_signals` to include:
- `team_id` (tenant key)
- `source`, `type`
- `subject_type`, `subject_id` (or entity refs)
- `payload_json` (small)
- `idempotency_key` (recommended)
- `created_at`
