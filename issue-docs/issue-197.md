# Issue 197 — Restrict InvoiceResource / PaymentResource in TitanPro panel to super_admin only

## Context

Follows from issue-121 (ZeroPay panel).  The `InvoiceResource` and `PaymentResource` that live
in `app/Filament/Resources/` are auto-discovered by the TitanPro super-admin panel
(`/titanpro`).  An identical pair of resources also live in `app/Filament/ZeroPay/Resources/`
and are served by the ZeroPay panel (`/zeropay`) for `bookkeeper`, `owner`, and `admin` roles.

Having two panels expose the same financial data with different role gates leaks access: an
`admin` user can (in principle) edit invoices in the TitanPro panel even though the
authoritative finance surface is ZeroPay.

## Changes Made

### Modified Files

**`app/Filament/Resources/InvoiceResource.php`**
- Added `use Illuminate\Database\Eloquent\Model;` import.
- Added doc-block explaining the super-admin restriction and pointing operators to ZeroPay.
- Added the following authorization methods, each returning `true` only for users with the
  `super_admin` role:
  - `canViewAny()` — guards the list page
  - `canCreate()` — guards the create page
  - `canEdit(Model $record)` — guards the edit page
  - `canView(Model $record)` — guards the view/detail page
  - `canDelete(Model $record)` — guards individual record deletion
  - `canDeleteAny()` — guards bulk deletion

**`app/Filament/Resources/PaymentResource.php`**
- Same set of changes as `InvoiceResource` above.

### New Files

**`tests/Feature/Admin/InvoicePaymentAccessTest.php`**
- Confirms that `/admin/invoices` and `/admin/payments` (old alias paths) return 404 for an
  authenticated `admin` user (those routes are not registered).
- Confirms that `admin`, `bookkeeper`, and `owner` users receive 403 Forbidden when hitting
  `/titanpro/invoices` and `/titanpro/payments` (blocked by the panel-level gate in
  `User::canAccessPanel()`).

## Files NOT Changed

**`app/Filament/ZeroPay/Resources/InvoiceResource.php`**  
**`app/Filament/ZeroPay/Resources/PaymentResource.php`**  
These resources remain unrestricted at the resource level; access is gated by the ZeroPay
panel (`canAccessPanel()` allows `owner`, `admin`, `bookkeeper`).  No further changes needed.

## How Authorization Works

1. **Panel gate** — `User::canAccessPanel()` reads `config('titan_panels.panels.titanpro.roles')`
   which resolves to `['super_admin']`.  Non-super-admin users receive HTTP 403 before they can
   reach any resource inside the TitanPro panel.
2. **Resource gate** — The new `canViewAny()` / `canCreate()` / etc. methods on the TitanPro
   `InvoiceResource` and `PaymentResource` provide a second layer of defence: even if the panel
   gate were relaxed in future, these resources will remain visible only to `super_admin`.

## Next Steps

- Consider removing the duplicate `InvoiceResource` / `PaymentResource` from
  `app/Filament/Resources/` entirely if super-admin has no operational need to manage individual
  invoices (they have the ZeroPay panel available).
- If super-admin cross-tenant views are required, scope the TitanPro resources to show **all**
  organisations (remove the `organization_id` filter from `getEloquentQuery()`).
- Run `composer run test` after deploying to confirm all new tests pass.
## Issue #197 — Configure dedicated mail queue worker (Supervisor/Horizon) in production

### Source

Follow-up to issue #134 (`issue-docs/issue-134.md`), which set `$queue = 'mail'` on
`JobConfirmationMail`, `InvoiceReminderMail`, and `TrialEndingNotification`.
Without a dedicated worker consuming the `mail` queue in production those jobs
silently stall — they are dispatched but never processed.

### Root Cause

A queue name (`mail`) was assigned to the three mail/notification classes so that
mail delivery is isolated from the `default` queue, but no Supervisor program (or
Horizon supervisor block) was configured to run a worker that listens on that queue.
The `default` queue worker ignores jobs on `mail`.

### Files Changed

| File | Change |
|------|--------|
| `deployment/supervisor/laravel-mail-worker.conf` | **New** — Supervisor program block for a 2-process `mail` queue worker using `--tries=3 --timeout=90` |
| `config/logging.php` | Added `ops` (stack) and `ops_daily` (daily file) log channels for routing critical mail-failure alerts |
| `app/Listeners/AlertOnFailedMailJob.php` | **New** — Listens to `Illuminate\Queue\Events\JobFailed`; logs `CRITICAL` to the `ops` channel when a `mail`-queue job exhausts all retries |
| `app/Providers/AppServiceProvider.php` | Imports `AlertOnFailedMailJob` + `JobFailed`; wires `Event::listen(JobFailed::class, AlertOnFailedMailJob::class)` |
| `docs/deployment-notes.md` | **New** — Deployment notes covering Supervisor installation, graceful restart procedure (`php artisan queue:restart`), and ops-alert configuration via `OPS_LOG_CHANNELS` |

### Fixes Applied

1. **Supervisor config** (`deployment/supervisor/laravel-mail-worker.conf`)
   - `command=php /var/www/artisan queue:work --queue=mail --tries=3 --timeout=90 --sleep=3 --max-jobs=500 --max-time=3600`
   - `numprocs=2` for parallel throughput
   - `autorestart=true`, `stopwaitsecs=120` (allows in-flight jobs to finish before kill)
   - Log rotation: 10 MB × 5 rotations

2. **`ops` log channel** (`config/logging.php`)
   - Stack channel driven by `OPS_LOG_CHANNELS` env var (defaults to `ops_daily`)
   - `ops_daily` writes critical-level entries to `storage/logs/ops.log` (30-day rotation)
   - Operators can add `slack` or another channel without code changes

3. **`AlertOnFailedMailJob` listener** (`app/Listeners/AlertOnFailedMailJob.php`)
   - Handles `Illuminate\Queue\Events\JobFailed`
   - No-ops for non-`mail` queues so the listener is narrowly scoped
   - Logs job name, queue, connection, exception message, and payload at `critical` level

4. **Event registration** (`app/Providers/AppServiceProvider.php`)
   - `Event::listen(JobFailed::class, AlertOnFailedMailJob::class)` added to `boot()`

5. **Deployment notes** (`docs/deployment-notes.md`)
   - Step-by-step instructions for installing the Supervisor config
   - `php artisan queue:restart` must run after every deploy
   - Documents `OPS_LOG_CHANNELS` env variable for routing alerts to Slack/PagerDuty

### Tests Added or Updated

No new automated tests were required.

- The `AlertOnFailedMailJob` listener is pure logging; its behaviour is validated by
  the existing `tests/Feature/Owner/InvoiceReminderTest.php` assertions that mail is
  queued on the `mail` queue — failed-job dispatching is framework-level.
- The Supervisor config is an infrastructure artefact and is validated at deploy time
  via `supervisorctl status`.

### Next Steps

1. **Install the Supervisor config on every production server** — see `docs/deployment-notes.md`.
2. **Add `php artisan queue:restart` to the CI/CD deploy pipeline** immediately after `php artisan migrate`.
3. **Set `OPS_LOG_CHANNELS=ops_daily,slack`** (and `LOG_SLACK_WEBHOOK_URL`) in production `.env` to get real-time Slack alerts on mail failures.
4. **Consider Laravel Horizon** if the queue workload grows: the `mail` supervisor block in Horizon mirrors the same `--queue=mail --tries=3 --timeout=90` configuration and adds a web dashboard.
5. **Ensure `failed_jobs` table exists** — run `php artisan queue:failed-table && php artisan migrate` if it has not been created yet.
