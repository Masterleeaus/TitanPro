# JobManager (Smart FSM → nWidart)

This module is a **first-pass port** of Smart FSM's Field Service domain into an nWidart module.

### Included (usable code first)
- Eloquent models: WorkOrder, WOType, WORequest, WOServiceAppointment, WOServiceTask, WOServicePart, ServiceTask, ServicePart
- Migrations: work_orders, service_tasks, service_parts (others to add as you validate)
- Controllers: WorkOrderController, WOTypeController, WORequestController, ServicePartController (namespaced)
- Views: related Blade templates pulled over (20 files)

### Install
1. Ensure your Laravel app has `nwidart/laravel-modules` installed.
2. Copy `Modules/JobManager` into your app root.
3. Run migrations:
   ```bash
   php artisan migrate
   ```
4. Visit `/jobmanager` while logged in.

### Next Pass Targets
- Add migrations for `wo_types`, `wo_requests`, `wo_service_appointments`, `wo_service_tasks`, `wo_service_parts` (if not already covered).
- Port Form Request classes and Policies; wire permissions (Spatie).
- Replace any remaining `view()` calls to use namespace `jobmanager::`.
- Add routes for controllers (index/create/store/show/edit/update/destroy).
- Write seeders for demo data and factories for testing.


---
## Pass 2
- Added migrations: wo_types, wo_requests, wo_service_appointments, wo_service_tasks, wo_service_parts
- Expanded routes: full resource routes for orders/types/requests/appointments/tasks/parts (+ service catalog)
- Created controller stubs for appointments, tasks, parts, and service-task controller


---
## Pass 3
- Added FormRequests for Job Manager, Appointments, Tasks, Parts
- Added permissive WorkOrderPolicy stub (swap to Gate/Spatie as needed)
- Implemented real CRUD handlers for Job Manager + line items + appointments
- Added minimal views (index/create/edit/show)
- Added JobManagerSeeder with demo data (labor + part + appointment)

**Seed command:**
```bash
php artisan db:seed --class="Modules\\JobManager\\Database\\Seeders\\JobManagerSeeder"
```


---
## Pass 4
- Registered `WorkOrderPolicy` via Gate in ServiceProvider
- Added Spatie roles/permissions seeder (admin/technician/viewer)
- Implemented totals recalculation service; hooked into line-item changes
- Added model factories and a demo dataset seeder

**Seed commands:**
```bash
php artisan db:seed --class="Modules\\JobManager\\Database\\Seeders\\JobManagerPermissionSeeder"
php artisan db:seed --class="Modules\\JobManager\\Database\\Seeders\\JobManagerDemoSeeder"
```


---
## Pass 5 (Production polish)
- **Route protection**: added Spatie `permission:*` middleware to all resources
- **Domain events**: Created `WorkOrderCreated/Updated/Completed` + listener `SendWorkOrderWebhook`
- **Webhook config**: set `WORKORDERS_WEBHOOK_URL` in `.env` to receive JSON payloads
- **Event provider**: auto-registered to wire events → webhook
- **SSO/JWT middleware stub**: `Http/Middleware/AcceptSsoJwt.php` for Worksuite handoff
- **Tests**: basic Feature test scaffold (adjust to your base TestCase)

**Enable webhook**: set `WORKORDERS_WEBHOOK_URL=https://your-listener.example/webhooks/jobmanager`

**Note**: For per-action permission middleware on `Route::resource`, if your Laravel version doesn’t support arrays, define explicit routes per action.


## Settings
- UI: `/admin/jobmanager/settings` (requires `jobmanager.settings`)
- Toggle API auth, set webhook URL + retry/backoff.
- Values cache for convenience. For permanence: `php artisan vendor:publish --tag=jobmanager-config` then edit `config/jobmanager.php` or .env:

```
WORKORDERS_WEBHOOK_URL=
WORKORDERS_WEBHOOK_RETRIES=3
WORKORDERS_WEBHOOK_BACKOFF=5
```

## CSV
- Export: `php artisan jobmanager:export-csv --path=exports/wo.csv`
- Import: `php artisan jobmanager:import-csv exports/wo.csv` (add `--dry-run` to preview)


## Queueable Webhooks
- Webhooks now dispatch via a **queue job** with retries/backoff.
- Failures are recorded in `jobmanager_failed_webhooks`.
- Run migrations after upgrading.

## Self test
```
php artisan jobmanager:selftest
php artisan jobmanager:selftest --queue   # also dispatches a test job
```


## Assignments widget
- Include in any Job page (replace `{{ $workOrder->id }}` accordingly):
```
<x-jobmanager::assignments :work_order_id="$workOrder->id" />
```
This renders a live widget and a button that jumps to the Contractors assignment screen pre-filled with the Job ID.
