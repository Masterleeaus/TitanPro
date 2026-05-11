## Issue #134 — Mail and Notification classes using Queueable trait have no explicit queue name or retry config

### Root Cause

`JobConfirmationMail`, `InvoiceReminderMail`, and `TrialEndingNotification` all used the `Queueable` trait but:

- Did not implement `ShouldQueue`, so they were not treated as proper queued jobs by Laravel's dispatch/notification infrastructure.
- Specified no `$queue` name, meaning jobs would land on the `default` queue with no isolation from other work.
- Specified no `$tries`, `$timeout`, or `$backoff`, so jobs had no retry policy and could retry indefinitely (or not at all) depending on the queue driver configuration.

### Files Changed

| File | Change |
|------|--------|
| `app/Mail/JobConfirmationMail.php` | Added `implements ShouldQueue`; added `$queue`, `$tries`, `$timeout`, `$backoff` properties |
| `app/Mail/InvoiceReminderMail.php` | Added `implements ShouldQueue`; added `$queue`, `$tries`, `$timeout`, `$backoff` properties |
| `app/Notifications/TrialEndingNotification.php` | Added `implements ShouldQueue`; added `$queue`, `$tries`, `$timeout`, `$backoff` properties |

### Fixes Applied

Each class now declares:

```php
use Illuminate\Contracts\Queue\ShouldQueue;

class ExampleMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $queue   = 'mail';
    public int    $tries   = 3;
    public int    $timeout = 60;
    public array  $backoff = [30, 60, 120];
}
```

- **`$queue = 'mail'`** — isolates mail jobs from the `default` queue so a slow mail driver cannot block other background work.
- **`$tries = 3`** — retries up to 3 times before marking the job as failed.
- **`$timeout = 60`** — kills stuck workers after 60 seconds to prevent resource exhaustion.
- **`$backoff = [30, 60, 120]`** — exponential-style back-off (30 s, 60 s, 120 s) between retries, giving the mail driver time to recover.

### Next Steps

1. **Configure a `mail` queue worker in production** — add a dedicated worker in your Supervisor/Horizon config:
   ```ini
   [program:laravel-mail-worker]
   command=php artisan queue:work --queue=mail --tries=3 --timeout=90
   ```
2. **Run migrations** if any queue/failed-jobs table changes are needed (`php artisan queue:failed-table && php artisan migrate`).
3. **Run the full test suite** in a PHP 8.4 environment (`composer run test`) to confirm no regressions.
4. **Monitor failed jobs** after deploying — check `failed_jobs` table or Horizon dashboard for any mail delivery failures.
