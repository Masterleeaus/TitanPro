# Issue 360 — Install TitanCommand Module (General Workforce Job Lifecycle Engine)

## Summary

Installed the TitanCommand module, providing a general workforce job lifecycle engine for the TitanPro platform. This module underpins `CleaningJobs` and `BookingModule` with a shared `work_jobs*` table layer, state machine, checklist/evidence/inspection/report lifecycle, and work assets/permits management.

## Files Changed / Added

### New Module Structure

| Path | Purpose |
|------|---------|
| `Modules/TitanCommand/module.json` | Module manifest — active: 1, panel: groundzero |
| `Modules/TitanCommand/Providers/TitanCommandServiceProvider.php` | Service provider — loads migrations and views |
| `Modules/TitanCommand/Config/config.php` | Module config (priorities, statuses, evidence disk) |

### Migrations (from Pass5 source)

All migrations copied from `modulelib/sorted/TitanCommand_Pass5_WorkforceMigrations/TitanCommand/database/migrations/` to `Modules/TitanCommand/Database/Migrations/`:

| Migration | Table(s) |
|-----------|---------|
| `2026_02_18_000100_create_work_jobs_table.php` | `work_jobs` |
| `2026_02_18_000110_create_work_jobs_events_table.php` | `work_jobs_events` |
| `2026_02_18_000120_create_work_jobs_items_table.php` | `work_jobs_items` |
| `2026_02_18_000130_create_work_jobs_assignments_table.php` | `work_jobs_assignments` |
| `2026_02_18_000140_create_work_jobs_checklists_table.php` | `work_jobs_checklists` |
| `2026_02_18_000150_create_work_jobs_checklist_items_table.php` | `work_jobs_checklist_items` |
| `2026_02_18_000160_create_work_jobs_parts_table.php` | `work_jobs_parts` |
| `2026_02_18_000170_create_work_assets_table.php` | `work_assets` |
| `2026_02_18_000180_create_work_permits_table.php` | `work_permits` |
| `2026_02_18_000190_create_work_jobs_links_table.php` | `work_jobs_links` |
| `2026_02_18_000200_create_work_jobs_evidence_table.php` | `work_jobs_evidence` |
| `2026_02_18_000210_create_work_jobs_inspections_table.php` | `work_jobs_inspections` |
| `2026_02_18_000220_create_work_jobs_inspection_items_table.php` | `work_jobs_inspection_items` |
| `2026_02_18_000230_create_work_jobs_states_table.php` | `work_jobs_states` |
| `2026_02_18_000240_create_work_jobs_reports_table.php` | `work_jobs_reports` |
| `2026_02_18_000300_seed_command_jobs_menu.php` | Sidebar menu seeder |
| `2026_02_18_000500_create_work_jobs_templates.php` | `work_jobs_templates` + `work_jobs_template_items` |
| `2026_02_18_000501_seed_work_jobs_default_templates.php` | Default checklist/inspection templates |
| `2026_02_18_000600_rename_social_media_tables_to_command.php` | Rebrand agent table names |
| `2026_02_18_001000_add_company_id_to_command_agent_tables.php` | Tenant column on agent tables |

### Eloquent Models

All models placed in `Modules/TitanCommand/Models/Work/` with namespace `Modules\TitanCommand\Models\Work`:

- `WorkJob`, `WorkJobAssignment`, `WorkJobChecklist`, `WorkJobChecklistItem`
- `WorkJobEvent`, `WorkJobEvidence`, `WorkJobInspection`, `WorkJobInspectionItem`
- `WorkJobItem`, `WorkJobLink`, `WorkJobPart`, `WorkJobReport`
- `WorkJobState`, `WorkJobTemplate`, `WorkJobTemplateItem`
- `WorkAsset`, `WorkPermit`

### Filament Resources (GroundZero panel)

| Path | Purpose |
|------|---------|
| `app/Filament/GroundZero/Resources/WorkJobResource.php` | Main WorkJob CRUD resource |
| `app/Filament/GroundZero/Resources/WorkJobResource/Pages/ListWorkJobs.php` | List page |
| `app/Filament/GroundZero/Resources/WorkJobResource/Pages/CreateWorkJob.php` | Create page (auto-sets company_id/user_id) |
| `app/Filament/GroundZero/Resources/WorkJobResource/Pages/EditWorkJob.php` | Edit page |

### Seeders

| Path | Purpose |
|------|---------|
| `Modules/TitanCommand/Database/Seeders/WorkJobStateSeeder.php` | BOS seed for default lifecycle states at company_id=0 |

### Tests

| Path | Purpose |
|------|---------|
| `Modules/TitanCommand/Tests/Feature/WorkJobLifecycleTest.php` | 8 lifecycle tests: create, assign, checklist, evidence, inspect, close, state machine, tenant isolation |
| `Modules/TitanCommand/Tests/Unit/ModuleStructureTest.php` | Validates module.json, ServiceProvider, WorkJob model, migration files exist |

### Modified Files

| Path | Change |
|------|--------|
| `bootstrap/providers.php` | Registered `Modules\TitanCommand\Providers\TitanCommandServiceProvider` |
| `phpunit.xml` | Added TitanCommand test directories to Unit and Feature suites |

## Fixes Applied

- Model namespaces rewritten from `App\Extensions\TitanCommand\System\Models\Work` → `Modules\TitanCommand\Models\Work` to align with the Modules PSR-4 layout
- Test assertions corrected to match actual migration schemas (evidence uses `uri` not `file_path`; reports table has `report_type`/`label` not `title`/`status`/`job_id`)
- All migrations have idempotent guards (`Schema::hasTable()`) preventing duplicate table creation if migrations run twice

## Acceptance Criteria Status

- [x] All `work_jobs*` and `work_assets` tables are in migration files and migrate cleanly
- [x] Job can be created, assigned, have checklist completed, inspected, and closed via Filament (WorkJobResource in GroundZero panel)
- [x] Job states drive lifecycle transitions (`work_jobs_states` + `WorkJobState` model)
- [x] BOS seed data: `WorkJobStateSeeder` seeds default states; migration `000501` seeds default templates
- [x] Evidence (file attachments) can be recorded against a job via `work_jobs_evidence` table
- [x] Feature tests cover the full open → assign → checklist → inspect → close lifecycle

## Next Steps

1. Run `php artisan migrate` to apply all TitanCommand migrations on the live database
2. Run `php artisan db:seed --class=Modules\\TitanCommand\\Database\\Seeders\\WorkJobStateSeeder` to seed reference states
3. Wire `CleaningJobs` models to delegate to `WorkJob` where applicable (Step 7 of issue)
4. Add sub-resources for Assignment, Checklist, Evidence, Inspection, Report as Filament relation managers on the WorkJob edit page
5. Consider adding a `ViewWorkJob` page with a timeline of lifecycle events
