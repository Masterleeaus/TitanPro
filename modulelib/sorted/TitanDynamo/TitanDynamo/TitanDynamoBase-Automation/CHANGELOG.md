## v2.0 — TitanPulse Kernel Rename + Neutral Tables

This release generalizes the automation engine to run **all domains** (Work, Marketing, Finance, Trust) by removing work-only naming and adopting neutral `tz_*` Pulse tables.

Key changes:
- Extension renamed to **TitanPulse**
- Runner renamed to **titan:pulse-run**
- Safe migration added to rename legacy `tz_work_*` tables to neutral names when present

---

# CHANGELOG.md

## v1.2
- Added **Quality & Retention Rule Pack (Rules 11–20)**.
- Added **weekly schedule sweep** support (`schedule.weekly`).
- Enhanced rule condition engine with `contains_any` operator.
- Added payload enrichment helpers: `evidence_quality_low`, `duration_ratio`.

## v1.1
- Implemented **10 Default Automation Rules for Cleaners (MVP1)** via seeder.
- Added **schedule sweep execution** to automation runner (daily/hourly/30min schedules).
- Added cron-friendly command flags:
  - `--sweeps-only`
  - `--signals-only`
- Updated docs + post-install instructions.

## v1.0
- Initial backend engine: rules + actions + run logs + signal consumer.
