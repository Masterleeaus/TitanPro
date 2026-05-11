# Deployment Notes

## Mail Queue Worker (Supervisor)

The mail queue is used by `JobConfirmationMail`, `InvoiceReminderMail`, and
`TrialEndingNotification`. A dedicated Supervisor program must be running in
production to consume this queue.

### Supervisor configuration

Copy (or symlink) the supplied configuration into Supervisor's `conf.d` directory
and reload the daemon:

```bash
# Copy the config
sudo cp deployment/supervisor/laravel-mail-worker.conf /etc/supervisor/conf.d/laravel-mail-worker.conf

# Re-read configuration and start the new program
sudo supervisorctl reread
sudo supervisorctl update

# Verify both worker processes are running
sudo supervisorctl status laravel-mail-worker:*
```

The file is located at `deployment/supervisor/laravel-mail-worker.conf` and
configures two worker processes with the following options:

| Option | Value | Reason |
|--------|-------|--------|
| `--queue=mail` | `mail` | Isolates mail jobs from the `default` queue |
| `--tries=3` | 3 | Maximum attempts before marking a job failed |
| `--timeout=90` | 90 s | Kills stuck workers after 90 seconds |
| `--sleep=3` | 3 s | Poll interval when the queue is empty |
| `--max-jobs=500` | 500 | Restart worker after 500 jobs to prevent memory leaks |
| `--max-time=3600` | 3 600 s | Restart worker every hour as a safety valve |
| `numprocs=2` | 2 | Two parallel workers for throughput headroom |

### Restarting workers after deployment

After every code deployment, the queue workers must be restarted so they pick up
the new application code. Do **not** rely on `supervisorctl restart` alone because
the workers need to gracefully finish any in-progress job first.

```bash
# Gracefully restart all queue workers (signals running workers to stop after
# the current job finishes, then Supervisor brings up fresh processes)
php artisan queue:restart

# Verify Supervisor has brought fresh processes back up
sudo supervisorctl status laravel-mail-worker:*
```

Add `php artisan queue:restart` to your CI/CD deploy pipeline **after**
`php artisan migrate` and **before** removing the maintenance flag.

### Failed-job alerts

When a job on the `mail` queue exhausts all retries, `AlertOnFailedMailJob`
fires and writes a `CRITICAL` entry to the `ops` log channel
(`storage/logs/ops.log` by default).

To route these alerts to an external channel (Slack, PagerDuty, etc.) set the
`OPS_LOG_CHANNELS` environment variable to a comma-separated list of additional
channel names defined in `config/logging.php`, for example:

```dotenv
# .env (production)
OPS_LOG_CHANNELS=ops_daily,slack
LOG_SLACK_WEBHOOK_URL=https://hooks.slack.com/services/...
```

Check for stalled/failed mail jobs via the `failed_jobs` table:

```bash
php artisan queue:failed
php artisan queue:retry all   # re-queue all failed jobs
```
