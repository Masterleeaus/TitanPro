# Job Manager Tables Plan (Titan Work Schema Doctrine v2)

End-state tables:
- work_jobs
- work_jobs_items
- work_jobs_events
- work_jobs_states
- work_jobs_notes
- work_jobs_links
- work_jobs_evidence
- work_jobs_assignments
- work_jobs_checklists
- work_jobs_checklist_items
- work_jobs_checklist_events
- work_jobs_parts
- work_assets
- work_permits
- work_jobs_inspections

Bridging approach:
- Pass 1/2: run imported JobManager migrations as-is under isolated namespace
- Pass 3+: add tenant columns + scopes
- Pass 4: migrate/rename to `work_*` schema with idempotent migrations and optional temporary compatibility views.
