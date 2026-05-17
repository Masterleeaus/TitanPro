# Pass 1 - Jobs Manager wiring (TitanCommand)

## What changed
- Added a new Jobs Manager workspace inside TitanCommand with its own sidebar + routed screens.
- Added `/dashboard/user/jobs/*` route group (web+auth) so the Jobs app can live alongside the existing Titan Command Agent workspace.
- Staged the imported JobManager code for activation in Pass 2 (DB + controllers + views).

## What you can test now
- Visit: `/dashboard/user/jobs` (Dashboard)
- Sidebar navigation routes:
  - `/dashboard/user/jobs/workorders`
  - `/dashboard/user/jobs/requests`
  - `/dashboard/user/jobs/tasks`
  - `/dashboard/user/jobs/parts`
  - `/dashboard/user/jobs/checklists`
  - `/dashboard/user/jobs/inspections`
  - `/dashboard/user/jobs/assets`
  - `/dashboard/user/jobs/permits`
  - `/dashboard/user/jobs/reports`
  - `/dashboard/user/jobs/settings`

## What’s next (Pass 2)
- Activate JobManager migrations (mapped into `work_*` schema), seeders, and DB-backed list pages.
- Switch the stub controllers over to the imported JobManager controllers.
- Add menu rows (DB menus + MenuService + MenuHelper gating) for a true “Jobs” workspace entry.
