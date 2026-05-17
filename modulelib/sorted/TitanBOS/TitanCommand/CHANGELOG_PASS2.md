# Pass 2 — Jobs DB + Real Reads

## Added
- work_* schema for Jobs Manager (jobs, events, items/tasks, assignments, checklists, checklist items, parts, assets, permits, links)
- Work models (Eloquent) with tenant scope helper

## Changed
- JobsController now performs real DB reads/writes for:
  - Jobs: index/store/show/update/archive/restore
  - Timeline events: timeline/addEvent
  - Tasks: list/create/update/complete/reopen (via work_jobs_items item_type=task)
  - Checklists: list/create/show/add item/check item/complete
  - Parts: list/create/update

## Notes
- All queries are tenant-scoped using the locked MVP rule: company_id == user_id (both pulled from auth()->id()).
- Dispatch/assign/schedule/assets/permits/inspections/evidence remain stubbed (wired routes, will be backed in next pass).
